<?php

session_start();

include("../config/database.php");

if(isset($_POST['saveProfile'])){

    $bio = trim($_POST['bio']);
    $twitter = trim($_POST['twitter']);
    $github = trim($_POST['github']);

    $userId = $_SESSION['user_id'];

    $socialLinks = json_encode([

        "twitter"=>$twitter,
        "github"=>$github

    ]);

    $avatarPath = null;

    if(
        isset($_FILES['profile_pic'])
        &&
        $_FILES['profile_pic']['error']==0
    ){

        $file=$_FILES['profile_pic'];

        $allowed=[

            "image/jpeg",
            "image/png"

        ];

        if(in_array(
            $file['type'],
            $allowed
        )){

            if($file['size']<=1000000){

                $ext=pathinfo(
                    $file['name'],
                    PATHINFO_EXTENSION
                );

                $fileName=
                time().".".$ext;

                $folder=
                "../public/uploads/avatars/";

                if(!is_dir($folder)){

                    mkdir(
                        $folder,
                        0777,
                        true
                    );

                }

                move_uploaded_file(

                    $file['tmp_name'],
                    $folder.$fileName

                );

                $avatarPath=

                "public/uploads/avatars/"
                .$fileName;

            }

        }

    }

    $query="

    UPDATE users

    SET

    bio=?,
    social_links=?,
    profile_pic_path=?

    WHERE id=?

    ";

    $stmt=
    $conn->prepare($query);

    $stmt->execute([

        $bio,
        $socialLinks,
        $avatarPath,
        $userId

    ]);

    echo "Profile Updated";

}
?>