<?php
header('Content-Type: application/json');
require_once __DIR__ . "/../Model/Article.php";

$data = json_decode(file_get_contents('php://input'), true);
$id   = $data['id'] ?? null;

if(!$id){
    echo json_encode(['error' => 'No ID']);
    exit;
}

$article   = new Article();
$result    = $article->getById($id)->fetch_assoc();
$newStatus = $result['status'] === 'published' ? 'draft' : 'published';

$stmt = $article->conn->prepare("UPDATE articles SET status=? WHERE id=?");
$stmt->bind_param("si", $newStatus, $id);
$stmt->execute();

echo json_encode(['status' => $newStatus]);
?>