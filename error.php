<?php

// ============================================================
// LITTLE STARLAND / PHP ASSIGNMENT 1
// BOOK MANAGER APPLICATION
// ============================================================
//
// FILE: error.php
//
// PURPOSE:
// This page is displayed when our PHP application cannot
// connect to the MySQL database.
//
// IMPORTANT:
// We DO NOT include db.php in this file.
//
// Why?
// Because db.php redirects to error.php when the database
// connection fails.
//
// If error.php included db.php, it would create a loop:
//
// db.php
//    ↓
// database connection fails
//    ↓
// error.php
//    ↓
// includes db.php
//    ↓
// database connection fails again
//    ↓
// error.php again...
//
// Therefore, this page must work independently.
// ============================================================

?>

<!DOCTYPE html>

<!--
    STEP 1: START THE HTML DOCUMENT

    The <html> element contains the entire webpage.

    "lang='en'" tells the browser that the page
    is written in English.
-->
<html lang="en">

<head>

    <!--
        STEP 2: CHARACTER ENCODING

        UTF-8 allows our webpage to correctly display
        letters, numbers, symbols, and special characters.
    -->
    <meta charset="UTF-8">


    <!--
        STEP 3: RESPONSIVE DESIGN

        This makes the webpage adjust properly to
        different screen sizes such as:

        - Desktop
        - Laptop
        - Tablet
        - Mobile phone
    -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!--
        STEP 4: PAGE TITLE

        This text appears in the browser tab.
    -->
    <title>Book Manager - Error</title>


    <!--
        STEP 5: CONNECT TO OUR CSS FILE

        The stylesheet will contain the visual design
        for our application.

        "css/style.css" means:

        PHPAssignment1
            |
            |--- css
            |      |
            |      |--- style.css
            |
            |--- error.php

        We will create style.css later.
    -->
    <link rel="stylesheet" href="css/style.css">

</head>


<body>

    <!--
        ========================================================
        STEP 6: MAIN PAGE CONTENT
        ========================================================

        The <main> element contains the main content
        of this webpage.
    -->

    <main class="container">


        <!--
            ====================================================
            STEP 7: ERROR MESSAGE BOX
            ====================================================

            We use a <section> to group all of the
            error-related information together.

            The "error-box" class will allow us to style
            this section later using CSS.
        -->

        <section class="error-box">


            <!--
                STEP 8: ERROR PAGE HEADING

                This is the main heading that the user sees.
            -->

            <h1>Something Went Wrong</h1>


            <!--
                STEP 9: FRIENDLY ERROR MESSAGE

                We do NOT display technical database errors
                to the normal user.

                Instead, we provide a simple and
                understandable message.
            -->

            <p>
                We are sorry, but we could not connect
                to the Book Manager database.
            </p>


            <!--
                STEP 10: ADDITIONAL MESSAGE

                This lets the user know that the problem
                may be temporary.
            -->

            <p>
                Please try again later.
            </p>


            <!--
                =================================================
                STEP 11: RETURN BUTTON
                =================================================

                This link takes the user back to index.php.

                index.php will be our main Book Manager page.

                The "button" class will be styled later
                using CSS so that this link looks like
                a button.
            -->

            <a href="index.php" class="button">
                Return to Book Manager
            </a>


        </section>

    </main>


</body>

</html>
