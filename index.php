<?php
session_start();

// Read the query string to decide which page to show.
$page = $_GET['page'] ?? 'home';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : null;

require_once __DIR__ . '/Controller/PublicController.php';
$controller = new PublicController();

switch ($page) {
    case 'home':
        // Show the homepage with published articles.
        $controller->home();
        break;

    case 'article':
        // Show one article if an ID is present.
        if ($id) {
            $controller->article($id);
        } else {
            header('Location: index.php');
        }
        break;

    case 'author':
        // Author profile page, handled by Task 1.
        include __DIR__ . '/views/authors/profile.php';
        break;

    default:
        header('Location: index.php');
        break;
}