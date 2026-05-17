<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Model/Comment.php';

$inputRaw = file_get_contents('php://input');
$data = json_decode($inputRaw, true);

if (!isset($_SESSION['user_id']) || !isset($data['body'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized or invalid data.']);
    exit;
}

$db = (new Database())->getConnection();
$commentModel = new Comment($db);

$articleId = (int)$data['article_id'];
$userId = (int)$_SESSION['user_id'];
$body = trim($data['body']);

if (strlen($body) < 5) {
    echo json_encode(['success' => false, 'message' => 'Comment must be at least 5 characters long.']);
    exit;
}

if ($commentModel->createComment($articleId, $userId, $body)) {
    echo json_encode(['success' => true, 'message' => 'Comment posted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error. Failed to save comment.']);
}
exit;