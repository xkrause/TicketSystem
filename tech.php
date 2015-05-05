<?php
    session_start();
    
    if($_SESSION['accessLevel'] != '1'){
        header("Location: login.php");
    }
?>

<h1>Hello Technician</h1>