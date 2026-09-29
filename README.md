
# Book Manager — PHP Assignment 1

## Project Overview

Book Manager is a PHP and MySQL web application that allows users to manage a collection of books.

The application demonstrates the fundamental CRUD operations:

- Create
- Read
- Update
- Delete

The project was developed using PHP, MySQL, PDO, HTML, and CSS.

---

## Application Purpose

The purpose of this application is to provide a simple and user-friendly way to manage book information.

Users can:

- View all books in the collection
- Add a new book
- Edit an existing book
- Delete a book
- View information such as title, author, genre, publication year, rating, and date added

---

## Technologies Used

The application uses the following technologies:

- PHP — Server-side programming
- MySQL — Database management
- PDO — PHP database connection and prepared statements
- HTML5 — Page structure
- CSS3 — Application styling and responsive layout
- XAMPP — Local development environment
- phpMyAdmin — Database administration
- Git — Version control
- GitHub — Source code repository

---

## Database

### Database Name

```text
book_manager

Table Name
books
Table Columns
Column	Data Type	Purpose
id	INT	Unique book identifier
title	VARCHAR(255)	Book title
author	VARCHAR(255)	Book author
genre	VARCHAR(100)	Book genre
year_published	INT	Year the book was published
rating	DECIMAL(3,1)	Book rating from 0 to 5
date_added	DATE	Date the book was added

The id column is the primary key and uses AUTO_INCREMENT.

CRUD Features
Create

The add.php page allows users to enter a new book.

The application validates the submitted information before inserting the record into MySQL.

A PDO prepared statement is used for the database INSERT operation.

Read

The index.php page retrieves all books from the books table and displays them in an HTML table.

The information displayed includes:

ID
Title
Author
Genre
Year Published
Rating
Date Added
Update

The edit.php page allows users to modify an existing book.

The application first retrieves the selected book and displays its current information.

After the user makes changes, the application validates the information and updates the database using a PDO prepared statement.

Delete

The delete.php page removes a selected book from the database.

The Delete button uses a confirmation message before the deletion is submitted.

The book ID is sent using the POST method.

A PDO prepared statement is used for the DELETE operation.

Validation and Security

The application includes several basic security and validation practices.

PDO Prepared Statements

Prepared statements are used for database operations.

This helps protect the application from SQL injection.

Output Escaping

Database values displayed on HTML pages are processed using:

htmlspecialchars()

This helps protect against Cross-Site Scripting (XSS).

Form Validation

The application checks that required fields contain information.

The publication year must be a valid number.

The rating must be between 0 and 5.

Friendly Error Handling

Database connection and query failures redirect the user to:

error.php

Technical database error information is not displayed to the normal user.

Reusable Components

The application uses reusable PHP files to reduce duplicated code.

db.php

Creates the PDO connection to the MySQL database.

header.php

Contains the reusable HTML header, navigation, and opening main content area.

footer.php

Contains the reusable footer and closing HTML structure.

error.php

Displays a friendly message when a database problem occurs.

Project Structure
PHPAssignment1/
│
├── css/
│   └── style.css
│
├── db.php
├── error.php
├── header.php
├── footer.php
├── index.php
├── add.php
├── edit.php
├── delete.php
├── schema.sql
└── README.md
Testing

The application was tested using the following scenarios.

Create Test

A new book was successfully added through the Add Book form.

Result: PASS

Read Test

Existing books were successfully retrieved from MySQL and displayed on the homepage.

Result: PASS

Update Test

The rating of an existing book was changed successfully.

Result: PASS

Delete Test

A book was selected for deletion.

The confirmation message appeared before deletion.

After confirmation, the selected book was successfully removed from the database.

Result: PASS

Database Failure Test

MySQL was temporarily stopped while Apache remained running.

The application was accessed through the homepage.

The application redirected to the friendly error page instead of displaying a technical database error.

MySQL was then restarted and the application returned to normal operation.

Result: PASS

Local Development Environment

The application was developed and tested locally using XAMPP.

The application can be accessed through:

http://localhost/PHPAssignment1/
Database Setup

The database structure and sample records are documented in:

schema.sql

The SQL file can be used to recreate the database and the books table.

Author

Fozia Akhtar

PHP Assignment 1

2026



