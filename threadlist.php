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
        min-height: 50vh;
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
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` where category_id = $id";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $catname = $row['category_name'];
        $catdesc = $row['category_description'];
    }
    ?>

    <?php
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') {
          //Insert thread into db 
            $th_title = $_POST['threadtitle'];
            $th_desc = $_POST['threaddescription'];
            $sno = $_POST["sno"];
            $th_title = str_replace("<", "&lt;", $th_title);
            $th_title = str_replace(">", "&gt;", $th_title);
            $th_desc = str_replace("<", "&lt;", $th_desc);
            $th_desc = str_replace(">", "&gt;", $th_desc);
            $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ('$th_title', '$th_desc', '$id', '$sno', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            $showAlert = true;
            if($showAlert)
            {
                echo '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your thread has been added! Please wait for community to respond.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                ';
            }
    }
    ?>
    <!-- Category container starts here -->
    <div class="container my-4" >
        <div class="jumbotron">
            <h1 class="display-4">Welcome to <?php echo $catname ?> Forums!</h1>
            <p class="lead"><?php echo $catdesc ?></p>
            <hr class="my-4">
            <ul>
                <li>No Spam / Advertising / Self-promote in the forums</li>
                <li>Do not post “offensive” posts, links or images</li>
                <li>Do not cross post questions</li>
                <li>Remain respectful of other members at all times</li>
            </ul>
            <!-- <a class="btn btn-success btn-lg" href="#" role="button">Learn more</a> -->

        </div>
    </div>

    <?php

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
    {
        echo '<div class="container">
        <h1 class="py-2">Start a Discussion</h1>
        <form action="' . $_SERVER["REQUEST_URI"] . '" method="post">
            <div class="form-group">
                <label for="threadtitle">Problem</label>
                <input type="text" class="form-control" id="threadtitle" name="threadtitle">
            </div>
            <div class="form-group">
                <label for="threaddescription">Elaborate your concern</label>
                <textarea class="form-control" id="threaddescription" name="threaddescription" rows="3"></textarea>
                <input type="hidden" name="sno" value="'. $_SESSION["sno"] .'">
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>';
    }
    else {
        echo '<div class="container">
        <h1 class="py-2">Start a Discussion</h1>
        <p class="lead">You are not logged in. Please login to be able to start a Discussion</p>
    </div>';
    }
    ?>
    <div class="container" id="mycontainer">
        <h1 class="py-2">Browse Questions</h1>
        <!-- for loop -->

        <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `threads` where thread_cat_id = $id";
        $result = mysqli_query($conn, $sql);
        $noResult = false;
        while ($row = mysqli_fetch_assoc($result)) {
            $noResult = true;

            $id = $row['thread_id'];
            $title = $row['thread_title'];
            $desc = $row['thread_desc'];
            $thread_time = $row['timestamp'];
            $formattedTimestamp = date('F j, Y  (g:i a)', strtotime($thread_time));
            $thread_user_id = $row['thread_user_id'];

            $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $posted_by = $row2['user_email'];
            $posted_by = explode("@", $posted_by)[0];

            

                echo ' <div class="media my-3">
                    <img src="https://i.postimg.cc/XN9zry1M/user-profile-icon-free-vector.jpg" height="40px" width="40px" class="mr-3" alt="...">
                    <div class="media-body">
                      <p class="font-weight-bold my-0">'. $posted_by . '<span class="timestamp">' . $formattedTimestamp . '</span></p>
                        <h5 class="mt-0"> <a href="thread.php?threadid=' . $id . '">' . $title . '</a></h5>
                             ' . $desc . '
                            </div>
                        </div>
                    </>';
        }
        if (!$noResult) {
            echo '<div class="jumbotron jumbotron-fluid">
            <div class="container">
              <p class="display-4">No Threads Found</p>
              <p class="lead">Be the first person to ask a question</p>
            </div>
          </div>';
        }
        ?>
    </div>






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