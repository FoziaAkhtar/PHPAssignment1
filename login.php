
<?php
// =====================================================
// STEP 1: START THE SESSION
// This lets PHP remember the user's login status
// and temporarily store login error messages.
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// STEP 2: ACCEPT POST REQUESTS ONLY
// Login credentials must come from the login form.
// If someone opens login.php directly, redirect them.
// =====================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_form.php');
    exit;
}

// =====================================================
// STEP 3: CONNECT TO THE DATABASE
// Reuse the existing Assignment 3 database connection.
// =====================================================
require_once 'db.php';

// =====================================================
// STEP 4: READ THE SUBMITTED CREDENTIALS
// IMPORTANT:
// login_form.php uses name="plainPassword".
// The PHP field name must match the form field name.
// We trim the username, but not the password.
// =====================================================
$userName = trim($_POST['userName'] ?? '');
$plainPassword = $_POST['plainPassword'] ?? '';

// Display a generic error if a field is missing.
if ($userName === '' || $plainPassword === '') {

    $_SESSION['login_error'] =
        'Incorrect username or password. Please try again.';

    header('Location: login_form.php');
    exit;
}

try {

    // =================================================
    // STEP 5: FIND THE REGISTERED USER
    // Retrieve the stored password hash and login-attempt
    // information from the registrations table.
    // =================================================
    $sql = 'SELECT userID, userName, password,
                   failedAttempts, lastFailedLogin
            FROM registrations
            WHERE userName = :userName';

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':userName', $userName);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // =================================================
    // STEP 5A: HANDLE AN UNKNOWN USERNAME
    // Use a generic message for security.
    // =================================================
    if (!$user) {

        $_SESSION['login_error'] =
            'Incorrect username or password. Please try again.';

        header('Location: login_form.php');
        exit;
    }

    // =================================================
    // STEP 6: CHECK WHETHER THE ACCOUNT IS LOCKED
    // Three failed attempts trigger a five-minute lockout.
    // =================================================
    $failedAttempts = (int) $user['failedAttempts'];
    $lastFailedLogin = $user['lastFailedLogin'];

    if ($failedAttempts >= 3 && $lastFailedLogin !== null) {

        $lastFailureTime = strtotime($lastFailedLogin);
        $lockoutSeconds = 300;
        $elapsedSeconds = time() - $lastFailureTime;

        if ($elapsedSeconds < $lockoutSeconds) {

            // Calculate how much lockout time remains.
            $remainingSeconds = $lockoutSeconds - $elapsedSeconds;
            $remainingMinutes = (int) ceil($remainingSeconds / 60);

            $_SESSION['login_error'] =
                'Your account is temporarily locked. '
                . 'Please try again in '
                . $remainingMinutes
                . ' minute(s).';

            header('Location: login_form.php');
            exit;
        }

        // The lockout has expired.
        // Begin a new sequence of failed attempts.
        $failedAttempts = 0;
    }

    // =================================================
    // STEP 7: VERIFY THE PASSWORD
    // password_verify() compares the submitted password
    // with the secure hash saved during registration.
    // =================================================
    if (password_verify($plainPassword, $user['password'])) {

        // -------------------------------------------------
        // STEP 7A: RESET FAILED ATTEMPTS
        // A successful login clears the failed-login count.
        // -------------------------------------------------
        $sql = 'UPDATE registrations
                SET failedAttempts = 0,
                    lastFailedLogin = NULL
                WHERE userID = :userID';

        $statement = $pdo->prepare($sql);

        $statement->bindValue(
            ':userID',
            $user['userID'],
            PDO::PARAM_INT
        );

        $statement->execute();

        // -------------------------------------------------
        // STEP 7B: REGENERATE THE SESSION ID
        // This helps protect against session fixation.
        // -------------------------------------------------
        session_regenerate_id(true);

        // -------------------------------------------------
        // STEP 7C: STORE THE AUTHENTICATED USER
        // These session values identify the signed-in user.
        // -------------------------------------------------
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userID'] = (int) $user['userID'];
        $_SESSION['userName'] = $user['userName'];

        // Remove any old login error.
        unset($_SESSION['login_error']);

        // Send the user to the login confirmation page.
        header('Location: login_confirmation.php');
        exit;

    } else {

        // =================================================
        // STEP 8: RECORD AN INCORRECT PASSWORD
        // Increase the failed-attempt counter and record
        // the time of the failed login.
        // =================================================
        $failedAttempts++;

        $sql = 'UPDATE registrations
                SET failedAttempts = :failedAttempts,
                    lastFailedLogin = NOW()
                WHERE userID = :userID';

        $statement = $pdo->prepare($sql);

        $statement->bindValue(
            ':failedAttempts',
            $failedAttempts,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':userID',
            $user['userID'],
            PDO::PARAM_INT
        );

        $statement->execute();

        // =================================================
        // STEP 8A: SET THE LOGIN ERROR MESSAGE
        // The third failed attempt triggers account lockout.
        // =================================================
        if ($failedAttempts >= 3) {

            $_SESSION['login_error'] =
                'Your account is temporarily locked after '
                . 'three failed attempts. Please try again '
                . 'in five minutes.';

        } else {

            $_SESSION['login_error'] =
                'Incorrect username or password. Please try again.';
        }

        // Return to the login form to display the message.
        header('Location: login_form.php');
        exit;
    }

} catch (PDOException $e) {

    // =================================================
    // STEP 9: HANDLE DATABASE ERRORS
    // Keep technical details out of the browser.
    // Store details in the PHP error log for troubleshooting.
    // =================================================
    error_log('Login database error: ' . $e->getMessage());

    $_SESSION['login_error'] =
        'We could not process your login. Please try again later.';

    header('Location: login_form.php');
    exit;
}
?>
