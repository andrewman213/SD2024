<?php
// manage_profile.php
session_start();

// Controleer of de gebruiker is ingelogd, anders omleiden naar login pagina
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

// Query om alle posts van de ingelogde gebruiker op te halen
$user_id = $_SESSION['id']; // Zorg dat je hier de juiste sessievariabele gebruikt voor user ID
$query = "SELECT * FROM posts WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Verwijder logica
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    
    // Verwijder alleen posts die toebehoren aan de ingelogde gebruiker
    $deleteQuery = "DELETE FROM posts WHERE post_id = ? AND user_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("ii", $post_id, $user_id);
    $deleteStmt->execute();
    $deleteStmt->close();

    // Ververs de pagina om de lijst te updaten
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
    <link href="manage_profile.css" rel="stylesheet" type="text/css"> <!-- De CSS-bestandsnaam komt overeen met de PHP-bestandsnaam -->
</head>
<body>
    <h1>Profile Management</h1>
    <p>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>, manage your profile here.</p>
    
    <div class="posts-container">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="post-item">
                <h3><?php echo htmlspecialchars($row['text_content']); ?></h3>
                <?php if ($row['image_path']): ?>
                    <img src="/SD2024/uploads/<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post Image" class="post-image">
                <?php endif; ?>
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
