<?php
    //opening session for this page
    session_start();
    
    //include the database credentials
    require 'dbts.php';
    
    //if user doesn't have permissions for this page redirect them to the login page
    if($_SESSION['accessLevel'] != '1' || $_SESSION['accessLevel'] != '2'){
        //header("Location: login.php");
    }
    //attempt a connection to the database. if not print error
    try {
            $dbh = new PDO("mysql:host=$hostname;
                           dbname=craigk_ticket", $username, $password);
            //echo "Connected to database.";
        } catch (PDOException $e) {
            echo $e->getMessage();
    }
    
    //pull the ticketid from the url using a get
    $id = $_GET['ticketid'];
    
    //query to access the database for the selected ticekt ,run the query to modify the open/closed status for the ticket
    $sql = "UPDATE `craigk_ticket`.`Tickets` SET active='1' WHERE ticketid='$id'";
    $dbh->query($sql);
    
    //get the time from the server and format it to fit our desire for accuracy.(hours minutes second included)
    $date = new DateTime();
    $dateFormatted=$date->format('Y-m-d H:i:s');
    
    //update the closed value for the ticket to include the time
    $sql2 = "UPDATE `craigk_ticket`.`Tickets` SET closed='$dateFormatted' WHERE ticketid='$id'";
    $dbh->query($sql2);
    
    //sql statment to access the information about the ticket
    $sqlemail = "SELECT `Tickets`.ticketid, `Tickets`.firstname, `Tickets`.lastname, `Tickets`.urgency, `Tickets`.description, `Tickets`.email, `Tickets`.domain, `Tickets`.`date submitted`, `notes`.note, `Tickets`.pcid, `Tickets`.stateid, `Tickets`.catagories, `Tickets`.assignedtech, `Tickets`.active
            FROM `craigk_ticket` . `Tickets`
            LEFT JOIN `craigk_ticket` . `notes`
            ON `Tickets`.ticketid = `notes`.`ticketid`
            WHERE Tickets.ticketid=$id";
    
    //save the result of the above query after it is run to be called later on page            
    $result = $dbh->query($sqlemail);
    
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
    
    //send email notification to ticket submitter that ticket is closed
    $to = $email;
    $tech = 'akrause3@mail.greenriver.edu';
    $closeSubject = "Ticket Closed";
    $closeSubmitter = "Your ticket has been closed. \nDescription: $des\nTicket ID: $tid";
    $closeTech = "A ticket has been closed.\nDescription: $des\nTicket ID: $tid";
    mail($to, $closeSubject, $closeSubmitter);
    mail($tech, $closeSubject, $closeTech);
    
    //redirect back to the users page after ticket is closed
    if($_SESSION['accessLevel']=='1'){
        $previous_page="techLanding.php";
    }elseif($_SESSION['accessLevel']=='2'){
        $previous_page="admin.php";
    }
    
    //redirect to previous page when done here
    header("location: $previous_page");
?>