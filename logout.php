
<?php
// =====================================================
// STEP 1: START THE SESSION
// Access the current user's session.
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// STEP 2: CLEAR ALL SESSION DATA
// This removes the login status and stored user details.
// =====================================================
$_SESSION = [];

// =====================================================
// STEP 3: REMOVE THE SESSION COOKIE
// Tell the browser to discard the session cookie.
// =====================================================
if (ini_get('session.use_cookies')) {
    $cookieParameters = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $cookieParameters['path'],
        $cookieParameters['domain'],
        $cookieParameters['secure'],
        $cookieParameters['httponly']
    );
}

// =====================================================
// STEP 4: DESTROY THE SESSION
// This ends the current server-side session.
// =====================================================
session_destroy();

// =====================================================
// STEP 5: REDIRECT TO THE LOGIN FORM
// Stop execution after redirecting.
// =====================================================
header('Location: login_form.php');
exit;
?>
