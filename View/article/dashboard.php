<?php

$conn = new mysqli("localhost", "root", "", "blog_news_project");

$sql = "SELECT * FROM articles";

$result = $conn->query($sql);

?>

<h2>Article Dashboard</h2>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Body</th>
    <th>Action</th>
</tr>

<?php

while($row = $result->fetch_assoc()){

?>

<tr>

    <td><?php echo $row['id']; ?></td>

    <td><?php echo $row['title']; ?></td>

    <td><?php echo $row['body']; ?></td>
    <td>
    <a href="edit.php?id=<?php echo $row['id']; ?>">
        Edit
    </a>
    <br><br>

    <a href="delete.php?id=<?php echo $row['id']; ?>">
    Delete
    </a>
    </td>
</tr>

<?php
}
?>

</table>