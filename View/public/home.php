<div class="container">

    <h2 style="margin-bottom: 20px;">Latest Articles</h2>

    <!-- Category Filter Tabs -->
    <div id="category-tabs" style="margin-bottom: 24px; display:flex; gap:10px; flex-wrap:wrap;">
        <button class="tab-btn active" data-id="" onclick="filterCategory(this, '')">All</button>
        <?php foreach ($categories as $cat): ?>
            <button class="tab-btn" data-id="<?= $cat['id'] ?>"
                onclick="filterCategory(this, '<?= $cat['id'] ?>')">
                <?= htmlspecialchars($cat['name']) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Article Cards Grid -->
    <div id="articles-grid" style="display:grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
        <?php foreach ($articles as $article): ?>
            <?php include __DIR__ . '/article_card.php'; ?>
        <?php endforeach; ?>
    </div>

</div>

<style>
    .tab-btn {
        padding: 8px 18px;
        border: 2px solid #1a1a2e;
        background: white;
        border-radius: 20px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s;
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
        transition: transform 0.2s;
    }
    .card:hover { transform: translateY(-4px); }
    .card img { width: 100%; height: 180px; object-fit: cover; }
    .card-body { padding: 14px; }
    .card-body h3 { font-size: 15px; margin-bottom: 8px; }
    .card-body h3 a { text-decoration: none; color: #1a1a2e; }
    .card-meta { font-size: 12px; color: #888; display:flex; gap:10px; flex-wrap:wrap; }
    .badge {
        background: #1a1a2e;
        color: white;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 11px;
    }
</style>

<script>
function filterCategory(btn, categoryId) {
    // update active tab
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const url = categoryId
        ? `${APP_ROOT}/Api/articles.php?category_id=${categoryId}`
        : `${APP_ROOT}/Api/articles.php`;

    fetch(url)
        .then(res => res.json())
        .then(articles => {
            const grid = document.getElementById('articles-grid');
            if (articles.length === 0) {
                grid.innerHTML = '<p style="color:#888;">No articles found.</p>';
                return;
            }
            grid.innerHTML = articles.map(a => `
                <div class="card">
                    <img src="${a.featured_image_path ? a.featured_image_path : 'https://placehold.co/400x180'}" alt="">
                    <div class="card-body">
                        <h3><a href="index.php?page=article&id=${a.id}">${a.title}</a></h3>
                        <div class="card-meta">
                            <span>✍️ ${a.author_name ?? 'Unknown'}</span>
                            <span>📅 ${a.created_at.substring(0,10)}</span>
                            ${a.category_name ? `<span class="badge">${a.category_name}</span>` : ''}
                            <span>❤️ ${a.like_count}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        });
}
</script>