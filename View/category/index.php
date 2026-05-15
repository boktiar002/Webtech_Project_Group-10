<?php
require_once "../../Controller/CategoryController.php";
require_once "../../Controller/TagController.php";

$categoryController = new CategoryController();
$tagController = new TagController();


if(isset($_POST['add_category'])){
    $name = trim($_POST['category_name']);
    if(!empty($name)){
        $categoryController->store($name);
    }
}

if(isset($_GET['delete_category'])){
    $result = $categoryController->delete($_GET['delete_category']);
    if($result === false){
        $cat_error = "Cannot delete — articles are using this category!";
    }
}


if(isset($_POST['add_tag'])){
    $name = trim($_POST['tag_name']);
    if(!empty($name)){
        $tagController->store($name);
    }
}

if(isset($_GET['delete_tag'])){
    $result = $tagController->delete($_GET['delete_tag']);
    if($result === false){
        $tag_error = "Cannot delete — articles are using this tag!";
    }
}

$categories = $categoryController->index();
$tags = $tagController->index();
?>

<!DOCTYPE html>
<html>
<head><title>Category & Tag Management</title></head>
<body>

<h2>Category & Tag Management</h2>
<a href="../article/dashboard.php">← Back to Dashboard</a>
<br><br>


<h3>Categories</h3>

<?php if(isset($cat_error)): ?>
    <p style="color:red"><?php echo $cat_error; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="category_name" placeholder="New category name" required>
    <button type="submit" name="add_category">Add Category</button>
</form>

<br>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Action</th>
</tr>
<?php while($cat = $categories->fetch_assoc()): ?>
<tr>
    <td><?php echo $cat['id']; ?></td>
    <td><?php echo $cat['name']; ?></td>
    <td>
        <a href="index.php?delete_category=<?php echo $cat['id']; ?>"
           onclick="return confirm('Delete this category?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

<br><br>


<h3>Tags</h3>

<?php if(isset($tag_error)): ?>
    <p style="color:red"><?php echo $tag_error; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="tag_name" placeholder="New tag name" required>
    <button type="submit" name="add_tag">Add Tag</button>
</form>

<br>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Action</th>
</tr>
<?php while($tag = $tags->fetch_assoc()): ?>
<tr>
    <td><?php echo $tag['id']; ?></td>
    <td><?php echo $tag['name']; ?></td>
    <td>
        <a href="index.php?delete_tag=<?php echo $tag['id']; ?>"
           onclick="return confirm('Delete this tag?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>