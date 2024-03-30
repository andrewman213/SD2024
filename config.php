<?php
// Database configuration
$host = 'localhost'; // Host name, usually "localhost" with XAMPP
$dbname = 'reddit_clone'; // The actual database name
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password is empty

// Data Source Name (DSN) contains the information required to connect to the database
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Create a PDO instance as db connection
try {
    $pdo = new PDO($dsn, $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; // If connection is successful, show a message
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage(); // If connection fails, show the error
}
?>
