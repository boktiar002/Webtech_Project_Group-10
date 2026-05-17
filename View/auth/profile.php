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

</head>

<body>

<h2>User Profile Management</h2>

<p>

Update your bio, social links and profile picture

</p>

<form

action="../../Controller/ProfileController.php"

method="POST"

enctype="multipart/form-data"

>

<label>Bio</label><br>

<textarea
name="bio"
rows="5"
cols="40"
></textarea>

<br><br>

<label>Twitter URL</label><br>

<input
type="text"
name="twitter"
placeholder="https://twitter.com/username"
>

<br><br>

<label>GitHub URL</label><br>

<input
type="text"
name="github"
placeholder="https://github.com/username"
>

<br><br>

<label>Profile Picture</label><br>

<input
type="file"
name="avatar"
accept=".jpg,.jpeg,.png"
>

<p>

Accepted formats: JPG, JPEG, PNG

</p>

<br><br>

<button
type="submit"
name="update_profile"
>

Update Profile

</button>

</form>

</body>

</html>