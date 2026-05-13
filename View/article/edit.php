<?php

$conn = new mysqli("localhost", "root", "", "blog_news_project");

$id = $_GET['id'];


// 🔵 UPDATE
if($_POST){

    $title = $_POST['title'];
    $body  = $_POST['body'];

    $update = "UPDATE articles
               SET title='$title', body='$body'
               WHERE id=$id";

    $conn->query($update);

    echo "Updated successfully <br><br>";

    echo "<a href='dashboard.php'>Go To Dashboard</a>";
}


// 🔵 OLD DATA LOAD
$sql = "SELECT * FROM articles WHERE id=$id";

$result = $conn->query($sql);

$row = $result->fetch_assoc();

?>


<h2>Edit Article</h2>

<form method="POST">

    <label>Title</label><br>

    <input type="text"
           name="title"
           value="<?php echo $row['title']; ?>">

    <br><br>

    <label>Body</label><br>

    <textarea name="body"><?php echo $row['body']; ?></textarea>

    <br><br>

    <button type="submit">Update</button>

</form>