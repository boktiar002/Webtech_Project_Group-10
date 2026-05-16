<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 0); 

try {
    require_once __DIR__ . '/../../Config/Database.php';
    require_once __DIR__ . '/../../Controller/ReportController.php';

    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        throw new Exception("Database connection failed.");
    }

    $controller = new ReportController($db);
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->comment_id) && !empty($data->user_id) && !empty($data->reason)) {
        $result = $controller->createReport($data->comment_id, $data->user_id, $data->reason);
        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incomplete data sent to server.']);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Server Error: ' . $e->getMessage()
    ]);
}
