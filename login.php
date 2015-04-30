<?php
    //hold session variables for login verifying
    session_start();

    if($_POST){
        //database connection
        require 'db.php';
        try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ts", $username, $password);
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
            
            $sql2 = "select "
            
            
            // This is wherever you want to redirect the user to
            //if technician go to technician page
            if ($count == 1 && $level == 1) {
                $_SESSION['accessLevel'] = "1";
                header("Location: tech.php"); 
            }
            //if admin go to admin page
            elseif($count == 1 && $level == 2){
                $_SESSION['accessLevel'] = "2";
                header("Location: admin.php");
            }
            //other wise redirect to login.php
            else {
                $_SESSION['accessLevel'] = "false";
                header("Location: login.php"); // Wherever you want the user to go when they fail the login
            }
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
   <form action="#">
    	<div id="login">
	    <input type="text" required placeholder = "Username" pattern="(\w+).{6,14}" name="username"
		title="Special characters are not allowed. You must have at least 6 characters in length.">
	    </input>
	    <br>
            <br>
			
	    <input type="text" required placeholder = "Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8}" name="password"
		title="Password must be exactly 8 characters in length and consists of at least one Upper and lowercase characters, number and special character.">	    
	    </input>
	    <br>
            <br>
			
	    <input type="submit" value="Submit">
	</div>
    </form>
    <!--Setting id for temporary home page text-->
    <h1 id="tempText">This is the home page <br> Styling will be added
        later</h1>
</body>