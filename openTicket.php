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
    
    //query to access the database for the selected ticekt and run the query
    $sql = "UPDATE `craigk_ticket`.`Tickets` SET active='1' WHERE ticketid='$id'";
    $dbh->query($sql);
    
    //query to access the database for the selected ticekt ,run the query to modify the open/closed status for the ticket
    $sql = "UPDATE `craigk_ticket`.`Tickets` SET active='0' WHERE ticketid='$id'";
    $dbh->query($sql);
    
    
    //redirect back to the relevant page after ticket is reopened
    if($_SESSION['accessLevel']=='1'){
        $previous_page="techLanding.php";
    }elseif($_SESSION['accessLevel']=='2'){
        $previous_page="admin.php";
    }
    
    //redirect to previous page when done here
    header("location: $previous_page");
?>