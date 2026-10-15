
<?php

// ============================================================
// STEP 0: PROTECT THE BOOK COLLECTION
// Redirect visitors who are not logged in.
// ============================================================

require_once "auth.php";
// ============================================================
// PHP ASSIGNMENT 3
// BOOK MANAGER APPLICATION
// ============================================================
//
// FILE: index.php
//
// PURPOSE:
// 1. Connect to the database.
// 2. Retrieve all books, including cover image filenames.
// 3. Display the book collection in a table.
// 4. Display book cover thumbnails.
// 5. Provide View Details, Edit, and Delete actions.
// 6. Preserve the existing Add Book functionality.
//
// ============================================================


// ============================================================
// STEP 1: CONNECT TO THE DATABASE
// ============================================================

require_once "db.php";


// ============================================================
// STEP 2: CREATE A SAFE OUTPUT FUNCTION
// ============================================================

function escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}


// ============================================================
// STEP 3: RETRIEVE ALL BOOKS
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
            date_added,
            imageName
        FROM books
        ORDER BY id ASC
    ";

    $statement = $pdo->prepare($sql);
    $statement->execute();

    $books = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    header("Location: error.php");
    exit;
}


// ============================================================
// STEP 4: LOAD THE REUSABLE HEADER
// ============================================================

require_once "header.php";

?>


<!-- ==========================================================
     STEP 5: MAIN PAGE HEADING
     ========================================================== -->

<section class="page-heading">

    <h2>My Book Collection</h2>

    <p>
        Browse and manage the books in your collection.
    </p>

</section>


<!-- ==========================================================
     STEP 6: ADD BOOK BUTTON
     ========================================================== -->

<div class="action-bar">

    <a href="add.php" class="button">
        + Add New Book
    </a>

</div>


<!-- ==========================================================
     STEP 7: CHECK WHETHER BOOKS EXIST
     ========================================================== -->

<?php if (count($books) === 0): ?>

    <section class="empty-message">

        <h3>No Books Found</h3>

        <p>
            Your book collection is currently empty.
        </p>

    </section>

<?php else: ?>


    <!-- ======================================================
         STEP 8: DISPLAY THE BOOK TABLE
         ====================================================== -->

    <div class="table-container">

        <table>

            <!-- TABLE HEADINGS -->

            <thead>

                <tr>

                    <th>Cover</th>

                    <th>ID</th>

                    <th>Title</th>

                    <th>Author</th>

                    <th>Genre</th>

                    <th>Year Published</th>

                    <th>Rating</th>

                    <th>Date Added</th>

                    <th>Details</th>

                    <th>Edit</th>

                    <th>Delete</th>

                </tr>

            </thead>


            <!-- ==================================================
                 STEP 9: DISPLAY EACH BOOK
                 ================================================== -->

            <tbody>

                <?php foreach ($books as $book): ?>

                    <?php

                    // STEP 9A: FIND THE BOOK COVER IMAGE

                    // Use the saved thumbnail filename.
                    // If no filename exists, use the placeholder.

                    $coverFile = !empty($book["imageName"])
                        ? basename($book["imageName"])
                        : "placeholder_100.jpg";

                    $coverPath = __DIR__
                        . DIRECTORY_SEPARATOR
                        . "images"
                        . DIRECTORY_SEPARATOR
                        . $coverFile;


                    // STEP 9B: CHECK WHETHER THE IMAGE EXISTS

                    if (!is_file($coverPath)) {
                        $coverFile = "placeholder_100.jpg";
                    }


                    // STEP 9C: CREATE THE IMAGE URL

                    $coverUrl = "images/" . rawurlencode($coverFile);

                    ?>

                    <tr>

                        <!-- BOOK COVER -->

                        <td>

                            <img
                                src="<?= escape($coverUrl) ?>"
                                alt="Cover of <?= escape($book["title"]) ?>"
                                width="70"
                                height="90"
                                loading="lazy"
                                style="object-fit: contain; display: block;"
                            >

                        </td>


                        <!-- BOOK ID -->

                        <td>
                            <?= escape($book["id"]) ?>
                        </td>


                        <!-- BOOK TITLE -->

                        <td>
                            <?= escape($book["title"]) ?>
                        </td>


                        <!-- AUTHOR -->

                        <td>
                            <?= escape($book["author"]) ?>
                        </td>


                        <!-- GENRE -->

                        <td>
                            <?= escape($book["genre"]) ?>
                        </td>


                        <!-- YEAR PUBLISHED -->

                        <td>
                            <?= escape($book["year_published"]) ?>
                        </td>


                        <!-- RATING -->

                        <td>
                            <?= escape($book["rating"]) ?>
                        </td>


                        <!-- DATE ADDED -->

                        <td>
                            <?= escape($book["date_added"]) ?>
                        </td>


                        <!-- ==================================================
                             STEP 10: VIEW BOOK DETAILS
                             ================================================== -->

                        <td class="actions">

                            <a
                                href="book_details.php?id=<?= (int) $book["id"] ?>"
                                class="button small-button"
                            >
                                View Details
                            </a>

                        </td>


                        <!-- ==================================================
                             STEP 11: EDIT BOOK
                             ================================================== -->

                        <td class="actions">

                            <a
                                href="edit.php?id=<?= (int) $book["id"] ?>"
                                class="button small-button"
                            >
                                Edit
                            </a>

                        </td>


                        <!-- ==================================================
                             STEP 12: DELETE BOOK
                             ================================================== -->

                        <td class="actions">

                            <form
                                method="POST"
                                action="delete.php"
                                style="display: inline;"
                                onsubmit="return confirm('Are you sure you want to delete this book?');"
                            >

                                <!-- Hidden book ID -->

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int) $book["id"] ?>"
                                >

                                <!-- Delete button -->

                                <button
                                    type="submit"
                                    class="button small-button delete-button"
                                >
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

<?php endif; ?>


<?php

// ============================================================
// STEP 13: LOAD THE REUSABLE FOOTER
// ============================================================

require_once "footer.php";

?>
