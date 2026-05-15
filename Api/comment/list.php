<?php
header("Content-Type: application/json");
require_once '../../config/database.php';
require_once '../../controllers/CommentController.php';

$db = (new Database())->getConnection();
$controller = new CommentController($db);

$article_id = $_GET['article_id'] ?? null;

if ($article_id) {
    echo json_encode($controller->getArticleComments($article_id));
} else {
    echo json_encode(['success' => false, 'message' => 'Article ID required.']);
}