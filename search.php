<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <style>
    #main-container{
        min-height: 100vh;
    }
    </style>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Coding Discussion Forum 🧑🏻‍💻</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php include 'partials/_navbar.php'; ?>

    <!-- Search Results -->

    <div class="container" id="main-container">
        <h3 class="my-3 text-center"> Search results for : <em>"<?php echo $_GET['search']; ?>"</em></h3>
            <?php 
                $sql = "SELECT * FROM `threads` WHERE MATCH (thread_title, thread_desc) against ('$_GET[search]')";
                $noResult = true;
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                        $title = $row['thread_title'];
                        $desc = $row['thread_desc'];
                        $id = $row['thread_id'];
                        $url = "thread.php?threadid=". $id;
                        $noResult = false;
                
                        echo '
                        <div class="alert alert-dark" role="alert">
                        <div class="my-3">
                        <h4>
                            <a href="'. $url .'" class="text-dark"> '. $title .' </a>
                        </h4>
                            <p>'. $desc .'</p>
                        </div>
                        </div>
                        ';
                    
                }
                    if($noResult){
                            echo '<div class="jumbotron jumbotron-fluid">
                            <div class="container">
                            <p class="display-4">No Results Found</p>
                            <p class="lead"> Suggestions: <ul>
                            <li>Make sure that all words are spelled correctly.</li>
                            <li>Try different keywords.</li>
                            <li>Try more general keywords.</li></ul>
                            </p>
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