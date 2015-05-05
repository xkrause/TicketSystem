<?php
    //hold session variables for login verifying
    session_start();
    require 'dbts.php';
    //Bring in database credentials
    //if a post occored then check the information to see if they get to login
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
    }
    

?>

<head>
    <title>Login</title>
    <!--LINK TO STYLESHEET-->
    <link rel="stylesheet" href="css/style.css">
</head>
<!----------------------------------------------------------------------------------------------->
<!--I left the javascript off from the original html version so that 
<body>
<!--Putting the login credentials in a div for styling purposes-->
   <!-- <form method="POST" action="form-handler" onsubmit="return checkForm(this);">-->
   <form action="#" method='post'>
    	<div id="login">
	    <input type="text" required placeholder = "Username"  name="username"></input>
	    <br>
            <br>
			
	    <input type="text" required placeholder = "Password" name="password"></input>
	    <br>
            <br>
			
	    <input type="submit" value="Submit" name="submit">
	</div>
    </form>
    <!--Setting id for temporary home page text-->
    <h1 id="tempText">This is the home page <br> Styling will be added
        later</h1>
</body>
                <!--      pattern="(\w+).{6,14}"      this was cut out for testing purposes-->
		<!--title="Special characters are not allowed. You must have at least 6 characters in length.">-->
                
                <!--pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8}"--> 
		<!--title="Password must be exactly 8 characters in length and consists of at least one Upper and lowercase characters, number and special character.">-->
                
<?php
    
?>