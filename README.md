
# Book Manager Application

A PHP and MySQL web application for managing a personal book collection, with user registration, secure authentication, and session-based access control.

## Project Overview

The Book Manager Application was developed as part of the PHP web development assignments. It allows authenticated users to manage a collection of books through a web-based interface.

The project builds on the earlier book-management functionality by introducing user registration, login, logout, password hashing, and temporary account lockout after repeated unsuccessful login attempts.

## Features

### Assignment 3 — Book Management

- Display the book collection.
- Add new books.
- View individual book details.
- Edit existing book information.
- Delete books.
- Store and retrieve book records using MySQL.
- Use PDO prepared statements for database operations.
- Upload and process book cover images.
- Validate book information and image uploads.
- Use reusable PHP files and CSS styling.

### Assignment 4 — User Authentication

- Register a new user account.
- Store passwords securely using PHP's `password_hash()`.
- Authenticate users using `password_verify()`.
- Maintain login status using PHP sessions.
- Regenerate the session ID after successful login.
- Display the signed-in user's username.
- Show navigation links based on login status.
- Log out users and clear their session.
- Protect restricted pages from unauthenticated access.
- Track failed login attempts.
- Temporarily lock an account after three unsuccessful login attempts for five minutes.

## Technologies Used

- **PHP** — Server-side programming.
- **MySQL** — Database management.
- **PDO** — Database connectivity and prepared statements.
- **HTML5** — Page structure.
- **CSS3** — Styling and layout.
- **XAMPP** — Local development environment.
- **phpMyAdmin** — Database administration.
- **Visual Studio Code** — Code editor.
- **Git and GitHub** — Version control and source-code hosting.

## Requirements

Install the following software before running the application:

- XAMPP with PHP, Apache, and MySQL/MariaDB.
- A modern web browser.
- A code editor such as Visual Studio Code.

## Installation and Setup

### Step 1 — Start XAMPP

Start the Apache and MySQL services through the XAMPP control panel or manager.

### Step 2 — Place the Project in the Web Server Directory

Place or clone the project into the XAMPP web root.

On macOS, the project directory used for this assignment is:

`/Applications/XAMPP/xamppfiles/htdocs/PHPAssignment1`

### Step 3 — Create the Database

1. Open phpMyAdmin in your browser.
2. Create a database named `book_manager`, if it does not already exist.
3. Import the project's `schema.sql` file if it contains the required database structure.
4. Ensure the `books` table and `registrations` table exist.

**Important:** If you already have a working `book_manager` database containing your Assignment 3 book records, preserve that database. Do not drop or recreate it unnecessarily.

### Step 4 — Configure the Database Connection

Open `db.php` and verify that the database settings match your local XAMPP configuration.

Typical local settings are:

- Host: `localhost`
- Database: `book_manager`
- Username: `root`
- Password: blank, if that is how your local XAMPP installation is configured.

Use the credentials configured on your own computer.

### Step 5 — Run the Application

Open this address in your browser:

`http://localhost/PHPAssignment1/`

You should be able to access the login and registration pages and use the application according to its authentication rules.

## Database Structure

### 1. Books Table

The `books` table stores book information. Its fields include:

- `id` — Primary key.
- `title` — Book title.
- `author` — Author's name.
- `genre` — Book genre.
- `year_published` — Publication year.
- `rating` — Book rating.
- `date_added` — Date the book was added.

Book cover image information may also be stored, depending on the existing database schema.

### 2. Registrations Table

The `registrations` table stores registered user information and login security data.

- `userID` — Primary key.
- `userName` — Unique username.
- `password` — Hashed password.
- `emailAddress` — User's email address.
- `failedAttempts` — Number of unsuccessful login attempts.
- `lastFailedLogin` — Timestamp of the most recent unsuccessful login.

Passwords are stored as hashes rather than as plain text.

## Authentication and Access Control

### User Registration

New users can create an account through `register_user_form.php`. Registration processing is handled by `register_user.php`, which validates the submitted information and hashes the password before storing the account.

### User Login

Users sign in through `login_form.php`. The application verifies their credentials and establishes a PHP session after successful authentication.

### Account Lockout

The application tracks unsuccessful login attempts. After three failed attempts, the account is temporarily locked for five minutes.

### Protected Pages

The `auth.php` file checks whether a user has an active authenticated session. Pages that require authentication include this file before executing their protected functionality.

### User Logout

The `logout.php` file clears the login session and redirects the user to the login page.

## Project File Structure

The project includes the following main files and directories:

```text
PHPAssignment1/
│
├── auth.php
├── db.php
├── header.php
├── footer.php
├── index.php
├── add.php
├── edit.php
├── delete.php
├── book_details.php
│
├── login.php
├── login_form.php
├── login_confirmation.php
├── logout.php
│
├── register_user.php
├── register_user_form.php
├── register_user_confirmation.php
├── user_error.php
│
├── image_util.php
├── error.php
├── schema.sql
├── README.md
│
└── css/
    └── style.css
```

The structure above lists the principal application files. Additional image directories or supporting files may exist in the project.

## Testing

The following authentication behaviours have been tested during development:

- Successful user registration.
- Successful login using valid credentials.
- Access to the book collection after login.
- Account lockout after three unsuccessful login attempts.
- Logout and return to the login page.
- Redirection to the login page when attempting to access protected pages while signed out.

The book-management functionality should also be checked to ensure adding, viewing, editing, deleting, and cover-image handling continue to work after the authentication changes.

## Security Considerations

- Passwords are hashed before storage.
- Password verification uses PHP's password verification function.
- PDO prepared statements are used for database operations.
- Usernames displayed in HTML are escaped with `htmlspecialchars()`.
- Session IDs are regenerated after successful authentication.
- Restricted pages check authentication before executing protected operations.
- Login attempts are tracked to support temporary account lockout.

This project is intended for local academic development. A production deployment would require additional security review, including CSRF protection, secure session-cookie settings, rate limiting, and HTTPS.

## Learning Objectives

This project demonstrates practical skills in:

- PHP application development.
- MySQL database design and connectivity.
- CRUD operations.
- Form processing and validation.
- File uploads and image handling.
- Password hashing and authentication.
- Session management and access control.
- Reusable application components.
- Git version control and GitHub repository management.

## Author

**Fozia Akhtar**

PHP Web Development Assignment

## Repository

GitHub: https://github.com/FoziaAkhtar/PHPAssignment1

## Conclusion

The Book Manager Application combines book-collection management with user authentication and session-based access control. It demonstrates the integration of PHP, MySQL, HTML, CSS, and common web application security practices while extending the functionality developed in the earlier assignment.