<?php

session_start();

require_once __DIR__ . '/../Config/Database.php';

$connection = (new Database())->getConnection();

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $query = "SELECT id, name, role, remember_token FROM users WHERE remember_token IS NOT NULL";
    $result = $connection->query($query);

    while ($user = $result->fetch_assoc()) {
        if (!empty($user['remember_token']) && password_verify($token, $user['remember_token'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            break;
        }
    }
}

if (isset($_SESSION['user_id'])) {
    echo "Welcome " . htmlspecialchars($_SESSION['name']);
    echo "<br><br>";
    echo "<a href='/Webtech_Project_Group-10/index.php?page=logout'>Logout</a>";
} else {
    echo "Not Logged In";
}

?>
