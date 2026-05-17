<?php

session_start();

if(!isset($_SESSION['user_id'])){

    die("Please Login First");

}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Profile</title>

    <style>

    body{

        font-family:Arial;
        background:#f4f4f4;
        padding:40px;

    }

    .profile-box{

        background:white;
        width:500px;
        margin:auto;
        padding:30px;
        border-radius:10px;
        box-shadow:0px 0px 10px rgba(0,0,0,0.1);

    }

    h2{

        text-align:center;

    }

    input,
    textarea{

        width:100%;
        padding:10px;
        margin-top:8px;
        margin-bottom:15px;
        border:1px solid #ccc;
        border-radius:5px;

    }

    button{

        background:#111827;
        color:white;
        border:none;
        padding:12px;
        width:100%;
        border-radius:5px;
        cursor:pointer;

    }

    button:hover{

        background:#374151;

    }

    p{

        color:#555;

    }

    </style>

</head>

<body>

<div class="profile-box">

<h2>User Profile Management</h2>

<p>

Update your bio, social links and profile picture

</p>

<form

action="/Webtech_Project_Group-10-main/Controller/ProfileController.php"

method="POST"

enctype="multipart/form-data"

>

<label>Bio</label><br>

<textarea

name="bio"

rows="5"

placeholder="Write something about yourself"

></textarea>

<label>Twitter URL</label><br>

<input

type="text"

name="twitter"

placeholder="https://twitter.com/username"

>

<label>GitHub URL</label><br>

<input

type="text"

name="github"

placeholder="https://github.com/username"

>

<label>Profile Picture</label><br>

<input

type="file"

name="avatar"

accept=".jpg,.jpeg,.png"

>

<p>

Accepted formats: JPG, JPEG, PNG

</p>

<button

type="submit"

name="update_profile"

>

Update Profile

</button>

</form>

</div>

</body>

</html>