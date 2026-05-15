<?php
header("Content-Type: application/json");
require_once '../../config/database.php';
require_once '../../controllers/ModerationController.php';

$db = (new Database())->getConnection();
$controller = new ModerationController($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->comment_id)) {
    echo json_encode($controller->deleteReportedComment($data->comment_id));
} else {
    echo json_encode(['success' => false, 'message' => 'Comment ID required.']);
}