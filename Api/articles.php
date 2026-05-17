<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../Config/Database.php';

$database = new Database();
$connection = $database->getConnection();

$categoryId = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? (int) $_GET['category_id'] : null;

$sql = "SELECT a.id, a.title, a.featured_image_path, a.created_at, u.name AS author_name, c.name AS category_name,
        (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
    FROM articles a
    LEFT JOIN users u ON a.author_id = u.id
    LEFT JOIN categories c ON a.category_id = c.id
    WHERE a.status = 'published'";

$params = [];
if ($categoryId) {
    $sql .= " AND a.category_id = ?";
}
$sql .= " ORDER BY a.created_at DESC";

if ($categoryId) {
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $connection->query($sql);
}

$articles = [];
while ($row = $result->fetch_assoc()) {
    $articles[] = $row;
}

echo json_encode($articles);
