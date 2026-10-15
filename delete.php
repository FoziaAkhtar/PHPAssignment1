<?php

// ============================================================
// ASSIGNMENT 4 — PROTECT THE DELETE BOOK PAGE
// ============================================================
//
// STEP 0: CHECK LOGIN STATUS
//
// auth.php checks whether the user is signed in.
// If the user is not signed in, they are redirected
// to login_form.php before the deletion code can run.
// ============================================================

require_once "auth.php";



// ============================================================
// STEP 1: CONNECT TO THE DATABASE
// ============================================================

require_once "db.php";


// ============================================================
// STEP 2: MAKE SURE THE REQUEST USES POST
// ============================================================
//
// We only want a book to be deleted when the application
// sends a POST request.
//
// If somebody tries to open delete.php directly in the
// browser, we simply return to the homepage.
// ============================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");
    exit;
}


// ============================================================
// STEP 3: GET THE BOOK ID
// ============================================================
//
// The ID is sent from the delete form on index.php.
//
// Example:
//
// id = 4
//
// filter_input() helps us make sure the value is treated
// as an integer.
// ============================================================

$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);


// ============================================================
// STEP 4: VALIDATE THE BOOK ID
// ============================================================
//
// A valid database ID should be a positive integer.
//
// If the ID is missing or invalid, we return to the
// homepage without deleting anything.
// ============================================================

if ($id === false || $id === null || $id <= 0) {

    header("Location: index.php");
    exit;
}


// ============================================================
// STEP 5: DELETE THE BOOK
// ============================================================
//
// We use a prepared statement.
//
// The ":id" placeholder is replaced safely by the
// actual book ID.
//
// This helps protect the application against SQL injection.
// ============================================================

try {

    $sql = "
        DELETE FROM books
        WHERE id = :id
    ";

    $statement = $pdo->prepare($sql);


    // ========================================================
    // STEP 6: EXECUTE THE DELETE
    // ========================================================

    $statement->execute([
        ":id" => $id
    ]);


} catch (PDOException $e) {

    // ========================================================
    // STEP 7: HANDLE DATABASE ERRORS
    // ========================================================
    //
    // If the DELETE operation fails, send the user to our
    // friendly error page.
    // ========================================================

    header("Location: error.php");
    exit;
}


// ============================================================
// STEP 8: RETURN TO THE MAIN PAGE
// ============================================================
//
// After the deletion is complete, return the user to
// the Book Manager homepage.
//
// ============================================================

header("Location: index.php");
exit;

?>
