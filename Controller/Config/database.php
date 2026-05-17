<?php

$host = "localhost";
$dbname = "blog_news_project";
$username = "root";
$password = "";

try{

    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname",
        $username,
        $password
    );

    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

}catch(PDOException $e){

    die("Database Connection Failed");

}

?>