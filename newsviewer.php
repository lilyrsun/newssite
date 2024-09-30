<?php
    session_start();
    require 'database.php';

    if(isset($_GET['news_id'])){
        $news_id = $_GET['news_id'];
        
        $stmt = $mysqli->prepare("select story_title, story_text, link, upload_time from news where id = ?");

        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('i', $news_id);
        $stmt->execute();

        $stmt->bind_result($title, $text, $link, $timestamp);
        $stmt->fetch();
        $stmt->close();
    } else{
        echo "Error: no story selected.";
        exit;
    }
?>

<!DOCTYPE html>
<html lang = "en" id = "home" class = "fadein">
<head>
	<title><?php echo htmlspecialchars($title);?></title>
	<link rel="stylesheet" href="home.css">
    <!--referenced the information about the like button from geeksforgeeks-->
    <!--link: https://www.geeksforgeeks.org/create-a-like-and-dislike-toggle-using-html-and-css/-->
    <!--include the font awesome for icons-->
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
  
    <style> 
        body { 
            justify-content: center; 
            height: 100vh; 
            margin: 0; 
        } 
  
        .like-btn i { 
            font-size: 40px; 
            cursor: pointer; 
            color: #4CAF50; 
            transition: color 0.3s ease; 
        } 
  
        .like-btn.active2 i { 
            color: #af4c4c; 
        } 
    </style> 
</head>

<body id = "newsviewer">
    <h1 class = 'pagetitle'><?php echo htmlspecialchars($title); ?></h1>

    <p class = "filelist"><?php echo htmlspecialchars($text); ?></p>

    <?php
        if($link){
            echo "<p class = 'filelist links'>Link: <a href = ". htmlspecialchars($link) . "class = 'filelist links'>" . htmlspecialchars($link) . "</p>";
        }
    ?> </a>

    <p>Uploaded on: <?php echo htmlspecialchars($timestamp); ?></p><br>
    
    <hr>
    
    <!--Like/dislike button-->
    <!--also referenced from geeks for geeks-->

    <div class="like-btn" onclick="toggleLike()"> 
        <i class="fas fa-thumbs-up"></i> 
    </div>
    
    <script> 
        let likeBtn = document.querySelector('.like-btn'); 
  
        function toggleLike() { 
            likeBtn.classList.toggle('active2'); 
  
            // Toggle Font Awesome class for the  
            // thumbs-up and thumbs-down icons 
            if (likeBtn.classList.contains('active2')) { 
                likeBtn.innerHTML =  
                    '<i class="fas fa-thumbs-down"></i>'; 
            } else { 
                likeBtn.innerHTML =  
                    '<i class="fas fa-thumbs-up"></i>'; 
            } 
        } 
    </script>

    <!--View/write comments section-->

    <br>

    <div id = "container">

        <div id = "comments">

        <h3>Comments</h3>

        <!--fetching comments from database and displaying them on the site-->
        <?php
            $stmt = $mysqli->prepare("select comments.comment_text, comments.comment_time, users.username from comments join users on comments.user_id = users.id where story_id = ?");
            
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            
            $stmt->bind_param('i', $news_id);
            $stmt->execute();
            $stmt->bind_result($comment_text, $comment_time, $comment_writer);

            echo "<ul class='commentlist'>";
                while($stmt->fetch()) {
                    echo "<li class = filelist>" . htmlspecialchars($comment_writer) . " (" . htmlspecialchars($comment_time) . "): ";
                    echo htmlspecialchars($comment_text);
                    echo "</li>";
                }
            echo "</ul>";
            $stmt->close();
        ?>

        <?php
            if(isset($_SESSION['username'])):
                echo "<a href = 'commentsportal.php' class = 'submitbutton'>Edit Comments</a>";
            endif;
        ?>

        </div>

        <div id = submitcomments>

        <!--comment submission form (only if logged in)
        referenced w3schools for <textarea> html tag: https://www.w3schools.com/tags/tag_textarea.asp-->
        <?php if(isset($_SESSION['user_id'])): ?>
            <h3>Submit a Comment:</h3>
            <form method="POST" action="submitcomment.php">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <textarea id = "comment_text" name = "comment" rows = "10" cols = "50" class = "fillin_long"></textarea><br>
                <input type = "hidden" name = "story_id" value = "<?php echo $news_id; ?>"><br>
                <input type = "submit" value = "Comment" class="submitbutton">
            </form>
        
    </div>
    </div>

        <!--Creative Portion: Bookmarking story-->
        <form method="POST" action="bookmarking.php">
            <input type = "hidden" name = "story_id" value = "<?php echo $news_id; ?>">
            <h3>Love this story? Bookmark below:</h3>
            <input type = "submit" value = "Bookmark" class="submitbutton">
        </form>
    <?php endif; ?>

    <br><br>

    <a href = "index.php" class = "submitbutton">Back to Home</a>

    <br><br><br>
</body>
</html>