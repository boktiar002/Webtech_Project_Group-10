<?php
require_once "../../Controller/ArticleController.php";

$controller = new ArticleController();

if(isset($_GET['delete'])){
    $controller->delete($_GET['delete']);
}

$result = $controller->index();
?>

<h2>Article Dashboard</h2>

<a href="create.php">+ New Article</a>
&nbsp;|&nbsp;
<a href="../category/index.php">Manage Categories & Tags</a>

<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Status</th>
    <th>Views</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr id="row-<?php echo $row['id']; ?>">
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['title']; ?></td>
    <td>
        <span id="badge-<?php echo $row['id']; ?>" style="
            padding:3px 8px;
            background: <?php echo $row['status']=='published' ? 'green' : 'gray'; ?>;
            color:white;
            border-radius:4px;">
            <?php echo $row['status']; ?>
        </span>
    </td>
    <td><?php echo $row['view_count']; ?></td>
    <td>
        <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
        &nbsp;|&nbsp;
        <a href="dashboard.php?delete=<?php echo $row['id']; ?>"
           onclick="return confirm('Delete করবে?')">Delete</a>
        &nbsp;|&nbsp;
        <button onclick="toggleStatus(<?php echo $row['id']; ?>, this)"
                data-status="<?php echo $row['status']; ?>">
            <?php echo $row['status']=='published' ? 'Unpublish' : 'Publish'; ?>
        </button>
    </td>
</tr>
<?php endwhile; ?>
</table>

<script>
function toggleStatus(id, btn){
    fetch('../../api/toggle_article.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: id})
    })
    .then(r => r.json())
    .then(data => {
        const badge = document.getElementById('badge-' + id);
        badge.textContent = data.status;
        badge.style.background = data.status === 'published' ? 'green' : 'gray';
        btn.textContent = data.status === 'published' ? 'Unpublish' : 'Publish';
        btn.dataset.status = data.status;
    });
}
</script>