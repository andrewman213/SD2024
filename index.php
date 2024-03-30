<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reddit Clone</title>
</head>
<body>
    <h1>Welcome to My Reddit Clone</h1>
    <?php if(isset($_SESSION['user_id'])): ?>
        <p>Hello, <?= htmlspecialchars($_SESSION['username']); ?>! You are logged in.</p>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a> | <a href="registration.php">Register</a>
    <?php endif; ?>
</body>
</html>
