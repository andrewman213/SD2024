<?php
session_start();
require 'config.php'; // Zorg dat je databaseconfiguratie hier inbegrepen is

// Haal alle posts uit de database
$sql = "SELECT p.post_id, p.user_id, p.text_content, p.image_path, p.video_path, p.created_at, u.username FROM posts p JOIN users u ON p.user_id = u.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome to My Reddit Clone</h1>
    <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! You are logged in.</p>
        <a href="create_post.php">Create Post</a>
        <a href="logout.php">Logout</a>

        <!-- Hier worden de posts weergegeven -->
        <?php if($result && $result->num_rows > 0): ?>
            <div id="posts">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="post">
                        <h2><?php echo htmlspecialchars($row['username']); ?></h2>
                        <p><?php echo htmlspecialchars($row['text_content']); ?></p>
                        <?php if($row['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post image">
                        <?php endif; ?>
                        <?php if($row['video_path']): ?>
                            <video src="<?php echo htmlspecialchars($row['video_path']); ?>" controls></video>
                        <?php endif; ?>
                        <span>Posted on: <?php echo htmlspecialchars($row['created_at']); ?></span>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>

    <?php else: ?>
        <p>Welcome, please <a href="login.php">login</a> or <a href="registration.php">register</a> to start.</p>
    <?php endif; ?>
</body>
</html>