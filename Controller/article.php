<?php
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Controller/CommentController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$config = $config ?? json_decode(file_get_contents(__DIR__ . '/../data.json'), true);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$database = new Database();
$connection = $database->getConnection();

$statement = $connection->prepare("SELECT a.*, u.name AS author_name, u.profile_pic_path,
        c.name AS category_name,
        (SELECT COUNT(*) FROM likes WHERE article_id = a.id) AS like_count
    FROM articles a
    LEFT JOIN users u ON a.author_id = u.id
    LEFT JOIN categories c ON a.category_id = c.id
    WHERE a.id = ? AND a.status = 'published'");
$statement->bind_param("i", $id);
$statement->execute();
$article = $statement->get_result()->fetch_assoc();

if (!$article) {
    include __DIR__ . '/../View/Layouts/header.php';
    echo "<div class=\"container\"><p>Article not found.</p></div>";
    include __DIR__ . '/../View/Layouts/footer.php';
    exit;
}

$viewStatement = $connection->prepare("UPDATE articles SET view_count = view_count + 1 WHERE id = ?");
$viewStatement->bind_param("i", $id);
$viewStatement->execute();

$tagStatement = $connection->prepare("SELECT t.name FROM tags t
    JOIN article_tags art ON t.id = art.tag_id
    WHERE art.article_id = ?");
$tagStatement->bind_param("i", $id);
$tagStatement->execute();
$tagResult = $tagStatement->get_result();
$tags = [];
while ($tag = $tagResult->fetch_assoc()) {
    $tags[] = $tag['name'];
}

$commentController = new CommentController($connection);
$article_id = $id;
$commentsResponse = $commentController->getArticleComments($article_id);
$comments = $commentsResponse['success'] ? $commentsResponse['data'] : [];

include __DIR__ . '/../View/Layouts/header.php';
include __DIR__ . '/../View/public/article.php';
include __DIR__ . '/../View/Layouts/footer.php';
?>
