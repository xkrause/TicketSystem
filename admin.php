<?php
    session_start();
    
    if($_SESSION['accessLevel'] != 'admin'){
        header(Location: 'login.php');
    }
?>

<h1>Hello Admin</h1>