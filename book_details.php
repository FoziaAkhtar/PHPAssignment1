<?php

// ============================================================
// ASSIGNMENT 3 — BOOK MANAGER
// FILE: book_details.php
//
// PURPOSE:
// Display the complete information for one selected book.
// ============================================================


// STEP 1: CONNECT TO THE DATABASE

require_once "db.php";


// STEP 2: CREATE A SAFE OUTPUT FUNCTION

function escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}


// STEP 3: GET THE BOOK ID FROM THE URL

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    header("Location: index.php");
    exit;
}


// STEP 4: FIND THE SELECTED BOOK

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


// STEP 5: CHECK WHETHER THE BOOK EXISTS

if (!$book) {
    header("Location: index.php");
    exit;
}


// STEP 6: FIND THE BOOK COVER

$imageDirectory = __DIR__ . DIRECTORY_SEPARATOR . "images";

$imageName = !empty($book["imageName"])
    ? basename($book["imageName"])
    : "placeholder_100.jpg";

$imagePath = $imageDirectory . DIRECTORY_SEPARATOR . $imageName;

// Use the placeholder if the saved image is missing.
if (!is_file($imagePath)) {
    $imageName = "placeholder_100.jpg";
}

$imageUrl = "images/" . rawurlencode($imageName);


// STEP 7: LOAD THE REUSABLE HEADER

require_once "header.php";

?>

<!-- STEP 8: DISPLAY THE BOOK DETAILS -->

<section class="page-heading">

    <h2>Book Details</h2>

    <p>View the complete information for this book.</p>

</section>


<section class="book-details">

    <!-- BOOK COVER -->

    <div class="book-details-cover">

        <img
            src="<?= escape($imageUrl) ?>"
            alt="Cover of <?= escape($book["title"]) ?>"
            style="max-width: 250px; max-height: 350px; object-fit: contain;"
        >

    </div>


    <!-- BOOK INFORMATION -->

    <div class="book-details-information">

        <h3><?= escape($book["title"]) ?></h3>

        <p>
            <strong>Author:</strong>
            <?= escape($book["author"]) ?>
        </p>

        <p>
            <strong>Genre:</strong>
            <?= escape($book["genre"]) ?>
        </p>

        <p>
            <strong>Year Published:</strong>
            <?= escape($book["year_published"]) ?>
        </p>

        <p>
            <strong>Rating:</strong>
            <?= escape($book["rating"]) ?> / 5
        </p>

        <p>
            <strong>Date Added:</strong>
            <?= escape($book["date_added"]) ?>
        </p>

        <p>
            <strong>Book ID:</strong>
            <?= escape($book["id"]) ?>
        </p>

    </div>

</section>


<!-- STEP 9: NAVIGATION BUTTONS -->

<div class="action-bar">

    <a
        href="edit.php?id=<?= (int) $book["id"] ?>"
        class="button"
    >
        Edit Book
    </a>

    <a href="index.php" class="button">
        Back to Book Collection
    </a>

</div>


<?php

// STEP 10: LOAD THE REUSABLE FOOTER

require_once "footer.php";

?>