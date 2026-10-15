
<?php
/*
============================================================
PHP ASSIGNMENT 4 — USER LOGIN FORM
File: login_form.php

PURPOSE:
1. Display the login form.
2. Allow a registered user to enter their username and password.
3. Display login errors in red.
4. Send the submitted information to login.php for verification.

IMPORTANT:
The actual password verification happens in login.php,
not in this form.
============================================================
*/

// ==========================================================
// STEP 1: START THE SESSION
// ==========================================================
// A session allows PHP to remember information about the
// current visitor, including login error messages.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================================
// STEP 2: RETRIEVE THE LOGIN ERROR MESSAGE
// ==========================================================
// login.php stores an error message in $_SESSION when a
// login attempt fails.
//
// The ?? operator provides an empty string if no error exists.

$loginError = $_SESSION['login_error'] ?? '';

// ==========================================================
// STEP 3: CLEAR THE STORED ERROR MESSAGE
// ==========================================================
// We have copied the message into $loginError, so we can
// remove it from the session.
//
// This prevents the same error from appearing again when
// the user refreshes or revisits the page.

unset($_SESSION['login_error']);

// ==========================================================
// STEP 4: INCLUDE THE WEBSITE HEADER
// ==========================================================
// The header contains the shared navigation and page styling.
// Keep this before the page's visible HTML content.

require_once 'header.php';
?>

<!-- ========================================================
     STEP 5: DISPLAY THE LOGIN PAGE HEADING
     ======================================================== -->

<main class="container">

    <h1>User Login</h1>

    <p>
        Please enter your username and password to access
        the Book Manager application.
    </p>

    <!-- ====================================================
         STEP 6: DISPLAY LOGIN ERRORS IN RED
         ==================================================== -->
    <!--
        If login.php sends an error message, display it here.
        The login-error-message CSS class gives the message
        a red background, red border, and red text.

        The htmlspecialchars() function protects the page by
        safely displaying the message instead of interpreting
        it as HTML or JavaScript.
    -->

    <?php if ($loginError !== ''): ?>

        <div class="login-error-message" role="alert">

            <strong>
                <?php
                echo htmlspecialchars(
                    $loginError,
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </strong>

        </div>

    <?php endif; ?>

    <!-- ====================================================
         STEP 7: CREATE THE LOGIN FORM
         ==================================================== -->
    <!--
        method="POST" sends the form information to login.php
        without placing the password in the page URL.

        The name attributes allow login.php to retrieve the
        submitted values using $_POST.
    -->

    <form action="login.php" method="POST">

        <!-- USERNAME FIELD -->
        <div class="form-group">

            <label for="userName">Username:</label>

            <input
                type="text"
                id="userName"
                name="userName"
                required
                autocomplete="username"
            >

        </div>

        <!-- PASSWORD FIELD -->
        <div class="form-group">

            <label for="plainPassword">Password:</label>

            <input
                type="password"
                id="plainPassword"
                name="plainPassword"
                required
                autocomplete="current-password"
            >

        </div>

        <!-- SUBMIT BUTTON -->
        <button type="submit">
            Log In
        </button>

    </form>

    <!-- ====================================================
         STEP 8: PROVIDE A REGISTRATION LINK
         ==================================================== -->
    <!--
        Users who do not have an account can visit the
        registration form to create one.
    -->

    <p>
        Don't have an account?
        <a href="register_user_form.php">Register here</a>.
    </p>

</main>

<?php
// ==========================================================
// STEP 9: INCLUDE THE WEBSITE FOOTER
// ==========================================================
// The footer closes the shared page layout and displays
// the common footer content.

require_once 'footer.php';
?>
