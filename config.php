<?php
// config.php
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "reddit_clone"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
