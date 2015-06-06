<?php
    session_start();
    require 'dbts.php';
    //redirect if you do not have the credentials
    /*if($_SESSION['accessLevel'] != '2'){
        header("Location: login.php");
    }*/
    
    try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ticket", $username, $password);
            //echo "Connected to database.";
        } catch (PDOException $e) {
            echo $e->getMessage();
    }
    
    $sql = "";
    
    $result = $dbh->query($sql);
?>
<script>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js">
    </script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css">
		
    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.js"></script>
    
    <style>
    .error{color: #ff0000;}

    </style>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css">
    <link type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.1.min.js">
    
    <link type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js">
</script>

<body>
    
    <form>
        <label><input type='radio' name='option' id='Add'>Add</label>
        <label><input type='radio' name='option' id='Remove'>Remove</label>
        <label><input type='radio' name='option' id='Change'>Change</label>
        
        <div id='fieldAdd'>Enter the email of the new user:<input type='text' name='add' ></div>
        <div id='fieldRemove'>Enter the user to be removed:<input type='text' name='remove'></div>
        <div id='fieldChange'>Select the user to be updated:<input type='text' name='change'></div>
    </form>
    
</body>
<script>
    
    $(document).ready(function(){
        $('#fieldAdd').hide();
        $('#fieldRemove').hide();
        $('#fieldChange').hide();
    });
    
    $("#Add").click(function(){
        $("#fieldAdd").show();
        $("#fieldRemove").hide();
        $("#fieldChange").hide();
    });
    $("#Remove").click(function(){
        $("#fieldRemove").show();
        $("#fieldAdd").hide();
        $("#fieldChange").hide();
    });
    $("#Change").click(function(){
        $("#fieldChange").show();
        $('#fieldAdd').hide();
        $('#fieldRemove').hide();
    });
    
</script>