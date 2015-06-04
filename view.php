<?php
    //open to allow for access to session variables
    session_start();
    
    //include database credentials
    require 'dbts.php';
    
    //check if user is permitted to be on this page. if not return to login page.
    if($_SESSION['accessLevel'] == '1' || $_SESSION['accessLevel'] == '2'){
        
    }else{
        header("Location: login.php");
    }
    
    try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ticket", $username, $password);
            //echo "Connected to database.";
        } catch (PDOException $e) {
            echo $e->getMessage();
    }
    
    //retrieve ticket id so we can query the correct ticket to access on the page later
    $id=$_GET['ticketid'];
    
    //sql statment to access the information about the ticket
    $sql = "SELECT `Tickets`.ticketid, `Tickets`.firstname, `Tickets`.lastname, `Tickets`.urgency, `Tickets`.description, `Tickets`.email, `Tickets`.domain, `Tickets`.`date submitted`, `notes`.note
            FROM `craigk_ticket` . `Tickets`
            LEFT JOIN `craigk_ticket` . `notes`
            ON `Tickets`.ticketid = `notes`.`ticketid`
            WHERE Tickets.ticketid=$id";
    
    //save the result of the above query after it is run to be called later on page            
    $result = $dbh->query($sql);
    
    //save various ticket info for use throughout the page
    foreach($result as $row) {
        $fname=$row['firstname'];
        $lname=$row['lastname'];
        $tid=$row['ticketid'];
        $urg=$row['urgency'];
        $des=$row['description'];
        $email=$row['email'];
        $dom=$row['domain'];
        $date=$row['date submitted'];
        $notes=$row['note'];
    }
    
    //if there is a post variable notes....
    if (!empty($_POST['notes'])){
        
        //gets the server time and formats it for display
        $date = new DateTime();
        $dateFormatted=$date->format('Y-m-d H:i:s');
        
        $identity=$_SESSION['identity'];
        foreach( $identity as $value ) {
           $identity=$value;
        }
        
        //save the new posted note to be used in insert statment for the database
        $scrib=$identity . "--------" . $dateFormatted . "<br>" . $_POST['notes'];
        
        //sql statment to insert the above saved notes into the database
        $sql="INSERT INTO `craigk_ticket`.`notes` (note,ticketid)
              VALUES (:scrib,:tid)";
        
        //prepare the PDO for security purposes
        $statement = $dbh->prepare($sql);
        
        //attach note to the PDO's and perform the query to insert into the database
        $statement->bindParam(':scrib', $scrib, PDO::PARAM_STR);
        $statement->bindParam(':tid', $tid, PDO::PARAM_STR);
        $statement->execute();
    }
    //sql query to access the notes of the selected ticket
    $sqlnotes="SELECT `notes`.note FROM `craigk_ticket` . `notes` WHERE ticketid=$id";
    
    //save the results of the above query to a variable to later access
    $resultNotes = $dbh->query($sqlnotes);
?>
<head>
    <!--LINK TO STYLESHEET-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
    <script>
    //Script to display the remaining characters in the database
    $(document).ready(function(){
        var text_max = 1000;
        $('#textarea_feedback').html(text_max + ' characters remaining');
    
        $('#notes').keyup(function() {
            var text_length = $('#notes').val().length;
            var text_remaining = text_max - text_length;
    
            $('#textarea_feedback').html(text_remaining + ' characters remaining');
        });
    });
    </script>
</head>

<body>
    <h1 id="adminGreeting">Welcome, <?php if($_SESSION['accessLevel'] == '1')
                                              { echo "Technician!"; }
                                          elseif($_SESSION['accessLevel'] == '2')
                                              { echo "Administrator!"; }?></h1>
    
    <div class="jumbotron">
        <?php
        echo "<div class='panel panel-default'>";
        echo "<div class='panel-heading'><h4>Ticket ID:$tid   Urgency:$urg</h4></div>";
        echo "<div class='panel-body'>$dom $fname $lname: $email</div>";
        echo "<div class='panel-footer'>Description: $des</div></div>";
      
        if(!empty($resultNotes)){
            echo "<ul class='list-group'>";
            foreach($resultNotes as $row){
                echo "<li class='list-group-item'>";
                echo $row['note'];
                echo "</li>"; 
            }
            echo "</ul>";
        }
        ?>
    
           <form action='view.php?ticketid=<?php echo $id; ?>' method='post'>
                <h4>Notes</h4>
                <textarea name="notes" rows="8" cols="50" id = "notes" maxlength = "1000" require placeholder="1000 character limit." class="form-control"></textarea>
                <div id="textarea_feedback"></div>
                <br>
                <input name="submit" type="submit" value="Submit" class="btn btn-default" style='float: right;'>
           </form>
           <br>
           <a href='closeTicket.php?ticketid=<?php echo $id; ?>'><button class="btn btn-default" onclick="closeConfirm()">Close Ticket</button></a>
           <a href='<?php if($_SESSION['accessLevel'] == '1'){
                              echo "techLanding.php";
                          }
                          elseif($_SESSION['accessLevel'] == '2'){
                              echo "admin.php";
                          }
                    ?>'><button class="btn btn-default" style='float: left;'>Return</button></a>
           <br> <br>
           
           <script>
				//The confirmation box
				function closeConfirm() {
                                    <?php
                                        $to = $email;
                                        $tech = 'akrause3@mail.greenriver.edu';
                                        $closeSubject = "Ticket Closed";
                                        $closeSubmitter = "Your ticket $description has been closed";
                                        $closeTech = "Hey Tech, the ticket $description has been closed";
                                        mail($to, $closeSubject, $subject);
                                        mail($tech, $closeSubject, $closeTech);
                                    ?>
					var Confirmed = confirm ("Do you want to close this ticket?");
					if (Confirmed) {
						var ConfirmedCeption = alert ("Case closed! \nPress Return to go back.");
						if (ConfirmedCeption) {
							//Page redirecting is not working :( Currently leaving it here.
							location.replace('http://google.com');
						}				 
					}
				}
			</script>  
    <!--Lets leave this in place for later if we get to it. Right now this is another
         sql statment and not as critical
        <form>
            Please choose a category:
            <select>
              <option value="option1">Problem 1</option>
              <option value="option2">Problem 2</option>
              <option value="option3">Problem 3</option>
              <option value="option4">Problem 4</option>
              <option value="option5">Problem 5</option>
            </select>
        </form>
            <!--This is a problem i think because we already have a form on the page this isn't critical so we should hold it off for later-->
            <!--<form> Assigned Technician: <input type="text" name="TechName"></form>-->
    </div>
</body>