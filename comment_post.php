// comment_post.php
<?php
session_start();
require 'config.php'; // Zorg ervoor dat dit bestand je databaseverbindingen bevat.

// Check of de gebruiker is ingelogd en een POST-verzoek heeft gedaan.
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SERVER['REQUEST_METHOD'] == 'POST') {
    // Controleer of de benodigde POST-data bestaat.
    if (isset($_POST['post_id']) && isset($_POST['comment'])) {
        $post_id = $_POST['post_id'];
        $comment = $_POST['comment'];
        $user_id = $_SESSION['user_id']; // Zorg ervoor dat je gebruikers-ID in de sessie staat.

        // Bereid een statement voor om de comment in de database in te voegen.
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $post_id, $user_id, $comment);

        // Voer de query uit en controleer of deze succesvol is.
        if ($stmt->execute()) {
            // Redirect terug naar de pagina waar de gebruiker vandaan kwam.
            header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            echo "Er is iets misgegaan: " . $stmt->error;
        }

        $stmt->close();
    }
} else {
    echo "Niet ingelogd of ongeldig verzoek.";
}

?>
<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST['post_id'];
    $comment = $_POST['comment'];
    // Voeg hier je code toe om de comment op te slaan in je database
    echo "Comment added successfully";
}
?>
