<?php
// get_comments.php

// Start the session and include the database configuration
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $post_id = $data['post_id'];

    // Prepare SQL to fetch comments. Ensure column names match your database structure.
    $sql = "SELECT user_id, comment, created_at FROM comments WHERE post_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $post_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $comments = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } else {
            $comments = ['error' => 'Failed to execute query.'];
        }
    } else {
        $comments = ['error' => 'Failed to prepare statement.'];
    }

    // Set header to application/json for proper AJAX handling
    header('Content-Type: application/json');
    echo json_encode($comments);
    $conn->close();
    exit;
}
?>
