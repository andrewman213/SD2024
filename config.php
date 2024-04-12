<?php
// Database credentials
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "reddit_clone"; 

// Attempt to establish a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("ERROR: Could not connect. " . $conn->connect_error);
}

// Enable error reporting for debugging (remove this in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>