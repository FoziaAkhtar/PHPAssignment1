<?php

// ============================================================
// PHP ASSIGNMENT 1
// BOOK MANAGER APPLICATION
// ============================================================
//
// FILE: header.php
//
// PURPOSE:
// This file contains the reusable HTML that appears at the
// top of our application pages.
//
// Instead of copying the same HTML into every PHP page,
// we can simply include this file.
//
// Example:
//
// require_once "header.php";
//
// This helps keep our code organized and avoids repetition.
// ============================================================

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- ========================================================
         STEP 1: CHARACTER ENCODING
         ========================================================

         UTF-8 allows the browser to correctly display
         normal letters, numbers, symbols, and special
         characters.
    ========================================================= -->

    <meta charset="UTF-8">


    <!-- ========================================================
         STEP 2: RESPONSIVE DESIGN
         ========================================================

         This tells the browser to make the page fit
         different screen sizes.

         This is especially important for mobile devices.
    ========================================================= -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- ========================================================
         STEP 3: PAGE TITLE
         ========================================================

         This appears in the browser tab.
    ========================================================= -->

    <title>Book Manager</title>


    <!-- ========================================================
         STEP 4: LOAD OUR CSS FILE
         ========================================================

         Our CSS file is located inside the "css" folder.

         Project structure:

         PHPAssignment1
         |
         |--- css
         |      |
         |      |--- style.css
         |
         |--- header.php

         Therefore we use:

         css/style.css
    ========================================================= -->

    <link rel="stylesheet" href="css/style.css">

</head>


<body>


    <!-- ========================================================
         STEP 5: WEBSITE HEADER
         ========================================================

         The <header> element contains the main branding
         and navigation area of our application.
    ========================================================= -->

    <header class="site-header">

        <div class="container">


            <!-- =================================================
                 STEP 6: APPLICATION TITLE
                 =================================================

                 This is the name of our application.
            ================================================== -->

            <h1>Book Manager</h1>


            <!-- =================================================
                 STEP 7: SHORT DESCRIPTION
                 =================================================

                 This tells the user what the application does.
            ================================================== -->

            <p class="tagline">
                Manage your books easily and efficiently.
            </p>


            <!-- =================================================
                 STEP 8: NAVIGATION
                 =================================================

                 Navigation links allow the user to move
                 between the main sections of our application.

                 We will create these pages as we continue
                 building the project.
            ================================================== -->

            <nav class="main-navigation">

                <a href="index.php">Home</a>

                <a href="add.php">Add Book</a>

            </nav>


        </div>

    </header>


    <!-- ========================================================
         STEP 9: START MAIN CONTENT AREA
         ========================================================

         Individual PHP pages will place their content
         after this header.

         The closing </main> will be handled by footer.php.
    ========================================================= -->

    <main class="container">
