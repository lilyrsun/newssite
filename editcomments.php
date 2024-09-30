<?php
session_start();
//connect to sql
require "database.php";

//get the comment id 
if (isset($_GET['id'])){
    $comment_id = $_GET['id'];

    /** referenced php manual
     * learned how to use different sql functions:
     * https://www.php.net/manual/en/mysqli.prepare.php
     */
    $stmt = $mysqli->prepare("SELECT comment_text from comments where id = ? and user_id = ?");
    mysqli_stmt_bind_param($stmt,"ii",$comment_id,$_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    //defining what comment_text is (and securing the data using htmlspecialchars)
    if ($row = mysqli_fetch_assoc($result)) {
        $comment_text = htmlspecialchars($row['comment_text']);
    }
    else {
        echo "no comment could be found";

        //no comment to edit --> exit page
        exit;
    }

    mysqli_stmt_close($stmt);
}
else {
    echo "no id was found";

    //no comment to edit --> exit page
    exit;
}
?>

<!-- form for updating either comment_text -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Comment Editor</title>
    </head>
    <body>
        <p>Comment Story</p>
        <form action="updatecomments.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($comment_id) ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <label for="comment_text">Comment Text:</label>
            <textarea name="comment_text" id="comment_text" required><?= $comment_text ?></textarea>
            <br>
            
            <input type="submit" value="Update Comment">
        </form>
    </body>
</html>