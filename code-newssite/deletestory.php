<?php
session_start();
require "database.php";

//get the story id
if (isset($_GET['id'])) {
    $story_id = $_GET['id'];

    //delete the news story
    $stmt = $mysqli->prepare("DELETE FROM news where id = ? and user_id = ?");
    $stmt->bind_param("ii", $story_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "Your story was deleted";
        header("Location: userprofile.php");
        exit;
    }
    else {
        echo "Your story was not deleted";
    }
    mysqli_stmt_close($stmt);
}
else {
    echo "No ID was provided";
    header("Location: index.php");
    exit;
}
?>