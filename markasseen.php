<?php
session_start();
require 'database.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $mysqli->prepare("update notifications set seen = 1 where user_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: userprofile.php");
    exit;
} else {
    echo "Invalid request.";
    exit;
}
?>