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
$sql = "SELECT p.post_id, p.user_id, p.text_content, p.image_path, p.video_path, p.created_at, u.username 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.text_content LIKE ?";

if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    if ($sort == 'oldest') {
        $sql .= " ORDER BY p.created_at ASC";
    } elseif ($sort == 'newest') {
        $sql .= " ORDER BY p.created_at DESC";
    }
}

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
    <link href="index.css" rel="stylesheet" type="text/css">
</head>
<body>
    <h1>Welcome to My Reddit Clone</h1>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! You are logged in.</p>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="search" placeholder="Search posts" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <div class="sort-buttons">
        <a href="?sort=oldest">Sort by Oldest</a>
        <a href="?sort=newest">Sort by Newest</a>
    </div>

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
                        <form action="delete_post.php" method="post">
                            <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                            <input type="submit" value="Delete Post">
                        </form>
                    <?php endif; ?>

                    <?php
                    $comment_sql = "SELECT COUNT(*) AS comment_count FROM comments WHERE post_id = ?";
                    $comment_stmt = $conn->prepare($comment_sql);
                    $comment_stmt->bind_param("i", $row['post_id']);
                    $comment_stmt->execute();
                    $comment_result = $comment_stmt->get_result();
                    $comment_row = $comment_result->fetch_assoc();
                    $comment_count = $comment_row['comment_count'];
                    $comment_stmt->close();
                    ?>
                    <button type="button" onclick="openCommentsModal(<?php echo $row['post_id']; ?>)">
                        Read Comments (<?php echo $comment_count; ?>)
                    </button>

                    <div class="comments" id="comments-<?php echo $row['post_id']; ?>">
                        <div id="comments-list-<?php echo $row['post_id']; ?>"></div>
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                            <div class="comment-form">
                                <form action="add_comment.php" method="post">
                                    <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                                    <textarea name="comment" placeholder="Add a comment..." required></textarea>
                                    <input type="submit" value="Submit Comment">
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>

    <div id="commentsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCommentsModal()">&times;</span>
            <div id="commentsList"></div>
        </div>
    </div>

    <script src="index.js"></script>
</body>
</html>