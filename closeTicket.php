<?php
    //opening session for this page
    session_start();
    
    //include the database credentials
    require 'dbts.php';
    
    //if user doesn't have permissions for this page redirect them to the login page
    if($_SESSION['accessLevel'] != '1' || $_SESSION['accessLevel'] != '2'){
        //header("Location: login.php");
    }
    //attempt a connection to the database. if not print error
    try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ticket", $username, $password);
            //echo "Connected to database.";
        } catch (PDOException $e) {
            echo $e->getMessage();
    }
    
    //pull the ticketid from the url using a get
    $id = $_GET['ticketid'];
    
    //query to access the database for the selected ticekt ,run the query to modify the open/closed status for the ticket
    $sql = "UPDATE `craigk_ticket`.`Tickets` SET active='1' WHERE ticketid='$id'";
    $dbh->query($sql);
    
    //get the time from the server and format it to fit our desire for accuracy.(hours minutes second included)
    $date = new DateTime();
    $dateFormatted=$date->format('Y-m-d H:i:s');
    
    //update the closed value for the ticket to include the time
    $sql2 = "UPDATE `craigk_ticket`.`Tickets` SET closed='$dateFormatted' WHERE ticketid='$id'";
    $dbh->query($sql2);
    
    //redirect back to the users page after ticket is closed
    if($_SESSION['accessLevel']=='1'){
        $previous_page="techLanding.php";
    }elseif($_SESSION['accessLevel']=='2'){
        $previous_page="admin.php";
    }
    
    //redirect to previous page when done here
    header("location: $previous_page");
?>