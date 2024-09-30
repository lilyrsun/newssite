<?php 
    session_start();
    require 'database.php';

    if(isset($_POST['story_id']) && isset($_SESSION['user_id'])){
        $news_id = $_POST['story_id'];
        $user_id = $_SESSION['user_id'];

        $stmt = $mysqli->prepare("insert into bookmarks (user_id, story_id, bookmark_time) values (?, ?, current_timestamp)");
        $stmt->bind_param('ii', $user_id, $news_id);

        if($stmt->execute()){
            echo "Story bookmarked successfully!";
            header("Location: userprofile.php");
            exit;
        }
        else{
            echo "Could not bookmark story.";
        }
        $stmt->close();
    }
?>