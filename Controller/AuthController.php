<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

include("../Config/Database.php");


// ==========================
// REGISTRATION
// ==========================

if(isset($_POST['register'])){

    $name = trim($_POST['name']);

    $email = trim($_POST['email']);

    $password = $_POST['password'];

    $selectedRole = $_POST['role'] ?? 'reader';


    // ==========================
    // VALIDATION
    // ==========================

    if(

        empty($name) ||

        empty($email) ||

        empty($password)

    ){

        echo "All fields are required";

        exit();

    }

    if(

        !filter_var(

            $email,

            FILTER_VALIDATE_EMAIL

        )

    ){

        echo "Invalid email format";

        exit();

    }

    if(

        strlen($password) < 8

    ){

        echo "Password must be at least 8 characters";

        exit();

    }


    // ==========================
    // CHECK EMAIL
    // ==========================

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

    if($stmt->rowCount() > 0){

        echo "Email already exists";

        exit();

    }


    // ==========================
    // ROLE LOGIC
    // ==========================

    if($selectedRole == "author"){

        $role = "reader";

        $pending = 1;

    }else{

        $role = "reader";

        $pending = 0;

    }


    // ==========================
    // PASSWORD HASH
    // ==========================

    $hashedPassword =

    password_hash(

        $password,

        PASSWORD_DEFAULT

    );


    // ==========================
    // INSERT USER
    // ==========================

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

    exit();

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


    // ==========================
    // VALIDATION
    // ==========================

    if(

        empty($email)

        ||

        empty($password)

    ){

        echo "All fields required";

        exit();

    }


    // ==========================
    // FIND USER
    // ==========================

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

            // ==========================
            // SESSION
            // ==========================

            $_SESSION['user_id']
            =
            $user['id'];

            $_SESSION['name']
            =
            $user['name'];

            $_SESSION['role']
            =
            $user['role'];


            // ==========================
            // REMEMBER ME
            // ==========================

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
                SET remember_token=?
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


            // ==========================
            // REDIRECT
            // ==========================

            header(

                "Location:../Public/index.php"

            );

            exit();

        }else{

            echo "Wrong Password";

            exit();

        }

    }else{

        echo "User Not Found";

        exit();

    }

}

?>