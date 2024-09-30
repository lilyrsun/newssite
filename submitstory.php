<?php
    session_start();
    require 'database.php';

    ini_set('upload_max_filesize', '20M');
    ini_set('post_max_size', '20M');

    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    $story_title = $_POST['story_title'];
    $story_text = $_POST['story_text'];
    $story_link = $_POST['link'];

    if (!empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    
    //referenced from 330 Wiki, PHP & SQL
    $stmt = $mysqli->prepare("insert into news (user_id, story_title, story_text, link) values (?,?,?,?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('isss', $user_id, $story_title, $story_text, $story_link);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?submitted=true");

    exit;
}
else {
    //csrf token is invalid
    error_log("CSRF token validation failed");
    echo "Invalid CSRF token. Please try again.";
}
?>