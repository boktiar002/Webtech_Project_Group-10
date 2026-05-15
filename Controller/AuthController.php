<?php

session_start();

include("../Config/database.php");


// ==========================
// REGISTRATION
// ==========================

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $selectedRole = $_POST['role'];

    // Validation

    if(
        empty($name) ||
        empty($email) ||
        empty($password)
    ){

        die("All fields are required");

    }

    if(strlen($password) < 8){

        die("Password must be at least 8 characters");

    }

    // Email Check

    $checkQuery =

    "SELECT id
    FROM users
    WHERE email=?";

    $stmt =
    $conn->prepare(
        $checkQuery
    );

    $stmt->execute([

        $email

    ]);

    if($stmt->rowCount()>0){

        die("Email already exists");

    }

    // Role Logic

    if($selectedRole=="author"){

        $role="reader";
        $pending=1;

    }else{

        $role="reader";
        $pending=0;

    }

    // Password Hash

    $hashedPassword =

    password_hash(

        $password,

        PASSWORD_DEFAULT

    );

    // Insert User

    $insertQuery =

    "INSERT INTO users
    (
    name,
    email,
    password_hash,
    role,
    pending_author
    )

    VALUES(?,?,?,?,?)";

    $stmt =
    $conn->prepare(
        $insertQuery
    );

    $result =
    $stmt->execute([

        $name,
        $email,
        $hashedPassword,
        $role,
        $pending

    ]);

    if($result){

        echo "Registration Successful";

    }else{

        echo "Registration Failed";

    }

}



// ==========================
// LOGIN
// ==========================

if(isset($_POST['login'])){

    $email =
    trim(
        $_POST['email']
    );

    $password =
    $_POST['password'];

    if(

        empty($email)

        ||

        empty($password)

    ){

        die(
            "All fields required"
        );

    }

    // Find User

    $query =

    "SELECT *
    FROM users
    WHERE email=?";

    $stmt =
    $conn->prepare(
        $query
    );

    $stmt->execute([

        $email

    ]);

    $user =
    $stmt->fetch(
        PDO::FETCH_ASSOC
    );

    if($user){

        if(

            password_verify(

                $password,

                $user['password_hash']

            )

        ){

            // SESSION

            $_SESSION['user_id']
            =
            $user['id'];

            $_SESSION['name']
            =
            $user['name'];

            $_SESSION['role']
            =
            $user['role'];



            // REMEMBER ME

            if(

                isset(
                    $_POST['remember_me']
                )

            ){

                $token =
                bin2hex(
                    random_bytes(32)
                );

                $hashedToken =
                password_hash(

                    $token,

                    PASSWORD_DEFAULT

                );

                $updateQuery =

                "UPDATE users
                SET
                remember_token=?
                WHERE id=?";

                $updateStmt =
                $conn->prepare(

                    $updateQuery

                );

                $updateStmt->execute([

                    $hashedToken,

                    $user['id']

                ]);

                setcookie(

                    "remember_token",

                    $token,

                    time() + (86400 * 30),

                    "/",

                    "",

                    false,

                    true

                );

            }

            header(

                "Location:../Public/index.php"

            );

            exit();

        }else{

            echo "Wrong Password";

        }

    }else{

        echo "User Not Found";

    }

}

?>