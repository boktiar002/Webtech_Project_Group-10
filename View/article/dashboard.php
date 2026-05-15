<?php
require_once __DIR__ . "/../../Controller/ArticleController.php";

$controller = new ArticleController();

if (isset($_GET['delete'])) {
    $controller->delete($_GET['delete']);
}

$result = $controller->index();
?>

<style>
    .article-dashboard {
        max-width: 1080px;
        margin: 36px auto;
        padding: 0 20px 40px;
        color: #1f2937;
    }

    .dashboard-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .dashboard-topbar h2 {
        margin: 0;
        font-size: 2rem;
    }

    .dashboard-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .dashboard-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        padding: 11px 16px;
        border-radius: 999px;
        font-weight: 700;
        border: 1px solid #d1d5db;
        color: #0f172a;
        background: #fff;
    }

    .dashboard-link.primary {
        background: #0f766e;
        border-color: #0f766e;
        color: #fff;
    }

    .article-grid {
        display: grid;
        gap: 18px;
    }

    .article-row {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 18px;
        align-items: center;
    }

    .article-row h3 {
        margin: 0 0 8px;
        font-size: 1.15rem;
    }

    .article-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        color: #6b7280;
        font-size: 0.92rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #fff;
    }

    .status-badge.published {
        background: #15803d;
    }

    .status-badge.draft {
        background: #6b7280;
    }

    .article-row-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .action-link,
    .action-button {
        text-decoration: none;
        padding: 10px 14px;
        border-radius: 999px;
        font-weight: 700;
        border: none;
        cursor: pointer;
    }

    .action-link {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .action-link.delete {
        background: #fef2f2;
        color: #dc2626;
    }

    .action-button {
        background: #111827;
        color: #fff;
    }

    .empty-state {
        background: #fff;
        border: 1px dashed #cbd5e1;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 760px) {
        .article-dashboard {
            padding: 0 14px 26px;
        }

        .article-row {
            grid-template-columns: 1fr;
        }

        .article-row-actions {
            justify-content: flex-start;
        }
    }
</style>

<div class="article-dashboard">
    <div class="dashboard-topbar">
        <h2>Article Dashboard</h2>
        <div class="dashboard-actions">
            <a class="dashboard-link primary" href="/Webtech_Project_Group-10/View/article/create.php">New Article</a>
            <a class="dashboard-link" href="/Webtech_Project_Group-10/View/category/index.php">Manage Categories & Tags</a>
        </div>
    </div>

    <div class="article-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="article-row" id="row-<?php echo $row['id']; ?>">
                    <div>
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <div class="article-meta">
                            <span>ID: <?php echo (int) $row['id']; ?></span>
                            <span>Views: <?php echo (int) $row['view_count']; ?></span>
                            <span id="badge-<?php echo $row['id']; ?>" class="status-badge <?php echo $row['status'] == 'published' ? 'published' : 'draft'; ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </div>
                    </div>

                    <div class="article-row-actions">
                        <a class="action-link" href="/Webtech_Project_Group-10/View/article/edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a class="action-link delete" href="/Webtech_Project_Group-10/index.php?page=dashboard&delete=<?php echo $row['id']; ?>" onclick="return confirm('Confirm delete?')">Delete</a>
                        <button class="action-button" onclick="toggleStatus(<?php echo $row['id']; ?>, this)">
                            <?php echo $row['status'] == 'published' ? 'Unpublish' : 'Publish'; ?>
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                No articles yet. Create your first article to get started.
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleStatus(id, btn) {
    fetch('/Webtech_Project_Group-10/Api/toggle_article.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: id})
    })
    .then(r => r.json())
    .then(data => {
        const badge = document.getElementById('badge-' + id);

        if (badge) {
            badge.textContent = data.status;
            badge.classList.toggle('published', data.status === 'published');
            badge.classList.toggle('draft', data.status !== 'published');
        }

        btn.textContent = data.status === 'published' ? 'Unpublish' : 'Publish';
    });
}
</script>
