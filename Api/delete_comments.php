<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Model/Comment.php';

$inputRaw = file_get_contents('php://input');
$data = json_decode($inputRaw, true);

if (!isset($data['comment_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid comment target.'
    ]);
    exit;
}

$db = (new Database())->getConnection();
$commentModel = new Comment($db);
$commentId = (int)$data['comment_id'];
$result = $commentModel->deleteComment($commentId);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Comment deleted successfully and cleared from dashboard.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database Error: Failed to remove the comment.'
    ]);
}
exit;