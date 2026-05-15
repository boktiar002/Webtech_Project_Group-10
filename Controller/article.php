<?php
include "../Models/Database.php";
session_start();

// read site config from data.json
$json_data = file_get_contents("../data.json");
$config = json_decode($json_data, true);
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../Controller/home.php");
    exit;
}

$id = (int)$_GET['id'];
$database = new Database();
$connection = $database->connection();

// get article
$sql = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
        c.name AS category_name,
        (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
        FROM articles a
        LEFT JOIN users u ON a.author_id = u.id
        LEFT JOIN categories c ON a.category_id = c.id
        WHERE a.id = ? AND a.status = 'published'";
$statement = $connection->prepare($sql);
$statement->bind_param("i", $id);
$statement->execute();
$result = $statement->get_result();
$article = $result->fetch_assoc();

if (!$article) {
    echo "Article not found.";
    exit;
}

// increment view count
$view_sql = "UPDATE articles SET view_count = view_count + 1 WHERE id = ?";
$view_stmt = $connection->prepare($view_sql);
$view_stmt->bind_param("i", $id);
$view_stmt->execute();

// get tags
$tag_sql = "SELECT t.name FROM tags t
            JOIN article_tags art ON t.id = art.tag_id
            WHERE art.article_id = ?";
$tag_stmt = $connection->prepare($tag_sql);
$tag_stmt->bind_param("i", $id);
$tag_stmt->execute();
$tag_result = $tag_stmt->get_result();
$tags = [];
while ($row = $tag_result->fetch_assoc()) {
    $tags[] = $row['name'];
}

include "../View/Layouts/header.php";
include "../View/Public/article.php";
include "../View/Layouts/footer.php";
?>