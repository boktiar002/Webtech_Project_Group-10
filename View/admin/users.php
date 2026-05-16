<?php
session_start();
require_once __DIR__ . '/../../Config/Database.php';

if (!isset($_SESSION['user_id'])) {
    die("Login First");
}

if ($_SESSION['role'] != "admin") {
    die("Admin Only");
}

$connection = (new Database())->getConnection();
$result = $connection->query("SELECT * FROM users ORDER BY id ASC");
$users = [];

while ($user = $result->fetch_assoc()) {
    $users[] = $user;
}

include __DIR__ . '/../Layouts/header.php';
?>

<h1>All Users</h1>

<table border="1" cellpadding="10" style="width:100%; border-collapse: collapse; margin-top:20px;">
<tr style="background-color: #f2f2f2;">
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Pending</th>
<th>Action</th>
</tr>

<?php foreach ($users as $user) { ?>
<tr id="user-row-<?php echo $user['id']; ?>">
<td><?php echo $user['id']; ?></td>
<td><?php echo htmlspecialchars($user['name']); ?></td>
<td><?php echo htmlspecialchars($user['email']); ?></td>
<td id="role-<?php echo $user['id']; ?>">
<?php echo htmlspecialchars($user['role']); ?>
</td>
<td id="pending-<?php echo $user['id']; ?>">
<?php echo $user['pending_author']; ?>
</td>
<td id="action-<?php echo $user['id']; ?>">
<?php if ($user['pending_author'] == 1 && $user['role'] == "reader") { ?>
<button onclick="promoteUser(<?php echo $user['id']; ?>)" style="background-color: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">
Promote To Author
</button>
<?php } else { ?>
<span>No Action Required</span>
<?php } ?>
</td>
</tr>
<?php } ?>

</table>

<script>
function promoteUser(userId){
    if(!confirm("Are you sure you want to promote this user?")) return;

    fetch("/Webtech_Project_Group-10/Api/users/promote.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "user_id=" + encodeURIComponent(userId)
    })
    .then(response => {
        return response.json(); // Standard secure JSON parsing
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            
            // Instantly updating fields without needing page reload
            document.getElementById('role-' + userId).textContent = 'author';
            document.getElementById('pending-' + userId).textContent = '0';
            document.getElementById('action-' + userId).innerHTML = '<span style="color: green; font-weight: bold;">Promoted</span>';
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error parsing response:", error);
        alert("Something went wrong with the connection or data format.");
    });
}
</script>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>