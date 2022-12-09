<?php
    // THIS PART CONNECTS TO THE DATABASE
    $servername = "localhost";
    $username = "root";
    $password = "";

    //Attempt to get the currently logged-in user's first name and last name
    $teacherDatabaseConnect = mysqli_connect('localhost', 'root', '', 'teacher');

    if ($teacherDatabaseConnect->connect_error) {
        //die() kinda functions like an exit() function
        exit('Error connecting to the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //on the 'teacher' database, 'login' table in phpmyadmin, search for the id number in the session array
    //mysql and sessions (use curly braces) https://stackoverflow.com/questions/5746614/session-variable-in-mysql-query
    $statementQuery = $teacherDatabaseConnect->prepare("SELECT * FROM login WHERE IDNumber = ?");
    $statementQuery->bind_param("s", $_SESSION["currentUser"]);
    $statementQuery->execute();

    //<!---THIS PART GETS THE FIRST NAME AND LAST NAME OF THE CURRENTLY LOGGED-IN USER --->
    //from: https://websitebeaver.com/prepared-statements-in-php-mysqli-to-prevent-sql-injection
    //this uses sql prepared statements
    $result = $statementQuery->get_result();
    if ($result->num_rows == 0) {
        exit('The teacher is not registered in the database.');
    }
    while ($row = $result->fetch_assoc()) {
        //set the $row[""] to the column you want to use
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
    }
    $statementQuery->close();
    mysqli_close($teacherDatabaseConnect);

    $dbname = $firstName . " " . $lastName;

    //$dbname = "monitoring";

    // Create connection with server
    $conn = new mysqli($servername, $username, $password);
    $create = $conn->query("CREATE Database IF NOT EXISTS `$dbname`");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create connection with database in server
    $db = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
?>
