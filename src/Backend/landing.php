<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Frontend/landing.css">
    <title>ATBS Landing page</title>
</head>
<body>
    <h1 class="cent">Ok now for some human verification please upload your photo here</h1>
    
    <form class="cent" action="./landing.php" method="post" enctype="multipart/form-data">
        <h2><label for="img_upload">Select the image that you wanna upload</label></h2>
        <input type="file" name="img_upload"><br>
        <input type="submit" name="submit">

    </form>

</body>

<?php
    
    $time_spent = time() - $_COOKIE["time_spent"];
    session_start();
    //If the user is authenticated
    if(isset($_SESSION["logged_in"])&&isset($_SESSION["username"])&&isset($_SESSION["tickets_booked"])){
        //Implementation of a file upload feature
        if(isset($_POST["submit"])){
            //Sets the target directory(this directory should have write perms)
            $target_dir = "uploads/";
            //$_FILES is a super global asssociative array, which contains the name of the uploaded file and has many methods in it here
            //"name" is a key having value as the name of the uploaded file
            $target_file = $target_dir.basename($_FILES["img_upload"]["name"]);
            //Here move_uploaded_file takes the contents of the temporary file name stored on the server and moves it to out target destination
            if(move_uploaded_file($_FILES["img_upload"]["tmp_name"],$target_file)){
                echo '<h2 class="cent">Your file '.$_FILES['img_upload']['name'].' Has been uploaded <h2>';
                echo '<h2 class="cent">Your ticket has been booked<h2>';
                echo '<h2 class="cent">Thanks for using ATBS,have a great flight.<h2>';
                echo '<h3 class="cent">The total time spent by you on this website is(in seconds): '.$time_spent.'<h3>';
            }
            else{
                echo "There was an error uploading the file";
            }
        }

        //TODO: Download ticket as txt file
    }
    //Unauthenticated User
    else{
        header("Location:../Frontend/index.html");
    }

?>