<?php
    //open session variables for use on this page
    session_start();
    //bring in database login credentials
    require 'dbts.php';
    //redirect if you do not have the credentials
    if($_SESSION['accessLevel'] != '2'){
        header("Location: login.php");
    }
    
    //create the connection
    $conn = new mysqli($hostname, $username, $password, $dbname);
    
    //check the connection
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    else{
        //echo "Success";
    }
    //pull the data into the page and save it to a variable
    $sql = "SELECT firstname, lastname, urgency, description, email, domain, `date submitted` FROM `craigk_ticket` . `Tickets`";
    $result = $conn->query($sql);
    
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
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css">
		
    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.js"></script>
    
    <style>
    .error{color: #ff0000;}

    </style>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css">
    <link type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.1.min.js">
    
    <link type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js">
        
</head>

<body>

    <h1 id="adminGreeting">Welcome, Administrator!</h1>
    <div class="jumbotron">
    <table id = "craigk_ticket" class="table table-bordered table-hover table-striped">
        <thead>
            <tr id="label"><td>First Name</td>
            <td>Last Name</td>
            <td>Urgency</td>
            <td>Description</td>
            <td>Email</td>
            <td>Domain</td>
	    <td>Date Submitted</td></tr>
        </thead>
        <tbody>
            <?php foreach($result as $row) { ?>
                <tr>
                    <td><?php echo $row['firstname']; ?></td>
                    <td><?php echo $row['lastname']; ?></td>
                    <td><?php echo $row['urgency']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['domain']; ?></td>
		    <td><?php echo $row['date submitted']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>    
    </div>

    <div id="ticketInfo">
        <!--<h3>List of tickets that have been submitted:</h3>-->
	<button type="button" id="logout" class="btn btn-default">Log Out</button>
        <h3>Submit a ticket <a href="ticket.php">here</a></h3>
    </div>
</body>
<script>
        $(document).ready(function(){
            $('#craigk_ticket').dataTable();
        });
	$("#logout").click(function(){
	    window.location = "logout.php";
	});
</script>