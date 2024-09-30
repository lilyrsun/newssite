<?php
        session_start();
        require 'database.php';

        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>News Site - Submit Story</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="fadein" id = "submitstory">
        <h1 class="pagetitle">Story Submission Form</h1>
            <form method="POST" action="submitstory.php">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
                <div id = "container">
                    <div id = "comments">
                        <label class="formlabels"><strong>Story Title</strong><br><br><input type="text" name="story_title" class="fillinstory" required></label><br><br><br>
                        <label class="formlabels"><strong>Link (Optional)</strong><br><br><input type="url" name="link" class="fillinstory"></label><br><br>
                    </div>
                    <div id = "submitcomments">
                        <label class="formlabels"><strong>Body</strong><br><br><textarea name = "story_text" rows = "15" cols = "50" class = "fillin_long"></textarea></label><br><br>
                    </div>
                </div>
            <input type="submit" value="Submit Story" class="submitbutton">
            </form>
            <br>
            <a href = "index.php" class = "links">Cancel</a>
    </div>
</body>
</html>