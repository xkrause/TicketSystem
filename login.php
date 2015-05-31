<?php
    //hold session variables for login verifying
    session_start();
    require 'dbts.php';
    //Bring in database credentials
    //if a post occored then check the information to see if they get to login

    //When the Submit button is clicked
    if(isset($_POST['submit']))
    {
        
        emailCheck();
	passCheck();
        //database connection
        try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ticket", $username, $password);
            //echo "Connected to database.";
        } catch (PDOException $e) {
            //echo $e->getMessage();
        }
		
            // Check if the user name exists. If one result is returned then there are no duplicates and
            // a result. User is then confirmed.
            $sql = "select COUNT(*) from `craigk_ticket`.`login` where username = :username and password = :password";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue("username", $_POST['username'], PDO::PARAM_STR);
            $stmt->bindValue("password", $_POST['password'], PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            
            $sqlid = "select username from `craigk_ticket` . `login` where username = :username";
            $stmtid = $dbh->prepare($sqlid);
            $stmtid->bindValue("username", $_POST['username'], PDO::PARAM_STR);
            $stmtid->execute();
            $id = $stmtid->fetch();
            
            $sqllvl = "select accesslevel from `craigk_ticket` . `login` where username = :username";
            $stmtlvl = $dbh->prepare($sqllvl);
            $stmtlvl->bindValue("username", $_POST['username'], PDO::PARAM_STR);
            $stmtlvl->execute();
            $al = $stmtlvl->fetchColumn();
            
            // This is wherever you want to redirect the user to
            //if technician go to technician page
            if ($count == 1 && $al == "1") {
                $_SESSION['identity'] = $id;
                $_SESSION['accessLevel'] = $al;
                header("Location: techLanding.php"); 
            }
            //if admin go to admin page
            elseif($count == 1 && $al == "2"){
                $_SESSION['identity'] = $id;
                $_SESSION['accessLevel'] = $al;
                header("Location: admin.php");
            }
            //other wise redirect to login.php
            else {
                $_SESSION['accessLevel'] = "false";
                //echo "log in failed.";
			        header("Location: login.php"); // Wherever you want the user to go when they fail the login
            }
            
		
    
    }
    //THIS IS THE VALIDATIONS
    /*
	Conditions:
	$ = beginning of string
        \S* = any set of characters
	(?=\S{8,}) = of at least length 8
	(?=\S*[a-z]) = containing at least one lowercase letter
	(?=\S*[A-Z]) = and at least one uppercase letter
	(?=\S*[\d]) = and at least one number
	(?=\S*[\W]) = and at least a special character (non-word characters)
	$ = end of the string
			*/
    // Email check function
    function emailCheck() {
	if(!preg_match_all("/^\"?[\w-_\.]*\"?@greenriver.edu$/", $userCheck)){
            $_SESSION['accessLevel'] = 'false';
            header("Location: login.php");
	    return FALSE;
	    }
    }
    //Password check function	
    function passCheck() {
	if (!preg_match_all('$\S*(?=\S{8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $passCheck)){
            $_SESSION['accessLevel'] = 'false';
            header("Location: login.php");
	    return FALSE;
	    }
    }
?>

<head>
    <title>Login</title>
    <!--LINK TO STYLESHEET-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<!----------------------------------------------------------------------------------------------->
 
<body>
<!--Putting the login credentials in a div for styling purposes-->
   <!-- <form method="POST" action="form-handler" onsubmit="return checkForm(this);">-->
   <div class="container"><div class="jumbotron">
   <form action="#" method='post'>
    
    	<div id="login"><span align="center" ><h2>Login</h2></span>
            <div><?php if($_SESSION['accessLevel'] == 'false'){ echo "Login Failed. Please Try Again!";
                }?></div>
	    <span class="col-lg-4 col-lg-offset-4"><input type="email" required placeholder = "Username"  name="username"
					title="Please use a valid Green River email address." class="form-control"></input></span>
	    <br>
            <br>
	    <span class="col-lg-4 col-lg-offset-4"><input class="form-control" type="text" required placeholder = "Password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8}"></input></span>
	    <br>
            <br>
	    <input type="submit" value="Submit" name="submit" class="btn btn-default">
	</div>
    </form>
   </div></div>
    <!--Setting id for temporary home page text-->

</body>
<!--
title="Password must be exactly 8 characters in length and consists of at least one Upper and lowercase characters, number and special character."
-->