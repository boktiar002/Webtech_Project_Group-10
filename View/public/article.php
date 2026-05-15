<style>
    .article-shell {
        max-width: 960px;
        margin: 32px auto 56px;
        padding: 0 20px;
        color: #1f2937;
    }

    .article-card,
    .comments-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
    }

    .article-card {
        overflow: hidden;
        margin-bottom: 28px;
    }

    .article-cover {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        display: block;
    }

    .article-content {
        padding: 28px;
    }

    .article-title {
        font-size: 2rem;
        line-height: 1.2;
        margin: 0 0 14px;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 22px;
        color: #6b7280;
    }

    .article-author {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #111827;
        font-weight: 700;
    }

    .article-author img {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        object-fit: cover;
        border: 2px solid #e5e7eb;
    }

    .article-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 0.85rem;
        background: #eef2ff;
        color: #3730a3;
    }

    .tag-list {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .tag-pill {
        background: #f3f4f6;
        color: #374151;
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 0.84rem;
    }

    .article-body {
        font-size: 1rem;
        line-height: 1.9;
        color: #374151;
    }

    .article-actions {
        margin-top: 30px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .article-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 999px;
        padding: 12px 20px;
        background: #0f766e;
        color: #ffffff;
        text-decoration: none;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s ease, background 0.15s ease;
    }

    .article-button:hover {
        background: #115e59;
        transform: translateY(-1px);
    }

    .article-button.liked {
        background: #dc2626;
    }

    .comments-card {
        padding: 28px;
    }

    @media (max-width: 640px) {
        .article-shell {
            padding: 0 14px;
        }

        .article-content,
        .comments-card {
            padding: 18px;
        }

        .article-title {
            font-size: 1.6rem;
        }
    }
</style>

<div class="article-shell">
    <article class="article-card">
        <?php if ($article['featured_image_path']): ?>
            <img
                class="article-cover"
                src="/Webtech_Project_Group-10/<?php echo htmlspecialchars($article['featured_image_path']); ?>"
                alt="<?php echo htmlspecialchars($article['title']); ?>"
            >
        <?php endif; ?>

        <div class="article-content">
            <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>

            <div class="article-meta">
                <a class="article-author" href="index.php?page=author&id=<?php echo $article['author_id']; ?>">
                    <img
                        src="<?php echo $article['profile_pic_path']
                            ? '/Webtech_Project_Group-10/public/uploads/avatars/' . htmlspecialchars($article['profile_pic_path'])
                            : 'https://placehold.co/40x40'; ?>"
                        alt="<?php echo htmlspecialchars($article['author_name'] ?? 'Unknown'); ?>"
                    >
                    <span><?php echo htmlspecialchars($article['author_name'] ?? 'Unknown'); ?></span>
                </a>

                <span><?php echo date('M d, Y', strtotime($article['created_at'])); ?></span>

                <?php if ($article['category_name']): ?>
                    <span class="article-chip"><?php echo htmlspecialchars($article['category_name']); ?></span>
                <?php endif; ?>
            </div>

            <?php if (!empty($tags)): ?>
                <div class="tag-list">
                    <?php foreach ($tags as $tag): ?>
                        <span class="tag-pill">#<?php echo htmlspecialchars($tag); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="article-body">
                <?php echo nl2br(htmlspecialchars($article['body'])); ?>
            </div>

            <div class="article-actions">
                <a class="article-button" href="/Webtech_Project_Group-10/index.php">Reader Home</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <button id="like-btn" class="article-button" onclick="toggleLike(<?php echo $article['id']; ?>)">
                        Like <span id="like-count"><?php echo (int) $article['like_count']; ?></span>
                    </button>
                <?php else: ?>
                    <a class="article-button" href="/Webtech_Project_Group-10/index.php?page=login">
                        Like <?php echo (int) $article['like_count']; ?> - Login first
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </article>

    <section class="comments-card">
        <?php include __DIR__ . '/../comments/comment_section.php'; ?>
    </section>
</div>

<script>
window.currentUserId = <?php echo isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 'null'; ?>;

function toggleLike(articleId) {
    fetch('/Webtech_Project_Group-10/Api/likes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ article_id: articleId })
    })
    .then(res => res.json())
    .then(data => {
        const count = document.getElementById('like-count');
        const btn = document.getElementById('like-btn');

        if (count) {
            count.textContent = data.count;
        }

        if (btn) {
            btn.classList.toggle('liked', !!data.liked);
        }
    })
    .catch(() => alert('Something went wrong. Try again.'));
}
</script>
<script src="/Webtech_Project_Group-10/public/js/comments.js"></script>
