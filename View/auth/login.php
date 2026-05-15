<!DOCTYPE html>

<!-- Authentication Login Page -->

<html>

<head>

    <title>Login</title>

</head>

<body>

<h2>Login Form</h2>

<p>

Enter your email and password to continue

</p>

<form

action="../../Controller/AuthController.php"

method="POST"

>

    <label>Email</label><br>

    <input

        type="email"

        name="email"

        required

    >

    <br><br>

    <label>Password</label><br>

    <input

        type="password"

        name="password"

        required

    >

    <br><br>

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

</body>

</html>