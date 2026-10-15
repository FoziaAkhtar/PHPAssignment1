
# Book Manager — PHP Assignment 1, 2 & 3

## Project Overview

Book Manager is a PHP and MySQL web application that allows users to manage a collection of books through a simple, user-friendly interface.

The application demonstrates the fundamental CRUD operations:

- **Create:** Add new books to the collection.
- **Read:** Display the collection and view individual book details.
- **Update:** Edit book information and optionally replace book cover images.
- **Delete:** Remove books from the collection.

The project was developed using PHP, MySQL, PDO, HTML5, CSS3, and Git. Image upload and processing functionality was added to enhance the application's features and presentation.

---

## Application Purpose

The purpose of Book Manager is to provide a practical way to organize and maintain book information while demonstrating fundamental web development concepts.

Users can:

- View all books in the collection.
- Add a new book with an optional cover image.
- Edit existing book information.
- Upload or replace a book cover image.
- View individual book details on a dedicated page.
- Delete a book after confirmation.
- View information including title, author, genre, publication year, rating, and date added.

---

## Technologies Used

The application uses the following technologies and tools:

- **PHP** — Server-side programming and application logic.
- **MySQL** — Relational database management.
- **PDO** — Database connectivity and prepared statements.
- **HTML5** — Webpage structure and forms.
- **CSS3** — Application styling and responsive layout.
- **XAMPP** — Local development environment.
- **phpMyAdmin** — Database administration.
- **Git** — Version control.
- **GitHub** — Source code hosting and project history.

---

## Application Features

### 1. Book Collection

The `index.php` page retrieves and displays the books stored in the database.

The collection includes:

- Book cover thumbnail.
- Book ID.
- Title.
- Author.
- Genre.
- Publication year.
- Rating.
- Date added.
- Links to view, edit, and delete books.

### 2. Add a Book

The `add.php` page allows users to enter information for a new book.

The form collects the book's title, author, genre, publication year, rating, and date added. Users can also upload a book cover image.

The application validates submitted information and uses a PDO prepared statement to insert the new record into MySQL.

### 3. Book Details

The `book_details.php` page displays detailed information about a selected book.

Users can access this page through the Details link in the book collection.

The page displays the available book information and cover image, with navigation to return to the collection or edit the selected book.

### 4. Edit a Book

The `edit.php` page allows users to update an existing book's information.

The page retrieves the selected book and displays its current values. Users can modify the information and optionally upload a replacement cover image.

When a replacement image is not uploaded, the existing cover image is retained.

The application validates the submitted information and updates the record using a PDO prepared statement.

### 5. Delete a Book

The `delete.php` page handles book deletion.

The Delete action uses a confirmation message before the deletion is submitted. The selected book ID is sent using the POST method.

A PDO prepared statement is used to remove the selected record from the database.

### 6. Book Cover Image Upload and Processing

The application supports uploading and displaying book cover images.

Image-related functionality includes:

- Uploading cover images when adding a book.
- Optionally replacing cover images when editing a book.
- Checking supported image formats.
- Enforcing an upload size limit in the application.
- Generating unique filenames for uploaded images.
- Creating resized image versions for display.
- Displaying smaller thumbnails in the collection.
- Providing placeholder images when a cover is unavailable.

The `image_util.php` file contains reusable image-processing functions.

The `images/` directory stores the book cover images, generated image sizes, and placeholder images.

Supported upload formats in the application are JPEG, PNG, and GIF, subject to the validation implemented in the PHP upload handlers.

---

## Database

### Database Name

`book_manager`

### Table Name

`books`

### Table Columns

| Column | Data Type | Purpose |
|---|---|---|
| `id` | INT | Unique book identifier and primary key |
| `title` | VARCHAR(255) | Book title |
| `author` | VARCHAR(255) | Book author |
| `genre` | VARCHAR(100) | Book genre |
| `year_published` | INT | Year the book was published |
| `rating` | DECIMAL(3,1) | Book rating from 0 to 5 |
| `date_added` | DATE | Date the book was added |
| `imageName` | VARCHAR(255), nullable | Stored filename of the book cover thumbnail |

The `id` column is the primary key and uses `AUTO_INCREMENT`.

The `imageName` column stores the filename associated with a book's cover thumbnail. Image files are stored in the project's `images/` directory.

**Note:** The database schema used for setup should include the `imageName` column to support the image functionality.

---

## Validation and Security

The application demonstrates several basic validation and security practices.

### PDO Prepared Statements

Prepared statements are used for database operations. This helps protect against SQL injection.

### Output Escaping

Database values displayed in HTML are escaped using `htmlspecialchars()` where implemented. This helps reduce the risk of cross-site scripting (XSS).

### Form Validation

The application validates submitted book information, including required fields, publication year, and rating.

The rating must be within the supported range of 0 to 5.

### Image Upload Validation

The image upload functionality checks the uploaded file's format and size before processing it.

### Friendly Error Handling

The `error.php` page provides a user-friendly error message when a database problem occurs, rather than intentionally displaying technical database details to the user.

### Reusable Components

The application uses separate PHP files for database connectivity, shared page elements, error handling, and image processing.

---

## Project Structure

```text
PHPAssignment1/
│
├── css/
│   └── style.css
│
├── images/
│   ├── Book cover images
│   ├── Resized cover images
│   └── Placeholder images
│
├── add.php
├── book_details.php
├── db.php
├── delete.php
├── edit.php
├── error.php
├── footer.php
├── header.php
├── image_util.php
├── index.php
├── schema.sql
├── book_manager.sql
├── README.md
└── .gitignore
```

The `book_manager.sql` file is included if present in the repository. The exact contents of the SQL files determine whether they contain the complete database structure and sample records.

---

## Local Development Environment

The application was developed using XAMPP on a local computer.

### Requirements

- XAMPP with Apache, PHP, and MySQL/MariaDB.
- A web browser.
- phpMyAdmin or another MySQL administration tool.

### Application URL

After starting Apache and MySQL in XAMPP, the application can be accessed at:

`http://localhost/PHPAssignment1/`

### Database Setup

1. Start Apache and MySQL in XAMPP.
2. Open phpMyAdmin at `http://localhost/phpmyadmin/`.
3. Create the database named `book_manager`, if it does not already exist.
4. Import the appropriate SQL file containing the database schema and sample data.
5. Confirm that the `books` table includes the `imageName` column.
6. Check that the database connection settings in `db.php` match your local environment.
7. Open `http://localhost/PHPAssignment1/` in your browser.

The `schema.sql` and `book_manager.sql` files should be checked to determine which one contains the complete setup instructions and data for your current version.

### Image Storage

Uploaded images and their resized versions are stored in the `images/` directory.

The PHP process must have permission to write uploaded images to that directory. Keep the image utility file and the image directory in their expected project locations.

---

## Testing

The following test scenarios should be performed against the current version of the application before submission.

| Test | Expected Result |
|---|---|
| Create a book | A valid new book is saved to MySQL. |
| Display collection | Existing books appear on the homepage. |
| View book details | The Details link opens the correct book record. |
| Update a book | Changes to book information are saved. |
| Replace a cover | A valid replacement cover is processed and displayed. |
| Keep existing cover | Editing without uploading another image preserves the current cover. |
| Delete a book | A confirmation appears and the selected record is deleted after confirmation. |
| Invalid form input | Invalid values are rejected with an appropriate message. |
| Invalid image upload | Unsupported or oversized images are rejected by the implemented validation. |
| Database failure | The application displays the intended friendly error page when the database connection fails. |

Record a test as **PASS** only after you have personally run it and confirmed the expected result.

---

## Version Control and GitHub

Git is used to track changes to the project, and GitHub hosts the repository.

**Repository:** [FoziaAkhtar/PHPAssignment1](https://github.com/FoziaAkhtar/PHPAssignment1)

The Assignment 3 changes include the book details page, image upload and processing functionality, cover thumbnails, and updates to the collection and editing pages.

---

## Learning Outcomes

This project provides practical experience with:

- PHP server-side programming.
- MySQL database design and queries.
- PDO database connections and prepared statements.
- CRUD operations.
- HTML forms and validation.
- File uploads and image processing.
- Reusable PHP components.
- CSS styling and responsive design.
- Error handling and basic security practices.
- Git version control and GitHub collaboration workflows.

---

## Author

**Fozia Akhtar**

**Project:** Book Manager — PHP Assignment 1, 2 & 3

**Year:** 2026

