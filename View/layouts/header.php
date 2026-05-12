<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog & News Platform</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; }

        nav {
            background: #1a1a2e;
            padding: 14px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        nav .logo {
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
    </style>
</head>
<body>

<nav>
    <a href="index.php" class="logo">📰 BlogNews</a>

    <!-- Live Search -->
    <div class="search-wrapper">
        <input type="text" id="search-input" placeholder="Search articles...">
        <div id="search-dropdown"></div>
    </div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="#"><?= htmlspecialchars($_SESSION['name']) ?></a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>

<script>
// Live Search with 300ms debounce
const searchInput = document.getElementById('search-input');
const dropdown = document.getElementById('search-dropdown');
let searchTimer;

searchInput.addEventListener('input', function () {
    clearTimeout(searchTimer);
    const q = this.value.trim();

    if (q.length < 2) {
        dropdown.style.display = 'none';
        return;
    }

    searchTimer = setTimeout(() => {
        fetch(`api/search.php?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    dropdown.style.display = 'none';
                    return;
                }
                dropdown.innerHTML = data.map(a =>
                    `<a href="index.php?page=article&id=${a.id}">${a.title} <small style="color:#888">— ${a.author_name}</small></a>`
                ).join('');
                dropdown.style.display = 'block';
            });
    }, 300);
});

document.addEventListener('click', function (e) {
    if (!searchInput.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});
</script>