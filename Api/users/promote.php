<?php

session_start();

include("../../config/database.php");

header("Content-Type: application/json");

if(!isset($_SESSION['user_id'])){

    echo json_encode([
        "status"=>"error",
        "message"=>"Login Required"
    ]);
    exit();
}

if($_SESSION['role']!="admin"){

    echo json_encode([
        "status"=>"error",
        "message"=>"Admin Only"
    ]);
    exit();
}

if(!isset($_POST['user_id'])){

    echo json_encode([
        "status"=>"error",
        "message"=>"User ID Missing"
    ]);
    exit();
}

$userId=$_POST['user_id'];

$query="UPDATE users
SET role='author',
pending_author=0
WHERE id=?";

$stmt=$conn->prepare($query);

if($stmt->execute([$userId])){

    echo json_encode([
        "status"=>"success",
        "message"=>"User Promoted"
    ]);

}else{

    echo json_encode([
        "status"=>"error",
        "message"=>"Promotion Failed"
    ]);
}
?>