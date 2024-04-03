<?php
session_start();
require_once 'config.php'; // Zorg ervoor dat je de juiste databaseverbinding hier hebt.

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['loggedin'])) {
    $text_content = $_POST['text_content'];

    $stmt = $conn->prepare("INSERT INTO posts (user_id, text_content) VALUES (?, ?)");
    $stmt->bind_param("is", $_SESSION['id'], $text_content);
    
    if($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Er is een fout opgetreden: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Niet toegestaan.";
}
?>
