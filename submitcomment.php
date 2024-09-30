<?php
session_start();
require 'database.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$comment_text = $_POST['comment'];
$news_id = $_POST['story_id'];

//verify the csrf token
if (!empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {

    //referenced from 330 Wiki, PHP & SQL
    $stmt = $mysqli->prepare("insert into comments (user_id, story_id, comment_text) values (?,?,?)");

    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('iis', $user_id, $news_id, $comment_text);
    $stmt->execute();
    $comment_id = $stmt->insert_id;
    $stmt->close();


    //start of creative portion 2: sending notifications to author of story
    $stmt = $mysqli->prepare("select user_id from news where id = ?");

    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('i', $news_id);
    $stmt->execute();
    $stmt->bind_result($writer_id);
    $stmt->fetch();
    $stmt->close();

    if($writer_id != $user_id){
        $stmt = $mysqli->prepare("insert into notifications (user_id, comment_id, seen) values (?, ?, 0)");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('ii', $writer_id, $comment_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: newsviewer.php?news_id=$news_id&submitted=true");

    exit;
}
else {
    error_log("CSRF token validation failed");
    echo "csrf token is invalid, try again";
    exit;
}
?>