<?php

session_start();

include("../config/database.php");


// REMEMBER ME LOGIN RESTORE

if(

    !isset($_SESSION['user_id'])

    &&

    isset($_COOKIE['remember_token'])

){

    $token =
        $_COOKIE['remember_token'];

    $query =
        "SELECT * FROM users";

    $stmt =
        $conn->prepare($query);

    $stmt->execute();

    $users =
        $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach(

        $users as $user

    ){

        if(

            !empty(
                $user['remember_token']
            )

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


// HOME PAGE

if(

    isset(
        $_SESSION['user_id']
    )

){

    echo
    "Welcome ";

    echo
    $_SESSION['name'];

    echo
    "<br><br>";

    echo
    "<a href='logout.php'>
    Logout
    </a>";

}else{

    echo
    "Not Logged In";

}

?>