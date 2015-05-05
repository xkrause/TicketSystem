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
        $to = 'sk8rak@gmail.com';
        $subject = "Your ticket submission";
        $message = 'An email dialog has been created.';
        mail($to, $message, $subject);
        
        }
?>

<head>
    <title>Ticket</title>
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
        function colorFunction() {
            var selected = document.getElementById("priority"),
            color = selected.options[selected.selectedIndex].className;
            selected.className = color;
            selected.blur();
        }
    </script>
</head>
<!--------------------------------------------------------------------------------------------------------------------------->
<body>

    <!--Title for the page-->
    <h2 id="title">Ticket Form</h2>
    <form method="post" action="#">
        <!--putting the form into a div for styling purposes-->
        <div id="ticketInfo">
            <input type="text" placeholder="First Name" name="fname"></input>
            <input type="text" placeholder="Last Name" name="lname"></input>
            <br>
            <input type="text" placeholder="Problem" name="description"></input>
            <input type="text" placeholder="Email" name="email"></input>
            <!--
            at present we don't have a matching database field
            <input type="text" placeholder="Location"></input>-->
            <br>
            
                <!--<div id="serial">
                    <!--Putting the "add fields" in a div-->
                    <!--<div class="input_fields_wrap">
                        <button class="add_field_button">Add A field</button>
                        <div><input type="text" id="serial" placeholder="Serial Number" name="serial"></div>
                    </div>
                </div>-->
                    
            <!--Adding a dropdown menu for priority level-->
            <select id="priority" onchange = "colorFunction()" name="urgency">
                <option class="green" value="Low">Low</option>
                <option class="orange" value="Medium">Medium</option>
                <option class="red" value="High">High</option>
            </select>
            <!--A dropdown down menu to answer student/staff/faculty-->
            <select id="Domain" name="domain">
                <option value="Student">Student</option>
                <option value="Staff">Staff</option>
                <option value="Faculty">Faculty</option>
            </select>
            
            <br>
            <input name="submit" type="submit" value="submit">
        </div>
    </form>
    <div>   
        <h1 id="tempText">This is where the ticket submitter will fill out the form.</h1>
    </div>

</body>