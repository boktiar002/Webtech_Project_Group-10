<?php

session_start();

include("../Config/database.php");


// ==========================
// REMEMBER ME LOGIN RESTORE
// ==========================

if(

    !isset($_SESSION['user_id'])

    &&

    isset($_COOKIE['remember_token'])

){

    $token = $_COOKIE['remember_token'];

    $query = "SELECT * FROM users";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($users as $user){

        if(

            !empty($user['remember_token'])

            &&

            password_verify(

                $token,

                $user['remember_token']

            )

        ){

            $_SESSION['user_id']
            =
            $user['id'];

            $_SESSION['name']
            =
            $user['name'];

            $_SESSION['role']
            =
            $user['role'];

            break;
        }
    }
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Home Page</title>

    <style>

    body{

        font-family: Arial;
        background: #f4f4f4;
        padding: 40px;

    }

    .container{

        background: white;
        width: 500px;
        margin: auto;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);

    }

    h1{

        color: #111827;

    }

    a{

        text-decoration: none;
        color: white;
        background: #111827;
        padding: 10px 20px;
        border-radius: 5px;
        display: inline-block;
        margin-top: 10px;

    }

    a:hover{

        background: #374151;

    }

    </style>

</head>

<body>

<div class="container">

<?php

if(isset($_SESSION['user_id'])){

?>

<h1>

Welcome <?php echo $_SESSION['name']; ?>

</h1>

<p>

You are successfully logged in.

</p>

<br>

<a href="http://localhost/Webtech_Project_Group-10-main/View/auth/profile.php">

Profile

</a>

<br><br>

<a href="http://localhost/Webtech_Project_Group-10-main/View/authors/public_profile.php?id=<?php echo $_SESSION['user_id']; ?>">

Public Author Profile

</a>

<br><br>

<a href="http://localhost/Webtech_Project_Group-10-main/View/auth/logout.php">

Logout

</a>

<?php

}else{

?>

<h1>

Not Logged In

</h1>

<p>

Please login to continue.

</p>

<br>

<a href="http://localhost/Webtech_Project_Group-10-main/View/auth/login.php">

Login

</a>

<br><br>

<a href="http://localhost/Webtech_Project_Group-10-main/View/auth/register.php">

Register

</a>

<?php

}

?>

</div>

</body>

</html>