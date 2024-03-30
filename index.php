<?php
session_start();
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
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <p>Welcome, please <a href="login.php">login</a> or <a href="registration.php">register</a> to start.</p>
    <?php endif; ?>
</body>
</html>
