
<?php
// =====================================================
// STEP 1: START THE SESSION
// Sessions allow us to retrieve the error message.
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// STEP 2: RETRIEVE AND CLEAR THE ERROR MESSAGE
// This is called the flash-message pattern.
// The message is displayed once, then removed.
// =====================================================
$errorMessage = $_SESSION['user_error']
    ?? 'Something went wrong. Please try again.';

unset($_SESSION['user_error']);

// =====================================================
// STEP 3: LOAD THE SHARED HEADER
// =====================================================
require_once 'header.php';
?>

<!-- ===================================================
     STEP 4: DISPLAY THE ERROR
     Escape the message before displaying it in HTML.
// ===================================================== -->

<h2>Unable to Complete Your Request</h2>

<p>
    <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
</p>

<p>
    <a href="register_user_form.php">Return to Registration</a>
</p>

<p>
    <a href="login_form.php">Go to Login</a>
</p>

<?php
// =====================================================
// STEP 5: LOAD THE SHARED FOOTER
// =====================================================
require_once 'footer.php';
?>
