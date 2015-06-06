<?php
    session_start();
    require 'dbts.php';
    //redirect if you do not have the credentials
    if($_SESSION['accessLevel'] != '2'){
        header("Location: login.php");
    }
    
    try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ticket", $username, $password);
            //echo "Connected to database.";
        } catch (PDOException $e) {
            echo $e->getMessage();
    }
    
    
?>