<?php
session_start();

// Controleer of de gebruiker ingelogd is en of het een admin is
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !$_SESSION["is_admin"]){
    header("location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome to the Admin Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <!-- Plaats hier admin-specifieke functies en informatie -->
    <a href="logout.php">Logout</a>
</body>
</html>
