<?php
session_start();
require "database.php";

//check if the form was submitted correctly
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $story_id = $_POST['id'];
    $story_text = $_POST['story_text'];
    $story_title = $_POST['story_title'];
    $link = $_POST['link'];

    //update the story by using the values inputted in the form
    $stmt = $mysqli->prepare("UPDATE news SET story_title = ?, story_text = ?, link = ? where id = ? and user_id = ?");
    $stmt->bind_param("sssii",$story_title,$story_text, $link, $story_id, $_SESSION['user_id']);
    
    //come back to this!!!
    if (mysqli_stmt_execute($stmt)) {
        echo "Your story was updated!";

        //redirect the user back to index.php
        header("Location: index.php");
        exit;
    }
    else {
        echo "Something went wrong.";
    }
    mysqli_stmt_close($stmt);
} else {
    //something is wrong with the csrf token
    error_log("CSRF token validation failed");
        echo "Invalid CSRF token. Please try again.";
        header("Location: editstory.php");
        exit;
}
} else {
    echo "Invalid request. Try again!";
    header("Location: editstory.php");
    exit;
}
?>