<?php
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