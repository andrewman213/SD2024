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
    <!-- Add your CSS links here -->
</head>
<body>
    <h1>Welcome to My Reddit Clone</h1>
    <!-- User greeting and logout link -->
    <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! You are logged in.</p>
        <a href="create_post.php">Create Post</a>
        <a href="logout.php">Logout</a>
    <?php endif; ?>

    <!-- Search form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="search" placeholder="Search posts" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <!-- Posts and comments display -->
    <?php if($result && $result->num_rows > 0): ?>
        <div id="posts">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="post">
                    <h2><?php echo htmlspecialchars($row['username']); ?></h2>
                    <p><?php echo htmlspecialchars($row['text_content']); ?></p>
                    <!-- Post image if exists -->
                    <?php if($row['image_path']): ?>
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post image">
                    <?php endif; ?>
                    <!-- Post video if exists -->
                    <?php if($row['video_path']): ?>
                        <video src="<?php echo htmlspecialchars($row['video_path']); ?>" controls></video>
                    <?php endif; ?>
                    <span>Posted on: <?php echo htmlspecialchars($row['created_at']); ?></span>
                    <!-- Admin only delete post button -->
                    <?php if ($isAdmin): ?>
                        <form action="delete_post.php" method="post">
                            <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                            <input type="submit" value="Delete Post">
                        </form>
                    <?php endif; ?>

                    <!-- Comments section -->
                    <div class="comments">
                        <!-- Here you should add PHP code to fetch and display comments -->
                        <!-- Comment form -->
                        <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                            <form action="add_comment.php" method="post">
                                <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                                <textarea name="comment" placeholder="Add a comment..." required></textarea>
                                <input type="submit" value="Submit Comment">
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>

    <!-- Footer or additional content here -->

</body>
</html>
