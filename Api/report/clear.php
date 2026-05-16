<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Controller/ModerationController.php';

$db = (new Database())->getConnection();
$controller = new ModerationController($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->report_id)) {
    echo json_encode($controller->dismissReport($data->report_id));
} else {
    echo json_encode(['success' => false, 'message' => 'Report ID required.']);
}
