
<?php
// =====================================================
// STEP 1: START THE SESSION
// This lets us store messages and login information.
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// STEP 2: ACCEPT POST REQUESTS ONLY
// Registration data must come from the form.
// =====================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register_user_form.php');
    exit;
}

// =====================================================
// STEP 3: CONNECT TO THE DATABASE
// Reuse the existing Assignment 3 PDO connection.
// =====================================================
require_once 'db.php';

// =====================================================
// STEP 4: READ AND VALIDATE THE FORM DATA
// Trim removes accidental spaces from text fields.
// =====================================================
$userName = trim($_POST['userName'] ?? '');
$emailAddress = trim($_POST['emailAddress'] ?? '');
$plainPassword = $_POST['password'] ?? '';

// Check that every field has been supplied.
if ($userName === '' || $emailAddress === '' || $plainPassword === '') {
    $_SESSION['user_error'] = 'Please complete all registration fields.';
    header('Location: user_error.php');
    exit;
}

// Check the username length.
if (strlen($userName) > 50) {
    $_SESSION['user_error'] = 'Your username must be 50 characters or fewer.';
    header('Location: user_error.php');
    exit;
}

// Validate the email address.
if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['user_error'] = 'Please enter a valid email address.';
    header('Location: user_error.php');
    exit;
}

// Check the password length before hashing.
// This application accepts passwords up to 72 bytes
// for compatibility with bcrypt.
if (strlen($plainPassword) < 8 || strlen($plainPassword) > 72) {
    $_SESSION['user_error'] = 'Your password must be between 8 and 72 bytes.';
    header('Location: user_error.php');
    exit;
}

// =====================================================
// STEP 5: CHECK FOR AN EXISTING USERNAME
// Prepared statements protect against SQL injection.
// =====================================================
try {
    $sql = 'SELECT userID
            FROM registrations
            WHERE userName = :userName';

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':userName', $userName);
    $statement->execute();

    if ($statement->fetch()) {
        $_SESSION['user_error'] = 'That username is already taken.';
        header('Location: user_error.php');
        exit;
    }

    // =================================================
    // STEP 6: HASH THE PASSWORD
    // Never store the original password in the database.
    // =================================================
    $passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);

    if ($passwordHash === false) {
        throw new RuntimeException('Password hashing failed.');
    }

    // =================================================
    // STEP 7: INSERT THE NEW USER
    // Store the secure hash, not the original password.
    // =================================================
    $sql = 'INSERT INTO registrations
                (userName, password, emailAddress)
            VALUES
                (:userName, :password, :emailAddress)';

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':userName', $userName);
    $statement->bindValue(':password', $passwordHash);
    $statement->bindValue(':emailAddress', $emailAddress);
    $statement->execute();

    // =================================================
    // STEP 8: PREPARE A SUCCESS MESSAGE
    // Redirect after POST to avoid accidental resubmission.
    // =================================================
    $_SESSION['registration_success'] =
        'Your account was created successfully. You can now log in.';

    header('Location: register_user_confirmation.php');
    exit;

} catch (PDOException $e) {
    // Log technical details privately rather than showing
    // database errors to the user.
    error_log('Registration database error: ' . $e->getMessage());

    $_SESSION['user_error'] =
        'We could not complete your registration. Please try again.';

    header('Location: user_error.php');
    exit;
}
?>
