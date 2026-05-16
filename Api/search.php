<?php
header('Content-Type: application/json');
include __DIR__ . '/../Config/Database.php';

if (!isset($_GET['q']) || trim($_GET['q']) === '') {
    echo json_encode([]);
    exit;
}

$q = trim($_GET['q']);
if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$database = new Database();
$connection = $database->connection();

$like = '%' . $q . '%';
$sql = "SELECT DISTINCT a.id, a.title, a.created_at, u.name AS author_name
        FROM articles a
        LEFT JOIN users u ON a.author_id = u.id
        LEFT JOIN article_tags art ON a.id = art.article_id
        LEFT JOIN tags t ON art.tag_id = t.id
        WHERE a.status = 'published'
        AND (a.title LIKE ? OR t.name LIKE ?)
        LIMIT 8";
$statement = $connection->prepare($sql);
$statement->bind_param("ss", $like, $like);
$statement->execute();
$result = $statement->get_result();
$articles = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($articles);
?>