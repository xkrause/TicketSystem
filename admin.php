<?php
    session_start();
    
    if($_SESSION['accessLevel'] != '2'){
        header("Location: login.php");
    }
?>

<h1>Hello Admin</h1>