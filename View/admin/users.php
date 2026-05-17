<?php

session_start();

include("../../Config/database.php");

if(!isset($_SESSION['user_id'])){

    die("Login First");

}

if($_SESSION['role']!="admin"){

    die("Admin Only");

}

<<<<<<< HEAD
$query = "SELECT * FROM users";

$stmt = $conn->prepare($query);

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
=======
$query="SELECT * FROM users";

$stmt=$conn->prepare($query);

$stmt->execute();

$users=$stmt->fetchAll(PDO::FETCH_ASSOC);
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

?>

<!DOCTYPE html>
<<<<<<< HEAD

=======
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
<html>

<head>

<<<<<<< HEAD
    <title>All Users</title>
=======
<title>All Users</title>
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

</head>

<body>

<h1>All Users</h1>

<table border="1" cellpadding="10">

<tr>

<<<<<<< HEAD
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Pending</th>
    <th>Action</th>
=======
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Pending</th>
<th>Action</th>
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

</tr>

<?php foreach($users as $user){ ?>

<tr>

<<<<<<< HEAD
    <td>

        <?php echo $user['id']; ?>

    </td>

    <td>

        <?php echo $user['name']; ?>

    </td>

    <td>

        <?php echo $user['email']; ?>

    </td>

    <td id="role<?php echo $user['id']; ?>">

        <?php echo $user['role']; ?>

    </td>

    <td>

        <?php echo $user['pending_author']; ?>

    </td>

    <td>

    <?php

    if(

        $user['pending_author'] == 1

        &&

        $user['role'] == "reader"

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
=======
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
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

</tr>

<?php } ?>

</table>

<script>

function promoteUser(userId){

<<<<<<< HEAD
    fetch("../../Api/users/promote.php",{

        method:"POST",

        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },

        body:"user_id="+userId

    })

    .then(response=>response.json())

    .then(data=>{

        if(data.success){

            document.getElementById(

                "role"+userId

            ).innerHTML = "author";

            alert(data.message);

        }else{

            alert(data.message);

        }

    })

    .catch(error=>{

        console.log(error);

        alert("Error");

    });
=======
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
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

}

</script>

</body>
<<<<<<< HEAD

=======
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
</html>