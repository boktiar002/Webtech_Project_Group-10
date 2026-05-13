<?php
<div class="container article-page">
    <?php if ($article['featured_image_path']): ?>
        <div class="article-hero">
            <img src="<?= 'Public/uploads/articles/' . htmlspecialchars($article['featured_image_path']) ?>"
                alt="Featured image">
        </div>
    <?php endif; ?>

    <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>

    <div class="article-meta">
        <a href="index.php?page=author&id=<?= $article['author_id'] ?>" class="author-pill">
            <img src="<?= $article['profile_pic_path']
                ? 'public/uploads/avatars/' . htmlspecialchars($article['profile_pic_path'])
                : 'https://placehold.co/44x44' ?>"
                alt="Author avatar" class="author-avatar">
            <span><?= htmlspecialchars($article['author_name'] ?? 'Unknown') ?></span>
        </a>

        <span>📅 <?= substr($article['created_at'], 0, 10) ?></span>

        <?php if ($article['category_name']): ?>
            <span class="badge"><?= htmlspecialchars($article['category_name']) ?></span>
        <?php endif; ?>
    </div>

    <?php if (!empty($tags)): ?>
        <div class="tag-list">
            <?php foreach ($tags as $tag): ?>
                <span class="tag-pill">#<?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="article-body">
        <?= nl2br(htmlspecialchars($article['body'])) ?>
    </div>

    <div class="like-section">
        <?php if (isset($_SESSION['user_id'])): ?>
            <button id="like-btn" class="like-button" onclick="toggleLike(<?= $article['id'] ?>)">
                ❤️ <span id="like-count"><?= $article['like_count'] ?></span> Likes
            </button>
        <?php else: ?>
            <a href="login.php" class="like-button">
                ❤️ <?= $article['like_count'] ?> Likes — Login to like
            </a>
        <?php endif; ?>
    </div>

    <div id="comments-section">
        <h3>Comments</h3>
        <p class="page-subtitle">Comments loaded by Task 4.</p>
    </div>
</div>

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
        btn.classList.toggle('liked', data.liked);
    })
    .catch(() => alert('Something went wrong. Try again.'));
}
</script>
