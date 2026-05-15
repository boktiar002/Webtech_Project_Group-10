<div class="card">
    <img src="<?= !empty($article['featured_image_path']) ? htmlspecialchars($article['featured_image_path']) : 'https://placehold.co/400x180' ?>" alt="">
    <div class="card-body">
        <h3>
            <a href="index.php?page=article&id=<?= $article['id'] ?>">
                <?= htmlspecialchars($article['title']) ?>
            </a>
        </h3>
        <div class="card-meta">
            <span>✍️ <?= htmlspecialchars($article['author_name'] ?? 'Unknown') ?></span>
            <span>📅 <?= substr($article['created_at'], 0, 10) ?></span>
            <?php if ($article['category_name']): ?>
                <span class="badge"><?= htmlspecialchars($article['category_name']) ?></span>
            <?php endif; ?>
            <span>❤️ <?= $article['like_count'] ?></span>
        </div>
    </div>
</div>