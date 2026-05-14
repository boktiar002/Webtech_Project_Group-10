<?php

session_start();

if(!isset($_SESSION['user_id'])){

    die("Please Login First");

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Profile Page</title>

</head>

<body>

<h2>Update Profile</h2>

<form

action="../../controllers/ProfileController.php"

method="POST"

enctype="multipart/form-data"

>

<label>Bio</label>

<br>

<textarea

name="bio"

rows="5"

cols="40"

placeholder="Write your bio"

></textarea>

<br><br>

<label>Twitter URL</label>

<br>

<input

type="text"

name="twitter"

placeholder="https://twitter.com/username"

>

<br><br>

<label>GitHub URL</label>

<br>

<input

type="text"

name="github"

placeholder="https://github.com/username"

>

<br><br>

<label>Profile Picture</label>

<br>

<input

type="file"

name="profile_pic"

accept="image/*"

>

<br><br>

<button

type="submit"

name="saveProfile"

>

Update Profile

</button>

</form>

<br><br>

<a href="../../public/index.php">

Go Home

</a>

<br><br>

<a href="../../public/logout.php">

Logout

</a>

</body>

</html>