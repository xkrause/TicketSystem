<?php
    //hold session variables for login verifying
    session_start();
    require 'dbts.php';
    //Bring in database credentials
    //if a post occored then check the information to see if they get to login
	
	//When the Submit button is clicked
    if(isset($_POST['submit']))
    {
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
                $_SESSION['identity'] = $sqlid;
                $_SESSION['accessLevel'] = $al;
                header("Location: techLanding.php"); 
            }
            //if admin go to admin page
            elseif($count == 1 && $al == "2"){
                $_SESSION['identity'] = $sqlid;
                $_SESSION['accessLevel'] = $al;
                header("Location: admin.php");
            }
            //other wise redirect to login.php
            else {
                $_SESSION['accessLevel'] = "false";
                //echo "log in failed.";
			        header("Location: login.php"); // Wherever you want the user to go when they fail the login
            }
		
		//THIS IS THE VALIDATIONS
		// Variable to check
		$emailCheck = "@greenriver.edu";
		// Validate email
		if (!filter_var($emailCheck, FILTER_VALIDATE_EMAIL) === true) {
			 echo("Please use your GreenRiver email to as Username.");
		}
		
		$userCheck = $_POST['username'];
		$passCheck = $_POST['password'];
		if (!empty($userCheck) && !empty($_POST['password'])){
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
			// Email check
			function emailCheck($userCheck) {
			if(!preg_match_all("/^\"?[\w-_\.]*\"?@greenriver.edu$/", $userCheck)){
					echo ("This is not a valid email.");
					return FALSE;
				}
			}
		
			//Password check		
			function passCheck($passCheck) {
			if (!preg_match_all('$\S*(?=\S{8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $passCheck)){
					echo ("This is not a valid password.");
					return FALSE;
				}
			}
		}
		else
			echo "Please check your username and password.<br />";
	}
?>

<head>
    <title>Login</title>
    <!--LINK TO STYLESHEET-->
    <link rel="stylesheet" href="css/style.css">
</head>
<!----------------------------------------------------------------------------------------------->
 
<body>
<!--Putting the login credentials in a div for styling purposes-->
   <!-- <form method="POST" action="form-handler" onsubmit="return checkForm(this);">-->
   <form action="#" method='post'>
    	<div id="login">
	    <input type="email" required placeholder = "Username"  name="username"
					title="Please use a valid Green River email address."></input>
	    <br>
            <br>
			
	    <input type="text" required placeholder = "Password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8}"
			title="Password must be exactly 8 characters in length and consists of at least one Upper and lowercase characters, number and special character."></input>
	    <br>
            <br>
			
	    <input type="submit" value="Submit" name="submit">
	</div>
    </form>
    <!--Setting id for temporary home page text-->

</body>
