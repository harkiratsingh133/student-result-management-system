-- Create Database
CREATE DATABASE college_result_display;
USE college_result_display;

-- Create Faculty Table
CREATE TABLE IF NOT EXISTS faculty 
( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL, 
    sem VARCHAR(50) NOT NULL, 
    email VARCHAR(255) NOT NULL UNIQUE, 
    password VARCHAR(255) NOT NULL, 
    UNIQUE (sem) 
); 

-- Create Students Table
CREATE TABLE students 
( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL, 
    rollno VARCHAR(50) NOT NULL, 
    sem VARCHAR(50) NOT NULL, 
    email VARCHAR(255) NOT NULL, 
    UNIQUE (rollno), 
    UNIQUE (email) 
); 

-- Create Subjects Table
CREATE TABLE IF NOT EXISTS subjects 
( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    Subject1 VARCHAR(255) NOT NULL, 
    Subject2 VARCHAR(255) NOT NULL, 
    Subject3 VARCHAR(255) NOT NULL, 
    Subject4 VARCHAR(255) NOT NULL, 
    Subject5 VARCHAR(255) NOT NULL, 
    sem VARCHAR(50) NOT NULL, 
    UNIQUE (sem) 
); 

-- Create Marks Table
CREATE TABLE marks 
( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(100) NOT NULL, 
    rollno VARCHAR(50) NOT NULL, 
    sem VARCHAR(10) NOT NULL, 
    Subject1 INT NOT NULL, 
    Subject2 INT NOT NULL, 
    Subject3 INT NOT NULL, 
    Subject4 INT NOT NULL, 
    Subject5 INT NOT NULL, 
    Total INT NOT NULL, 
    Percentage DECIMAL(5,2) NOT NULL, 
    Result VARCHAR(10) NOT NULL, 
    certificate_id VARCHAR(50) UNIQUE, -- ✅ UNIQUE CERTIFICATE ID 
    UNIQUE (rollno, sem) -- ✅ PREVENT DUPLICATE MARKS ENTRY 
); 

-- Create Admin Table
CREATE TABLE admin 
( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(100) NOT NULL UNIQUE, 
    password VARCHAR(255) NOT NULL 
); 

-- insert admin
INSERT INTO admin (username, password) VALUES ('admin', SHA2('admin123', 256));

