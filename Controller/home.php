<?php
// Database and session are initialized in index.php.
$database = new Database();
$connection = $database->getConnection();

$connection->query(
    "UPDATE articles
     SET status = 'published'
     WHERE status = 'draft'
       AND publish_at IS NOT NULL
       AND publish_at <= NOW()"
);

$categories = [];
$categoryResult = $connection->query("SELECT id, name FROM categories ORDER BY name ASC");
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row;
}

$articles = [];
$articleQuery = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
        c.name AS category_name,
        (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
    FROM articles a
    LEFT JOIN users u ON a.author_id = u.id
    LEFT JOIN categories c ON a.category_id = c.id
    WHERE a.status = 'published'
    ORDER BY a.created_at DESC";
$articleResult = $connection->query($articleQuery);
while ($row = $articleResult->fetch_assoc()) {
    $articles[] = $row;
}

include __DIR__ . '/../View/Layouts/header.php';
include __DIR__ . '/../View/public/home.php';
include __DIR__ . '/../View/Layouts/footer.php';
?>
