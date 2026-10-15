
<?php

// ============================================================
// ASSIGNMENT 4 — PROTECT THE ADD BOOK PAGE
// ============================================================
//
// STEP 0: CHECK LOGIN STATUS
//
// auth.php checks whether the user is signed in.
// If the user is not signed in, they are redirected
// to login_form.php before the Add Book page can load.
//
// require_once prevents the same file from being included
// more than once during the request.
// ============================================================

require_once "auth.php";


// ============================================================
// PHP ASSIGNMENT 3
// BOOK MANAGER APPLICATION
// ============================================================
//
// KEEP ALL YOUR EXISTING CODE BELOW THIS POINT.
// ============================================================


require_once "db.php";


// ============================================================
// STEP 2: LOAD THE IMAGE PROCESSING FUNCTIONS
// ============================================================

require_once "image_util.php";


// ============================================================
// STEP 3: SET UP THE IMAGE FOLDER AND DEFAULT IMAGE
// ============================================================

$imageDirectory = __DIR__ . DIRECTORY_SEPARATOR . "images";

// This is the thumbnail assigned when no image is uploaded.
$defaultImage = "placeholder_100.jpg";


// ============================================================
// STEP 4: CREATE VARIABLES FOR THE FORM VALUES
// ============================================================

$title = "";
$author = "";
$genre = "";
$year_published = "";
$rating = "";
$date_added = date("Y-m-d");


// ============================================================
// STEP 5: CREATE AN ARRAY FOR VALIDATION ERRORS
// ============================================================

$errors = [];


// ============================================================
// STEP 6: PREPARE VARIABLES FOR IMAGE UPLOADING
// ============================================================

$imageName = $defaultImage;
$uploadedOriginalPath = "";
$uploaded400Path = "";
$uploaded100Path = "";


// ============================================================
// STEP 7: CHECK WHETHER THE FORM WAS SUBMITTED
// ============================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ========================================================
    // STEP 8: GET THE FORM VALUES
    // ========================================================

    $title = trim($_POST["title"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $genre = trim($_POST["genre"] ?? "");
    $year_published = trim($_POST["year_published"] ?? "");
    $rating = trim($_POST["rating"] ?? "");
    $date_added = trim($_POST["date_added"] ?? "");


    // ========================================================
    // STEP 9: VALIDATE THE BOOK TITLE
    // ========================================================

    if ($title === "") {
        $errors[] = "Book title is required.";
    }


    // ========================================================
    // STEP 10: VALIDATE THE AUTHOR
    // ========================================================

    if ($author === "") {
        $errors[] = "Author name is required.";
    }


    // ========================================================
    // STEP 11: VALIDATE THE GENRE
    // ========================================================

    if ($genre === "") {
        $errors[] = "Genre is required.";
    }


    // ========================================================
    // STEP 12: VALIDATE THE PUBLICATION YEAR
    // ========================================================

    if ($year_published === "") {

        $errors[] = "Publication year is required.";

    } else {

        $validYear = filter_var(
            $year_published,
            FILTER_VALIDATE_INT,
            [
                "options" => [
                    "min_range" => 0,
                    "max_range" => 2100
                ]
            ]
        );

        if ($validYear === false) {
            $errors[] = "Please enter a valid publication year.";
        }
    }


    // ========================================================
    // STEP 13: VALIDATE THE RATING
    // ========================================================

    if ($rating === "") {

        $errors[] = "Rating is required.";

    } elseif (!is_numeric($rating)) {

        $errors[] = "Rating must be a number.";

    } elseif ((float) $rating < 0 || (float) $rating > 5) {

        $errors[] = "Rating must be between 0 and 5.";
    }


    // ========================================================
    // STEP 14: VALIDATE THE DATE ADDED
    // ========================================================

    $dateObject = DateTime::createFromFormat("!Y-m-d", $date_added);

    if (
        $date_added === "" ||
        $dateObject === false ||
        $dateObject->format("Y-m-d") !== $date_added
    ) {
        $errors[] = "Please enter a valid date.";
    }


    // ========================================================
    // STEP 15: CHECK WHETHER AN IMAGE WAS UPLOADED
    // ========================================================
    //
    // Uploading a cover is optional.
    //
    // If no image is selected, the default thumbnail will be
    // saved in the database.
    //
    // Accepted image types:
    // JPEG, PNG, and GIF.
    //
    // Maximum upload size:
    // 5 MB.
    //
    // ========================================================

    $imageToProcess = null;

    if (isset($_FILES["book_image"])) {

        $uploadError = $_FILES["book_image"]["error"];

        // ----------------------------------------------------
        // STEP 15A: CHECK FOR UPLOAD ERRORS
        // ----------------------------------------------------

        if ($uploadError !== UPLOAD_ERR_NO_FILE) {

            if ($uploadError !== UPLOAD_ERR_OK) {

                $errors[] = "The image upload failed. Please try again.";

            } else {

                // --------------------------------------------
                // STEP 15B: CHECK THE FILE SIZE
                // --------------------------------------------

                $maxFileSize = 5 * 1024 * 1024;

                if ($_FILES["book_image"]["size"] > $maxFileSize) {

                    $errors[] = "The image must be 5 MB or smaller.";

                } else {

                    // ----------------------------------------
                    // STEP 15C: CHECK THE ACTUAL IMAGE TYPE
                    // ----------------------------------------
                    //
                    // Do not rely only on the filename extension.
                    // getimagesize() checks the uploaded file.
                    //
                    // ----------------------------------------

                    $temporaryPath = $_FILES["book_image"]["tmp_name"];

                    $imageInformation = @getimagesize($temporaryPath);

                    if ($imageInformation === false) {

                        $errors[] = "Please select a valid image file.";

                    } else {

                        $mimeType = $imageInformation["mime"];

                        $allowedImageTypes = [
                            "image/jpeg" => "jpg",
                            "image/png"  => "png",
                            "image/gif"  => "gif"
                        ];

                        if (!isset($allowedImageTypes[$mimeType])) {

                            $errors[] = "Only JPG, PNG, and GIF images are allowed.";

                        } else {

                            $imageToProcess = [
                                "temporaryPath" => $temporaryPath,
                                "extension" => $allowedImageTypes[$mimeType]
                            ];
                        }
                    }
                }
            }
        }
    }


    // ========================================================
    // STEP 16: SAVE THE IMAGE IF VALIDATION PASSES
    // ========================================================
    //
    // The original image and its two resized versions are saved
    // in the images folder.
    //
    // Example:
    //
    // book_123.jpg
    // book_123_400.jpg
    // book_123_100.jpg
    //
    // The database stores book_123_100.jpg.
    //
    // ========================================================

    if (empty($errors) && $imageToProcess !== null) {

        try {

            // -----------------------------------------------
            // STEP 16A: GENERATE A UNIQUE FILENAME
            // -----------------------------------------------

            $uniqueBaseName = "book_" . bin2hex(random_bytes(8));

            $extension = "." . $imageToProcess["extension"];

            $originalFileName = $uniqueBaseName . $extension;

            $uploadedOriginalPath =
                $imageDirectory . DIRECTORY_SEPARATOR . $originalFileName;

            $uploaded400Path =
                $imageDirectory . DIRECTORY_SEPARATOR .
                $uniqueBaseName . "_400" . $extension;

            $uploaded100Path =
                $imageDirectory . DIRECTORY_SEPARATOR .
                $uniqueBaseName . "_100" . $extension;


            // -----------------------------------------------
            // STEP 16B: MOVE THE UPLOADED IMAGE
            // -----------------------------------------------

            if (!is_dir($imageDirectory)) {
                throw new RuntimeException("The images folder was not found.");
            }

            if (!move_uploaded_file(
                $imageToProcess["temporaryPath"],
                $uploadedOriginalPath
            )) {
                throw new RuntimeException("The uploaded image could not be saved.");
            }


            // -----------------------------------------------
            // STEP 16C: CREATE THE RESIZED IMAGES
            // -----------------------------------------------
            //
            // process_image() is provided by image_util.php.
            //
            // It creates:
            // - A maximum 400 x 300 image.
            // - A maximum 100 x 100 thumbnail.
            //
            // -----------------------------------------------

            process_image("images", $originalFileName);


            // -----------------------------------------------
            // STEP 16D: SET THE THUMBNAIL FILENAME
            // -----------------------------------------------

            $imageName = $uniqueBaseName . "_100" . $extension;

        } catch (Throwable $e) {

            // -----------------------------------------------
            // STEP 16E: REMOVE INCOMPLETE IMAGE FILES
            // -----------------------------------------------

            foreach (
                [
                    $uploadedOriginalPath,
                    $uploaded400Path,
                    $uploaded100Path
                ] as $filePath
            ) {
                if ($filePath !== "" && is_file($filePath)) {
                    @unlink($filePath);
                }
            }

            $imageName = $defaultImage;

            $errors[] =
                "The image could not be processed. Check the image folder and PHP image support, then try again.";
        }
    }


    // ========================================================
    // STEP 17: INSERT THE BOOK INTO THE DATABASE
    // ========================================================
    //
    // Continue only if there are no validation or image errors.
    //
    // imageName stores the filename of the thumbnail.
    //
    // Prepared statements help protect against SQL injection.
    //
    // ========================================================

    if (empty($errors)) {

        try {

            $sql = "
                INSERT INTO books
                (
                    title,
                    author,
                    genre,
                    year_published,
                    rating,
                    date_added,
                    imageName
                )
                VALUES
                (
                    :title,
                    :author,
                    :genre,
                    :year_published,
                    :rating,
                    :date_added,
                    :imageName
                )
            ";

            $statement = $pdo->prepare($sql);


            // ==================================================
            // STEP 18: EXECUTE THE PREPARED STATEMENT
            // ==================================================

            $statement->execute([
                ":title" => $title,
                ":author" => $author,
                ":genre" => $genre,
                ":year_published" => $year_published,
                ":rating" => $rating,
                ":date_added" => $date_added,
                ":imageName" => $imageName
            ]);


            // ==================================================
            // STEP 19: REDIRECT AFTER SUCCESS
            // ==================================================

            header("Location: index.php");
            exit;


        } catch (PDOException $e) {

            // -----------------------------------------------
            // REMOVE NEWLY UPLOADED FILES IF THE INSERT FAILS
            // -----------------------------------------------

            foreach (
                [
                    $uploadedOriginalPath,
                    $uploaded400Path,
                    $uploaded100Path
                ] as $filePath
            ) {
                if ($filePath !== "" && is_file($filePath)) {
                    @unlink($filePath);
                }
            }

            header("Location: error.php");
            exit;
        }
    }
}


// ============================================================
// STEP 20: LOAD THE REUSABLE HEADER
// ============================================================

require_once "header.php";

?>


<!-- ==========================================================
     STEP 21: PAGE HEADING
     ========================================================== -->

<section class="page-heading">

    <h2>Add New Book</h2>

    <p>
        Enter the information below to add a book
        to your collection.
    </p>

</section>


<!-- ==========================================================
     STEP 22: DISPLAY VALIDATION ERRORS
     ========================================================== -->

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


<!-- ==========================================================
     STEP 23: BOOK ENTRY FORM
     ========================================================== -->
<!--
     IMPORTANT:
     enctype="multipart/form-data" allows the form to upload
     an image file to the PHP server.
-->

<form
    method="POST"
    action="add.php"
    enctype="multipart/form-data"
>


    <!-- ======================================================
         STEP 24: BOOK TITLE
         ====================================================== -->

    <p>

        <label for="title">
            Book Title
        </label>

        <br>

        <input
            type="text"
            id="title"
            name="title"
            value="<?= htmlspecialchars($title, ENT_QUOTES, "UTF-8") ?>"
            required
        >

    </p>


    <!-- ======================================================
         STEP 25: AUTHOR
         ====================================================== -->

    <p>

        <label for="author">
            Author
        </label>

        <br>

        <input
            type="text"
            id="author"
            name="author"
            value="<?= htmlspecialchars($author, ENT_QUOTES, "UTF-8") ?>"
            required
        >

    </p>


    <!-- ======================================================
         STEP 26: GENRE
         ====================================================== -->

    <p>

        <label for="genre">
            Genre
        </label>

        <br>

        <input
            type="text"
            id="genre"
            name="genre"
            value="<?= htmlspecialchars($genre, ENT_QUOTES, "UTF-8") ?>"
            required
        >

    </p>


    <!-- ======================================================
         STEP 27: PUBLICATION YEAR
         ====================================================== -->

    <p>

        <label for="year_published">
            Year Published
        </label>

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


    <!-- ======================================================
         STEP 28: RATING
         ====================================================== -->

    <p>

        <label for="rating">
            Rating
        </label>

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

        <small>
            Enter a rating between 0 and 5.
        </small>

    </p>


    <!-- ======================================================
         STEP 29: DATE ADDED
         ====================================================== -->

    <p>

        <label for="date_added">
            Date Added
        </label>

        <br>

        <input
            type="date"
            id="date_added"
            name="date_added"
            value="<?= htmlspecialchars($date_added, ENT_QUOTES, "UTF-8") ?>"
            required
        >

    </p>


    <!-- ======================================================
         STEP 30: BOOK COVER IMAGE
         ====================================================== -->

    <p>

        <label for="book_image">
            Book Cover Image
        </label>

        <br>

        <input
            type="file"
            id="book_image"
            name="book_image"
            accept="image/jpeg,image/png,image/gif"
        >

        <br>

        <small>
            Optional. Select a JPG, PNG, or GIF image.
            Maximum file size: 5 MB.
        </small>

    </p>


    <!-- ======================================================
         STEP 31: FORM BUTTONS
         ====================================================== -->

    <p>

        <button type="submit" class="button">
            Add Book
        </button>

        <a href="index.php" class="button">
            Cancel
        </a>

    </p>


</form>


<?php

// ============================================================
// STEP 32: LOAD THE REUSABLE FOOTER
// ============================================================

require_once "footer.php";

?>

