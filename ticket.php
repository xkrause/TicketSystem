<?php
    require ('dbts.php');
    
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
        if ($_POST)
        {
        //adding a comment here so the git bash will detect changes
        //estalish connection with database OR print error message
        try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ticket", $username, $password);
            //echo "Connected to database.";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        //attach posted values to variables for validation
                    //just realized we need to add a time stamp to the database
        $firstName = $_POST['fname'];
        $lastName = $_POST['lname'];
        $urgency = $_POST['urgency'];
        $catagories = " ";
        $description = $_POST['description'];
        $domain = $_POST['domain'];
        $email = $_POST['email'];
        //$Serial = $_POST['serial'];
		
		//THIS IS THE VALIDATIONS
        
        $checks = 0;
        
        if($checks == 0)
        {
            $sql = "INSERT INTO `craigk_ticket`.`Tickets` (`firstname`, `lastname`, `urgency`, `description`, `domain`, `email`)
            VALUES (:firstname, :lastname, :urgency, :description, :domain, :email)";
            
            //prepares the sql statment
            $statement = $dbh->prepare($sql);
            
            //attaches posted validated variable to the column name for the database
            $statement->bindParam(':firstname', $firstName, PDO::PARAM_STR);
            $statement->bindParam(':lastname', $lastName, PDO::PARAM_STR);
            $statement->bindParam(':urgency', $urgency, PDO::PARAM_STR);
            //->bindParam(':catagories', $catagories, PDO::PARAM_INT);
            $statement->bindParam(':description', $description, PDO::PARAM_STR);
            
            $statement->bindParam(':domain', $domain, PDO::PARAM_STR);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            //$statement->bindParam(':serial', $Serial, PDO::PARAM_STR);
            
            //performs the sql statment writing to the database
            $statement->execute();
        }
        
        
        //this example was obtained from http://www.inmotionhosting.com/support/website/sending-email-from-site/using-the-php-mail-function-to-send-emails
        $to = $_POST['email'];
        $tech = 'akrause3@mail.greenriver.edu';
        $subject = "Hello $firstName! \nThank you for submitting your ticket. \nDescription: $description. \nA technician will contact you shortly.";
        $techSubject = "A new ticket has been submitted. \n$firstName $lastName ($domain) \n $description. \n Priority is $urgency. \n Contact $firstName at $email. \n View ticket at http:/xanderkrause.greenrivertech.net/login.php";
        $message = 'An email dialog has been created.';
        mail($to, $message, $subject);
        mail($tech, $message, $techSubject);
        
        //confirmation of submission 
        }
?>

<head>
    <title>Ticket</title>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.css">
		
    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="http://cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.js"></script>
    
    <style>
    .error{color: #ff0000;}

    </style>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css">
    <link type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.1.min.js">
    
    <link type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js">
    <!--Linking to the stylesheet and jQuery library-->
    <link rel="stylesheet" href="css/style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>
        /*
         * This function will allow for multiple
         * serial number fields to be added or
         * removed if multiple computers are
         * having the same problems.
         * This example was obtained from http://www.sanwebe.com/2013/03/addremove-input-fields-dynamically-with-jquery/comment-page-1
         */
        $(document).ready(function(){
            var max_fields = 10; //maximum of 10 serial numbers (THIS CAN BE CHANGED LATER)
            var wrapper = $(".input_fields_wrap"); //wrapping the fields
            var add_button = $(".add_field_button"); //add serial number button
            
            var x = 1; //field counter variable
            $(add_button).click(function(e){
                e.preventDefault();
                if (x < max_fields) { //if current serial number field count is less than 10, add another field and increase counter by 1
                    x++;
                    //below, this will remove a serial number input field
                $(wrapper).append('<div><input type="text" id="serial" placeholder="Serial Number"/><a href="#" class="remove_field">Remove</a></div>');
            }
        });
              $(wrapper.on("click",".remove_field", function(e){
                e.preventDefault(); $(this).parent('div').remove();
                x--;
              }))
        });
        
        //Color coding the priority level dropdown menu
        //this example was obtained from http://stackoverflow.com/questions/16521565/html-select-with-different-background-color-for-every-option-that-works-properly
        /*function colorFunction() {
            var selected = document.getElementById("priority"),
            color = selected.options[selected.selectedIndex].className;
            selected.className = color;
            selected.blur();
        }*/
        
    </script>
</head>
<!--------------------------------------------------------------------------------------------------------------------------->
<body>

    <!--Title for the page-->
    <img src="images/grcicon.png" alt="greenriver college icon"><span id="title" style="font-size: 3em;">Green River College Online Ticket Form</span>
    <div class="container">
        <div class="jumbotron">
            <form method="post" action="#" onsubmit="submissionConfirm()">
                <!--putting the form into a div for styling purposes-->
                <div id="ticketInfo">
                    <input type="text" required placeholder="First Name" name="fname" class="form-control" 
						title="First name can only contain characters."></input>
                    <br>
                    <input type="text" required placeholder="Last Name" name="lname" class="form-control" 
						title="Last name can only contain characters."></input>
                    <br>
                    <textarea name="description" required placeholder="Please describe the problem. 500 character limit." class="form-control"></textarea>
                    <br>
                    <!--<input type="textarea" placeholder="Problem description" name="description"></input>-->
                    <input type="email" placeholder="Email" name="email" class="form-control" required></input>
                    <br>
                    <!--
                    at present we don't have a matching database field
                    <input type="text" placeholder="Location"></input>-->
                    
                        <!--<div id="serial">
                            <!--Putting the "add fields" in a div-->
                            <!--<div class="input_fields_wrap">
                                <button class="add_field_button">Add A field</button>
                                <div><input type="text" id="serial" placeholder="Serial Number" name="serial"></div>
                            </div>
                        </div>-->
                            
                    <!--Adding a dropdown menu for priority level-->
                    <select id="priority"  name="urgency" class="form-control" required>
                        <option selected="selected" disabled="disabled" value="">- Urgency - </option>
                        <option class="green" value="Low">Low</option>
                        <option class="orange" value="Medium">Medium</option>
                        <option class="red" value="High">High</option>
                    </select>
                    <br>
                    <!--A dropdown down menu to answer student/staff/faculty-->
                    <select required id="Domain" name="domain" class="form-control" required>
                        <option selected="selected" disabled="disabled" value="">- Select: Student/Staff/Faculty -</option>
                        <option value="Student">Student</option> 
                        <option value="Staff">Staff</option>
                        <option value="Faculty">Faculty</option>
                    </select>
                    
                    <br>
                    <input name="submit" type="submit" value="Submit" >
                </div>
            </form>
			
				<script>
				//The confirmation box
				function submissionConfirm() {
					var Confirmed = confirm ("Do you want to submit your ticket?");
					if (Confirmed) {
						var ConfirmedCeption = alert ("Your ticket has been submitted!");
						if (ConfirmedCeption) {
							//Page redirecting is not working :( Currently leaving it here.
							location.replace('http://google.com');
						}				 
					}
				}
			</script>
        </div>
    </div>
	

</body>