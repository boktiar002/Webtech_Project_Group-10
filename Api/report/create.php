<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Model/Comment.php';
$inputRaw = file_get_contents('php://input');
$data = json_decode($inputRaw, true);
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in to report this comment.'
    ]);
    exit;
}
if (!isset($data['comment_id']) || !isset($data['reason'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Incomplete data sent to server.'
    ]);
    exit;
}

$db = (new Database())->getConnection();
$commentModel = new Comment($db);

$commentId = (int)$data['comment_id'];
$userId = (int)$_SESSION['user_id'];
$reason = trim($data['reason']);

if (empty($reason)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please select a valid reason.'
    ]);
    exit;
}
$result = $commentModel->reportComment($commentId, $userId, $reason);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! The comment has been flagged for moderation.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database Error: Failed to save the report.'
    ]);
}
exit;