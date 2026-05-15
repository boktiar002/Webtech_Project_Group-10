<?php

session_start();

include("../config/database.php");

if(isset($_POST['update_profile'])){

    $bio = trim($_POST['bio']);
    $twitter = trim($_POST['twitter']);
    $github = trim($_POST['github']);

    $userId = $_SESSION['user_id'];

    $socialLinks = json_encode([

        "twitter"=>$twitter,
        "github"=>$github

    ]);

    $avatarPath = null;

    // IMAGE UPLOAD

    if(
        isset($_FILES['avatar'])
        &&
        $_FILES['avatar']['error']==0
    ){

        $file = $_FILES['avatar'];

        $allowed = [

            "image/jpeg",
            "image/png"

        ];

        // FILE TYPE CHECK

        if(
            in_array(
                $file['type'],
                $allowed
            )
        ){

            // FILE SIZE CHECK

            if($file['size'] <= 1000000){

                $ext = pathinfo(

                    $file['name'],

                    PATHINFO_EXTENSION

                );

                $fileName =

                time().".".$ext;

                $folder =

                "../public/uploads/avatars/";

                // CREATE FOLDER

                if(!is_dir($folder)){

                    mkdir(

                        $folder,
                        0777,
                        true

                    );

                }

                // MOVE FILE

                move_uploaded_file(

                    $file['tmp_name'],

                    $folder.$fileName

                );

                $avatarPath =

                "public/uploads/avatars/"
                .$fileName;

            }

        }

    }

    // UPDATE PROFILE

    $query = "

    UPDATE users

    SET

    bio=?,
    social_links=?,
    profile_pic_path=?

    WHERE id=?

    ";

    $stmt =

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