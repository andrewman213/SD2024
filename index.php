<?php
session_start();
require_once 'config.php'; // Zorg ervoor dat je de juiste databaseverbinding hier hebt.

$sql = "SELECT * FROM posts ORDER BY created_at DESC";
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
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div>
                    <p><?php echo htmlspecialchars($row['text_content']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
        
    <?php else: ?>
        <p>Welcome, please <a href="login.php">login</a> or <a href="registration.php">register</a> to start.</p>
    <?php endif; ?>
</body>
</html>
