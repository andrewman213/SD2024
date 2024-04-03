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



// Aanname: $conn is je databaseverbinding
// Aanname: gebruiker_id is opgeslagen in $_SESSION['id'] na inloggen

$query = "SELECT post_id, text_content, image_path FROM posts WHERE user_id = ?";
if($stmt = $conn->prepare($query)){
    // Bind de parameter
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        // Hiermee kun je elke post en bijbehorende afbeelding weergeven
        echo "<div>";
        echo "<h3>" . htmlspecialchars($row['text_content']) . "</h3>";
        if($row['image_path']){
            echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='post image'>";
        }
        echo "</div>";
    }
}


$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Profile</title>
</head>
<body>
    <h1>Profile Management</h1>
    <p>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>, manage your profile here.</p>
    
    <h2>Your Posts</h2>
    <?php while($row = $result->fetch_assoc()): ?>
        <div>
            <h3><?php echo htmlspecialchars($row['text_content']); ?></h3>
            <!-- Voeg hier meer details toe zoals afbeeldingen als je die wilt tonen -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                <input type="submit" value="Delete Post">
            </form>
        </div>
    <?php endwhile; ?>
    $conn->close();
</body>
</html>
