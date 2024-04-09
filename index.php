<?php include 'header.php'; ?>

<?php
session_start();
require 'config.php';

$search = "";
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

// Check if a search was made
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search = trim($_POST['search']);
}

// The SQL query incorporates the search term using a LIKE clause
$searchString = '%' . $search . '%';
$sql = "SELECT p.post_id, p.user_id, p.text_content, p.image_path, p.video_path, p.created_at, u.username 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.text_content LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $searchString);
$stmt->execute();
$result = $stmt->get_result();

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

        <!-- Search form for posts -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search" placeholder="Search posts" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search">
        </form>

        <!-- Posts Display -->
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
                        <!-- Delete button for admin -->
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
