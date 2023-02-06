<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Frontend/admin.css">
    <title>ATBS Admin panel</title>
</head>
<body>
    <div class="cent">
        <h1>
            Welcome back Admin
        </h1>
        <form class="cent" action="./admin.php" method="post">

        <h3><label for="delete_usr">Which user do you wanna delete?</label></h3>
        <input type="text" name="delete_usr"><br>

        <h3><label for="change_usr">The username of the user you wanna change the password of</label></h3>
        <input type="text" name="usr"><br>

        <h3><label for="new_pass">The new password you wanna set</label></h3>
        <input type="text" name="pass"><br>

        <input type="submit" name="submit">
    </div>
</body>


<?php
    //Connect with the mysql db
    $servername = "localhost";
    $username   = "root";
    $password   = "arun360360";
    $dbname     = "atbs";

    //Make the connection
    $conn = new mysqli($servername,$username,$password,$dbname);

    //Check if there is a connection error
    if($conn->connect_error){
        die("Connection with mysql db failed".$conn->connect_error);
    }
    //We need to start the session 
    session_start();
    //Check if the user is actually an admin
    //Check the session data also check the cookie data for added layer of protection
    if($_SESSION['logged_in']==True && $_SESSION['username']==='admin'){
        //The following section fetches the table details from the db and renders it on the website, this way of query execution is different from the methods i've
        //used in other server side scripts
        //First table
        echo "<h1 class='cent'><u>List of user accounts</u></h1>";
        //Making a table like view
        echo "<table class='cent'>";
        echo "<tr>";
        echo "<th>Username</th>";
        echo "<th>Password</th>";
        echo "</tr>";
        //The sql query
        $res = mysqli_query($conn,"SELECT * FROM auth;");
        //mysqli_fetch_array fetches on row at a time from the db as an associative array
        while($row = mysqli_fetch_array($res)){
            echo "<tr>";
            //Accessing the key's from the associative array
            echo "<td>".$row["username"]."</td>";
            echo "<td>".$row["password"]."</td>";   
            echo "</tr>";
        }
        echo "</table>";
        //Some space in between
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        
        //Second table
        echo "<h1 class='cent'><u>List of booked tickets</u></h1>";
        //Making a table like view
        echo "<table class='cent'>";
        echo "<tr>";
        echo "<th>Username</th>";
        echo "<th>Airline</th>";
        echo "</tr>";
        $res = mysqli_query($conn,"SELECT * FROM ticket;");
        while($row = mysqli_fetch_array($res)){
            echo "<tr>";
            echo "<td>".$row["username"]."</td>";
            echo "<td>".$row["airline"]."</td>";
            echo "</tr>";
        }
        //Get the user name to be deleted
        if(isset($_POST["delete_usr"])){
            $delete_usr = $_POST["delete_usr"];
            //The Delete query
            $sql = "DELETE FROM auth WHERE username="."'".$delete_usr."';";
            $sql_ticket = "DELETE FROM ticket WHERE username="."'".$delete_usr."';";
            //Execute the query
            $res = mysqli_query($conn,$sql);
            //remove user from the tickets table
            $res_ticket = mysqli_query($conn,$sql_ticket);
        }
        
        //Change the password of a user
        if(isset($_POST["usr"])&&isset($_POST["pass"])){
            $usr = $_POST["usr"];
            $pass = $_POST["pass"];
            $sql = "UPDATE auth SET password='$pass' WHERE username='$usr';";
            $res = mysqli_query($conn,$sql);
        }
    }
    else{
        header("Location:../Frontend/index.html");
    }

?>