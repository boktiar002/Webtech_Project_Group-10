<?php
require_once "../../Controller/ArticleController.php";
require_once "../../Controller/CategoryController.php";

$controller = new ArticleController();
$categoryController = new CategoryController();

$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $controller->store();
}

$categories = $categoryController->index();
?>

<h2>Create Article</h2>

<?php foreach($errors as $e): ?>
    <p style="color:red"><?php echo $e; ?></p>
<?php endforeach; ?>

<form method="POST" enctype="multipart/form-data">

    <label>Title</label><br>
    <input type="text" name="title"><br><br>

    <label>Body</label><br>
    <textarea name="body" rows="6" cols="50"></textarea><br><br>

    <label>Featured Image (JPEG/PNG, max 3MB)</label><br>
    <input type="file" name="featured_image" accept=".jpg,.jpeg,.png"><br><br>

    <label>Category</label><br>
    <select name="category_id">
        <option value="">-- Select Category --</option>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Tags (comma separated, e.g: php, web, tutorial)</label><br>
    <input type="text" name="tags" placeholder="php, web, tutorial"><br><br>

    <label>Status</label><br>
    <input type="radio" name="status" value="draft" checked> Draft
    <input type="radio" name="status" value="published"> Published<br><br>

    <label>Scheduled Publish Date (optional)</label><br>
    <input type="datetime-local" name="publish_at"><br><br>

    <button type="submit">Save</button>
    <a href="dashboard.php">Cancel</a>

</form>