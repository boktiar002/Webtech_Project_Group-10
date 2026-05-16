<?php

session_start();

require_once __DIR__ . '/../../Config/Database.php';

$config = $config ?? json_decode(file_get_contents(__DIR__ . '/../../data.json'), true);

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

<table border="1" cellpadding="10">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Pending</th>
<th>Action</th>
</tr>

<?php foreach ($users as $user) { ?>
<tr>
<td><?php echo $user['id']; ?></td>
<td><?php echo htmlspecialchars($user['name']); ?></td>
<td><?php echo htmlspecialchars($user['email']); ?></td>
<td id="role<?php echo $user['id']; ?>">
<?php echo htmlspecialchars($user['role']); ?>
</td>
<td>
<?php echo $user['pending_author']; ?>
</td>
<td>
<?php if ($user['pending_author'] == 1 && $user['role'] == "reader") { ?>
<button onclick="promoteUser(<?php echo $user['id']; ?>)">
Promote To Author
</button>
<?php } ?>
</td>
</tr>
<?php } ?>

</table>

<script>
function promoteUser(userId){
fetch("/Webtech_Project_Group-10/Api/users/promote.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"user_id="+encodeURIComponent(userId)
})
.then(response=>response.json())
.then(data=>{
alert(data.message || "Done");
if(data.status==="success"){
location.reload();
}
})
.catch(error=>{
console.log(error);
alert("Error");
});
}
</script>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
