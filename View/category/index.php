<?php
require_once __DIR__ . "/../../Controller/CategoryController.php";
require_once __DIR__ . "/../../Controller/TagController.php";

$config = $config ?? json_decode(file_get_contents(__DIR__ . "/../../data.json"), true);
include __DIR__ . "/../Layouts/header.php";

$categoryController = new CategoryController();
$tagController = new TagController();

if (isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);
    if (!empty($name)) {
        $categoryController->store($name);
    }
}

if (isset($_GET['delete_category'])) {
    $result = $categoryController->delete($_GET['delete_category']);
    if ($result === false) {
        $cat_error = "Cannot delete - articles are using this category.";
    } else {
        header("Location: /Webtech_Project_Group-10/index.php?page=categories");
        exit();
    }
}

if (isset($_POST['add_tag'])) {
    $name = trim($_POST['tag_name']);
    if (!empty($name)) {
        $tagController->store($name);
    }
}

if (isset($_GET['delete_tag'])) {
    $result = $tagController->delete($_GET['delete_tag']);
    if ($result === false) {
        $tag_error = "Cannot delete - articles are using this tag.";
    } else {
        header("Location: /Webtech_Project_Group-10/index.php?page=categories");
        exit();
    }
}

$categories = $categoryController->index();
$tags = $tagController->index();
?>

<style>
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #1f2937;
            margin: 0;
            padding: 30px 18px;
        }

        .wrap {
            max-width: 980px;
            margin: 0 auto;
        }

        .hero {
            background: linear-gradient(135deg, #0f766e, #1d4ed8);
            color: #fff;
            border-radius: 24px;
            padding: 26px 24px;
            margin-bottom: 22px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        }

        .hero h2 {
            margin: 0 0 6px;
            font-size: 2rem;
        }

        .hero p {
            margin: 0;
            color: rgba(255, 255, 255, 0.88);
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .back-link {
            text-decoration: none;
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
            padding: 10px 16px;
            border-radius: 999px;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .panel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        .panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.06);
            padding: 22px;
        }

        .panel h3 {
            margin-top: 0;
        }

        .error {
            color: #b91c1c;
            margin-bottom: 12px;
        }

        form {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        input[type="text"] {
            flex: 1 1 220px;
            padding: 11px 14px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
        }

        button {
            border: none;
            border-radius: 999px;
            padding: 11px 16px;
            background: #0f766e;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(15, 118, 110, 0.18);
        }

        .item-list {
            display: grid;
            gap: 10px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            background: #f8fafc;
            border-radius: 14px;
        }

        .item-meta {
            color: #64748b;
            font-size: 0.92rem;
        }

        .delete-link {
            text-decoration: none;
            color: #dc2626;
            font-weight: 700;
        }

        .section-caption {
            margin-top: 0;
            margin-bottom: 16px;
            color: #64748b;
        }
    </style>
    <div class="wrap">
        <div class="hero">
            <div class="topbar">
                <div>
                    <h2>Category & Tag Management</h2>
                    <p>Organize articles, keep tags tidy, and manage publishing structure in one place.</p>
                </div>
                <a class="back-link" href="/Webtech_Project_Group-10/index.php?page=dashboard">Back to Dashboard</a>
            </div>
        </div>

        <div class="panel-grid">
            <section class="panel">
                <h3>Categories</h3>
                <p class="section-caption">Use categories to group articles into broad sections.</p>

                <?php if (isset($cat_error)): ?>
                    <p class="error"><?php echo htmlspecialchars($cat_error); ?></p>
                <?php endif; ?>

                <form method="POST">
                    <input type="text" name="category_name" placeholder="New category name" required>
                    <button type="submit" name="add_category">Add Category</button>
                </form>

                <div class="item-list">
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <div class="item-row">
                            <div>
                                <strong><?php echo htmlspecialchars($cat['name']); ?></strong>
                                <div class="item-meta">ID: <?php echo (int) $cat['id']; ?></div>
                            </div>
                            <a class="delete-link" href="/Webtech_Project_Group-10/index.php?page=categories&delete_category=<?php echo $cat['id']; ?>" onclick="return confirm('Delete this category?')">Delete</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>

            <section class="panel">
                <h3>Tags</h3>
                <p class="section-caption">Use tags for smaller topics and better article discovery.</p>

                <?php if (isset($tag_error)): ?>
                    <p class="error"><?php echo htmlspecialchars($tag_error); ?></p>
                <?php endif; ?>

                <form method="POST">
                    <input type="text" name="tag_name" placeholder="New tag name" required>
                    <button type="submit" name="add_tag">Add Tag</button>
                </form>

                <div class="item-list">
                    <?php while ($tag = $tags->fetch_assoc()): ?>
                        <div class="item-row">
                            <div>
                                <strong><?php echo htmlspecialchars($tag['name']); ?></strong>
                                <div class="item-meta">ID: <?php echo (int) $tag['id']; ?></div>
                            </div>
                            <a class="delete-link" href="/Webtech_Project_Group-10/index.php?page=categories&delete_tag=<?php echo $tag['id']; ?>" onclick="return confirm('Delete this tag?')">Delete</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        </div>
    </div>
<?php include __DIR__ . "/../Layouts/footer.php"; ?>
