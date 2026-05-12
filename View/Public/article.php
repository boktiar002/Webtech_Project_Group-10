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
        <?= nl2br(htmlspecialchars(