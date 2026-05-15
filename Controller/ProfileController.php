<?php

session_start();

include("../Config/database.php");

if(!isset($_SESSION['user_id'])){

    die("Please Login First");

}

if(isset($_POST['update_profile'])){

    $bio = trim($_POST['bio']);

    $twitter = trim($_POST['twitter']);

    $github = trim($_POST['github']);

    $userId = $_SESSION['user_id'];

    // SOCIAL LINKS JSON

    $socialLinks = json_encode([

        "twitter" => $twitter,

        "github" => $github

    ]);

    $avatarPath = "";

    // IMAGE UPLOAD

    if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0){

        $fileName =
            time() . "_" .
            $_FILES['avatar']['name'];

        $tmpName =
            $_FILES['avatar']['tmp_name'];

        $uploadPath =
            "../Public/uploads/avatars/" .
            $fileName;

        move_uploaded_file(

            $tmpName,

            $uploadPath

        );

        $avatarPath =
            "Public/uploads/avatars/" .
            $fileName;

    }

    // UPDATE QUERY

    $query =
    "UPDATE users
    SET
    bio=?,
    social_links=?,
    profile_pic_path=?
    WHERE id=?";

    $stmt =
    $conn->prepare($query);

    $result =
    $stmt->execute([

        $bio,
        $socialLinks,
        $avatarPath,
        $userId

    ]);

    if($result){

        echo "Profile Updated Successfully";

    }else{

        echo "Profile Update Failed";

    }

}

?>