
<?php

// ============================================================
// PHP ASSIGNMENT 4
// BOOK MANAGER APPLICATION
// ============================================================
//
// FILE: header.php
//
// PURPOSE:
// 1. Start the login session when necessary.
// 2. Check whether a user is signed in.
// 3. Retrieve the signed-in user's username.
// 4. Display the reusable website header.
// 5. Display navigation links based on login status.
//
// This file is included by other PHP pages using:
//
// require_once "header.php";
//
// IMPORTANT:
// We must check the session BEFORE displaying HTML.
// Otherwise, PHP session headers may be sent too late.
// ============================================================


// ============================================================
// STEP 0: START THE LOGIN SESSION
// ============================================================
//
// A session allows PHP to remember a user's login status
// as they move between different pages.
//
// session_status() checks whether a session already exists.
//
// PHP_SESSION_NONE means that no session has been started.
// We start one only when necessary to avoid starting it twice.
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ============================================================
// STEP 1: CHECK WHETHER THE USER IS SIGNED IN
// ============================================================
//
// During a successful login, login.php sets:
//
// $_SESSION['isLoggedIn'] = true;
//
// We check that the variable exists and is exactly true.
//
// This determines which navigation links to display.
// ============================================================

$isLoggedIn = (
    isset($_SESSION['isLoggedIn']) &&
    $_SESSION['isLoggedIn'] === true
);


// ============================================================
// STEP 2: RETRIEVE THE SIGNED-IN USERNAME
// ============================================================
//
// login.php stores the username in:
//
// $_SESSION['userName']
//
// If the user is signed in and the username exists,
// we retrieve it for display in the website header.
//
// Otherwise, we use an empty string.
// ============================================================

$currentUserName = '';

if ($isLoggedIn && isset($_SESSION['userName'])) {
    $currentUserName = $_SESSION['userName'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- ========================================================
         STEP 3: CHARACTER ENCODING
         ========================================================

         UTF-8 allows the browser to display letters,
         numbers, symbols, and special characters correctly.
    ========================================================= -->

    <meta charset="UTF-8">


    <!-- ========================================================
         STEP 4: RESPONSIVE DESIGN
         ========================================================

         This viewport setting helps the website fit
         different screen sizes, including mobile devices.
    ========================================================= -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- ========================================================
         STEP 5: PAGE TITLE
         ========================================================

         This title appears in the browser tab.
    ========================================================= -->

    <title>Book Manager</title>


    <!-- ========================================================
         STEP 6: LOAD THE EXISTING CSS STYLESHEET
         ========================================================

         Our CSS file is inside the css folder.

         Project structure:

         PHPAssignment1/
         |
         |--- css/
         |    |
         |    |--- style.css
         |
         |--- header.php

         We keep the existing stylesheet so the application
         retains its current design.
    ========================================================= -->

    <link rel="stylesheet" href="css/style.css">

</head>


<body>


    <!-- ========================================================
         STEP 7: WEBSITE HEADER
         ========================================================

         The header contains the application name,
         description, and navigation links.
    ========================================================= -->

    <header class="site-header">

        <div class="container">


            <!-- =================================================
                 STEP 8: APPLICATION TITLE
                 =================================================

                 Display the name of our application.
            ================================================== -->

            <h1>Book Manager</h1>


            <!-- =================================================
                 STEP 9: APPLICATION DESCRIPTION
                 =================================================

                 Briefly explain the purpose of the application.
            ================================================== -->

            <p class="tagline">
                Manage your books easily and efficiently.
            </p>


            <!-- =================================================
                 STEP 10: MAIN NAVIGATION
                 =================================================

                 Navigation links help users move between
                 the different pages of our application.

                 The links change according to login status.

                 SIGNED-IN USER:
                 - Home
                 - Add Book
                 - Welcome message
                 - Logout

                 SIGNED-OUT USER:
                 - Home
                 - Login
                 - Register
            ================================================== -->

            <nav class="main-navigation">


                <!-- =============================================
                     STEP 10A: HOME LINK
                     =============================================

                     The Home link opens the book collection.
                ============================================== -->

                <a href="index.php">Home</a>


                <!-- =============================================
                     STEP 10B: NAVIGATION FOR SIGNED-IN USERS
                     =============================================

                     If $isLoggedIn is true, PHP displays
                     the links inside this IF block.

                     The user can access Add Book and Logout.
                ============================================== -->

                <?php if ($isLoggedIn): ?>


                    <!-- Add a new book to the collection. -->

                    <a href="add.php">Add Book</a>


                    <!-- =========================================
                         STEP 10C: DISPLAY THE USERNAME
                         =========================================

                         Show the username stored in the session.

                         htmlspecialchars() safely escapes
                         special HTML characters before output.

                         ENT_QUOTES escapes both types of quotes.
                         UTF-8 specifies the character encoding.
                    ========================================== -->

                    <span class="welcome-message">
                        Welcome,
                        <?= htmlspecialchars(
                            $currentUserName,
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>!
                    </span>


                    <!-- =========================================
                         STEP 10D: LOGOUT LINK
                         =========================================

                         Clicking Logout opens logout.php.

                         That file clears the login session
                         and redirects the user to the login page.
                    ========================================== -->

                    <a href="logout.php">Logout</a>


                <!-- =============================================
                     STEP 10E: NAVIGATION FOR SIGNED-OUT USERS
                     =============================================

                     If the user is not signed in, PHP displays
                     the Login and Register links instead.
                ============================================== -->

                <?php else: ?>


                    <!-- Allow an existing user to sign in. -->

                    <a href="login_form.php">Login</a>


                    <!-- Allow a new user to create an account. -->

                    <a href="register_user_form.php">Register</a>


                <?php endif; ?>


            </nav>


        </div>

    </header>


    <!-- ========================================================
         STEP 11: START THE MAIN CONTENT AREA
         ========================================================

         Individual PHP pages display their content below
         this shared header.

         footer.php is responsible for closing the main
         content area and the remaining HTML elements.
    ========================================================= -->

    <main class="container">
