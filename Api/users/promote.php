<?php

session_start();

require_once __DIR__ . '/../../Config/Database.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Login Required"
    ]);
    exit();
}

if ($_SESSION['role'] != "admin") {
    echo json_encode([
        "status" => "error",
        "message" => "Admin Only"
    ]);
    exit();
}

if (!isset($_POST['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "User ID Missing"
    ]);
    exit();
}

$userId = (int) $_POST['user_id'];
$connection = (new Database())->getConnection();

$query = "UPDATE users
SET role='author',
pending_author=0
WHERE id=?";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $userId);
$success = $stmt->execute();

if ($success) {
    echo json_encode([
        "status" => "success",
        "message" => "User Promoted"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Promotion Failed"
    ]);
}
?>
