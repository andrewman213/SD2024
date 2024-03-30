<?php
// config.php

// Database credentials
$servername = "localhost"; // the hostname you need to connect to (e.g. localhost or an IP address)
$username = "database_user"; // your database username
$password = "database_password"; // your database password
$dbname = "reddit_clone"; // your database name

// Create a new database connection instance
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
