<?php

// ============================================================
// PHP ASSIGNMENT 1
// BOOK MANAGER APPLICATION
// ============================================================
//
// FILE: edit.php
//
// PURPOSE:
// This page allows the user to UPDATE an existing book.
//
// CRUD OPERATION:
// UPDATE
//
// The page will:
//
// 1. Connect to the database.
// 2. Get the book ID from the URL.
// 3. Find the matching book.
// 4. Display the existing information.
// 5. Allow the user to edit the information.
// 6. Validate the updated information.
// 7. Update the database.
// 8. Return to the main page.
//
// ============================================================


// ============================================================
// STEP 1: CONNECT TO THE DATABASE
// ============================================================

require_once "db.php";


// ============================================================
// STEP 2: GET THE BOOK ID FROM THE URL
// ============================================================
//
// When the user clicks Edit on index.php, the link looks like:
//
// edit.php?id=6
//
// The number "6" is the ID of the book.
//
// $_GET["id"] allows PHP to read that number.
// ============================================================

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);


// ============================================================
// STEP 3: CHECK WHETHER A VALID ID WAS PROVIDED
// ============================================================
//
// If the ID is missing or invalid, we cannot determine
// which book the user wants to edit.
//
// Therefore, we return to the main page.
// ============================================================

if ($id === false || $id === null || $id <= 0) {

    header("Location: index.php");
    exit;
}


// ============================================================
// STEP 4: CREATE VARIABLES FOR THE BOOK INFORMATION
// ============================================================
//
// These variables will hold the existing book information.
// ============================================================

$title = "";
$author = "";
$genre = "";
$year_published = "";
$rating = "";
$date_added = "";


// ============================================================
// STEP 5: FIND THE EXISTING BOOK
// ============================================================
//
// We use a prepared statement to safely search for the
// book with the requested ID.
// ============================================================

try {

    $sql = "
        SELECT
            id,
            title,
            author,
            genre,
            year_published,
            rating,
            date_added
        FROM books
        WHERE id = :id
    ";

    $statement = $pdo->prepare($sql);

    $statement->execute([
        ":id" => $id
    ]);

    $book = $statement->fetch(PDO::FETCH_ASSOC);


} catch (PDOException $e) {

    // If the database query fails, use our friendly
    // error page.

    header("Location: error.php");
    exit;
}


// ============================================================
// STEP 6: CHECK WHETHER THE BOOK EXISTS
// ============================================================
//
// If no book was found with this ID, we return to the
// main page instead of showing an empty edit form.
// ============================================================

if (!$book) {

    header("Location: index.php");
    exit;
}


// ============================================================
// STEP 7: LOAD THE EXISTING BOOK INFORMATION
// ============================================================
//
// We copy the database values into our form variables.
// ============================================================

$title = $book["title"];

$author = $book["author"];

$genre = $book["genre"];

$year_published = $book["year_published"];

$rating = $book["rating"];

$date_added = $book["date_added"];


// ============================================================
// STEP 8: CREATE AN ARRAY FOR VALIDATION ERRORS
// ============================================================

$errors = [];


// ============================================================
// STEP 9: CHECK WHETHER THE EDIT FORM WAS SUBMITTED
// ============================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    // ========================================================
    // STEP 10: GET THE UPDATED FORM VALUES
    // ========================================================

    $title = trim($_POST["title"] ?? "");

    $author = trim($_POST["author"] ?? "");

    $genre = trim($_POST["genre"] ?? "");

    $year_published = trim($_POST["year_published"] ?? "");

    $rating = trim($_POST["rating"] ?? "");

    $date_added = trim($_POST["date_added"] ?? "");


    // ========================================================
    // STEP 11: VALIDATE THE TITLE
    // ========================================================

    if ($title === "") {

        $errors[] = "Book title is required.";

    }


    // ========================================================
    // STEP 12: VALIDATE THE AUTHOR
    // ========================================================

    if ($author === "") {

        $errors[] = "Author name is required.";

    }


    // ========================================================
    // STEP 13: VALIDATE THE GENRE
    // ========================================================

    if ($genre === "") {

        $errors[] = "Genre is required.";

    }


    // ========================================================
    // STEP 14: VALIDATE THE PUBLICATION YEAR
    // ============================================================

    if ($year_published === "") {

        $errors[] = "Publication year is required.";

    } elseif (
        !filter_var(
            $year_published,
            FILTER_VALIDATE_INT,
            [
                "options" => [
                    "min_range" => 0,
                    "max_range" => 2100
                ]
            ]
        )
    ) {

        $errors[] = "Please enter a valid publication year.";

    }


    // ========================================================
    // STEP 15: VALIDATE THE RATING
    // ========================================================

    if ($rating === "") {

        $errors[] = "Rating is required.";

    } elseif (!is_numeric($rating)) {

        $errors[] = "Rating must be a number.";

    } elseif ($rating < 0 || $rating > 5) {

        $errors[] = "Rating must be between 0 and 5.";

    }


    // ========================================================
    // STEP 16: VALIDATE THE DATE
    // ========================================================

    if ($date_added === "") {

        $errors[] = "Date added is required.";

    }


    // ========================================================
    // STEP 17: UPDATE THE DATABASE
    // ========================================================
    //
    // We only perform the UPDATE when there are no
    // validation errors.
    // ========================================================

    if (empty($errors)) {

        try {

            $sql = "
                UPDATE books
                SET
                    title = :title,
                    author = :author,
                    genre = :genre,
                    year_published = :year_published,
                    rating = :rating,
                    date_added = :date_added
                WHERE id = :id
            ";

            $statement = $pdo->prepare($sql);


            // ==================================================
            // STEP 18: EXECUTE THE UPDATE
            // ==================================================

            $statement->execute([
                ":title" => $title,
                ":author" => $author,
                ":genre" => $genre,
                ":year_published" => $year_published,
                ":rating" => $rating,
                ":date_added" => $date_added,
                ":id" => $id
            ]);


            // ==================================================
            // STEP 19: RETURN TO THE MAIN PAGE
            // ==================================================

            header("Location: index.php");
            exit;


        } catch (PDOException $e) {

            // ==================================================
            // STEP 20: HANDLE DATABASE ERRORS
            // ==================================================

            header("Location: error.php");
            exit;
        }
    }
}


// ============================================================
// STEP 21: LOAD THE REUSABLE HEADER
// ============================================================

require_once "header.php";

?>


<!-- ==========================================================
     STEP 22: PAGE HEADING
     ========================================================== -->

<section class="page-heading">

    <h2>Edit Book</h2>

    <p>
        Update the information for this book.
    </p>

</section>


<!-- ==========================================================
     STEP 23: DISPLAY VALIDATION ERRORS
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
     STEP 24: EDIT BOOK FORM
     ========================================================== -->

<form method="POST" action="edit.php?id=<?= htmlspecialchars($id, ENT_QUOTES, "UTF-8") ?>">


    <!-- ======================================================
         STEP 25: BOOK TITLE
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
         STEP 26: AUTHOR
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
         STEP 27: GENRE
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
         STEP 28: PUBLICATION YEAR
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
         STEP 29: RATING
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
         STEP 30: DATE ADDED
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
         STEP 31: FORM BUTTONS
         ====================================================== -->

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

// ============================================================
// STEP 32: LOAD THE REUSABLE FOOTER
// ============================================================

require_once "footer.php";

?>
