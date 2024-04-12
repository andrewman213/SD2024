<?php include 'header.php'; ?>

<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? ''; 
    $text_content = $_POST['text_content'] ?? '';
    $image_path = '';

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filesize = $_FILES["image"]["size"];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (!array_key_exists($ext, $allowed)) die("Error: Invalid file format.");
        if ($filesize > 5 * 1024 * 1024) die("Error: File size is larger than allowed limit.");
        
        $new_filename = uniqid() . "." . $ext;
        $image_path = "uploads/" . $new_filename;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            die('Error: Failed to move uploaded file.');
        }
    }

    if ($title !== '' && ($text_content !== '' || $image_path !== '')) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, text_content, image_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $_SESSION['id'], $title, $text_content, $image_path);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit;
        } else {
            echo 'Post creation failed: ' . htmlspecialchars($conn->error);
            $stmt->close();
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
    <link rel="stylesheet" href="create_post.css"> 
</head>

<body>
    <h1>Create a new post</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="text_content">Post Content:</label>
        <textarea id="text_content" name="text_content" required></textarea><br>

        <label for="image">Select image to upload:</label>
        <input type="file" name="image" id="image"><br>

        <input type="submit" value="Upload Post" name="submit">
    </form>
    <form action="manage_profile.php" method="get">
        <input type="submit" value="Manage Profile">
    </form>
</body>
</html>