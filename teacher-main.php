<?php
session_start();
//display if the session variable is passed
print_r($_SESSION);
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<html>
<head>
    <title> Teacher Menu </title>
    <link type="text/css" rel="stylesheet" href="css/teacher-main-style.css"/>
</head>
<body>

<div class= "topnav">
    <!-- Search: how to upload things here -->
    <a href="class-list-upload.php">Upload Class List </a>
    <a href="2-create-table.php"> Class Monitoring </a>
    <a href="logout.php"> Log Out </a> &nbsp;
    <?php
    $databaseLink = mysqli_connect('localhost', 'root', '', 'teacher');
    $sqlStatement = $databaseLink->prepare("SELECT * FROM login WHERE IDNumber = ?");
    $sqlStatement->bind_param("s", $_SESSION["currentUser"]);
    $sqlStatement->execute();
    $result = $sqlStatement->get_result();
    if ($result->num_rows == 0) {
        exit('The teacher is not registered in the database.');
    }
    while ($row = $result->fetch_assoc())
        //set the $row[""] to the column you want to use
        $firstName = $row["firstName"];
    $sqlStatement->close();
    mysqli_close($databaseLink);
    echo "<p class = 'welcome'> Welcome, $firstName! </p>";
    ?>
</div>
<div>
    <!--try this for table of contents: https://stackoverflow.com/questions/11399537/how-do-you-make-a-div-follow-as-you-scroll -->
    <p>
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
    </p>

</div>
</body>
</html>