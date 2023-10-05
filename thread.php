<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <style>
    #mycontainer{
        min-height: 40vh;
     }
    .timestamp {
        float: right;
        font-size: 0.8em;
        /* Adjust the font size as needed */
    }
    </style>

    <title>Coding Discussion Forum 🧑🏻‍💻</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php include 'partials/_navbar.php'; ?>
    <?php
        $id = $_GET['threadid'];
        $sql = "SELECT * FROM `threads` where thread_id = $id";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $title = $row['thread_title'];
            $desc = $row['thread_desc'];
        }
    ?>

    <?php
            $showAlert = false;
            $method = $_SERVER['REQUEST_METHOD'];
            if ($method == 'POST') {
                    //Insert into comment db 
                    $comment = $_POST['comment'];
                    $comment = str_replace("<", "&lt;", $comment);
                    $comment = str_replace(">", "&gt;", $comment);
                    $sno = $_POST["sno"];
                    $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment', '$id', '$sno', current_timestamp())";
                    $result = mysqli_query($conn, $sql);
                    $showAlert = true;
                    if($showAlert)
                    {
                        echo '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your comment has been added!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        ';
                    }
            }
          ?>

    <!-- Category container starts here -->
    <div class="container my-4" id="mycontainer">
        <div class="jumbotron">
            <h1 class="display-4"> <?php echo $title ?> Forums!</h1>
            <p class="lead"><?php echo $desc ?></p>
            <?php 
            $id = $_GET['threadid'];
            $sql = "SELECT * FROM `threads` where thread_id = $id";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $posted_by = $row['thread_user_id'];
            }
            $sql2 = "SELECT user_email FROM `users` WHERE sno='$posted_by'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $posted_by = $row2['user_email'];
            $posted_by = explode("@", $posted_by)[0];

            echo '
            <p><b>Posted by: '. $posted_by .' </b></p>
            '
            ?> 
            <hr class="my-4">

            <ul>
                <li>No Spam / Advertising / Self-promote in the forums</li>
                <li>Do not post “offensive” posts, links or images</li>
                <li>Do not cross post questions</li>
                <li>Remain respectful of other members at all times</li>
            
            
        </div>
    </div>

    <div class="container">
        <?php 
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
        {
            echo '<div class="container px-0">
            <h1 class="py-2">Post Your Comment </h1>
            <h4 class="alert-heading">Welcome! '. $_SESSION['useremail'] .'</h4>
            <hr>
            <form action=' . $_SERVER["REQUEST_URI"] . ' method="post">
                <div class="form-group">
                    <label>Type Your Comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                    <input type="hidden" name="sno" value="'. $_SESSION["sno"] .'">
                    </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
            <p class="mt-4">Whenever you need to, be sure to logout <a href="partials/_logout.php">using this link.</a></p>
            </div>';
        }
        else
        {
            echo '
            <div class="container px-0"> 
            <h1 class="py-2"> Post Your Comment</h1>
            <p class="lead">You are not logged in. Please login to be able to post comments.</p>
            </div>
            ';
        }
        ?>
       
    </div>


    <div class="container" id="mycontainer">
        <h1 class="py-2">Discussion</h1>
        <!-- for loop -->

        <?php
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `comments` where thread_id = $id";
    $result = mysqli_query($conn, $sql);
    $noResult = true;
    while ($row = mysqli_fetch_assoc($result)) {
        $noResult = false;
        $id = $row['comment_id'];
        $timestamp = $row['comment_time'];
        $formattedTimestamp = date('F j, Y  (g:i a)', strtotime($timestamp));
        $content = $row['comment_content'];
        $commentid = $row['comment_by'];

        $sql2 = "SELECT user_email FROM `users` WHERE sno='$commentid'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $posted_by = $row2['user_email'];
        $posted_by = explode("@", $posted_by)[0];

    
        echo ' <div class="media my-3">
                <img src="https://i.postimg.cc/zDQk7DLz/360-F-229758328-7x8jw-Cwjt-BMm-C6rg-Fz-LFh-Zo-Ep-Lob-B6-L8.jpg" height="50px" width="50px" class="mr-3" alt="...">
                <div class="media-body">
                <p class="font-weight-bold my-0">' . $posted_by . '<span class="timestamp">' . $formattedTimestamp . '</span></p>
                         '. $content . '
                        </div>
                </div>';
    }
    if($noResult)
    {
        echo '<div class="jumbotron jumbotron-fluid">
        <div class="container">
          <p class="display-4">No Discussions Found</p>
          <p class="lead">Be the first person to start the discussion</p>
        </div>
      </div>';
    }
?>




        <?php include 'partials/_footer.php'; ?>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
        </script>
</body>

</html>