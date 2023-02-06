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

    //User inputs
    $usr_username = $_POST['username'];
    $usr_password = $_POST['password'];
 
    //Sql query to show the contents of all the columns
    $select_sql = "SELECT * from auth;";
    //Execute the sql query 
    $res = $conn->query($select_sql);
    
    //If rows exists
    if($res->num_rows > 0){
        $login = 0;
        //fetch_assoc actually creates an array of subsequent rows in which the key is the respective column and the value is the value in the  tuple of that column
        while($row = $res->fetch_assoc()){
            //No user name entered
            if($usr_username==NULL){
                $login=3;
                break;

            }
            // Check is the user is already in the db
            elseif($usr_username === $row['username'] && $usr_password === $row['password']){
                $login = 1;
                break;
            }
            //Username might be correct but the password might be wrong, since i'm implementing  a unique username model this is important
            elseif($usr_username === $row['username'] && $usr_password !== $row['password']){
                $login = 2;
                break;
            }
            else{
                $login = 0;
            }
        }
    }
    else{
        echo "No rows exists";
    }

    //Now there will be 2 cases either the user info will be already there on the db or it won't be present in which case we'll create a login info for the user
    //aka registering the user

    //Checking if the user input is present in the username and password array,usernames are unique
    if($login===1){
        //Since the user is logged in start a session
        session_start();

        // Check if the user is admin
        if($usr_username==="admin"&&$usr_password==="root"){
            //If the user is admin then display the admin page
            //Set the sessions variables
            $_SESSION['logged_in'] = TRUE;
            $_SESSION['username'] = 'admin';
            //admin cookie
            header("Location:./admin.php");
            
        }
        else{
            //regular users go on to book their tickets
            $_SESSION['logged_in'] = TRUE;
            $_SESSION['username'] = $usr_username;
            //User cookie
            setcookie("time_spent",time(),time()+(86400*30),"/");
            header("Location:./tickets.php");

            
        }
    }
    elseif($login===2){
        //Display wrong password warning since username is correct
        echo "<h1>Your password is wrong please go back and re enter  your password<h1>";
    }
    elseif($login===3){
        echo "<h1>Fields can't be empty<h1>";
    }
    else{
        //Register the user -> add the info to the db
        //Here i'm using prepared sql statements this reduces the chance of sql injection attacks
        //This statement prepares the sql statement
        $insert_sql = $conn->prepare("INSERT INTO auth VALUES(?,?);");
        //This statement actually binds the params to the palacholders in the query,"ss" -> says the value is a string
        $insert_sql->bind_param("ss",$usr_username,$usr_password);

        if($insert_sql->execute()===TRUE){
            echo "<h1>You're now registered, you may go back and login now <h1>";
        }
        else{
            echo "Error: ".$insert_sql."<br>".$conn->error;
        }
        //Hence the user is now registered.
    }
?>