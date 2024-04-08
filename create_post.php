<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text_content = $_POST['text_content'] ?? '';
    $image_path = '';

    // Verwerk de afbeelding als deze bestaat.
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filesize = $_FILES["image"]["size"];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (!array_key_exists($ext, $allowed)) die("Error: Invalid file format.");
        if ($filesize > 5 * 1024 * 1024) die("Error: File size is larger than allowed limit.");
        
        // Voorkom dat bestanden met dezelfde naam overschreven worden
        $new_filename = uniqid() . "." . $ext;
        $image_path = "uploads/" . $new_filename;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            die('Error: Failed to move uploaded file.');
        }
    }

    // Voeg nu de post toe aan de database
    if ($text_content !== '' || $image_path !== '') {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, text_content, image_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $_SESSION['id'], $text_content, $image_path);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit;
        } else {
            die('Post creation failed: ' . $conn->error);
        }
    }
    $conn->close();
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="text_content">Post Content:</label>
        <textarea id="text_content" name="text_content"></textarea><br>
        <label for="image">Select image to upload:</label>
        <input type="file" name="image" id="image"><br>
        <input type="submit" value="Upload Post" name="submit">
    </form>
    <form action="manage_profile.php" method="get">
        <input type="submit" value="Manage Profile">
    </form>
</body>
</html>
