<div class="container">

    <!-- Featured Image -->
    <?php if ($article['featured_image_path']): ?>
        <img src="<?= 'public/uploads/articles/' . htmlspecialchars($article['featured_image_path']) ?>"
            alt="" style="width:100%; max-height:400px; object-fit:cover; border-radius:10px; margin-bottom:24px;">
    <?php endif; ?>

    <!-- Title -->
    <h1 style="font-size:28px; margin-bottom:12px;">
        <?= htmlspecialchars($article['title']) ?>
    </h1>

    <!-- Meta -->
    <div style="display:flex; align-items:center; gap:16px; margin-bottom:20px; flex-wrap:wrap;">

        <!-- Author -->
        <a href="index.php?page=author&id=<?= $article['author_id'] ?>"
            style="display:flex; align-items:center; gap:8px; text-decoration:none; color:#333;">
            <img src="<?= $article['profile_pic_path']
                ? 'public/uploads/avatars/' . htmlspecialchars($article['profile_pic_path'])
                : 'https://placehold.co/40x40' ?>"
                alt="" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
            <span style="font-weight:bold;"><?= htmlspecialchars($article['author_name'] ?? 'Unknown') ?></span>
        </a>

        <span style="color:#888; font-size:13px;">
            📅 <?= substr($article['created_at'], 0, 10) ?>
        </span>

        <?php if ($article['category_name']): ?>
            <span style="background:#1a1a2e; color:white; padding:3px 12px;
                border-radius:12px; font-size:12px;">
                <?= htmlspecialchars($article['category_name']) ?>
            </span>
        <?php endif; ?>

    </div>

    <!-- Tags -->
    <?php if (!empty($tags)): ?>
        <div style="margin-bottom:20px; display:flex; gap:8px; flex-wrap:wrap;">
            <?php foreach ($tags as $tag): ?>
                <span style="background:#f0f0f0; padding:4px 12px;
                    border-radius:12px; font-size:12px; color:#555;">
                    #<?= htmlspecialchars($tag) ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Body -->
    <div style="background:white; padding:30px; border-radius:10px;
        box-shadow:0 2px 8px rgba(0,0,0,0.08); line-height:1.8;
        font-size:16px; margin-bottom:30px;">
        <?= nl2br(htmlspecialchars($article['body'])) ?>
    </div>

    <!-- Like Button -->
    <div style="margin-bottom:40px;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <button id="like-btn" onclick="toggleLike(<?= $article['id'] ?>)"
                style="background:#1a1a2e; color:white; border:none;
                padding:10px 24px; border-radius:20px; cursor:pointer; font-size:15px;">
                ❤️ <span id="like-count"><?= $article['like_count'] ?></span> Likes
            </button>
        <?php else: ?>
            <a href="login.php" style="background:#1a1a2e; color:white;
                padding:10px 24px; border-radius:20px; text-decoration:none; font-size:15px;">
                ❤️ <?= $article['like_count'] ?> Likes — Login to like
            </a>
        <?php endif; ?>
    </div>

    <!-- Comments Section (Task 4 renders here) -->
    <div id="comments-section">
        <h3 style="margin-bottom:16px;">Comments</h3>
        <p style="color:#888;">Comments loaded by Task 4.</p>
    </div>

</div>

<style>
    #like-btn { transition: transform 0.1s; }
    #like-btn:active { transform: scale(0.95); }
    #like-btn.liked { background: #e74c3c; }
</style>

<script>
function toggleLike(articleId) {
    fetch('api/likes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ article_id: articleId })
    })
    .then(res => res.json())
    .then(data => {
        const btn = document.getElementById('like-btn');
        const countSpan = document.getElementById('like-count');
        countSpan.textContent = data.count;
        if (data.liked) {
            btn.classList.add('liked');
            btn.childNodes[0].textContent = '❤️ ';
        } else {
            btn.classList.remove('liked');
            btn.childNodes[0].textContent = '🤍 ';
        }
    })
    .catch(() => alert('Something went wrong. Try again.'));
}
</script>