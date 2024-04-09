<!-- header.php -->
<?php
// Start the session in the header if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Determine the name to display
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Make sure to link to your general_structure.css file -->
    <link href="general_structure.css" rel="stylesheet" type="text/css">
    <title>My Reddit Clone</title>
</head>
<body>
    <header>
        <h1>My Reddit Clone</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <li><a href="create_post.php">Create Post</a></li>
                    <li><a href="manage_profile.php">Profile Management</a></li>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
