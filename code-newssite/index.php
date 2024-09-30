<?php
    session_start();
    require 'database.php';
?>

<!DOCTYPE html>
<html id = "home" class = "fadein" lang = "en">
<head>
	<title>News Site - Home</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div id = "homepage">
    <!--referenced for creating navigation bar: https://www.w3schools.com/Css/css_navbar.asp-->
    <?php if (isset($_SESSION['username'])): ?>
        <img src = "SunshineNewsLogo.png" class = "logo" alt = "logo">
        <br><br>
        <ul class = "navbar">
            <li class = "navbarlist"><a href="index.php" class = "navbarlink active">Home</a></li>
            <li class = "navbarlist"><a href="submitstory_form.php" class = "navbarlink">Submit a Story</a></li>

            <li class = "navbarlist" style="float:right"><a href="userprofile.php" class = "navbarlink"><?php echo $_SESSION['username']?>'s Profile</a></li>
            <li class = "navbarlist" style="float:right"><a href="logout.php" class = "navbarlink">Logout</a></li>
        </ul>
        <!--<a href="submitstory_form.php" class="submitbutton">Submit a Story</a>
        <a href="storiesportal.php" class="submitbutton">Edit or Delete Stories</a>-->

        <?php if(isset($_GET['submitted']) && $_GET['submitted'] == 'true') {
            echo "<p class = 'formlabels'>Thank you for contributing!</p>";
        } ?>

    <?php else: ?>
        <img src = "SunshineNewsLogo.png" class = "logo" alt = "logo">
        <br><br>
        <ul class = "navbar">
            <li class = "navbarlist"><a href="index.php" class = "navbarlink active">Home</a></li>
            <li class = "navbarlist" style="float:right"><a href="login_form.php" class = "navbarlink">Login</a></li>
        </ul>
    <?php endif; ?>

    <br><br>

    <div id = "viewfiles">
        <!--fetching news stories and listing them on the site-->
        <?php
            $stmt = $mysqli->prepare("select id, story_title, user_id from news");
            $stmt->execute();
            $stmt->bind_result($news_id, $news_title, $news_user_id);

            echo "<ul class='newslist'>";
                while($stmt->fetch()) {
                    echo "<li class = 'filelist'>";
                    echo "<a href='newsviewer.php?news_id=$news_id' target='_blank' class = 'links'>" . htmlspecialchars($news_title) . "</a>";
                    echo "</li>";
                }
            echo "</ul>";
            $stmt->close();
        ?>
    </div>

    </div>

</body>
</html>