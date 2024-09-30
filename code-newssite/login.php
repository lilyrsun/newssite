<?php
        session_start();
        require 'database.php';

        /*referenced stack overflow:
        https://stackoverflow.com/questions/19205610/php-login-from-text-file 
        https://stackoverflow.com/questions/4103287/read-a-plain-text-file-with-php
        */
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Use a prepared statement
        $stmt = $mysqli->prepare("SELECT COUNT(*), id, username, hashed_pwd FROM users WHERE username=?");

        // Bind the parameter
        $stmt->bind_param('s', $username);
        $stmt->execute();

        // Bind the results
        $stmt->bind_result($cnt, $id, $username, $hashed_pwd);
        $stmt->fetch();

    if($cnt == 1){
        // Compare the submitted password to the actual password hash
        if(password_verify($password, $hashed_pwd)){
            // Login succeeded!
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            /** referenced stack overflow:
             * https://stackoverflow.com/questions/6287903/how-to-properly-add-cross-site-request-forgery-csrf-token-using-php
             */
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            // Redirect to your target page
            header("Location: index.php");
            exit;
        } else {
            // Password did not match
            header("Location: index.php?error=true");
            exit;
        }
    }

    else {
        // User does not exist (username not found)
        header("Location: index.php?error=true");
        exit;
    }
?>