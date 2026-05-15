<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Please Login First");
}

$config = $config ?? json_decode(file_get_contents(__DIR__ . "/../../data.json"), true);
include __DIR__ . "/../Layouts/header.php";
?>

<style>
    .profile-page {
        max-width: 720px;
        margin: 36px auto 48px;
        padding: 0 18px;
    }

    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.07);
        padding: 28px;
    }

    .profile-card h2 {
        margin: 0 0 8px;
    }

    .profile-card p {
        margin: 0 0 22px;
        color: #64748b;
    }

    .profile-card label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .profile-card textarea,
    .profile-card input[type="text"],
    .profile-card input[type="file"] {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 16px;
        font: inherit;
    }

    .profile-card textarea {
        min-height: 140px;
        resize: vertical;
    }

    .profile-btn {
        border: none;
        border-radius: 999px;
        padding: 12px 18px;
        font-weight: 700;
        cursor: pointer;
        background: #0f766e;
        color: #fff;
    }
</style>

<div class="profile-page">
    <div class="profile-card">
        <h2>User Profile Management</h2>
        <p>Update your bio, social links and profile picture.</p>

        <form action="/Webtech_Project_Group-10/Controller/ProfileController.php" method="POST" enctype="multipart/form-data">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio"></textarea>

            <label for="twitter">Twitter URL</label>
            <input type="text" id="twitter" name="twitter" placeholder="https://twitter.com/username">

            <label for="github">GitHub URL</label>
            <input type="text" id="github" name="github" placeholder="https://github.com/username">

            <label for="avatar">Profile Picture</label>
            <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png">
            <p>Accepted formats: JPG, JPEG, PNG</p>

            <button class="profile-btn" type="submit" name="update_profile">Update Profile</button>
        </form>
    </div>
</div>

<?php include __DIR__ . "/../Layouts/footer.php"; ?>
