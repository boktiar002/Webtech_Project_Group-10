<?php
header('Content-Type: application/json');
include "../Models/Database.php";

$database = new Database();
$connection = $database->connection();

// publish scheduled
$connection->query("UPDATE articles SET status = 'published'
    WHERE status = 'draft'
    AND publish_at IS NOT NULL
    AND publish_at <= NOW()");

$category_id = isset($_GET['category_id']) && $_GET['category_id'] !== ''
    ? (int)$_GET['category_id']
    : null;

if ($category_id) {
    $sql = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
            c.name AS category_name,
            (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published' AND a.category_id = ?
            ORDER BY a.created_at DESC";
    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $category_id);
} else {
    $sql = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
            c.name AS category_name,
            (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published'
            ORDER BY a.created_at DESC";
    $statement = $connection->prepare($sql);
}

$statement->execute();
$result = $statement->get_result();
$articles = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($articles);
?>