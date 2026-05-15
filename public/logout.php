<?php

session_start();

// Session remove

$_SESSION = [];

// Session destroy

session_destroy();

// Remove remember cookie

setcookie(

    "remember_token",
    "",
    time() - 3600,
    "/"

);

// Redirect

header(

    "Location:index.php"

);

exit();

?>