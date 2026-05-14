<?php

$conn = new mysqli("localhost", "root", "", "blog_news_project");

$id = $_GET['id'];

$sql = "DELETE FROM articles WHERE id=$id";

$conn->query($sql);

?>

<h2>Deleted successfully</h2>

<a href="dashboard.php">
    Go To Dashboard
</a>