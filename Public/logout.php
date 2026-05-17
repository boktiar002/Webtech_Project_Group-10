<?php

session_start();

session_unset();

session_destroy();

setcookie(
    "remember_token",
    "",
    time() - 3600,
    "/"
);

header("Location: ../View/auth/login.php");

exit();

?>