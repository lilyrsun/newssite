<?php
    /** referenced community.spiceworks
     * found this link online about editing a mySQL table:
     * https://community.spiceworks.com/t/have-users-edit-a-mysql-table-via-php-html/249595
     * however I did change the code around to suite the needs of the assigment better!
     */
    session_start();
    //connect to SQL
    require "database.php";

    //check if the user is logged in (have to be logged in to edit)
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    //get the comments  for the user
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($mysqli,"SELECT * from comments where user_id = '$user_id'");
?>

<!-- creating a table to display all of the users comments -->
<!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Your Comments</title>
    </head>
<body>
    <table id="myTable" class="tablesorter">
    <thead> 
    <tr>
        <th>Comment ID</th> 
        <th>Story ID</th> 
        <th>Comment_text</th> 
        <th>Comment_time</th> 
        <th>Edit/Delete</th>
    </tr> 
    </thead>
    <tbody>

    <?php
    while($row = mysqli_fetch_array($result)) {
 
    //added htmlspecialchars for web safety concerns
    //displays the following information in the table
    //if the user clicks edit or delete they should be taken to either storyedit.php or deletestory.php
    echo "<tr>";
    echo "<td>".htmlspecialchars($row['id']) . "</td>";
    echo "<td>".htmlspecialchars($row['story_id']) . "</td>";
    echo "<td>".htmlspecialchars($row['user_id']) . "</td>";
    echo "<td>".htmlspecialchars($row['comment_text']) . "</td>";
    echo "<td>".htmlspecialchars($row['comment_time']) . "</td>";
    echo "<td>
        <a href='editcomments.php?id=" . $row['id'] . "'>Edit</a> /
        <a href='deletecomments.php?id=" . $row['id'] . "'>Delete</a>
        </td>";
    echo "</tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>