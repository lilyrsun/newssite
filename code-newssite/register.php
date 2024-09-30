<?php
    //referenced for registration site: https://www.webslesson.info/2016/11/php-login-registration-script-by-using-password-hash-method.html
    require 'database.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    //referenced from 330 Wiki, PHP & SQL
    $stmt = $mysqli->prepare("insert into users (username, hashed_pwd) values (?,?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('ss', $username, $hashed_password);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?registered=true");

    exit;
?>