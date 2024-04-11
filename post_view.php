<?php
// post_view.php

include 'header.php';
session_start();
require 'config.php';

// Controleer of er een post_id parameter in de URL is
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Haal de informatie voor de specifieke post
    $post_sql = "SELECT * FROM posts WHERE post_id = ?";
    $post_stmt = $conn->prepare($post_sql);
    $post_stmt->bind_param("i", $post_id);
    $post_stmt->execute();
    $post_result = $post_stmt->get_result();
    $post = $post_result->fetch_assoc();

    // Haal de comments voor deze post
    $comments_sql = "SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC";
    $comments_stmt = $conn->prepare($comments_sql);
    $comments_stmt->bind_param("i", $post_id);
    $comments_stmt->execute();
    $comments_result = $comments_stmt->get_result();

    // Haal het aantal likes voor deze post
    $likes_sql = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = ?";
    $likes_stmt = $conn->prepare($likes_sql);
    $likes_stmt->bind_param("i", $post_id);
    $likes_stmt->execute();
    $likes_result = $likes_stmt->get_result();
    $likes = $likes_result->fetch_assoc();

    // Toon de post, comments en likes
    // ...
}
?>
