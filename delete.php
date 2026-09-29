<?php

// ============================================================
// PHP ASSIGNMENT 1
// BOOK MANAGER APPLICATION
// ============================================================
//
// FILE: delete.php
//
// PURPOSE:
// This page deletes an existing book from the database.
//
// CRUD OPERATION:
// DELETE
//
// IMPORTANT:
// We use POST for the actual delete operation.
//
// The ID of the book is received from the form on
// index.php.
//
// The page will:
//
// 1. Connect to the database.
// 2. Check that a valid book ID was received.
// 3. Delete the matching book.
// 4. Return to the Book Manager homepage.
//
// ============================================================


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
