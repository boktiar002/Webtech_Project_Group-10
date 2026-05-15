<?php
header("Content-Type: application/json");
require_once '../../config/database.php';
require_once '../../models/Comment.php';

$db = (new Database())->getConnection();
$commentModel = new Comment($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $success = $commentModel->deleteComment($data->id);
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'ID required.']);
}