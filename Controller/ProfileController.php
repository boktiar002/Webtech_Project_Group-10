<?php

session_start();

require_once __DIR__ . "/../Config/Database.php";

if (!isset($_SESSION['user_id'])) {
    die("Please Login First");
}

$connection = (new Database())->getConnection();

if (isset($_POST['update_profile'])) {
    $bio = trim($_POST['bio'] ?? '');
    $twitter = trim($_POST['twitter'] ?? '');
    $github = trim($_POST['github'] ?? '');
    $userId = (int) $_SESSION['user_id'];

    $socialLinks = json_encode([
        "twitter" => $twitter,
        "github" => $github
    ]);

    $profileImageQuery = "SELECT profile_pic_path FROM users WHERE id = ?";
    $profileStmt = $connection->prepare($profileImageQuery);
    $profileStmt->bind_param("i", $userId);
    $profileStmt->execute();
    $existingUser = $profileStmt->get_result()->fetch_assoc();

    $avatarPath = $existingUser['profile_pic_path'] ?? null;

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $fileName = time() . "_" . basename($_FILES['avatar']['name']);
        $tmpName = $_FILES['avatar']['tmp_name'];
        $uploadDir = __DIR__ . "/../public/uploads/avatars/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $uploadPath)) {
            $avatarPath = "public/uploads/avatars/" . $fileName;
        }
    }

    $query = "UPDATE users
        SET bio = ?, social_links = ?, profile_pic_path = ?
        WHERE id = ?";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("sssi", $bio, $socialLinks, $avatarPath, $userId);
    $result = $stmt->execute();

    if ($result) {
        echo "Profile Updated Successfully";
    } else {
        echo "Profile Update Failed";
    }
}

?>
