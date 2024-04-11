<?php
session_start();
require 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Niet ingelogd of ongeldig verzoek.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['post_id'])) {
    $post_id = $data['post_id'];
    $user_id = $_SESSION['user_id'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $post_id, $user_id);
            $stmt->execute();
            $liked = false;
        } else {
            $stmt = $conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $post_id, $user_id);
            $stmt->execute();
            $liked = true;
        }
        $stmt->close();
        $conn->commit();

        echo json_encode(['success' => true, 'liked' => $liked]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Post ID niet meegegeven.']);
}
?>
