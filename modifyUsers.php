<?php
    session_start();
    require 'dbts.php';
    
    $id=$_SESSION['identity'];
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
        $sqlRemove = "DELETE FROM `craigk_ticket`.`login` WHERE username=:userName";
        $statementRemove = $dbh->prepare($sqlRemove);
        $statementRemove->bindParam(':userName', $selectUserRemove, PDO::PARAM_STR);
        $statementRemove->execute();
        
    }elseif($_POST['option'] == 'ChangePermission'){
        $selectUserChange = $_POST['selectUserChange'];
        $accessLevel = $_POST['accessLevel'];
        $sqlPermissions = "UPDATE `craigk_ticket`.`login` SET accesslevel=:accessLevel WHERE username=:userName";
        $statementPermissions = $dbh->prepare($sqlPermissions);
        $statementPermissions->bindParam(':userName', $selectUserChange, PDO::PARAM_STR);
        $statementPermissions->bindParam(':accessLevel', $accessLevel, PDO::PARAM_STR);
        $statementPermissions->execute();

        
    }elseif($_POST['option'] == 'ChangePassword'){
        $selectUserPassword = $_POST['selectUserPassword'];
        $updatePassword = $_POST['updatePassword'];
        $sqlPassword = "UPDATE `craigk_ticket`.`login` SET password=:password WHERE username=:userName";
        $statementPassword = $dbh->prepare($sqlPassword);
        $statementPassword->bindParam(':userName', $selectUserPassword, PDO::PARAM_STR);
        $statementPassword->bindParam(':password', $updatePassword, PDO::PARAM_STR);
        $statementPassword->execute();
        
    }

    $sqlid = "SELECT username from `craigk_ticket` . `login`";
    $techs = $dbh->query($sqlid);
    $techs2 = $dbh->query($sqlid);
    $techs3 = $dbh->query($sqlid);
?>
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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
	
	<style type="text/css"> 
		input[type="radio"]{margin: 10px 0};} 
	</style>
</head>

<body>
    <h1 id="adminGreeting">Welcome Administrator</h1>
    <div class='jumbotron'>
        <form action='#' method='post' onsubmit= "return stripHTML(this.newUser, this.accessLevel, this.selectUserChange, this.selectUserRemove, this.selectUserPassword, this.updatePassword) & closeConfirm()">
            <h3>Choose an action</h3>
            <label><input type='radio' name='option' value='Add' id='Add' > Add New User</label><br>
            <label><input type='radio' name='option' value='Remove' id='Remove' > Remove a User</label><br>
            <label><input type='radio' name='option' value='ChangePermission' id='ChangePermission' > Change permissions</label><br>
            <label><input type='radio' name='option' value='ChangePassword' id='ChangePassword' > Change Password</label><br>
            <br>
            <div id='fieldAdd' class="form-group">
                Enter new users username:<input type='text' name='newUser' class="form-control" pattern = "[^@]+@greenriver.edu"
					title="Please use an email with greenriver.edu domain.">
                Enter new users password:
				<i>(password must contain <b>AT LEAST</b> one capital letter, one lowercase letter, one number, and one special character <b>AND</b> be no longet or shorter than 8 characters)
				<input type='text' name='newPassword' class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8}">
                Select their permissions:
                <select name='accessLevel' class='form-control'>
				    <option selected="selected" disabled="disabled" value="">- Permission - </option>
                    <option value='1'>Technician</option>
                    <option value='2'>Administrator</option>
                </select>
            </div>
            <div id='fieldChange' class="form-group">
                Select a user to change:
                <select name='selectUserChange' class='form-control'>
					<option selected="selected" disabled="disabled" value="">- Change User - </option>
                    <?php
                        foreach($techs as $row){
                            echo "<option value='$row[username]'>$row[username]</option>";
                        }
                    ?>
                </select>
                Set privilages to:
                <select name='accessLevel' class='form-control'>
					<option selected="selected" disabled="disabled" value="">- Permission - </option>
                    <option value="1">Technician</option>
                    <option value="2">Administrator</option>
                </select>
            </div>
            <div id='fieldRemove' class="form-group">
                Select the user to be removed:
                <select name='selectUserRemove' class='form-control'>
					<option selected="selected" disabled="disabled" value="">- Remove User - </option>
                    <?php
                        foreach($techs2 as $row){
                            echo "<option value='$row[username]'>$row[username]</option>";
                        }
                    ?>
                </select>
            </div>
            <div id='fieldPassword' class="form-group">
                Select the users to change their password:
                <select name='selectUserPassword' class='form-control'>
					<option selected="selected" disabled="disabled" value="">- Select User - </option>
                    <?php
                        foreach($techs3 as $row){
                            echo "<option value='$row[username]'>$row[username]</option>";
                        }
                    ?>
                </select>
                Enter the users new password:<i>(password must contain <b>AT LEAST</b> one capital letter, one lowercase letter, one number, and one special character <b>AND</b> be no longet or shorter than 8 characters)</i>
				<input type='text' name='updatePassword' class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8}">
            </div>
            <br>
            <input type='submit' name='submit' value='submit' class="btn btn-default"><br><br>
        </form>
    </div>
    <a href="admin.php"><button class="btn btn-default" style='float: left;'>Return</button></a>
</body>
<script>
    
    function closeConfirm(){
	var Confirmed = confirm ("Are you sure submit these changes?");
        if (Confirmed == true) {
	    var ConfirmedCeption = alert ("User modified!");
                return true;
	    }else{
                return false;
            }
    }
    
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
    
    // Strip HTML Tags (form) script- By JavaScriptKit.com (http://www.javascriptkit.com)    
    function stripHTML(){
	var re= /<\S[^><]*>/g
	for (i=0; i<arguments.length; i++)
	arguments[i].value=arguments[i].value.replace(re, "")
    }
</script>