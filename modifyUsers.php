<?php
    session_start();
    require 'dbts.php';
    
    $id=$_SESSION['identity'];
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
    
    $sqlid = "SELECT username from `craigk_ticket` . `login`";
    $techs = $dbh->query($sqlid);
    $techs2 = $dbh->query($sqlid);
    $techs3 = $dbh->query($sqlid);
    
    if($_POST['option'] == 'Add'){
        $newUser = $_POST['newUser'];
        $newPassword = $_POST['newPassword'];
        $accessLevel = $_POST['accessLevel'];
        $sqlAdd = "INSERT INTO `craigk_ticket`.`login` (username, password, accesslevel) VALUES (:userName,:passWord,:accessLevel)";
        $statementAdd = $dbh->prepare($sqlAdd);
        $statementAdd->bindParam(':userName', $newUser, PDO::PARAM_STR);
        $statementAdd->bindParam(':passWord', $newPassword, PDO::PARAM_STR);
        $statementAdd->bindParam(':accessLevel', $accessLevel, PDO::PARAM_INT);
        $statementAdd->execute();
        
    }elseif($_POST['option'] == 'Remove'){
        $selectUserRemove = $_POST['selectUserRemove'];
        $sqlRemove = "DELETE FROM `craigk_ticket`.`login` WHERE username=:username";
        $statementRemove = $dbh->prepare($sqlUpdate);
        $statementRemove->bindParam(':userName', $selectUserRemove, PDO::PARAM_STR);
        $statementRemove->execute();
        
    }elseif($_POST['option'] == 'ChangePermission'){
        $selectUserChange = $_POST['selectUserChange'];
        $accessLevel = $_POST['accessLevel'];
        $sqlPermissions = "UPDATE `craigk_ticket`.`Tickets` SET accesslevel=:accessLevel WHERE username=:userName";
        $statementPermissions = $dbh->prepare($sqlPermissions);
        $statementPermissions->bindParam(':userName', $selectUserChange, PDO::PARAM_STR);
        $statementPermissions->bindParam(':accessLevel', $accessLevel, PDO::PARAM_STR);
        $statementPermissions->execute();
        
    }elseif($_POST['option'] == 'ChangePassword'){
        $selectUserPassword = $_POST['selectUserPassword'];
        $updatePassword = $_POST['updatePassword'];
        $sqlPassword = "UPDATE `craigk_ticket`.`Tickets` SET password=:password WHERE username=:userName";
        $statementPassword = $dbh->prepare($sqlPassword);
        $statementPassword->bindParam(':userName', $selectUserPassword, PDO::PARAM_STR);
        $statementPassword->bindParam(':password', $updatePassword, PDO::PARAM_STR);
        $statementPassword->execute();
        
    }

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
    
    <form action='#' method='post'>
        <label><input type='radio' name='option' id='Add'>Add</label>
        <label><input type='radio' name='option' id='Remove'>Remove</label>
        <label><input type='radio' name='option' id='ChangePermission'>Change permissions</label>
        <label><input type='radio' name='option' id='ChangePassword'>Change Password</label>
        
        <div id='fieldAdd'>
            Enter new users username:<input type='text' name='newUser'>
            Enter new users password:<input type='text' name='newPassword'>
            <select name='accessLevel' class='form-control'>
                <option value="1">technician</option>
                <option value="2">administrator</option>
            </select>
        </div>
        <div id='fieldChange'>
            <select name='selectUserChange' class='form-control'>
                <?php
                    foreach($techs as $row){
                        echo "<option value='$row[username]'>$row[username]</option>";
                    }
                ?>
            </select>
            Set privilages to:
            <select name='accessLevel' class='form-control'>
                <option value="1">technician</option>
                <option value="2">administrator</option>
            </select>
        </div>
        <div id='fieldRemove'>
            Select the user to be removed:
            <select name='selectUserRemove' class='form-control'>
                <?php
                    foreach($techs2 as $row){
                        echo "<option value='$row[username]'>$row[username]</option>";
                    }
                ?>
            </select>
        </div>
        <div id='fieldPassword'>
            Select the users to change their password:
            <select name='selectUserPassword' class='form-control'>
                <?php
                    foreach($techs2 as $row){
                        echo "<option value='$row[username]'>$row[username]</option>";
                    }
                ?>
            </select>
            Enter the users new password:<input type='text' name='updatePassword'>
        </div>
        <input type='submit' name='submit' value='submit' class="btn btn-default">
    </form>
    
</body>
<script>
    
    $(document).ready(function(){
        $('#fieldAdd').hide();
        $('#fieldRemove').hide();
        $('#fieldChange').hide();
        $('#fieldPassword').hide();
    });
    
    $("#Add").click(function(){
        $("#fieldAdd").show();
        $("#fieldRemove").hide();
        $("#fieldChange").hide();
        $('#fieldPassword').hide();
    });
    $("#Remove").click(function(){
        $("#fieldRemove").show();
        $("#fieldAdd").hide();
        $("#fieldChange").hide();
        $('#fieldPassword').hide();
    });
    $("#ChangePermission").click(function(){
        $("#fieldChange").show();
        $('#fieldAdd').hide();
        $('#fieldRemove').hide();
        $('#fieldPassword').hide();
    });
    $("#ChangePassword").click(function(){
        $("#fieldChange").hide();
        $('#fieldAdd').hide();
        $('#fieldRemove').hide();
        $('#fieldPassword').show();
    });
    
</script>