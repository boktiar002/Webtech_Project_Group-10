<?php
$config = $config ?? json_decode(file_get_contents(__DIR__ . "/../../data.json"), true);
include __DIR__ . "/../Layouts/header.php";
?>

<style>
    .auth-page {
        max-width: 560px;
        margin: 36px auto 48px;
        padding: 0 18px;
    }

    .auth-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.07);
        padding: 28px;
    }

    .auth-card h2 {
        margin: 0 0 18px;
    }

    .auth-card label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .auth-card input[type="text"],
    .auth-card input[type="email"],
    .auth-card input[type="password"] {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 16px;
    }

    .role-row {
        display: flex;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .role-row label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        margin: 0;
    }

    .auth-btn {
        border: none;
        border-radius: 999px;
        padding: 12px 18px;
        font-weight: 700;
        cursor: pointer;
        background: #0f766e;
        color: #fff;
    }
</style>

<div class="auth-page">
    <div class="auth-card">
        <h2>Registration Form</h2>

        <form action="/Webtech_Project_Group-10/Controller/AuthController.php" method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name">

            <label for="email">Email</label>
            <input type="email" id="email" name="email">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" minlength="8" required>

            <label>Select Role</label>
            <div class="role-row">
                <label><input type="radio" name="role" value="reader" checked> Reader</label>
                <label><input type="radio" name="role" value="author"> Author</label>
            </div>

            <button class="auth-btn" type="submit" name="register">Register</button>
        </form>
    </div>
</div>

<?php include __DIR__ . "/../Layouts/footer.php"; ?>
