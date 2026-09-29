-- ============================================================
-- PHP ASSIGNMENT 1
-- BOOK MANAGER APPLICATION
-- ============================================================
--
-- FILE: schema.sql
--
-- PURPOSE:
-- This file contains the SQL needed to create the
-- Book Manager database and books table.
--
-- It also contains sample data used to test the application.
--
-- This is a hand-written SQL file for documentation
-- and database setup purposes.
--
-- ============================================================


-- ============================================================
-- STEP 1: CREATE THE DATABASE
-- ============================================================
--
-- CREATE DATABASE creates our MySQL database.
--
-- IF NOT EXISTS means MySQL will not produce an error
-- if the database already exists.
--
-- ============================================================

CREATE DATABASE IF NOT EXISTS book_manager;


-- ============================================================
-- STEP 2: SELECT THE DATABASE
-- ============================================================
--
-- USE tells MySQL which database we want to work with.
--
-- All tables created after this command will belong
-- to the book_manager database.
--
-- ============================================================

USE book_manager;


-- ============================================================
-- STEP 3: CREATE THE BOOKS TABLE
-- ============================================================
--
-- The books table stores information about each book.
--
-- Our table contains:
--
-- id
-- title
-- author
-- genre
-- year_published
-- rating
-- date_added
--
-- ============================================================

CREATE TABLE IF NOT EXISTS books (

    -- --------------------------------------------------------
    -- ID COLUMN
    -- --------------------------------------------------------
    --
    -- INT stores a whole number.
    --
    -- NOT NULL means every record must have an ID.
    --
    -- PRIMARY KEY uniquely identifies each book.
    --
    -- AUTO_INCREMENT automatically generates the next
    -- available ID when a new book is added.
    -- --------------------------------------------------------

    id INT(11) NOT NULL AUTO_INCREMENT,


    -- --------------------------------------------------------
    -- BOOK TITLE
    -- --------------------------------------------------------
    --
    -- VARCHAR(255) allows us to store text up to 255
    -- characters.
    --
    -- NOT NULL means the title is required.
    -- --------------------------------------------------------

    title VARCHAR(255) NOT NULL,


    -- --------------------------------------------------------
    -- AUTHOR
    -- --------------------------------------------------------

    author VARCHAR(255) NOT NULL,


    -- --------------------------------------------------------
    -- GENRE
    -- --------------------------------------------------------

    genre VARCHAR(100) NOT NULL,


    -- --------------------------------------------------------
    -- YEAR PUBLISHED
    -- --------------------------------------------------------
    --
    -- INT is used because the publication year is a
    -- whole number.
    -- --------------------------------------------------------

    year_published INT(4) NOT NULL,


    -- --------------------------------------------------------
    -- RATING
    -- --------------------------------------------------------
    --
    -- DECIMAL(3,1) allows values such as:
    --
    -- 4.5
    -- 3.8
    -- 5.0
    --
    -- The first number represents the total number of
    -- digits and the second represents decimal places.
    -- --------------------------------------------------------

    rating DECIMAL(3,1) NOT NULL,


    -- --------------------------------------------------------
    -- DATE ADDED
    -- --------------------------------------------------------
    --
    -- DATE stores a date using the format:
    --
    -- YYYY-MM-DD
    -- --------------------------------------------------------

    date_added DATE NOT NULL,


    -- --------------------------------------------------------
    -- PRIMARY KEY
    -- --------------------------------------------------------
    --
    -- The ID uniquely identifies every book.
    -- --------------------------------------------------------

    PRIMARY KEY (id)

);


-- ============================================================
-- STEP 4: INSERT SAMPLE DATA
-- ============================================================
--
-- The following records provide realistic sample books
-- for testing the application.
--
-- The ID column is intentionally not included because
-- MySQL automatically creates IDs using AUTO_INCREMENT.
--
-- ============================================================


INSERT INTO books
(
    title,
    author,
    genre,
    year_published,
    rating,
    date_added
)
VALUES

(
    'The Little Prince',
    'Antoine de Saint-Exupery',
    'Children''s',
    1943,
    4.8,
    '2026-09-28'
),

(
    'The Hobbit',
    'J.R.R. Tolkien',
    'Fantasy',
    1937,
    4.5,
    '2026-09-28'
),

(
    'The Alchemist',
    'Paulo Coelho',
    'Fiction',
    1988,
    4.6,
    '2026-09-28'
),

(
    'Charlotte''s Web',
    'E.B. White',
    'Children''s',
    1952,
    4.7,
    '2026-09-28'
),

(
    'The Great Gatsby',
    'F. Scott Fitzgerald',
    'Classic',
    1925,
    4.4,
    '2026-09-28'
);


-- ============================================================
-- END OF SCHEMA FILE
-- ============================================================
--
-- This file can be used to recreate the database structure
-- and sample records for the Book Manager application.
--
-- ============================================================