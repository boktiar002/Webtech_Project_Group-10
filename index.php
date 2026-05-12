<?php
session_start();

$page = $_GET['page'] ?? 'home';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : null;

require_once __DIR__ . '/controllers/PublicController.php';

$controller = new PublicController();

switch ($page) {
    case 'home':
        $controller->home();
        break;

    case 'article':
        if ($id) {
            $controller->article($id);
        } else {
            header('Location: index.php');
        }
        break;

    case 'author':
        // Task 1 handles this
        include __DIR__ . '/views/authors/profile.php';
        break;

    default:
        header('Location: index.php');
        break;
}