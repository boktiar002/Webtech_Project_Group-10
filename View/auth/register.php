<!DOCTYPE html>
<<<<<<< HEAD

<html>

<head>

    <title>Register</title>

    <style>

    body{
        font-family:Arial;
        background:#f4f4f4;
        padding:40px;
    }

    .register-box{

        background:white;
        width:400px;
        margin:auto;
        padding:30px;
        border-radius:10px;
        box-shadow:0px 0px 10px rgba(0,0,0,0.1);

    }

    h2{
        text-align:center;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"]{

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
        padding:10px 20px;
        width:100%;
        border-radius:5px;
        cursor:pointer;

    }

    button:hover{

        background:#374151;

    }

    </style>

</head>

<body>

<div class="register-box">

<h2>Registration Form</h2>

<form

action="/Webtech_Project_Group-10-main/Controller/AuthController.php"

method="POST"

>

    <label>Name</label><br>

    <input

    type="text"

    name="name"

    required

    >

    <label>Email</label><br>

    <input

    type="email"

    name="email"

    required

    >

    <label>Password</label><br>

    <input

    type="password"

    name="password"

    minlength="8"

    required

    >

    <label>Select Role</label><br><br>

    <input

    type="radio"

    name="role"

    value="reader"

    checked

    >

    Reader

    <input

    type="radio"

    name="role"

    value="author"

    >

=======
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Registration Form</h2>

<form action="../../Controller/AuthController.php" method="POST">

    <label>Name</label><br>
    <input type="text" name="name"><br><br>

    <label>Email</label><br>
    <input type="email" name="email"><br><br>

    <label>Password</label><br>
    <input
type="password"
name="password"
minlength="8"
required
><br><br>

    <label>Select Role</label><br>

    <input type="radio" name="role" value="reader" checked>
    Reader

    <input type="radio" name="role" value="author">
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
    Author

    <br><br>

<<<<<<< HEAD
    <button

    type="submit"

    name="register"

    >

    Register

=======
    <button type="submit" name="register">
        Register
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
    </button>

</form>

<<<<<<< HEAD
<br>

<p>

Already have an account?

<a href="login.php">

Login Here

</a>

</p>

</div>

</body>

=======
</body>
>>>>>>> 247e429fe6d54bd0cc0d546c5726ee9b632ac2e2
</html>