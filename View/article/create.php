<?php
$conn = new mysqli("localhost", "root", "", "blog_news_project");

if($_POST){

    // 🔵 STEP 1: data collect (আগে এটা হবে)
    $title = $_POST['title'];
    $body  = $_POST['body'];
    $tags  = $_POST['tags'];

    // 🔵 STEP 2: SQL query
    $sql = "INSERT INTO articles(title, body)
        VALUES('$title', '$body')";

    // 🔵 STEP 3: run query
    $conn->query($sql);

    echo "Article saved successfully";
}
?>

<h2>Create Article</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Title</label><br>
    <input type="text" name="title"><br><br>

    <label>Body</label><br>
    <textarea name="body"></textarea><br><br>

    <label>Image</label><br>
    <input type="file" name="image"><br><br>

    <label>Tags</label><br>
    <input type="text" name="tags"><br><br>

    <button type="submit">Save</button>

</form>