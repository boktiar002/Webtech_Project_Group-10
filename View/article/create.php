<?php
require_once __DIR__ . "/../../Controller/ArticleController.php";
require_once __DIR__ . "/../../Controller/CategoryController.php";

$controller = new ArticleController();
$categoryController = new CategoryController();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store();
}

$categories = $categoryController->index();
?>

<style>
    .article-form-page {
        max-width: 860px;
        margin: 36px auto;
        padding: 0 20px 40px;
        color: #1f2937;
    }

    .article-form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.07);
        padding: 28px;
    }

    .article-form-card h2 {
        margin-top: 0;
        margin-bottom: 8px;
        font-size: 2rem;
    }

    .article-form-card p {
        margin-top: 0;
        margin-bottom: 24px;
        color: #64748b;
    }

    .field-group {
        margin-bottom: 18px;
    }

    .field-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .field-group input[type="text"],
    .field-group input[type="datetime-local"],
    .field-group input[type="file"],
    .field-group textarea,
    .field-group select {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 12px 14px;
        font: inherit;
        background: #fff;
    }

    .field-group textarea {
        min-height: 180px;
        resize: vertical;
    }

    .radio-group {
        display: flex;
        gap: 18px;
        flex-wrap: wrap;
    }

    .radio-group label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 26px;
    }

    .btn-primary,
    .btn-secondary {
        text-decoration: none;
        border: none;
        border-radius: 999px;
        padding: 12px 18px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-primary {
        background: #0f766e;
        color: #fff;
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #111827;
    }

    .error-item {
        color: #b91c1c;
        margin: 0 0 8px;
    }

    @media (max-width: 640px) {
        .article-form-page {
            padding: 0 14px 24px;
        }

        .article-form-card {
            padding: 20px;
        }
    }
</style>

<div class="article-form-page">
    <div class="article-form-card">
        <h2>Create Article</h2>
        <p>Write, organize, and publish your next post from one clean form.</p>

        <?php foreach ($errors as $e): ?>
            <p class="error-item"><?php echo htmlspecialchars($e); ?></p>
        <?php endforeach; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="field-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title">
            </div>

            <div class="field-group">
                <label for="body">Body</label>
                <textarea id="body" name="body"></textarea>
            </div>

            <div class="field-group">
                <label for="featured_image">Featured Image (JPEG/PNG, max 3MB)</label>
                <input type="file" id="featured_image" name="featured_image" accept=".jpg,.jpeg,.png">
            </div>

            <div class="field-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">-- Select Category --</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="field-group">
                <label for="tags">Tags (comma separated)</label>
                <input type="text" id="tags" name="tags" placeholder="php, web, tutorial">
            </div>

            <div class="field-group">
                <label>Status</label>
                <div class="radio-group">
                    <label><input type="radio" name="status" value="draft" checked> Draft</label>
                    <label><input type="radio" name="status" value="published"> Published</label>
                </div>
            </div>

            <div class="field-group">
                <label for="publish_at">Scheduled Publish Date (optional)</label>
                <input type="datetime-local" id="publish_at" name="publish_at">
            </div>

            <div class="form-actions">
                <button class="btn-primary" type="submit">Save Article</button>
                <a class="btn-secondary" href="/Webtech_Project_Group-10/index.php?page=dashboard">Cancel</a>
            </div>
        </form>
    </div>
</div>
