<?php
session_start();
require "database.php";

//check if the form was submitted correctly
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //verify the csrf token
    if (!empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $id = $_POST['id'];
        $comment_text = $_POST['comment_text'];

        //update the comment by using the values inputted in the form
        $stmt = $mysqli->prepare("UPDATE comments SET comment_text = ? where id = ? and user_id = ?");
        $stmt->bind_param("sii",$comment_text, $id, $_SESSION['user_id']);
    
     //execute system
        if (mysqli_stmt_execute($stmt)) {
        echo "Your comment was updated!";

        //redirect the user back to index.php
        header("Location: newsviewer.php?news_id=$id");
        exit;
        }

        else {
            echo "Something went wrong.";
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("csrf token validation failed");
        echo "Invalid csrf. Try again!";
        header("Location: editcomments.php");
        exit;
    } 
} else {
    echo "Invalid request. Try again!";
    header("Location: editcomments.php");
    exit;
}

?>