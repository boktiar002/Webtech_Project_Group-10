<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Article.php';

$articleModel = new Article();
$articleModel->publishScheduled();

$category_id = isset($_GET['category_id']) && $_GET['category_id'] !== ''
    ? (int)$_GET['category_id']
    : null;

$articles = $articleModel->getPublished($category_id);
echo json_encode($articles);