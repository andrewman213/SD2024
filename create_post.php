<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
</head>
<body>
    <h1>Create a New Post</h1>
    <form action="post_submit.php" method="post">
        <label for="text_content">Post Content:</label>
        <textarea id="text_content" name="text_content"></textarea><br>
        <input type="submit" value="Submit Post" name="submit">
    </form>
</body>
</html>
