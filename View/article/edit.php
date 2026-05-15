<?php
require_once "../../Controller/ArticleController.php";
require_once "../../Controller/CategoryController.php";

$controller = new ArticleController();
$categoryController = new CategoryController();

$id = $_GET['id'];
$data = $controller->getById($id);
$row = $data->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $controller->update($id);
}

$categories = $categoryController->index();
?>

<h2>Edit Article</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Title</label><br>
    <input type="text" name="title" value="<?php echo $row['title']; ?>"><br><br>

    <label>Body</label><br>
    <textarea name="body" rows="6" cols="50"><?php echo $row['body']; ?></textarea><br><br>

    <label>Featured Image (JPEG/PNG, max 3MB)</label><br>
    <?php if($row['featured_image_path']): ?>
        <img src="/Webtech_Project_Group-10/<?php echo $row['featured_image_path']; ?>" width="150"><br>
    <?php endif; ?>
    <input type="file" name="featured_image" accept=".jpg,.jpeg,.png"><br><br>

    <label>Category</label><br>
    <select name="category_id">
        <option value="">-- Select Category --</option>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <option value="<?php echo $cat['id']; ?>"
                <?php echo ($cat['id'] == $row['category_id']) ? 'selected' : ''; ?>>
                <?php echo $cat['name']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Tags (comma separated)</label><br>
    <input type="text" name="tags" value="<?php echo $row['tag_names'] ?? ''; ?>" placeholder="php, web, tutorial"><br><br>

    <label>Status</label><br>
    <input type="radio" name="status" value="draft" <?php echo ($row['status']=='draft') ? 'checked' : ''; ?>> Draft
    <input type="radio" name="status" value="published" <?php echo ($row['status']=='published') ? 'checked' : ''; ?>> Published<br><br>

    <label>Scheduled Publish Date (optional)</label><br>
    <input type="datetime-local" name="publish_at" 
           value="<?php echo $row['publish_at'] ? date('Y-m-d\TH:i', strtotime($row['publish_at'])) : ''; ?>"><br><br>

    <button type="submit">Update</button>
    <a href="dashboard.php">Cancel</a>

</form>