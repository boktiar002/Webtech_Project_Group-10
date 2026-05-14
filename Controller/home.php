<?php
include "../Models/Database.php";
session_start();

// read site config from data.json
$json_data = file_get_contents("../data.json");
$config = json_decode($json_data, true);
$database = new Database();
$connection = $database->connection();

// publish scheduled articles
$sql = "UPDATE articles SET status = 'published'
        WHERE status = 'draft'
        AND publish_at IS NOT NULL
        AND publish_at <= NOW()";
$connection->query($sql);

// get all categories
$cat_sql = "SELECT * FROM categories";
$cat_result = $connection->query($cat_sql);
$categories = $cat_result->fetch_all(MYSQLI_ASSOC);

// get all published articles
$art_sql = "SELECT a.*, u.name AS author_name, u.profile_pic_path,
            c.name AS category_name,
            (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published'
            ORDER BY a.created_at DESC";
$art_result = $connection->query($art_sql);
$articles = $art_result->fetch_all(MYSQLI_ASSOC);

include "../View/Layouts/header.php";
include "../View/Public/home.php";
include "../View/Layouts/footer.php";
?>