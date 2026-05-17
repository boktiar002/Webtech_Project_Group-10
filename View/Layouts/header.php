<?php 
if(session_status() === PHP_SESSION_NONE) 
    session_start(); 

$appRoot = str_replace('\\', '/', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['site_name'] ?? 'BlogNews Platform') ?></title>
    <style>




        *{ margin: 0; padding: 0; box-sizing: border-box; }

        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; }

        nav{
            background: #1a1a2e;
            padding: 14px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        nav.logo {
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
        }
        .search-wrapper {
            position: relative;
            width: 300px;
        }
        .search-wrapper input {
            width: 100%;
            padding: 8px 14px;
            border-radius: 20px;
            border: none;
            outline: none;
            font-size: 14px;
        }
        #search-dropdown {
            position: absolute;
            top: 36px;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            z-index: 999;
            display: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        #search-dropdown a {
            display: block;
            padding: 10px 14px;
            text-decoration: none;
            color: #333;
            font-size: 13px;
            border-bottom: 1px solid #f0f0f0;
        }
        #search-dropdown a:hover { background: #f5f5f5; }


        nav .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 14px;
        }
        nav .nav-links a:hover { color: #aaa; }



        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }

        .tab-btn {
            padding: 8px 18px;
            border: 2px solid #1a1a2e;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
        }
        .tab-btn.active, .tab-btn:hover {
            background: #1a1a2e;
            color: white;
        }

        .card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .card img{ width: 100%; height: 180px; object-fit: cover; }
        .card-body{ padding: 14px; }
        .card-body h3{ font-size: 15px; margin-bottom: 8px; }
        .card-body h3 a{ text-decoration: none; color: #1a1a2e; }
        .card-meta { font-size: 12px; color: #888; display:flex; gap:10px; flex-wrap:wrap; }
        .badge{
            background: #1a1a2e;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
        }
        #like-btn { transition: transform 0.1s; }
        #like-btn:active { transform: scale(0.95); }
        #like-btn.liked { background: #e74c3c; }


    </style>
</head>
<body>

<nav>
    <a href="<?= $appRoot ?>/index.php" class="logo">
        📰 <?= $config['site_name'] ?>
    </a>

    
    <div class="search-wrapper">
        <input type="text" id="search-input"
            placeholder="Search articles..."
            onkeyup="liveSearch()">
        <div id="search-dropdown"></div>
    </div>


    <div class="nav-links">
        <a href="<?= $appRoot ?>/index.php">Home</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="<?= $appRoot ?>/index.php?page=author&id=<?= urlencode($_SESSION['user_id']) ?>"><?= htmlspecialchars($_SESSION['name']) ?></a>
            <a href="<?= $appRoot ?>/index.php?page=logout">Logout</a>
        <?php else: ?>
            <a href="<?= $appRoot ?>/index.php?page=login">Login</a>
            <a href="<?= $appRoot ?>/index.php?page=register">Register</a>
        <?php endif; ?>
    </div>
</nav>



<script>const APP_ROOT = '<?= $appRoot ?>';</script>
<script src="<?= $appRoot ?>/Controller/JS/ajax.js"></script>
