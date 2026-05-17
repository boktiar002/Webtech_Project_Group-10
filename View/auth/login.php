<!DOCTYPE html>

<html>

<head>

    <title>Login</title>

    <style>

    body{
        font-family:Arial;
        background:#f4f4f4;
        padding:40px;
    }

    .login-box{
        background:white;
        width:350px;
        margin:auto;
        padding:30px;
        border-radius:10px;
        box-shadow:0px 0px 10px rgba(0,0,0,0.1);
    }

    h2{
        text-align:center;
    }

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

    a{
        text-decoration:none;
        color:blue;
    }

    </style>

</head>

<body>

<div class="login-box">

<h2>Login Form</h2>

<p>

Enter your email and password to continue

</p>

<form

action="/Webtech_Project_Group-10-main/Controller/AuthController.php"

method="POST"

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

        required

    >

    <input

        type="checkbox"

        name="remember_me"

    >

    <label>Remember Me</label>

    <br><br>

    <button

        type="submit"

        name="login"

    >

        Login

    </button>

</form>

<br>

<p>

Don't have an account?

<a href="register.php">

Register Here

</a>

</p>

</div>

</body>

</html>