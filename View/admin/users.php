<?php

session_start();

include("../../Config/database.php");

if(!isset($_SESSION['user_id'])){

    die("Login First");

}

if($_SESSION['role'] != "admin"){

    die("Admin Only");

}


// ==========================
// FETCH USERS
// ==========================

$query = "SELECT * FROM users";

$stmt = $conn->prepare($query);

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html>

<head>

    <title>All Users</title>

    <style>

    body{

        font-family:Arial;
        background:#f4f4f4;
        padding:40px;

    }

    .container{

        background:white;
        padding:30px;
        border-radius:10px;
        box-shadow:0px 0px 10px rgba(0,0,0,0.1);

    }

    table{

        width:100%;
        border-collapse:collapse;

    }

    th,
    td{

        border:1px solid #ccc;
        padding:12px;
        text-align:center;

    }

    th{

        background:#111827;
        color:white;

    }

    button{

        background:#111827;
        color:white;
        border:none;
        padding:8px 15px;
        border-radius:5px;
        cursor:pointer;

    }

    button:hover{

        background:#374151;

    }

    </style>

</head>

<body>

<div class="container">

<h1>All Users</h1>

<table>

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

</tr>

<?php } ?>

</table>

</div>

<script>

function promoteUser(userId){

    fetch("../../Api/users/promote.php",{

        method:"POST",

        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },

        body:"user_id="+userId

    })

    .then(response=>response.json())

    .then(data=>{

        if(data.status == "success"){

            document.getElementById(

                "role"+userId

            ).innerHTML = "author";

            alert(data.message);

            location.reload();

        }else{

            alert(data.message);

        }

    })

    .catch(error=>{

        console.log(error);

        alert("Error");

    });

}

</script>

</body>

</html>