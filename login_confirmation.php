
<?php
// =====================================================
// STEP 1: START THE SESSION
// This allows us to check the authenticated user.
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// STEP 2: PROTECT THE CONFIRMATION PAGE
// Only authenticated users can see this page.
// =====================================================
if (
    !isset($_SESSION['isLoggedIn']) ||
    $_SESSION['isLoggedIn'] !== true
) {
    header('Location: login_form.php');
    exit;
}

// =====================================================
// STEP 3: LOAD THE SHARED HEADER
// =====================================================
require_once 'header.php';
?>

<!-- ===================================================
     STEP 4: DISPLAY THE LOGIN CONFIRMATION
     =================================================== -->

<h2>Login Successful!</h2>

<p>
    Welcome,
    <?php
    echo htmlspecialchars(
        $_SESSION['userName'],
        ENT_QUOTES,
        'UTF-8'
    );
    ?>!
</p>

<p>You are now logged in to Book Manager.</p>

<p>
    <a href="index.php">View the Book Collection</a>
</p>

<p>
    <a href="logout.php">Log Out</a>
</p>

<?php
// =====================================================
// STEP 5: LOAD THE SHARED FOOTER
// =====================================================
require_once 'footer.php';
?>
