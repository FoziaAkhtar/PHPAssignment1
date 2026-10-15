
<?php

// ============================================================
// PHP ASSIGNMENT 3 — BOOK MANAGER
// FILE: edit.php
//
// PURPOSE:
// 1. Find the selected book.
// 2. Display its existing information and cover.
// 3. Allow the user to replace the cover image.
// 4. Preserve the existing cover if no new image is selected.
// 5. Validate and save the updated book information.
// ============================================================


// STEP 1: CONNECT TO THE DATABASE

require_once "db.php";
require_once "image_util.php";


// STEP 2: DEFINE IMAGE SETTINGS

$imageDirectory = __DIR__ . DIRECTORY_SEPARATOR . "images";
$defaultImage = "placeholder_100.jpg";


// STEP 3: GET AND VALIDATE THE BOOK ID

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    header("Location: index.php");
    exit;
}


// STEP 4: FIND THE EXISTING BOOK, INCLUDING ITS COVER

try {

    $sql = "
        SELECT
            id,
            title,
            author,
            genre,
            year_published,
            rating,
            date_added,
            imageName
        FROM books
        WHERE id = :id
    ";

    $statement = $pdo->prepare($sql);
    $statement->execute([":id" => $id]);

    $book = $statement->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    header("Location: error.php");
    exit;
}


// STEP 5: CHECK THAT THE BOOK EXISTS

if (!$book) {
    header("Location: index.php");
    exit;
}


// STEP 6: LOAD THE EXISTING BOOK VALUES

$title = $book["title"];
$author = $book["author"];
$genre = $book["genre"];
$year_published = $book["year_published"];
$rating = $book["rating"];
$date_added = $book["date_added"];

// Keep the current cover unless a replacement is uploaded.
$imageName = $book["imageName"] ?? "";


// STEP 7: CREATE THE VALIDATION ERROR ARRAY

$errors = [];


// STEP 8: PROCESS THE FORM WHEN SUBMITTED

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // STEP 8A: READ THE UPDATED FORM VALUES

    $title = trim($_POST["title"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $genre = trim($_POST["genre"] ?? "");
    $year_published = trim($_POST["year_published"] ?? "");
    $rating = trim($_POST["rating"] ?? "");
    $date_added = trim($_POST["date_added"] ?? "");


    // STEP 8B: VALIDATE THE TITLE

    if ($title === "") {
        $errors[] = "Book title is required.";
    }


    // STEP 8C: VALIDATE THE AUTHOR

    if ($author === "") {
        $errors[] = "Author name is required.";
    }


    // STEP 8D: VALIDATE THE GENRE

    if ($genre === "") {
        $errors[] = "Genre is required.";
    }


    // STEP 8E: VALIDATE THE PUBLICATION YEAR

    $yearNumber = filter_var(
        $year_published,
        FILTER_VALIDATE_INT
    );

    if (
        $year_published === "" ||
        $yearNumber === false ||
        $yearNumber < 0 ||
        $yearNumber > 2100
    ) {
        $errors[] = "Please enter a valid publication year between 0 and 2100.";
    }


    // STEP 8F: VALIDATE THE RATING

    if (
        $rating === "" ||
        !is_numeric($rating) ||
        (float) $rating < 0 ||
        (float) $rating > 5
    ) {
        $errors[] = "Rating must be a number between 0 and 5.";
    }


    // STEP 8G: VALIDATE THE DATE

    $dateObject = DateTime::createFromFormat("!Y-m-d", $date_added);
    $dateErrors = DateTime::getLastErrors();

    if (
        !$dateObject ||
        ($dateErrors !== false &&
            ($dateErrors["warning_count"] > 0 ||
             $dateErrors["error_count"] > 0)) ||
        $dateObject->format("Y-m-d") !== $date_added
    ) {
        $errors[] = "Please enter a valid date.";
    }


    // STEP 9: CHECK FOR A NEW COVER IMAGE

    $newImageName = null;
    $uploadedFiles = [];

    if (
        isset($_FILES["book_image"]) &&
        $_FILES["book_image"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        $uploadError = $_FILES["book_image"]["error"];

        // STEP 9A: CHECK WHETHER THE UPLOAD SUCCEEDED

        if ($uploadError !== UPLOAD_ERR_OK) {

            if ($uploadError === UPLOAD_ERR_INI_SIZE ||
                $uploadError === UPLOAD_ERR_FORM_SIZE) {
                $errors[] = "The image is too large. Please choose a smaller image.";
            } else {
                $errors[] = "The image could not be uploaded. Please try again.";
            }

        } else {

            // STEP 9B: CHECK THE FILE SIZE (MAXIMUM 5 MB)

            if ($_FILES["book_image"]["size"] > 5 * 1024 * 1024) {

                $errors[] = "The cover image must be 5 MB or smaller.";

            } else {

                // STEP 9C: CHECK THE ACTUAL IMAGE TYPE

                $temporaryFile = $_FILES["book_image"]["tmp_name"];
                $imageInfo = @getimagesize($temporaryFile);

                $allowedTypes = [
                    IMAGETYPE_JPEG => "jpg",
                    IMAGETYPE_PNG  => "png",
                    IMAGETYPE_GIF  => "gif"
                ];

                if (
                    $imageInfo === false ||
                    !isset($allowedTypes[$imageInfo[2]])
                ) {

                    $errors[] = "Please choose a valid JPG, PNG, or GIF image.";

                } else {

                    // STEP 9D: PREPARE A UNIQUE IMAGE FILENAME

                    $extension = $allowedTypes[$imageInfo[2]];

                    $baseName = "book_" . bin2hex(random_bytes(8));

                    $originalFileName = $baseName . "." . $extension;

                    $originalPath = $imageDirectory
                        . DIRECTORY_SEPARATOR . $originalFileName;

                    $thumbnailName = $baseName . "_100." . $extension;

                    $thumbnailPath = $imageDirectory
                        . DIRECTORY_SEPARATOR . $thumbnailName;

                    $mediumName = $baseName . "_400." . $extension;

                    $mediumPath = $imageDirectory
                        . DIRECTORY_SEPARATOR . $mediumName;

                    // STEP 9E: MOVE THE UPLOADED FILE INTO images/

                    if (!is_dir($imageDirectory) ||
                        !is_writable($imageDirectory)) {

                        $errors[] = "The images folder is missing or cannot be written to.";

                    } elseif (!move_uploaded_file(
                        $temporaryFile,
                        $originalPath
                    )) {

                        $errors[] = "The uploaded image could not be saved.";

                    } else {

                        $uploadedFiles = [
                            $originalPath,
                            $thumbnailPath,
                            $mediumPath
                        ];

                        // STEP 9F: CREATE THE IMAGE SIZES

                        try {

                            process_image("images", $originalFileName);

                            // Confirm the thumbnail was created.
                            if (!is_file($thumbnailPath)) {
                                throw new RuntimeException(
                                    "The thumbnail could not be created."
                                );
                            }

                            // Save the thumbnail filename in the database.
                            $newImageName = $thumbnailName;

                        } catch (Throwable $e) {

                            foreach ($uploadedFiles as $filePath) {
                                if (is_file($filePath)) {
                                    @unlink($filePath);
                                }
                            }

                            $uploadedFiles = [];

                            $errors[] = "The cover image could not be processed. Please try another image.";
                        }
                    }
                }
            }
        }
    }


    // STEP 10: UPDATE THE DATABASE IF THERE ARE NO ERRORS

    if (empty($errors)) {

        // If a new cover was uploaded, use its thumbnail filename.
        // Otherwise, keep the existing cover filename.
        $imageNameToSave = $newImageName ?? $imageName;

        try {

            $sql = "
                UPDATE books
                SET
                    title = :title,
                    author = :author,
                    genre = :genre,
                    year_published = :year_published,
                    rating = :rating,
                    date_added = :date_added,
                    imageName = :imageName
                WHERE id = :id
            ";

            $statement = $pdo->prepare($sql);

            $statement->execute([
                ":title" => $title,
                ":author" => $author,
                ":genre" => $genre,
                ":year_published" => $year_published,
                ":rating" => $rating,
                ":date_added" => $date_added,
                ":imageName" => $imageNameToSave,
                ":id" => $id
            ]);

            // STEP 11: RETURN TO THE BOOK COLLECTION

            header("Location: index.php");
            exit;

        } catch (PDOException $e) {

            // Remove newly uploaded files if the database update fails.
            foreach ($uploadedFiles as $filePath) {
                if (is_file($filePath)) {
                    @unlink($filePath);
                }
            }

            header("Location: error.php");
            exit;
        }
    }
}


// STEP 12: PREPARE THE CURRENT COVER PREVIEW

$previewName = !empty($imageName)
    ? basename($imageName)
    : $defaultImage;

$previewPath = $imageDirectory
    . DIRECTORY_SEPARATOR . $previewName;

if (!is_file($previewPath)) {
    $previewName = $defaultImage;
}

$previewUrl = "images/" . rawurlencode($previewName);


// STEP 13: LOAD THE REUSABLE HEADER

require_once "header.php";

?>

<!-- STEP 14: PAGE HEADING -->

<section class="page-heading">

    <h2>Edit Book</h2>

    <p>Update the book information or replace its cover.</p>

</section>


<!-- STEP 15: DISPLAY VALIDATION ERRORS -->

<?php if (!empty($errors)): ?>

    <section class="error-box">

        <h3>Please correct the following:</h3>

        <ul>
            <?php foreach ($errors as $error): ?>
                <li>
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </li>
            <?php endforeach; ?>
        </ul>

    </section>

<?php endif; ?>


<!-- STEP 16: EDIT FORM
     IMPORTANT: enctype allows image uploads. -->

<form
    method="POST"
    action="edit.php?id=<?= (int) $id ?>"
    enctype="multipart/form-data"
>

    <!-- BOOK TITLE -->

    <p>
        <label for="title">Book Title</label>
        <br>
        <input
            type="text"
            id="title"
            name="title"
            value="<?= htmlspecialchars($title, ENT_QUOTES, "UTF-8") ?>"
            required
        >
    </p>


    <!-- AUTHOR -->

    <p>
        <label for="author">Author</label>
        <br>
        <input
            type="text"
            id="author"
            name="author"
            value="<?= htmlspecialchars($author, ENT_QUOTES, "UTF-8") ?>"
            required
        >
    </p>


    <!-- GENRE -->

    <p>
        <label for="genre">Genre</label>
        <br>
        <input
            type="text"
            id="genre"
            name="genre"
            value="<?= htmlspecialchars($genre, ENT_QUOTES, "UTF-8") ?>"
            required
        >
    </p>


    <!-- PUBLICATION YEAR -->

    <p>
        <label for="year_published">Year Published</label>
        <br>
        <input
            type="number"
            id="year_published"
            name="year_published"
            value="<?= htmlspecialchars($year_published, ENT_QUOTES, "UTF-8") ?>"
            min="0"
            max="2100"
            required
        >
    </p>


    <!-- RATING -->

    <p>
        <label for="rating">Rating</label>
        <br>
        <input
            type="number"
            id="rating"
            name="rating"
            value="<?= htmlspecialchars($rating, ENT_QUOTES, "UTF-8") ?>"
            min="0"
            max="5"
            step="0.1"
            required
        >
        <br>
        <small>Enter a rating between 0 and 5.</small>
    </p>


    <!-- DATE ADDED -->

    <p>
        <label for="date_added">Date Added</label>
        <br>
        <input
            type="date"
            id="date_added"
            name="date_added"
            value="<?= htmlspecialchars($date_added, ENT_QUOTES, "UTF-8") ?>"
            required
        >
    </p>


    <!-- STEP 17: CURRENT BOOK COVER -->

    <section class="current-book-cover">

        <h3>Current Book Cover</h3>

        <img
            src="<?= htmlspecialchars($previewUrl, ENT_QUOTES, "UTF-8") ?>"
            alt="Current cover of <?= htmlspecialchars($title, ENT_QUOTES, "UTF-8") ?>"
            width="100"
            height="120"
            style="object-fit: contain; display: block;"
        >

        <p>
            <small>
                Your current cover will remain unchanged unless you select a replacement.
            </small>
        </p>

    </section>


    <!-- STEP 18: UPLOAD A REPLACEMENT COVER -->

    <p>
        <label for="book_image">Replace Book Cover Image (Optional)</label>
        <br>
        <input
            type="file"
            id="book_image"
            name="book_image"
            accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif"
        >
        <br>
        <small>
            Accepted formats: JPG, PNG, or GIF. Maximum size: 5 MB.
        </small>
    </p>


    <!-- STEP 19: FORM BUTTONS -->

    <p>
        <button type="submit" class="button">
            Save Changes
        </button>

        <a href="index.php" class="button">
            Cancel
        </a>
    </p>

</form>


<?php

// STEP 20: LOAD THE REUSABLE FOOTER

require_once "footer.php";

?>
