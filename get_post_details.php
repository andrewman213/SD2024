<?php
// get_post_details.php

session_start();
require 'config.php';

if(isset($_POST['post_id']) && !empty($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    
    // Query om de comments en likes van de post te halen
    $comments_query = "SELECT * FROM comments WHERE post_id = ?";
    $likes_query = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = ?";
    
    // Bereid de queries voor en voer ze uit
    $comments_stmt = $conn->prepare($comments_query);
    $comments_stmt->bind_param("i", $post_id);
    $comments_stmt->execute();
    $comments_result = $comments_stmt->get_result();
    
    $likes_stmt = $conn->prepare($likes_query);
    $likes_stmt->bind_param("i", $post_id);
    $likes_stmt->execute();
    $likes_result = $likes_stmt->get_result()->fetch_assoc();
    
    $data = [
        'comments' => $comments_result->fetch_all(MYSQLI_ASSOC),
        'likes' => $likes_result['like_count']
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
    
    $comments_stmt->close();
    $likes_stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Geen post ID meegegeven.']);
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
