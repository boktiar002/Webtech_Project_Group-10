<?php
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin'; 

require_once 'config/database.php';
require_once 'models/Article.php';
require_once 'models/Comment.php';
require_once 'models/Report.php';

$database = new Database();
$db = $database->getConnection();
$page = isset($_GET['page']) ? $_GET['page'] : 'article';
$article_id = 1;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP MVC Project</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<nav class="container" style="margin-bottom: 20px; padding: 10px 30px;">
    <a href="index.php?page=article">View Article</a> | 
    <a href="index.php?page=admin">Admin Dashboard</a>
</nav>

<main class="container">
    <?php
    if ($page === 'admin') {
        // Load Moderation Dashboard
        if ($_SESSION['role'] === 'admin') {
            $reportModel = new Report($db);
            $reports = $reportModel->getAllReports()->fetchAll(PDO::FETCH_ASSOC);
            include 'views/admin/moderation_dashboard.php';
            echo '<script src="public/js/moderation.js"></script>';
        } else {
            echo "<h2>Access Denied</h2>";
        }
    } else {
        $commentModel = new Comment($db);
        $comments = $commentModel->getCommentsByArticle($article_id)->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h1>Sample Article Page</h1>";
        echo "<p>This is where the article content would be displayed.</p>";
        
        include 'views/comments/comment_section.php';
        echo '<script src="public/js/comments.js"></script>';
    }
    ?>
</main>

</body>
</html>