<?php
// db_connect.php
// This file handles the database connection.
// Replace 'your_db_password' and 'your_db_name' with your actual database credentials.

$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "spicevilla_db"; // The database name we created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session for user management
session_start();
?>