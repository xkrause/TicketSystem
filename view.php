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
    $sql = "SELECT `Tickets`.ticketid, `Tickets`.firstname, `Tickets`.lastname, `Tickets`.urgency, `Tickets`.description, `Tickets`.email, `Tickets`.domain, `Tickets`.`date submitted`, `notes`.note, `Tickets`.pcid, `Tickets`.stateid, `Tickets`.catagories, `Tickets`.assignedtech, `Tickets`.active
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
        $pcid=$row['pcid'];
        $stid=$row['stateid'];
        $catagory=$row['catagories'];
        $technician=$row['assignedtech'];
        $status=$row['active'];
    }
    
    //query to get a list of the possible technicians in the system
    $fixer = "SELECT username FROM `login`";
    
    //save the results of the above query
    $techs = $dbh->query($fixer);
    
    if($_POST['sendEmail'] == 'yes'){
        if(!empty($_POST['sendEmailContent'])){
            $emailMessage = $_POST['sendEmailContent'];
            //send email notification to ticket submitter and technician if one is assigned
            $to = $email;
            $tech = $technician;
            $closeSubject = "Ticket Submission Update";
            $closeSubmitter = $emailMessage . "\nDescription: $des\nTicket ID: $tid";
            $closeTech = "An email has been sent to the submitter of ticket id: $tid";
            mail($to, $closeSubject, $closeSubmitter);
            mail($tech, $closeSubject, $closeTech);
        }
    }
    
    //if there is a post variable notes....
    if (!empty($_POST['notes']) or !empty($_POST['pcid']) or !empty($_POST['stid'])){
        
        //gets the server time and formats it for display
        $date = new DateTime();
        $dateFormatted=$date->format('Y-m-d H:i:s');
        
        //get the current user ID into the page from login to insert into ticket information
        $identity=$_SESSION['identity'];
        foreach( $identity as $value ) {
           $identity=$value;
        }
        
        //save the new posted note to be used in insert statment for the database
        $scrib=$identity . "--------" . $dateFormatted;
        if(!empty($_POST['stid'])){
            $stid=$_POST['stid'];
        }
        if(!empty($_POST['pcid'])){
            $pcid=$_POST['pcid'];
        }
        
        $scrib=$scrib . "    " . "PCID: " . $pcid . "    " . "StateID: " . $stid . "<br>" . $_POST['notes'];
        
        //save posted values for assigned technician and catagory
        if(!empty($_POST['catagory'])){
            $catagory=$_POST['catagory'];
        }
        if(!empty($_POST['technician'])){
            $technician=$_POST['technician'];
        }
        
        //sql statment to insert the above saved notes into the database
        $sql="INSERT INTO `craigk_ticket`.`notes` (note,ticketid)
              VALUES (:scrib,:tid)";
        
        //sql statment to insert stateid &|| pcid
        $sql2="UPDATE `craigk_ticket`.`Tickets`
               SET pcid=:pcid,stateid=:stid
               WHERE ticketid=$id";
               
        //sql statement to update technician and catagory into Tickets table
        $sql3="UPDATE `craigk_ticket`.`Tickets`
               SET catagories=:catagory,assignedtech=:technician
               WHERE ticketid=$id";
        
        //prepare the PDO for security purposes
        $statement = $dbh->prepare($sql);
        $statement2 = $dbh->prepare($sql2);
        $statement3 = $dbh->prepare($sql3);
        
        //attach note to the PDO's and perform the query to insert into the database
        $statement->bindParam(':scrib', $scrib, PDO::PARAM_STR);
        $statement->bindParam(':tid', $tid, PDO::PARAM_STR);
        $statement2->bindParam(':pcid', $pcid, PDO::PARAM_STR);
        $statement2->bindParam(':stid', $stid, PDO::PARAM_STR);
        $statement3->bindParam(':technician', $technician, PDO::PARAM_STR);
        $statement3->bindParam(':catagory', $catagory, PDO::PARAM_STR);
        
        //perform the query
        $statement->execute();
        $statement2->execute();
        $statement3->execute();
        
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
        echo "<div class='panel-heading'><h4>Ticket ID:$tid   Urgency:$urg</h4>\nThis is a $catagory problem assigned to $technician.</div>";
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
    
           <form action='view.php?ticketid=<?php echo $id; ?>' method='post' onsubmit='return submissionConfirm()'>
                <div class='col-xs-6'>PCID:<input type='text' class="form-control" name='pcid'></div>
                <div class='col-xs-6'>StateID:<input type='text' class="form-control" name='stid'></div><br>
                <div class='col-xs-6'>Assign a Technician:
                    <select name="technician" class="form-control">
                            <option selected="selected" disabled="disabled" value="">- Assign Technician - </option>
                            <?php 
                            foreach($techs as $row){
                                echo "<option value='$row[username]'>$row[username]</option>";
                            }
                            ?>
                    </select>
                </div>
                <div class='col-xs-6'>
                    Assign category:
                    <select name='catagory' class='form-control'>
                      <option value="network">network</option>
                      <option value="software">software</option>
                      <option value="hardware">hardware</option>
                    </select>
                </div>
            <br><br>
                <h4>Notes</h4>
                <textarea name="notes" rows="8" cols="50" id = "notes" maxlength = "1000" require placeholder="1000 character limit." class="form-control"></textarea>
                <div id="textarea_feedback"></div>
                <br>
                <label><input type='checkbox' name='sendEmail' value='yes'  id='display'>Send Email when submitting</label>
                <div id='sendEmailContent'><textarea name='sendEmailContent' rows='2'  class="form-control"></textarea><i>(This will only notify a technician if one has been assigned.)</i></div>
                <input name="submit" type="submit" value="Submit" class="btn btn-default" style='float: right;'>
            </div>
           </form>
           <br>
            <?php
                if($status == 0){
                    echo "<button class='btn btn-default' onclick='closeConfirm()'>Close Ticket</button>";
                }
                elseif($status == 1){
                    echo "<button class='btn btn-default' onclick='openConfirm()'>Open Ticket</button>";
                }
            ?>
           
           <a href='<?php if($_SESSION['accessLevel'] == '1'){
                              echo "techLanding.php";
                          }
                          elseif($_SESSION['accessLevel'] == '2'){
                              echo "admin.php";
                          }
                    ?>'><button class="btn btn-default" style='float: left;'>Return</button></a>
           <br><br>
	         <script>
		    //The confirmation box
                    function closeConfirm(){
			var Confirmed = confirm ("Are you sure you want to CLOSE this ticket?");
                            if (Confirmed == true) {
				var ConfirmedCeption = alert ("Ticket Closed!");
                                    window.location.replace("closeTicket.php?ticketid=<?php echo $id; ?>");
                                    return true;
				}				 
                                else if(Confirmed == false){
                                    return false;
                                }else{
                                    return false;
                                }
			    }
				
                            function openConfirm(){
                                var reopen = confirm("Do you want to REOPEN this ticket?");
                                if (reopen == true) {
                                    window.location.replace("openTicket.php?ticketid=<?php echo $id; ?>");
                                    return true;
                                }else if(reopen == false){
                                    return false;
                                }else{
                                    return false;
                                }
                            }
                                

		//The confirmation box
		function submissionConfirm() {
		    var Confirmed = confirm ("Are you sure you want to commit these changes?");
			if (Confirmed == true) {
                            alert("Your changes have been submitted.");
                            return true;
			}
			else{
                            return false;
			}
		}
                
                $(document).ready(function(){
                    $('#sendEmailContent').hide();
                });
                
                $("#display").click(function(){
                    if ($('#sendEmailContent').css('display') == 'none') {
                        $("#sendEmailContent").show();
                    }else{
                        $("#sendEmailContent").hide();
                    }
                });
                
        </script>
    </div>
</body>