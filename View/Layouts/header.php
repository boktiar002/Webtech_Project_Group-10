<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog & News Platform</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', Arial, sans-serif; background: #f4f6fb; color: #1f2937; line-height: 1.6; }
        a { color: inherit; }
        img { max-width: 100%; display: block; }
        button { font: inherit; }

        nav {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 16px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        nav .logo {
            color: #f8fafc;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-decoration: none;
        }
        .search-wrapper {
            position: relative;
            width: min(100%, 320px);
        }
        .search-wrapper input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.18);
            background: rgba(255,255,255,0.1);
            color: #f8fafc;
            outline: none;
            transition: background 0.2s, border-color 0.2s;
        }
        .search-wrapper input::placeholder { color: rgba(248,250,252,0.75); }
        .search-wrapper input:focus { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.35); }

        #search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            z-index: 999;
            display: none;
            box-shadow: 0 14px 32px rgba(15,23,42,0.12);
            max-height: 320px;
            overflow-y: auto;
        }
        #search-dropdown a {
            display: block;
            padding: 12px 16px;
            text-decoration: none;
            color: #0f172a;
            font-size: 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        #search-dropdown a:hover { background: #f8fafc; }

        nav .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        nav .nav-links a {
            color: #f8fafc;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }
        nav .nav-links a:hover { color: #cbd5e1; }

        .container { max-width: 1120px; margin: 32px auto; padding: 0 22px; }
        .section-title { margin-bottom: 22px; font-size: 28px; font-weight: 700; color: #0f172a; }
        .page-subtitle { color: #64748b; font-size: 14px; }

        .card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(15,23,42,0.08); transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 18px 40px rgba(15,23,42,0.12); }
        .card img { width: 100%; height: 220px; object-fit: cover; }
        .card-body { padding: 20px; }
        .card-body h3 { font-size: 18px; margin-bottom: 10px; line-height: 1.35; }
        .card-body h3 a { text-decoration: none; color: #0f172a; }
        .card-meta { font-size: 13px; color: #64748b; display: flex; gap: 10px; flex-wrap: wrap; }
        .badge { background: #0f172a; color: white; padding: 4px 12px; border-radius: 999px; font-size: 12px; }

        .article-page { padding-bottom: 40px; }
        .article-hero img { width: 100%; max-height: 420px; object-fit: cover; border-radius: 16px; margin-bottom: 22px; }
        .article-title { font-size: clamp(32px, 4vw, 46px); margin-bottom: 16px; font-weight: 800; color: #0f172a; }
        .article-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 14px; margin-bottom: 18px; color: #475569; font-size: 14px; }
        .author-pill { display: inline-flex; align-items: center; gap: 10px; text-decoration: none; color: #0f172a; }
        .author-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #0f172a; }
        .tag-list { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 24px; }
        .tag-pill { background: #e2e8f0; color: #334155; padding: 8px 14px; border-radius: 999px; font-size: 13px; }
        .article-body { background: white; padding: 32px; border-radius: 22px; box-shadow: 0 14px 30px rgba(15,23,42,0.08); line-height: 1.8; font-size: 17px; color: #334155; margin-bottom: 34px; }
        .like-section { margin-bottom: 40px; }
        .like-button { display: inline-flex; align-items: center; gap: 10px; background: #0f172a; color: white; border: none; padding: 14px 26px; border-radius: 999px; cursor: pointer; transition: transform 0.15s, background 0.2s; }
        .like-button:hover { transform: translateY(-1px); }
        .like-button.liked { background: #e11d48; }

        @media (max-width: 860px) {
            .card img { height: 180px; }
            .container { padding: 0 16px; }
            .card-body h3 { font-size: 17px; }
            nav { justify-content: center; }
        }
        @media (max-width: 640px) {
            nav { flex-direction: column; align-items: stretch; }
            .search-wrapper { width: 100%; }
            .nav-links { justify-content: center; }
            .article-meta { flex-direction: column; align-items: flex-start; }
        }
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
    const wrapper = document.querySelector('.search-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});
</script>