<?php
    session_start();

    //unset the session variables and get rid of the any sensitive data
    $_SESSION = array();

    //get rid of the session (bc logging out)
    session_destroy();
    header("Location: index.php");
    exit;
?>