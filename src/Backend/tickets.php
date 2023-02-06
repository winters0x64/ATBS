<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Frontend/tickets.css">
    <title>Airport ticket booking system</title>
</head>
<body>
    <table id=center>
        <!-- Table representation -->
            <tr>
                <th>
                    Airlines name
                </th>
                <th>
                    Departure
                </th>
                <th>
                    Arrival
                </th>
                <th>
                    Date
                </th>
                <th>
                    Rate
                </th>
            </tr>
            <tr>
                <td>
                    Emirates
                </td>
                <td>
                    Trivandrum
                </td>
                <td>
                    Dubai
                </td>
                <td>
                    22/08/2004
                </td>
                <td>
                    RS 40000
                </td>
            </tr>
            <tr>
                <td>
                    Qatar Airways
                </td>
                <td>
                    Trivandrum
                </td>
                <td>
                    Qatar
                </td>
                <td>
                    23/08/2004
                </td>
                <td>
                    RS 45000
                </td>
            </tr>
            <tr>
                <td>
                    Jet Airways
                </td>
                <td>
                    Trivandrum
                </td>
                <td>
                    Mumbai
                </td>
                <td>
                    24/08/2004
                </td>
                <td>
                    RS 15000
                </td>
            </tr>
            <tr>
                <td>
                    Air Canada
                </td>
                <td>
                    Cochin
                </td>
                <td>
                    Canada
                </td>
                <td>
                    24/08/2004
                </td>
                <td>
                    RS 75000
                </td>
            </tr>
            <tr>
                <td>
                    American airlines
                </td>
                <td>
                    Cochin
                </td>
                <td>
                    Dallas
                </td>
                <td>
                    25/08/2004
                </td>
                <td>
                    RS 90000
                </td>
            </tr>
        </table>
    <form id="tickets" action="./tickets.php" method="post"> 
        <input type="text" name="info"><br>
        <input type="submit" value="Book tickets">
    </form>

</body>
<html>

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

    //Using sessions to verify authentication
    session_start();
    if(isset($_SESSION["logged_in"])){
        echo '<h2 id="tickets">'.'Hello, '.$_SESSION["username"].' Welcome to ATBS portal please enter the airline name that you wanna take'.'</h2>';
        //Get the post value
        $airline = $_POST["info"];
        //array of airplanes
        $air_arr = array("Emirates","Qatar Airways","Jet Airways","Air Canada","American airlines");
        //sql query(similar as we've done in login.php)
        $sql = $conn->prepare("INSERT INTO ticket VALUES(?,?);");
        $sql->bind_param("ss",$_SESSION["username"],$airline);
        //If $airline exists
        if(isset($airline)){
            //If the user input is among  the allowed airlines
            if(in_array($airline,$air_arr)){
                if($sql->execute()==TRUE){
                    $_SESSION["tickets_booked"]=TRUE;
                    header("Location:./landing.php");
                }
            }
            //If the user inputs something not listed in the webpage
            else{
                echo "<script>alert('Please enter a valid string')</script>";
            }
        }
    }
    //Unauthenticated user
    else{
        header("Location:../Frontend/index.html");
    }
?>
