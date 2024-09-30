<?php
session_start();
require "database.php";

//get the comment id
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    //delete the comment
    $stmt = $mysqli->prepare("DELETE FROM comments where id = ? and user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "Your comment was deleted";
        header("Location: index.php");
        exit;
    }
    else {
        echo "Your comment was not deleted";
    }
    mysqli_stmt_close($stmt);
}
else {
    echo "No ID was provided";
    header("Location: index.php");
    exit;
}
?>