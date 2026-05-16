<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Model/Comment.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $body = trim($_POST['body'] ?? '');
    $articleId = (int) ($_POST['article_id'] ?? 0);

    if (strlen($body) < 5) {
        echo json_encode(['error' => 'Too short']);
        exit;
    }

    $db = (new Database())->getConnection();
    $comment = new Comment($db);
    $success = $comment->create($articleId, $_SESSION['user_id'], $body);

    echo json_encode([
        'success' => $success,
        'user' => $_SESSION['name'],
        'body' => $body
    ]);
    exit;
}

echo json_encode(['error' => 'Unauthorized request']);
?>
