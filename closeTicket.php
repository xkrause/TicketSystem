<?php
    session_start();
    require 'dbts.php';
    if($_SESSION['accessLevel'] != '1' || $_SESSION['accessLevel'] != '2'){
        //header("Location: login.php");
    }
    $conn = new mysqli($hostname, $username, $password, "craigk_ticket");
    
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    $id = $_GET['ticketid']; 
    $sql = "UPDATE `craigk_ticket`.`Tickets` SET active='1' WHERE ticketid='$id'";
    $conn->query($sql);
    
    $date = new DateTime();
    $dateFormatted=$date->format('Y-m-d H:i:s');
    
    $sql2 = "UPDATE `craigk_ticket`.`Tickets` SET closed='$dateFormatted' WHERE ticketid='$id'";
    $conn->query($sql2);
    
    if($_SESSION['accessLevel']=='1'){
        $previous_page="techLanding.php";
    }elseif($_SESSION['accessLevel']=='2'){
        $previous_page="admin.php";
    }
    
    header("location: $previous_page");
?>