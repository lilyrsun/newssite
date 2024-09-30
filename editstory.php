<?php
session_start();
//connect to sql
require "database.php";

//get the story id 
if (isset($_GET['id'])){
    $story_id = $_GET['id'];

    /** referenced php manual
     * learned how to use different sql functions:
     * https://www.php.net/manual/en/mysqli.prepare.php
     */
    $stmt = mysqli_prepare($mysqli,"SELECT story_title, story_text, link from news where id = ? and user_id = ?");
    mysqli_stmt_bind_param($stmt,"ii",$story_id,$_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    //defining what story title and story text are (and securing the data using htmlspecialchars)
    if ($row = mysqli_fetch_assoc($result)) {
        $story_title = htmlspecialchars($row['story_title']);
        $story_text = htmlspecialchars($row['story_text']);
        $story_link = htmlspecialchars($row['link']);
    }
    else {
        echo "no story could be found";

        //no story to edit --> exit page
        exit;
    }

    mysqli_stmt_close($stmt);
}
else {
    echo "no id was found";

    //no story to edit --> exit page
    exit;
}
?>

<!-- form for updating either story title or story text -->
<!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Story Editor</title>
    </head>
    <body>
        <p>Edit Story</p>
        <form action="updatestory.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($story_id) ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <label for="story_title">Story Title:</label>
            <input type="text" name="story_title" id="story_title" value="<?= $story_title ?>" required>
            <br>
            <label for="story_text">Story Text:</label>
            <textarea name="story_text" id="story_text" required><?= $story_text ?></textarea>
            <br>
            <label for="story_link">Link (Optional):</label>
            <input type="url" name="link" id="story_link" value="<?= $story_link ?>">

            <input type="submit" value="Update Story">
        </form>
    </body>
</html>