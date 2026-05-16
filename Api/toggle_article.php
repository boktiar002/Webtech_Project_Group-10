<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../Config/Database.php';

// Safe authentication validation check context standard
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// RAW JSON Input Stream Parsing Fix (Line 15 protection)
$inputRaw = file_get_contents('php://input');
$data = json_decode($inputRaw, true);

// Warning context fallback protection
$articleId = isset($data['id']) ? (int)$data['id'] : 0;

if ($articleId > 0) {
    $db = (new Database())->getConnection();

    // Current status variable lookup fetch sequence
    $stmt = $db->prepare("SELECT status FROM articles WHERE id = ?");
    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        // Toggle action dynamic assignments computation switching
        $currentStatus = $result['status'];
        $newStatus = ($currentStatus === 'published') ? 'draft' : 'published';

        // Update sequence database context trigger
        $updateStmt = $db->prepare("UPDATE articles SET status = ? WHERE id = ?");
        $updateStmt->bind_param("si", $newStatus, $articleId);
        
        if ($updateStmt->execute()) {
            // Success response back to dashboard.php callback mapping
            echo json_encode([
                'success' => true,
                'status' => $newStatus
            ]);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid Request Execution Processing']);
header("Content-Type: application/json");
require_once __DIR__ . "/../Model/Article.php";

$data = json_decode(file_get_contents("php://input"), true);
$id   = $data["id"] ?? null;

if(!$id) {
    echo json_encode(["error" => "No ID provided"]);
    exit;
}

$article    = new Article();
$newStatus  = $article->toggleArticleStatus($id);

echo json_encode(["status" => $newStatus]);
?>