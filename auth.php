
<?php
// =====================================================
// STEP 1: START THE SESSION
// This allows PHP to check whether a user is logged in.
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// STEP 2: CHECK THE LOGIN STATUS
// If the user is not logged in, redirect to login_form.php.
// =====================================================
if (
    !isset($_SESSION['isLoggedIn']) ||
    $_SESSION['isLoggedIn'] !== true
) {
    header('Location: login_form.php');
    exit;
}
?>
