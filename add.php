<?php

// ============================================================
// PHP ASSIGNMENT 1
// BOOK MANAGER APPLICATION
// ============================================================
//
// FILE: add.php
//
// PURPOSE:
// This page allows the user to add a new book.
//
// CRUD OPERATION:
// CREATE
//
// The page will:
//
// 1. Connect to the database.
// 2. Display a book entry form.
// 3. Check that the form was submitted.
// 4. Validate the user's information.
// 5. Insert the new book into MySQL.
// 6. Redirect back to the homepage after success.
//
// ============================================================


// ============================================================
// STEP 1: CONNECT TO THE DATABASE
// ============================================================
//
// db.php creates our PDO connection.
//
// The connection is stored in:
//
// $pdo
//
// ============================================================

require_once "db.php";


// ============================================================
// STEP 2: CREATE VARIABLES FOR FORM VALUES
// ============================================================
//
// These variables allow us to keep the information entered
// by the user if validation fails.
//
// Starting with empty values means the form will initially
// appear blank.
// ============================================================

$title = "";
$author = "";
$genre = "";
$year_published = "";
$rating = "";
$date_added = date("Y-m-d");


// ============================================================
// STEP 3: CREATE AN ARRAY FOR VALIDATION ERRORS
// ============================================================
//
// If the user enters invalid information, we can store
// the error messages inside this array.
//
// Example:
//
// $errors[] = "Title is required.";
//
// ============================================================

$errors = [];


// ============================================================
// STEP 4: CHECK WHETHER THE FORM WAS SUBMITTED
// ============================================================
//
// The form below will use:
//
// method="POST"
//
// Therefore, when the user clicks "Add Book", PHP will
// receive the information through $_SERVER["REQUEST_METHOD"].
//
// ============================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    // ========================================================
    // STEP 5: GET THE FORM VALUES
    // ========================================================
    //
    // trim() removes unnecessary spaces from the beginning
    // and end of the user's input.
    //
    // Example:
    //
    // "  The Hobbit  "
    //
    // becomes:
    //
    // "The Hobbit"
    //
    // ========================================================

    $title = trim($_POST["title"] ?? "");

    $author = trim($_POST["author"] ?? "");

    $genre = trim($_POST["genre"] ?? "");

    $year_published = trim($_POST["year_published"] ?? "");

    $rating = trim($_POST["rating"] ?? "");

    $date_added = trim($_POST["date_added"] ?? "");


    // ========================================================
    // STEP 6: VALIDATE THE TITLE
    // ========================================================

    if ($title === "") {

        $errors[] = "Book title is required.";

    }


    // ========================================================
    // STEP 7: VALIDATE THE AUTHOR
    // ========================================================

    if ($author === "") {

        $errors[] = "Author name is required.";

    }


    // ========================================================
    // STEP 8: VALIDATE THE GENRE
    // ========================================================

    if ($genre === "") {

        $errors[] = "Genre is required.";

    }


    // ========================================================
    // STEP 9: VALIDATE THE PUBLICATION YEAR
    // ========================================================

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
    // STEP 10: VALIDATE THE RATING
    // ========================================================

    if ($rating === "") {

        $errors[] = "Rating is required.";

    } elseif (!is_numeric($rating)) {

        $errors[] = "Rating must be a number.";

    } elseif ($rating < 0 || $rating > 5) {

        $errors[] = "Rating must be between 0 and 5.";

    }


    // ========================================================
    // STEP 11: VALIDATE THE DATE
    // ========================================================

    if ($date_added === "") {

        $errors[] = "Date added is required.";

    }


    // ========================================================
    // STEP 12: INSERT THE BOOK INTO THE DATABASE
    // ========================================================
    //
    // We only attempt the INSERT if there are no validation
    // errors.
    //
    // IMPORTANT:
    // We use a prepared statement instead of directly placing
    // user input inside the SQL statement.
    //
    // This is safer and helps protect our application
    // against SQL injection.
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
                    date_added
                )
                VALUES
                (
                    :title,
                    :author,
                    :genre,
                    :year_published,
                    :rating,
                    :date_added
                )
            ";

            $statement = $pdo->prepare($sql);


            // ==================================================
            // STEP 13: EXECUTE THE PREPARED STATEMENT
            // ==================================================

            $statement->execute([
                ":title" => $title,
                ":author" => $author,
                ":genre" => $genre,
                ":year_published" => $year_published,
                ":rating" => $rating,
                ":date_added" => $date_added
            ]);


            // ==================================================
            // STEP 14: RETURN TO THE MAIN PAGE
            // ==================================================
            //
            // After successfully adding the book, redirect
            // the user to index.php.
            //
            // This also prevents the browser from submitting
            // the same form again if the user refreshes.
            //
            // ==================================================

            header("Location: index.php");
            exit;


        } catch (PDOException $e) {

            // ==================================================
            // STEP 15: HANDLE DATABASE ERRORS
            // ==================================================
            //
            // If the INSERT fails, send the user to the
            // friendly error page.
            // ==================================================

            header("Location: error.php");
            exit;
        }
    }
}


// ============================================================
// STEP 16: LOAD THE REUSABLE HEADER
// ============================================================
//
// header.php provides:
//
// - HTML structure
// - CSS
// - Book Manager heading
// - Navigation
//
// ============================================================

require_once "header.php";

?>

<!-- ==========================================================
     STEP 17: PAGE HEADING
     ========================================================== -->

<section class="page-heading">

    <h2>Add New Book</h2>

    <p>
        Enter the information below to add a book
        to your collection.
    </p>

</section>


<!-- ==========================================================
     STEP 18: DISPLAY VALIDATION ERRORS
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
     STEP 19: BOOK FORM
     ========================================================== -->

<form method="POST" action="add.php">


    <!-- ======================================================
         STEP 20: BOOK TITLE
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
         STEP 21: AUTHOR
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
         STEP 22: GENRE
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
         STEP 23: PUBLICATION YEAR
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
         STEP 24: RATING
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
         STEP 25: DATE ADDED
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
         STEP 26: FORM BUTTONS
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
// STEP 27: LOAD THE REUSABLE FOOTER
// ============================================================

require_once "footer.php";

?>