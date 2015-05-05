<?php
    
    session_start();
    //redirect if you do not have the credentials
    if($_SESSION['accessLevel'] != '1'){
        header("Location: login.php");
    }

    //this example was obtained from http://www.w3schools.com/php/php_mysql_select.asp
    $username = "craigk_ts";
    $password = "Password02";
    $hostname = "localhost";
    $dbname = "craigk_ticket";
    
    //create the connection
    $conn = new mysqli($hostname, $username, $password, $dbname);
    
    //check the connection
    if ($conn->connect_error){
        die("Connection failes: " . $conn->connect_error);
    }
    
    $sql = "SELECT firstname, lastname FROM `craigk_ticket` . `Tickets`";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0){
        //output the data of each row
        while($row = $result->fetch_assoc()){
            echo "First Name: " . $row['firstname'] . "Last Name: " . $row['lastname'] . "<br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
?>

<head>
    <title>Technician Landing</title>
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
        
        <script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#Tickets').dataTable();
			} );
		</script>
</head>

<body>
    <div id="ticketInfo">
        <h3>List of tickets that have been submitted:</h3>
        <!--Link to the db here-->
        <!--something like: if "login" == "correct login"
        && if primary key != 0, display "x", x++ -->
        
        <select>
            <option value="ticket_1">Ticket 1</option>
            <option value="ticket_2">Ticket 2</option>
            <option value="ticket_3">Ticket 3</option>
        </select>
        
        <h3>Submit a ticket <a href="ticket.html">here</a></h3>
    </div>
</body>