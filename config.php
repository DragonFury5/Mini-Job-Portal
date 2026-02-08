<?php
// config.php
$host = 'localhost';
$username = 'root'; // Change to your MySQL username
$password = ''; // Change to your MySQL password
$database = 'job_portal'; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT 1
)";

if (!$conn->query($sql)) {
    // If there's an error, you might want to log it
    error_log("Error creating users table: " . $conn->error);
}

// Create jobs table if it doesn't exist (just in case)
$jobs_sql = "CREATE TABLE IF NOT EXISTS jobs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    company VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    salary VARCHAR(50),
    requirements TEXT,
    posted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT 1
)";

$conn->query($jobs_sql);
?>