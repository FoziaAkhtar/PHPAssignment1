<?php

// ============================================================
// PHP ASSIGNMENT 1
// BOOK MANAGER APPLICATION
// ============================================================
//
// FILE: index.php
//
// PURPOSE:
// This is the MAIN PAGE of our Book Manager application.
//
// This page will:
//
// 1. Connect to the MySQL database.
// 2. Retrieve all books from the books table.
// 3. Safely prepare the information for display.
// 4. Include our reusable header.
// 5. Display the books in an HTML table.
// 6. Provide Edit and Delete links.
// 7. Include our reusable footer.
//
// ============================================================


// ============================================================
// STEP 1: CONNECT TO THE DATABASE
// ============================================================
//
// We use require_once to load db.php.
//
// db.php creates our PDO database connection and stores
// the connection inside the $pdo variable.
//
// require_once means PHP will include this file only once.
//
// IMPORTANT:
// We do NOT write the database connection code again here.
// It is already contained inside db.php.
// ============================================================

require_once "db.php";


// ============================================================
// STEP 2: CREATE A SAFE OUTPUT FUNCTION
// ============================================================
//
// Information coming from a database should not be printed
// directly onto an HTML page.
//
// htmlspecialchars() converts special HTML characters
// into safe HTML entities.
//
// For example:
//
// <script>
//
// would be displayed as text instead of being interpreted
// as actual browser code.
//
// This helps protect our application from Cross-Site
// Scripting (XSS) attacks.
//
// We create a reusable function so that we don't have to
// write htmlspecialchars() repeatedly.
// ============================================================

function escape($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}


// ============================================================
// STEP 3: RETRIEVE ALL BOOKS
// ============================================================
//
// We prepare a SQL SELECT statement.
//
// SELECT * means:
// "Get all columns"
//
// FROM books means:
// "Get the information from our books table."
//
// ORDER BY id ASC means:
// "Display the oldest/lowest ID first."
//
// Using PDO's prepare() and execute() is good practice
// because it separates SQL from data values.
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
        ORDER BY id ASC
    ";

    $statement = $pdo->prepare($sql);

    $statement->execute();

    // Fetch all records into an array.
    $books = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    // ========================================================
    // STEP 4: HANDLE DATABASE QUERY ERRORS
    // ========================================================
    //
    // If something goes wrong while retrieving the books,
    // send the user to our friendly error page.
    //
    // We do NOT display the technical database error
    // to the normal user.
    // ========================================================

    header("Location: error.php");
    exit;
}


// ============================================================
// STEP 5: LOAD THE REUSABLE HEADER
// ============================================================
//
// header.php contains:
//
// - HTML document setup
// - Page title
// - CSS connection
// - Application heading
// - Navigation
// - Opening <main> element
//
// We don't need to repeat all of that code here.
// ============================================================

require_once "header.php";

?>

<!-- ==========================================================
     STEP 6: MAIN PAGE HEADING
     ========================================================== -->

<section class="page-heading">

    <h2>My Book Collection</h2>

    <p>
        Browse and manage the books in your collection.
    </p>

</section>


<!-- ==========================================================
     STEP 7: ADD BOOK BUTTON
     ========================================================== -->

<div class="action-bar">

    <a href="add.php" class="button">
        + Add New Book
    </a>

</div>


<!-- ==========================================================
     STEP 8: CHECK WHETHER BOOKS EXIST
     ==========================================================
//
// If the database contains no books, we don't want to
// display an empty table.
//
// Instead, we display a friendly message.
// ========================================================== -->

<?php if (count($books) === 0): ?>

    <section class="empty-message">

        <h3>No Books Found</h3>

        <p>
            Your book collection is currently empty.
        </p>

    </section>

<?php else: ?>


    <!-- ======================================================
         STEP 9: BOOK TABLE
         ====================================================== -->

    <div class="table-container">

        <table>

            <!-- ==================================================
                 STEP 10: TABLE HEADER
                 ================================================== -->

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Title</th>

                    <th>Author</th>

                    <th>Genre</th>

                    <th>Year Published</th>

                    <th>Rating</th>

                    <th>Date Added</th>

                    <th>Actions</th>

                </tr>

            </thead>


            <!-- ==================================================
                 STEP 11: TABLE BODY
                 ================================================== -->

            <tbody>

                <?php foreach ($books as $book): ?>

                    <tr>

                        <!-- ======================================
                             BOOK ID
                             ====================================== -->

                        <td>
                            <?= escape($book['id']) ?>
                        </td>


                        <!-- ======================================
                             BOOK TITLE
                             ====================================== -->

                        <td>
                            <?= escape($book['title']) ?>
                        </td>


                        <!-- ======================================
                             AUTHOR
                             ====================================== -->

                        <td>
                            <?= escape($book['author']) ?>
                        </td>


                        <!-- ======================================
                             GENRE
                             ====================================== -->

                        <td>
                            <?= escape($book['genre']) ?>
                        </td>


                        <!-- ======================================
                             YEAR PUBLISHED
                             ====================================== -->

                        <td>
                            <?= escape($book['year_published']) ?>
                        </td>


                        <!-- ======================================
                             RATING
                             ====================================== -->

                        <td>
                            <?= escape($book['rating']) ?>
                        </td>


                        <!-- ======================================
                             DATE ADDED
                             ====================================== -->

                        <td>
                            <?= escape($book['date_added']) ?>
                        </td>


                        <!-- ======================================
                             ACTIONS
                             ====================================== -->

                        <td class="actions">

                            <a
                                href="edit.php?id=<?= escape($book['id']) ?>"
                                class="button small-button"
                            >
                                Edit
                            </a>

<!-- ==========================================================
     STEP 1: DELETE BOOK FORM
     ==========================================================

     We use a FORM instead of a normal <a> link because
     our delete.php file expects the book ID to be sent
     using the POST method.

     Why POST?

     A DELETE operation changes information in the database.

     Therefore, we should not perform the delete operation
     simply by opening a URL such as:

         delete.php?id=5

     Instead, the browser sends the ID securely through
     the form using POST.

     The process is:

         User clicks Delete
                 ↓
         Confirmation message appears
                 ↓
         User confirms
                 ↓
         Form sends POST request
                 ↓
         delete.php receives the book ID
                 ↓
         Database deletes the book
                 ↓
         User returns to index.php

     ========================================================== -->

<form
    method="POST"
    action="delete.php"
    style="display: inline;"
    onsubmit="return confirm('Are you sure you want to delete this book?');"
>


    <!-- ======================================================
         STEP 2: HIDDEN BOOK ID
         ======================================================

         The user does not need to see the ID.

         However, delete.php needs to know which book
         should be deleted.

         Therefore, we use a hidden input.

         Example:

         If the book ID is 4, the browser sends:

             id = 4

         The user does not see this field on the page.

         ====================================================== -->

    <input
        type="hidden"
        name="id"
        value="<?= escape($book['id']) ?>"
    >


    <!-- ======================================================
         STEP 3: DELETE BUTTON
         ======================================================

         This is the button the user clicks to delete
         the selected book.

         type="submit" tells the browser to submit the
         surrounding form.

         The form then sends the book ID to:

             delete.php

         using the POST method.

         ====================================================== -->

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
// STEP 12: LOAD THE REUSABLE FOOTER
// ============================================================
//
// footer.php contains:
//
// - Closing </main>
// - Footer information
// - Closing </body>
// - Closing </html>
//
// ============================================================

require_once "footer.php";

?>
