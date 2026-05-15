<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Controller/CommentController.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

$controller = new CommentController($db);

$input = file_get_contents("php://input");
$data = json_decode($input);
if (!empty($data->article_id) && !empty($data->user_id) && !empty($data->body)) {
    
    $result = $controller->createComment($data->article_id, $data->user_id, $data->body);
    echo json_encode($result);

} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Incomplete data. Article ID, User ID, and Body are required.'
    ]);
}
