<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id'])) {
    $text_content = $_POST['text_content'];
    // Verwerk en sla de afbeelding/video op, genereer paden
    $image_path = ''; // Stel in op de opgeslagen bestandspad
    $video_path = ''; // Stel in op de opgeslagen bestandspad

    // Bereid de SQL-query voor
    $stmt = $conn->prepare("INSERT INTO posts (user_id, text_content, image_path, video_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $_SESSION['id'], $text_content, $image_path, $video_path);

    // Voer de query uit
    if($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: index.php"); // Redirect naar index.php
        exit;
    } else {
        echo "Er is een fout opgetreden: " . $conn->error;
    }
} else {
    echo "Niet toegestaan.";
}
?>
