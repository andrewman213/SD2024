<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !$_SESSION["is_admin"]) {
    header("location: login.php");
    exit;
}

require_once "config.php";

if (isset($_POST["post_id"]) && !empty(trim($_POST["post_id"]))) {
    $sql = "DELETE FROM posts WHERE post_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_id);

        $param_id = trim($_POST["post_id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            header("location: index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
    
    $conn->close();
} else {
    echo "No post_id parameter was passed to this script.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome to My Reddit Clone</h1>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! You are logged in.</p>
        <a href="create_post.php">Create Post</a>
        <a href="logout.php">Logout</a>

        <?php if ($result && $result->num_rows > 0): ?>
            <div id="posts">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="post">
                        <h2><?php echo htmlspecialchars($row['username']); ?></h2>
                        <p><?php echo htmlspecialchars($row['text_content']); ?></p>
                        <?php if ($row['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post image">
                        <?php endif; ?>
                        <?php if ($row['video_path']): ?>
                            <video src="<?php echo htmlspecialchars($row['video_path']); ?>" controls></video>
                        <?php endif; ?>
                        <span>Posted on: <?php echo htmlspecialchars($row['created_at']); ?></span>
                        <?php if ($isAdmin): ?>
                            <form action="delete_post.php" method="post" style="display: inline;">
                                <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                                <input type="submit" value="Delete Post">
                            </form>
                        <?php endif; ?>
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