<?php

session_start();

include("../../config/database.php");

if(!isset($_SESSION['user_id'])){

    die("Login First");

}

if($_SESSION['role']!="admin"){

    die("Admin Only");

}

$query="SELECT * FROM users";

$stmt=$conn->prepare($query);

$stmt->execute();

$users=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>

<title>All Users</title>

</head>

<body>

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

<?php foreach($users as $user){ ?>

<tr>

<td><?php echo $user['id']; ?></td>

<td><?php echo $user['name']; ?></td>

<td><?php echo $user['email']; ?></td>

<td id="role<?php echo $user['id']; ?>">
<?php echo $user['role']; ?>
</td>

<td>
<?php echo $user['pending_author']; ?>
</td>

<td>

<?php

if(

$user['pending_author']==1

&&

$user['role']=="reader"

){

?>

<button

onclick="promoteUser(

<?php echo $user['id']; ?>

)"

>

Promote To Author

</button>

<?php } ?>

</td>

</tr>

<?php } ?>

</table>

<script>

function promoteUser(userId){

fetch("../../api/users/promote.php",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:"user_id="+userId

})

.then(response=>response.text())
.then(data=>{

console.log(data);

alert("Done");

location.reload();

})

.catch(error=>{

console.log(error);

alert("Error");

});

}

</script>

</body>
</html>