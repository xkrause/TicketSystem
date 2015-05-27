<?php
    session_start();
    require 'dbts.php';
    //redirect if you do not have the credentials
    if($_SESSION['accessLevel'] != '1'){
        header("Location: login.php");
    }

    /*this example was obtained from http://www.w3schools.com/php/php_mysql_select.asp
    $username = "craigk_ts";
    $password = "Password02";
    $hostname = "localhost";
    $dbname = "craigk_ticket";*/
    
    //create the connection
    $conn = new mysqli($hostname, $username, $password, $dbname);
    
    //check the connection
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    else{
        //echo "Success";
    }
    
    $sql = "SELECT `Tickets`.ticketid, `Tickets`.firstname, `Tickets`.lastname, `Tickets`.urgency, `Tickets`.description, `Tickets`.email, `Tickets`.domain, `Tickets`.`date submitted`, `notes`.note FROM `craigk_ticket` . `Tickets` LEFT JOIN `craigk_ticket` . `notes` ON `Tickets`.ticketid = `notes`.`ticketid` WHERE active != 1";
    $result = $conn->query($sql);
    
    /*if ($result->num_rows > 0){
        //output the data of each row
        while($row = $result->fetch_assoc()){
            echo "First Name: " . $row['firstname'] . " Last Name: " . $row['lastname'] . "<br>";
        }
    }
    else {
        echo "0 results";
    }*/
    
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

    <h1 id="adminGreeting">Welcome, Technician!</h1>
    <div class="jumbotron">
    <table id = "craigk_ticket" class="table table-bordered table-hover table-striped">
        <thead>
            <tr id="label">
	    <td>First Name</td>
            <td>Last Name</td>
            <td>Urgency</td>
            <td>Description</td>
            <td>Email</td>
            <td>Domain</td>
	    <td>Date Submitted</td>
	    <td>Notes</td>
	    </tr>
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
		    <td><?php echo "<a href='view.php?ticketid=$row[ticketid]'><button>View/Edit Notes</button></a>" ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>    
    </div>
</body>
<script>
        $(document).ready(function(){
            $('#craigk_ticket').dataTable( {
		"order": [[ 6, "desc" ]]
		});
	});
	
	$("#logout").click(function(){
	    window.location = "logout.php";
	});
        
        if (closeAlert == true) {
            <?php
            $close;
            ?>
        }
</script>