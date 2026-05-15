<?php
header('Content-Type: application/json');
session_start();
require_once '../config/database.php';
require_once '../Model/Comment.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    if (strlen($_POST['body']) < 5) {
        echo json_encode(['error' => 'Too short']);
        exit;
    }
    $comment = new Comment($db);
    $success = $comment->create($_POST['article_id'], $_SESSION['user_id'], $_POST['body']);
    echo json_encode(['success' => $success, 'user' => $_SESSION['name'], 'body' => $_POST['body']]);
}