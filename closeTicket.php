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
    
    $current_page = $_SERVER['HTTP_REFERER'];
    header("location: $current_page");
?>