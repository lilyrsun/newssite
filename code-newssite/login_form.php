<?php
        session_start();
        require 'database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>News Site - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="fadein" id="loginpage">
        <h1 class="pagetitle">Welcome to <strong>Sunshine News!</strong></h1>
        <div id="loginfields">
            <form method="POST" action="login.php">
                <label class="formlabels2"><strong>Username: </strong><br><input type="text" name="username" class="fillin" required></label><br><br>
                <label class="formlabels2"><strong>Password: </strong><br><input type="password" name="password" class="fillin" required></label><br><br>
                <input type="submit" value="Login" class="submitbutton">
                <p class = "formlabels2">Don't have an account? <a href="register.html" class = "links">Register here</a>.</p>
            </form>
        </div>

        <!--referenced for error message: https://www.codeproject.com/Questions/725678/whats-query-string-in-php-->
        <?php
        if (isset($_GET['error'])) {
            echo "<p class='errormessage'>You have entered the wrong username or password. Please try again.</p>";
        }
        ?>
    </div>
</body>
</html>