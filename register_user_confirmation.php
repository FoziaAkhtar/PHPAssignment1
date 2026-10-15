
<?php
// =====================================================
// STEP 1: START THE SESSION
// The session contains the registration success message.
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// STEP 2: GET THE SUCCESS MESSAGE
// Remove it from the session after reading it.
// =====================================================
$successMessage = $_SESSION['registration_success']
    ?? 'Your registration process has finished.';

unset($_SESSION['registration_success']);

// =====================================================
// STEP 3: LOAD THE SHARED HEADER
// =====================================================
require_once 'header.php';
?>

<!-- ===================================================
     STEP 4: DISPLAY THE CONFIRMATION
     =================================================== -->

<h2>Registration Successful!</h2>

<p>
    <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
</p>

<p>
    Your account has been created. You can now log in
    to access the Book Manager.
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
