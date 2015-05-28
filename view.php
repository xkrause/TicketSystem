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
    if (isset($_POST['notes'])){
        
        //attempt connection to the database. print error if unsuccessful
        /*try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ticket", $username, $password);
            //echo "Connected to database.";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }*/
        
        //save the new posted note to be used in insert statment for the database
        $scrib=$_POST['notes'];
        
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
        var text_max = 500;
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
        //JOE this is where all the other information should be displayed.ie email,description,.....
        //Maybe style each not entry
        //I want to add, later, the tech or admin name to each note as well as the time but that will be later
        echo "$fname " . "$lname" . "<br>";
        
        if(!empty($resultNotes)){
            foreach($resultNotes as $row){
                echo $row['note'];
                echo "<br>";
            }
        }
        ?>
    
           <form action='view.php?ticketid=<?php echo $id; ?>' method='post'>
                <h4>Add Notes</h4>
                <textarea name="notes" rows="8" cols="50" id = "notes" maxlength = "500" require placeholder="500 character limit." class="form-control"></textarea>
                <div id="textarea_feedback"></div>
                <br>
                <input name="submit" type="submit" value="Submit">
           </form>
           
           <a href='closeTicket.php?ticketid=<?php echo $id; ?>'><button>Close Ticket</button></a>
           <a href='<?php if($_SESSION['accessLevel'] == '1'){
                              echo "techLanding.php";
                          }
                          elseif($_SESSION['accessLevel'] == '2'){
                              echo "admin.php";
                          }
                    ?>'><button>Return</button></a>
           <br> <br>
           
        <form>
            <!--Lets leave this in place for later if we get to it. Right now this is another
                sql statment and not as critical
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