<?php
ob_start(); // Start output buffering

session_start();
require 'config.php';
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'], $_POST['post_id'], $_SESSION['id'])) {
    $comment = trim($_POST['comment']);
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['id'];

    // De kolomnaam hier moet 'comment' zijn, zoals in je database.
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    if ($stmt === false) {
        $response['success'] = false;
        $response['message'] = 'Failed to prepare the statement: ' . htmlspecialchars($conn->error);
    } else {
        $stmt->bind_param("iis", $post_id, $user_id, $comment);

        // Execute the statement and build the response
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Comment added successfully';
            $response['newCommentCount'] = getCommentCount($post_id, $conn);
        } else {
            $response['success'] = false;
            $response['message'] = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Required fields are missing.';
}

// Use the getCommentCount function to retrieve the new count of comments
function getCommentCount($postId, $conn) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM comments WHERE post_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['count'];
    }
    return false;
}

// Set header to application/json for proper AJAX handling
header('Content-Type: application/json');
echo json_encode($response);

// Close the connection
$conn->close();
ob_end_flush(); // Send output buffer and turn off output buffering
exit;
?>
