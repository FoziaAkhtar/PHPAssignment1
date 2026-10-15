
<?php
// =====================================================
// STEP 1: START THE SESSION
// Sessions allow us to display messages between pages.
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// STEP 2: LOAD THE SHARED HEADER
// =====================================================
require_once 'header.php';
?>

<!-- ===================================================
     STEP 3: REGISTRATION FORM
     The form sends the submitted data to register_user.php.
     =================================================== -->

<h2>Create Your Book Manager Account</h2>

<form action="register_user.php" method="POST">

    <div>
        <label for="userName">Username:</label>
        <input
            type="text"
            id="userName"
            name="userName"
            maxlength="50"
            required
            autocomplete="username"
        >
    </div>

    <br>

    <div>
        <label for="emailAddress">Email Address:</label>
        <input
            type="email"
            id="emailAddress"
            name="emailAddress"
            maxlength="100"
            required
            autocomplete="email"
        >
    </div>

    <br>

    <div>
        <label for="password">Password:</label>
        <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="new-password"
        >
    </div>

    <br>

    <button type="submit">Register</button>

</form>

<p>
    Already have an account?
    <a href="login_form.php">Log in here</a>.
</p>

<?php
// =====================================================
// STEP 4: LOAD THE SHARED FOOTER
// =====================================================
require_once 'footer.php';
?>
