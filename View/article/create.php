<?php
require_once __DIR__ . "/../../Controller/ArticleController.php";
require_once __DIR__ . "/../../Controller/CategoryController.php";

$config = $config ?? json_decode(file_get_contents(__DIR__ . "/../../data.json"), true);
include __DIR__ . "/../Layouts/header.php";

$controller = new ArticleController();
$categoryController = new CategoryController();

$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $errors = $controller->store() ?? [];
}

$categories = $categoryController->index();
?>

<h2>Create Article</h2>

<?php foreach($errors as $e): ?>
    <p style="color:red"><?php echo $e; ?></p>
<?php endforeach; ?>

<form method="POST" enctype="multipart/form-data">
    <table>
        <tr>
            <td><label>Title</label></td>
            <td><input type="text" name="title"></td>
        </tr>
        <tr>
            <td><label>Body</label></td>
            <td><textarea name="body" rows="6" cols="50"></textarea></td>
        </tr>
        <tr>
            <td><label>Featured Image (JPEG/PNG, max 3MB)</label></td>
            <td><input type="file" name="featured_image" accept=".jpg,.jpeg,.png"></td>
        </tr>
        <tr>
            <td><label>Category</label></td>
            <td>
                <select name="category_id">
                    <option value="">-- Select Category --</option>
                    <?php while($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Tags (comma separated)</label></td>
            <td><input type="text" name="tags" placeholder="php, web, tutorial"></td>
        </tr>
        <tr>
            <td><label>Status</label></td>
            <td>
                <input type="radio" name="status" value="draft" checked> Draft
                <input type="radio" name="status" value="published"> Published
            </td>
        </tr>
        <tr>
            <td><label>Scheduled Publish Date (optional)</label></td>
            <td><input type="datetime-local" name="publish_at"></td>
        </tr>
        <tr>
            <td><input type="submit" value="Save"></td>
            <td><a href="/Webtech_Project_Group-10/index.php?page=dashboard">Cancel</a></td>
        </tr>
    </table>
</form>

<?php include __DIR__ . "/../Layouts/footer.php"; ?>