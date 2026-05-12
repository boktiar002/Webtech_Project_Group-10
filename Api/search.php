<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Article.php';

if (!isset($_GET['q']) || trim($_GET['q']) === '') {
    echo json_encode([]);
    exit;
}

$q = trim($_GET['q']);

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$articleModel = new Article();
$results = $articleModel->search($q);
echo json_encode($results);