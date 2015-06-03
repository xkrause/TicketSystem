<?php
    session_start();
    require 'dbts.php';
    if($_SESSION['accessLevel'] != '1' || $_SESSION['accessLevel'] != '2'){
        //header("Location: login.php");
    }
    if ($dbh->connect_error){
        die("Connection failed: " . $dbh->connect_error);
    }
    try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ticket", $username, $password);
            //echo "Connected to database.";
        } catch (PDOException $e) {
            echo $e->getMessage();
    }
    
    
    $dbh = new mysqli($hostname, $username, $password, $dbname);
    $id=$_GET['ticketid'];
    
    $sql = "SELECT `Tickets`.ticketid, `Tickets`.firstname, `Tickets`.lastname, `Tickets`.urgency, `Tickets`.description, `Tickets`.email, `Tickets`.domain, `Tickets`.`date submitted`, `notes`.note
            FROM `craigk_ticket` . `Tickets`
            LEFT JOIN `craigk_ticket` . `notes`
            ON `Tickets`.ticketid = `notes`.`ticketid`
            WHERE Tickets.ticketid=$id";
    $result = $dbh->query($sql);
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

    $sql="SELECT `notes`.note FROM `craigk_ticket` . `notes` WHERE ticketid=$id";
    $resultNotes = $dbh->query($sql);
    if (isset($_POST['notes'])){
        try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ticket", $username, $password);
            //echo "Connected to database.";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $scrib=$_POST['notes'];
        $sql="INSERT INTO `craigk_ticket`.`notes` (note,ticketid)
              VALUES (:scrib,:tid)";
        
        $statement = $dbh->prepare($sql);
        
        $statement->bindParam(':scrib', $scrib, PDO::PARAM_STR);
        $statement->bindParam(':tid', $tid, PDO::PARAM_STR);
        $statement->execute();
    }
    
?>
<head>
    <!--LINK TO STYLESHEET-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
        <script>
    //Counting the letters
    $(document).ready(function() {
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
    <h1 id="adminGreeting">Welcome, Technician!</h1>
    
    <div class="jumbotron">
        <?php
        
        echo "$fname $lname"; echo "<br>";
        
        foreach($resultNotes as $row){
            echo $row['note'];
            echo "<br>";
        }
        
        ?>
    
           <form action='view.php?ticketid=<?php echo $tid; ?>' method='post'>
                <h4>Add Notes</h4>
                <textarea rows="8" cols="50" id = "notes" maxlength = "500" require placeholder="Please describe the problem. 500 character limit." class="form-control"></textarea>
                <div id="textarea_feedback"></div>
                <br>
                <input name="submit" type="submit" value="Submit">
           </form>
           
           
           <a href='closeTicket.php?ticketid=<?php echo $tid; ?>'><button >Close Ticket</button></a>
           <a href='techLanding.php'><button>Return</button></a>
           <br> <br>

           <script>
				//The confirmation box
				function closeConfirm() {
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
            
            <form> Assigned Technician: <input type="text" name="TechName"></form>
    </div>
</body>