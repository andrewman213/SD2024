<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$message = ''; // Variable to hold messages for the user

// Verwerk het formulierverslag wanneer het formulierverslag wordt ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text_content = $_POST['text_content'];
    $image_path = '';

    // Check if file was uploaded without errors
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        // Verify MYME type of the file
        if (in_array($filetype, $allowed)) {
            // Check whether file exists before uploading it
            if (file_exists("uploads/" . $filename)) {
                $message = $filename . ' already exists.';
            } else {
                $image_path = "uploads/" . uniqid() . "." . $ext;
                move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
                $message = "Your file was uploaded successfully.";
            } 
        } else {
            $message = "Error: There was a problem uploading your file. Please try again."; 
        }
    } else {
        $message = "Error: " . $_FILES["image"]["error"];
    }

    // Insert post into the database
    if (!empty($text_content) && !empty($image_path)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, text_content, image_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $_SESSION['id'], $text_content, $image_path);
        
        if ($stmt->execute()) {
            $message = 'Post successfully created!';
        } else {
            $message = 'Post creation failed: ' . $conn->error;
        }

        $stmt->close();
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
    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="text_content">Post Content:</label>
        <textarea id="text_content" name="text_content"></textarea>
        <br>
        <label for="image">Select image to upload:</label>
        <input type="file" name="image" id="image">
        <br>
        <input type="submit" value="Upload Post" name="submit">
    </form>
</body>
</html>
