<?php
session_start();
require 'config.php';

// Controleer of het formulier is verzonden en of de gebruiker is ingelogd
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id'])) {
    // Tekstinhoud valideren
    if (empty($_POST['text_content'])) {
        // Als tekstinhoud leeg is, doe dan iets, zoals een foutmelding geven
        echo "Voer alstublieft tekst in voor de post.";
    } else {
        $text_content = $_POST['text_content'];
        $image_path = '';
        $video_path = '';

        // Check en verwerk de geüploade afbeelding als deze bestaat
        if (!empty($_FILES['image']['name'])) {
            // Je upload script hier
        }

        // Check en verwerk de geüploade video als deze bestaat
        if (!empty($_FILES['video']['name'])) {
            // Je upload script hier
        }

        // Bereid de SQL-query voor
        $stmt = $conn->prepare("INSERT INTO posts (user_id, text_content, image_path, video_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $_SESSION['id'], $text_content, $image_path, $video_path);

        // Voer de query uit
        if ($stmt->execute()) {
            // Redirect naar index.php als het succesvol is
            header("Location: index.php");
            exit;
        } else {
            // Toon SQL-fout als het mislukt
            echo "Er is een fout opgetreden: " . $stmt->error;
        }

        // Sluit de prepared statement en de databaseverbinding
        $stmt->close();
        $conn->close();
    }
} else {
    // Als het verzoek geen POST is of de gebruiker niet is ingelogd
    echo "Niet toegestaan.";
    exit;
}
?>
