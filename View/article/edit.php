<?php
require_once __DIR__ . "/../../Controller/ArticleController.php";
require_once __DIR__ . "/../../Controller/CategoryController.php";
session_start();
$config = $config ?? json_decode(file_get_contents(__DIR__ . "/../../data.json"), true);
include __DIR__ . "/../Layouts/header.php";

$controller = new ArticleController();
$categoryController = new CategoryController();

$id = $_GET['id'];
$data = $controller->getById($id);
$row = $data->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->update($id);
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

    .article-form-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .article-form-topbar h2 {
        margin: 0;
    }

    .back-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        background: #e2e8f0;
        color: #0f172a;
        padding: 10px 16px;
        border-radius: 999px;
        font-weight: 700;
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

    .preview-image {
        display: block;
        max-width: 220px;
        border-radius: 14px;
        margin-bottom: 12px;
        border: 1px solid #e5e7eb;
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
        background: #1d4ed8;
        color: #fff;
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #111827;
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
        <div class="article-form-topbar">
            <h2>Edit Article</h2>
            <a class="back-pill" href="/Webtech_Project_Group-10/index.php?page=dashboard">Back to Dashboard</a>
        </div>
        <p>Update content, image, tags, and publish settings from one place.</p>
         <?php if(isset($_SESSION['errors'])): ?>
         <?php foreach($_SESSION['errors'] as $e): ?>
        <p style="color:red"><?php echo $e; ?></p>
         <?php endforeach; ?>
          <?php unset($_SESSION['errors']); ?>
          <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="field-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($row['title']); ?>">
            </div>

            <div class="field-group">
                <label for="body">Body</label>
                <textarea id="body" name="body"><?php echo htmlspecialchars($row['body']); ?></textarea>
            </div>

            <div class="field-group">
                <label for="featured_image">Featured Image (JPEG/PNG, max 3MB)</label>
                <?php if (!empty($row['featured_image_path'])): ?>
                    <img class="preview-image" src="/Webtech_Project_Group-10/<?php echo htmlspecialchars($row['featured_image_path']); ?>" alt="Featured image">
                <?php endif; ?>
                <input type="file" id="featured_image" name="featured_image" accept=".jpg,.jpeg,.png">
            </div>

            <div class="field-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">-- Select Category --</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $row['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="field-group">
                <label for="tags">Tags (comma separated)</label>
                <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($row['tag_names'] ?? ''); ?>" placeholder="php, web, tutorial">
            </div>

            <div class="field-group">
                <label>Status</label>
                <div class="radio-group">
                    <label><input type="radio" name="status" value="draft" <?php echo ($row['status'] == 'draft') ? 'checked' : ''; ?>> Draft</label>
                    <label><input type="radio" name="status" value="published" <?php echo ($row['status'] == 'published') ? 'checked' : ''; ?>> Published</label>
                </div>
            </div>

            <div class="field-group">
                <label for="publish_at">Scheduled Publish Date (optional)</label>
                <input type="datetime-local" id="publish_at" name="publish_at" value="<?php echo $row['publish_at'] ? date('Y-m-d\TH:i', strtotime($row['publish_at'])) : ''; ?>">
            </div>

            <div class="form-actions">
                <button class="btn-primary" type="submit">Update Article</button>
                <a class="btn-secondary" href="/Webtech_Project_Group-10/index.php?page=dashboard">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . "/../Layouts/footer.php"; ?>
