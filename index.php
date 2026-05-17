<?php
session_start();
include __DIR__ . '/Config/Database.php';

$json_data = file_get_contents(__DIR__ . '/data.json');
$config = json_decode($json_data, true);

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch($page)
{
    // Boktiar 
    case 'home':
        include __DIR__ . '/Controller/home.php';
        break;

    case 'article':
        if($id)
        {
            include __DIR__ . '/Controller/article.php';
        }
        else
        {
            header('Location: index.php');
            exit;
        }
        break;

    // Sagor 
    case 'login':
        include __DIR__ . '/View/auth/login.php';
        break;

    case 'register':
        include __DIR__ . '/View/auth/register.php';
        break;

    case 'profile':
        include __DIR__ . '/View/auth/profile.php';
        break;

    case 'author':
        include __DIR__ . '/View/authors/profile.php';
        break;

    case 'logout':
        include __DIR__ . '/public/logout.php';
        break;

    // Abir
    case 'dashboard':
        include __DIR__ . '/View/article/dashboard.php';
        break;

    case 'article_form':
        include __DIR__ . '/View/article/create.php';
        break;

    case 'categories':
        include __DIR__ . '/View/category/index.php';
        break;

    case 'tags':
        include __DIR__ . '/Controller/TagController.php';
        break;

    // Roni
    case 'admin':
        if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin')
        {
            $database = new Database();
            $connection = $database->getConnection();
            include __DIR__ . '/View/admin/moderation_dashboard.php';
        }
        else
        {
            echo "<h2 style='text-align:center; margin-top:50px; font-family:sans-serif;'>Access Denied</h2>";
        }
        break;

    case 'comments':
        include __DIR__ . '/View/comments/comment_item.php';
        break;

    default:
        header('Location: index.php');
        exit;
}
?>