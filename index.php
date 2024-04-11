<?php include 'header.php'; ?>

<?php
session_start();
require 'config.php';

$search = "";
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search = trim($_POST['search']);
}

$searchString = '%' . $search . '%';
$sql = "SELECT p.post_id, p.user_id, p.title, p.text_content, p.image_path, p.created_at, u.username,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.post_id) as comment_count
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.title LIKE ? OR p.text_content LIKE ?
        ORDER BY p.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $searchString, $searchString);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="content">
        <h1>Welcome to Reddit Clone</h1>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <p>Hello, <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>! You are logged in.</p>
            <a href="create_post.php">Create Post</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>

        <form class="search-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="search" placeholder="Search posts" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search">
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <div id="posts">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="post">
                        <h2><?php echo htmlspecialchars($row['username'] ?? ''); ?>: <?php echo htmlspecialchars($row['title'] ?? ''); ?></h2>
                        <p><?php echo htmlspecialchars($row['text_content'] ?? ''); ?></p>
                        <?php if ($row['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post image">
                        <?php endif; ?>
                        <span>Posted on: <?php echo htmlspecialchars($row['created_at'] ?? ''); ?></span>
                        <p class="comment-count"><?php echo $row['comment_count']; ?> comments</p>
                        <?php if ($isAdmin): ?>
                            <form action="delete_post.php" method="post">
                                <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                                <input type="submit" value="Delete Post">
                            </form>
                        <?php endif; ?>

                        <!-- Comment Form -->
                        <form action="comment_post.php" method="post">
                            <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                            <input type="text" name="comment" placeholder="Write a comment...">
                            <input type="submit" value="Comment">
                        </form>

                        <!-- Comments weergave -->
                        <?php
                        $commentSql = "SELECT c.comment_text, u.username FROM comments c  JOIN users u ON c.user_id = u.id WHERE c.post_id = ?";
                        $commentStmt = $conn->prepare($commentSql);
                        $commentStmt->bind_param("i", $row['post_id']);
                        $commentStmt->execute();
                        $commentsResult = $commentStmt->get_result();

                        if ($commentsResult && $commentsResult->num_rows > 0): ?>
                            <div class="comments">
                                <?php while ($commentRow = $commentsResult->fetch_assoc()): ?>
                                    <div class="comment">
                                        <strong><?php echo htmlspecialchars($commentRow['username'] ?? ''); ?>:</strong>
                                        <p><?php echo htmlspecialchars($commentRow['comment_text'] ?? ''); ?></p>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p>Geen comments.</p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>
    <script src="index.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const commentForms = document.querySelectorAll('form[action="comment_post.php"]');
        commentForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                const commentInput = this.querySelector('input[name="comment"]');
                if (!commentInput.value.trim()) {
                    alert('Comment cannot be empty!');
                    event.preventDefault();
                }
            });
        });
    });
    </script>
</body>
</html>
