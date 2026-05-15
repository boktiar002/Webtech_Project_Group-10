<?php
$config = $config ?? json_decode(file_get_contents(__DIR__ . "/../../data.json"), true);
include __DIR__ . "/../Layouts/header.php";
?>

<style>
    .auth-page {
        max-width: 520px;
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
        margin: 0 0 8px;
    }

    .auth-card p {
        color: #64748b;
        margin: 0 0 22px;
    }

    .auth-card label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .auth-card input[type="email"],
    .auth-card input[type="password"] {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 16px;
    }

    .remember-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
    }

    .auth-btn {
        border: none;
        border-radius: 999px;
        padding: 12px 18px;
        font-weight: 700;
        cursor: pointer;
        background: #1d4ed8;
        color: #fff;
    }

    .auth-link {
        margin-top: 18px;
    }
</style>

<div class="auth-page">
    <div class="auth-card">
        <h2>Login Form</h2>
        <p>Enter your email and password to continue.</p>

        <form action="/Webtech_Project_Group-10/Controller/AuthController.php" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <div class="remember-row">
                <input type="checkbox" id="remember_me" name="remember_me">
                <label for="remember_me" style="margin:0; font-weight:500;">Remember Me</label>
            </div>

            <button class="auth-btn" type="submit" name="login">Login</button>
        </form>

        <p class="auth-link">
            Don't have an account?
            <a href="/Webtech_Project_Group-10/index.php?page=register">Register Here</a>
        </p>
    </div>
</div>

<?php include __DIR__ . "/../Layouts/footer.php"; ?>
