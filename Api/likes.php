<?php
header('Content-Type: application/json');
session_start();
include "../Models/Database.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Login required']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['article_id']) || !is_numeric($data['article_id'])) {
    echo json_encode(['error' => 'Invalid article']);
    exit;
}

$article_id = (int)$data['article_id'];
$user_id    = (int)$_SESSION['user_id'];

$database = new Database();
$connection = $database->connection();

// check if already liked
$check_sql = "SELECT id FROM likes WHERE article_id = ? AND user_id = ?";
$check_stmt = $connection->prepare($check_sql);
$check_stmt->bind_param("ii", $article_id, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // already liked — remove like
    $del_sql = "DELETE FROM likes WHERE article_id = ? AND user_id = ?";
    $del_stmt = $connection->prepare($del_sql);
    $del_stmt->bind_param("ii", $article_id, $user_id);
    $del_stmt->execute();
    $liked = false;
} else {
    // not liked — add like
    $ins_sql = "INSERT INTO likes (article_id, user_id) VALUES (?, ?)";
    $ins_stmt = $connection->prepare($ins_sql);
    $ins_stmt->bind_param("ii", $article_id, $user_id);
    $ins_stmt->execute();
    $liked = true;
}

// get updated count
$count_sql = "SELECT COUNT(*) AS total FROM likes WHERE article_id = ?";
$count_stmt = $connection->prepare($count_sql);
$count_stmt->bind_param("i", $article_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$count_row = $count_result->fetch_assoc();

echo json_encode(['liked' => $liked, 'count' => (int)$count_row['total']]);
?>