<?php

<<<<<<< HEAD
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

include("../Config/Database.php");
=======
session_start();

include("../Config/database.php");
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2


// ==========================
// REGISTRATION
// ==========================

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
<<<<<<< HEAD

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
=======
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
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

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

<<<<<<< HEAD
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
=======
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
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

    $hashedPassword =

    password_hash(

        $password,

        PASSWORD_DEFAULT

    );

<<<<<<< HEAD

    // ==========================
    // INSERT USER
    // ==========================
=======
    // Insert User
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

    $insertQuery =

    "INSERT INTO users
    (
<<<<<<< HEAD
        name,
        email,
        password_hash,
        role,
        pending_author
=======
    name,
    email,
    password_hash,
    role,
    pending_author
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
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

<<<<<<< HEAD

=======
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
    if($result){

        echo "Registration Successful";

    }else{

        echo "Registration Failed";

    }

<<<<<<< HEAD
    exit();

=======
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
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

<<<<<<< HEAD

    // ==========================
    // VALIDATION
    // ==========================

=======
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
    if(

        empty($email)

        ||

        empty($password)

    ){

<<<<<<< HEAD
        echo "All fields required";

        exit();

    }


    // ==========================
    // FIND USER
    // ==========================
=======
        die(
            "All fields required"
        );

    }

    // Find User
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

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

<<<<<<< HEAD

=======
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
    if($user){

        if(

            password_verify(

                $password,

                $user['password_hash']

            )

        ){

<<<<<<< HEAD
            // ==========================
            // SESSION
            // ==========================
=======
            // SESSION
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

            $_SESSION['user_id']
            =
            $user['id'];

            $_SESSION['name']
            =
            $user['name'];

            $_SESSION['role']
            =
            $user['role'];


<<<<<<< HEAD
            // ==========================
            // REMEMBER ME
            // ==========================
=======

            // REMEMBER ME
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2

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
<<<<<<< HEAD
                SET remember_token=?
=======
                SET
                remember_token=?
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
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

<<<<<<< HEAD

            // ==========================
            // REDIRECT
            // ==========================

=======
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
            header(

                "Location:../Public/index.php"

            );

            exit();

        }else{

            echo "Wrong Password";

<<<<<<< HEAD
            exit();

=======
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
        }

    }else{

        echo "User Not Found";

<<<<<<< HEAD
        exit();

=======
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
    }

}

?>