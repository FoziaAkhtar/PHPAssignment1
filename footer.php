<?php

// ============================================================
// PHP ASSIGNMENT 1
// BOOK MANAGER APPLICATION
// ============================================================
//
// FILE: footer.php
//
// PURPOSE:
// This file contains the reusable HTML that appears at the
// bottom of our application pages.
//
// Just like header.php, we create this once and reuse it
// throughout the application.
//
// Example:
//
// require_once "footer.php";
//
// This keeps our PHP files organized and avoids repeating
// the same footer code on every page.
// ============================================================

?>


    <!-- ========================================================
         STEP 1: CLOSE THE MAIN CONTENT AREA
         ========================================================

         The <main> element was opened in header.php.

         Individual pages place their content between:

         <main>
             PAGE CONTENT
         </main>

         We close it here because footer.php is responsible
         for the bottom portion of the page.
    ========================================================= -->

    </main>


    <!-- ========================================================
         STEP 2: WEBSITE FOOTER
         ========================================================

         The <footer> element contains information that
         normally appears at the bottom of a website.
    ========================================================= -->

    <footer class="site-footer">

        <div class="container">


            <!-- =================================================
                 STEP 3: COPYRIGHT / APPLICATION INFORMATION
                 =================================================

                 This gives our application a professional
                 footer section.
            ================================================== -->

            <p>
                &copy; 2026 Book Manager. All rights reserved.
            </p>


            <!-- =================================================
                 STEP 4: PROJECT DESCRIPTION
                 =================================================

                 This identifies the application as our
                 PHP assignment project.
            ================================================== -->

            <p>
                PHP &amp; MySQL Book Management Application
            </p>


        </div>

    </footer>


</body>

</html>
