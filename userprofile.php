<?php
    session_start();
    require 'database.php';

    $user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html id = "home" class = "fadein" lang = "en">
<head>
	<title>News Site - Home</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div id = "profile">
        <img src = "SunshineNewsLogo.png" class = "logo" alt = "logo">
        <br><br>
        <ul class = "navbar">
            <li class = "navbarlist"><a href="index.php" class = "navbarlink">Home</a></li>
            <li class = "navbarlist"><a href="submitstory_form.php" class = "navbarlink">Submit a Story</a></li>

            <li class = "navbarlist" style="float:right"><a href="userprofile.php" class = "navbarlink active"><?php echo $_SESSION['username']?>'s Profile</a></li>
            <li class = "navbarlist" style="float:right"><a href="logout.php" class = "navbarlink">Logout</a></li>
        </ul>

        <?php if(isset($_GET['submitted']) && $_GET['submitted'] == 'true') {
            echo "Thank you for contributing!";
        } ?>

        <br><br>

        <div id = "container">
            <div id = "comments">
                <div id = "yourstories">

                    <h3>Your Stories</h3>

                    <!--fetching news stories and listing them on the site-->
                    <?php
                        $stmt = $mysqli->prepare("select id, story_title from news where user_id = ?");
                        $stmt->bind_param('i', $user_id);
                        $stmt->execute();
                        $stmt->bind_result($news_id, $news_title);

                        echo "<ul class='newslist'>";
                            while($stmt->fetch()) {
                                echo "<li class = 'filelist'><a href='newsviewer.php?news_id=$news_id' target='_blank' class = 'links'>" . htmlspecialchars($news_title) . "</a></li>";
                            }
                        echo "</ul>";
                        $stmt->close();
                    ?>

                    <br>

                    <a href="storiesportal.php" class="submitbutton">Edit or Delete Stories</a>

                    <br>
                </div>

                <div id = "notifications">
                
                    <!--Creative portion 2: notifications (displaying on profile)-->

                    <?php
                    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND seen = 0");
                    $stmt->bind_param('i', $user_id);
                    $stmt->execute();
                    $stmt->bind_result($unseen_notifs);
                    $stmt->fetch();
                    $stmt->close();
                    ?>
                    
                    <h3><button id = "rednotification"><?php echo $unseen_notifs; ?></button> New Comment Notifications</h3>
                    <?php
                        $stmt = $mysqli->prepare("select comments.comment_text, comments.comment_time, news.story_title, users.username from notifications join comments on notifications.comment_id = comments.id join users on comments.user_id = users.id join news on comments.story_id = news.id where notifications.user_id = ? and notifications.seen = 0");
                        $stmt->bind_param('i', $user_id);
                        $stmt->execute();
                        $stmt->bind_result($comment_text, $comment_time, $story_title, $comment_writer);

                        echo "<ul class='notifications'>";
                        
                        echo "<form method='POST' action='markasseen.php'>";
                        echo "<input type='submit' value='Mark All As Seen' class = 'submitbutton'> </form>";
                    
                        while($stmt->fetch()) {
                            echo "<li>New comment on " . htmlspecialchars($story_title) . " by " . htmlspecialchars($comment_writer) . " (" . htmlspecialchars($comment_time) . "): ";
                            echo htmlspecialchars($comment_text);
                            echo "</li>";
                        }
                        echo "</ul>";
                        $stmt->close();
                    ?>
                </div>
            </div>

            <div id = "submitcomments">

                <h3>Your Bookmarks</h3>
                <?php
                    $stmt = $mysqli->prepare("select news.id, news.story_title from bookmarks join news on bookmarks.story_id = news.id where bookmarks.user_id = ?");
                    $stmt->bind_param('i', $user_id);
                    $stmt->execute();
                    $stmt->bind_result($news_id, $news_title);

                    echo "<ul class='newslist'>";
                        while($stmt->fetch()) {
                            echo "<li class = 'filelist'><a href='newsviewer.php?news_id=$news_id' target='_blank' class = 'links'>" . htmlspecialchars($news_title) . "</a></li>";
                        }
                    echo "</ul>";
                    $stmt->close();
                ?>

            </div> 
        </div>

        <br>
        <a href = "index.php" class = "submitbutton">Return to Homepage</a>

    </div>

</body>
</html>