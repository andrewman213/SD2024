<?php
// Plaats dit bovenaan in het create_post.php bestand
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: login.php");
    exit;
}

// Inclusief databaseverbinding
require_once 'config.php';

// Verwerk het formulierverslag wanneer het formulierverslag wordt ingediend
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Formuliervariabelen en uploadlogica hier
    // Zorg ervoor dat je de afbeeldingen/video's uploadt naar een map en hun paden opslaat in de database
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
</head>
<body>
    <h1>Create a new post</h1>
    <form action="post_submit.php" method="post" enctype="multipart/form-data">
        <label for="text_content">Post Content:</label>
        <textarea id="text_content" name="text_content"></textarea>
        <br>
        <label for="image">Select image to upload:</label>
        <input type="file" name="image" id="image">
        <br>
        <label for="video">Select video to upload:</label>
        <input type="file" name="video" id="video">
        <br>
        <input type="submit" value="Upload Post" name="submit">
    </form>
</body>
</html>
