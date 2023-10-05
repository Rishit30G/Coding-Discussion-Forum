<?php 

$showError = "false";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    include '_dbconnect.php';
    $user_email = $_POST['signupEmail'];
    $password = $_POST['signuppassword'];
    $cpassword = $_POST['signupcpassword'];

    // Check whether this email exists
    $existSql = "SELECT * FROM `users` WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $existSql);
    $numRows = mysqli_num_rows($result);
    if($numRows>0)
    {
        $showError = "Email already in use";
    }
    else
    {
        if($password == $cpassword)
        {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (`user_email`, `user_password`, `timestamp`) VALUES ('$user_email', '$hash', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            if($result)
            {
                $showAlert = true;
                header("Location: /tutorial/Forum App/index.php?signupsuccess=true");
                exit();
            }
        }
        else
        {
            $showError = "Passwords do not match";
        }
    }
    header("Location: /tutorial/Forum App/index.php?signupsuccess=false&error=$showError");
}

?>