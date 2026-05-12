<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Login required']);
    exit;
}

require_once __DIR__ . '/../models/Like.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['article_id']) || !is_numeric($data['article_id'])) {
    echo json_encode(['error' => 'Invalid article']);
    exit;
}

$article_id = (int)$data['article_id'];
$user_id    = (int)$_SESSION['user_id'];

$likeModel = new Like();

if ($likeModel->hasLiked($article_id, $user_id)) {
    $likeModel->unlike($article_id, $user_id);
    $liked = false;
} else {
    $likeModel->like($article_id, $user_id);
    $liked = true;
}

$count = $likeModel->getCount($article_id);
echo json_encode(['liked' => $liked, 'count' => (int)$count]);