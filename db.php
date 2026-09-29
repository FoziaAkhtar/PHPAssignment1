<?php
// =====================================================
// STEP 1: DATABASE CONNECTION
// This file connects our PHP application to MySQL.
// =====================================================

$host = "localhost";
$dbname = "book_manager";
$username = "root";
$password = "";

// =====================================================
// STEP 2: CREATE THE PDO CONNECTION
// =====================================================

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Tell PDO to report database errors as exceptions.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    // If the database connection fails,
    // send the user to our friendly error page.
    header("Location: error.php");
    exit;
}
?>
