<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reddit Clone</title>
    <link href="general_structure.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header id="header">
        <h1>My Reddit Clone</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
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
    <script>
        var lastScrollTop = 0;
        var header = document.getElementById("header");

        window.addEventListener("scroll", function() {
            var currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll > lastScrollTop) {
                // Scroll down
                header.style.top = "-70px"; // Adjust the value to the height of your header
            } else {
                // Scroll up
                header.style.top = "0";
            }
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // For Mobile or negative scrolling
        }, false);
    </script>
    <!-- The rest of the page content goes here -->
    
</body>
</html>
