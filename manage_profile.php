<?php
// manage_profile.php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

// Query to fetch all posts from the logged-in user
$user_id = $_SESSION['id'];
$query = "SELECT * FROM posts WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Deletion logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    
    // Delete only posts that belong to the logged-in user
    $deleteQuery = "DELETE FROM posts WHERE post_id = ? AND user_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("ii", $post_id, $user_id);
    $deleteStmt->execute();
    $deleteStmt->close();

    // Refresh the page to update the list
    header("Location: manage_profile.php");
    exit;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Profile</title>
    <link href="manage_profile.css" rel="stylesheet" type="text/css">
</head>
<body>
    <h1>Profile Management</h1>
    <!-- Display the logged-in user's name -->
    <p class="welcome-message">Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>, manage your profile here.</p>

    <div class="posts-container">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="post-item">
                <div class="post-content">
                    <h3><?php echo htmlspecialchars($row['text_content']); ?></h3>
                    <?php if ($row['image_path']): ?>
                        <!-- Use the value from the database directly -->
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post Image" class="post-image">
                    <?php endif; ?>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="delete-form">
                    <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                    <input type="submit" value="Delete Post" class="delete-button">
                </form>
            </div>
        <?php endwhile; ?>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
