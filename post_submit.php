<?php
session_start();
require 'config.php';

// Controleer of het formulier is verzonden en of de gebruiker is ingelogd
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id'])) {
    if (empty($_POST['text_content'])) {
        echo "Voer alstublieft tekst in voor de post.";
    } else {
        $text_content = $_POST['text_content'];
        $image_path = '';
        $video_path = '';

        if (!empty($_FILES['image']['name'])) {
        }

        if (!empty($_FILES['video']['name'])) {
        }

        $stmt = $conn->prepare("INSERT INTO posts (user_id, text_content, image_path, video_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $_SESSION['id'], $text_content, $image_path, $video_path);

        if ($stmt->execute()) {
            // Redirect naar index.php als het succesvol is
            header("Location: index.php");
            exit;
        } else {
            // Toon SQL-fout als het mislukt
            echo "Er is een fout opgetreden: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
} else {
    // Als het verzoek geen POST is of de gebruiker niet is ingelogd
    echo "Niet toegestaan.";
    exit;
}
?>