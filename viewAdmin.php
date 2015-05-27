<?php
    session_start();
    require 'dbts.php';
    if($_SESSION['accessLevel'] != '2'){
        header("Location: login.php");
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
<link rel="stylesheet" href="css/style.css">
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
                <textarea name="notes" require placeholder="Please describe the problem. 500 character limit." class="form-control"></textarea>
                <input name="submit" type="submit" value="Submit">
           </form>
           <a href='closeTicket.php?ticketid=<?php echo $tid; ?>'><button>Close Ticket</button></a>
           <a href='techLanding.php'><button>Return</button></a>
    </div>
    
</body>