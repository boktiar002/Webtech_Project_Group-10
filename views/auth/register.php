<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Registration Form</h2>

<form action="../../controllers/AuthController.php" method="POST">

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
    Author

    <br><br>

    <button type="submit" name="register">
        Register
    </button>

</form>

</body>
</html>