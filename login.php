<?php
    
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
   <form action="ticket.html"
    	<div id="login">
	    <input type="text" required placeholder = "Username" pattern="(\w+).{6,14}"
		title="Special characters are not allowed. You must have at least 6 characters in length.">
	    </input>
		<br> <br>
			
	    <input type="text" required placeholder = "Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8}"
		title="Password must be exactly 8 characters in length and consists of at least one Upper and lowercase characters, number and special character.">	    
	    </input>
		<br> <br>
			
	    <input type="submit" value="Submit">
	</div>
    </form>
    <!--Setting id for temporary home page text-->
    <h1 id="tempText">This is the home page <br> Styling will be added
        later</h1>
</body>