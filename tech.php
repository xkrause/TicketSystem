<?php
    session_start();
    
    if($_SESSION['accessLevel'] != 'tech'){
        header(Location: 'login.php');
    }
?>

<h1>Hello Technician</h1>