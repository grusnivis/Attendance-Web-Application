<?php
session_start();
//initializing variables from the 1-scan-directory.php
$files_arr = array();
$dir = './Attendance Logs/';

//THIS PART JUST CALLS THE PHP FILE FOR SCANNING OF ATTENDANCE LOG FOLDER
include('1-scan-directory.php');
?>

<?php
// THIS CREATES A TABLE FOR EVERY FILE FOUND IN FOLDER
foreach ($files_arr as $file_name) {
    //gets the filename, then splits it (filename, and then the .csv)
    $split = explode('.', $file_name);
    //manipulates only the index with the filename. no .csv
    $temp = explode('_', $split[0]);

    // ACTUAL TABLE NAME = Group Number - Course Name (Schedule)
    //temp[0] is the date of the attendance log
    //temp[1] is the name of the teacher
    //temp[3] = group number, temp[2] = course code, temp[4] = schedule
    // &#10; is for new line
    $date = $temp[0];
    $teacher = $temp[1];
    $cg = $temp[3] . '-' . $temp[2] . ' ' .'(' . $temp[4] . ')';
    //$_SESSION['table'] = $cg;

    //<!-- THIS PART CHECKS IF THE CLASS IS A TEAM TEACH FILE -->
    //the 'TM' is at the group number index
    if (strpos($temp[3], 'TM') !== false) {

        // connect to the teamteach database
        $tm_db = new mysqli("localhost", "root", "", "teamteach");
        if ($tm_db->connect_error) {
            die("Connection failed: " . $tm_db->connect_error);
        } else {
            //course[1] contains the course code. $temp[2] contains the group number
            $course = explode('-', $temp[2]);
            $course_code = "$course[1]-$temp[2]";

            // stores the teamteach names based on the course
            // DO NOT PLACE - (DASH) FOR THE COURSE NAME-CODE! modify for later
            $get = mysqli_query($tm_db, "SELECT Teacher, Partner FROM `teamteach` WHERE Course = '$course_code'");

            while ($row = $get->fetch_assoc()) {
                //the $row variable gets the teacher and partner's names
                foreach ($row as $db_name) {
                    //<!-- THE FUNCTION PUSHES THE ATTENDANCE LOG CONTENTS TO THE TEACHER'S DATABASE (TEAMTEACH VERSION)-->
                    //see function property below!
                    //the file_name variable corresponds to the attendance log folder contents. the foreach loop limits the scanning
                    //to the currently logged in teacher's name only
                    connect_to_db($date, $dir, $file_name, $cg, $db_name);
                }
            }
        }
    }
    //<!-- THE FUNCTION PUSHES THE ATTENDANCE LOG CONTENTS TO THE TEACHER'S DATABASE (NOT-TEAMTEACH VERSION)-->
    else {
        //the teacher variable corresponds to the teacher name in the csv file attendance log
        connect_to_db($date, $dir, $file_name, $cg, $teacher);
    }
}
?>

<?php
//<!-- THE FUNCTION PUSHES THE ATTENDANCE LOG CONTENTS TO THE TEACHER'S DATABASE-->
function connect_to_db($date, $dir, $file_name, $cg, $teacher){
    $_SESSION["teacherName"] = $teacher;
    //$_SESSION["Class Selected"] = $temp[3].'  '.$temp[2];
    //$_SESSION["table"] = $cg;

    //include the connection to the database
    include('0-connect.php');

    // sql to create table if it doesn't exist yet
    $create_table = "CREATE TABLE IF NOT EXISTS `{$cg}` (
            RFID VARCHAR(255) NOT NULL,
            ID VARCHAR(255),
            Surname VARCHAR(255) NOT NULL,
            Name VARCHAR(255) NOT NULL,
            Date VARCHAR(255) NOT NULL,
            Status VARCHAR(255) NOT NULL,
            Time VARCHAR(255) NOT NULL,
            PRIMARY KEY (`RFID`,`ID`,`Date`)) 
            DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    
    // adds table to database
    //$db corresponds to the currently logged-in teacher's database connection in 0-connect.php
    mysqli_query($db, $create_table);

    //<!-- THIS PART STORES THE NAME AND STATUS OF THE STUDENT INTO THE DATABASE -->

    // path = directory + filename
    // dir =  ./Attendance Logs/
    // $file_name = the currently logged-in teacher's passed attendance log csv file
    $path = $dir . $file_name;

    if (($file = fopen($path, "r")) !== FALSE) {
        // skips the first line (first row in the csv file are the headers [rfid, id, last name, etc])
        fgetcsv($file);

        // reads through the csv file
        while (($ar = fgetcsv($file)) !== FALSE) {
            // SQL query to store data in database
            // table name is teacher name-schedule-g#

            // checks if row already exists in the table. adds the csv file row if yes, does nothing if no
            $check = mysqli_query($db, "SELECT * FROM `$cg` WHERE
                        RFID = '$ar[0]' 
                        AND ID='$ar[1]' 
                        AND Surname='$ar[2]' 
                        AND Name='$ar[3]'
                        AND Date='$date'"
            );

            //checks for the number of rows in the current teacher attendance table
            $checkrows = mysqli_num_rows($check);

            //<!-- [IF] if there are records that exist, update the table
            if ($checkrows > 0) {
                while ($row = $check->fetch_assoc()) {
                    if (empty($row['Time'])) {
                        $db->query("UPDATE `$cg` SET Status='$ar[4]', Time='$ar[5]' WHERE 
                                        ID='$ar[1]' 
                                        AND Surname='$ar[2]'
                                        AND Name='$ar[3]' 
                                        AND Date='$date'"
                        );
                    }
                }
            } //<!-- [ELSE] if no records are found in that table, insert every attendance log contents in that table
            else {
                // adds row, no entry found
                $insert = "INSERT INTO `$cg`(RFID, ID, Surname, Name, Date, Status,Time) 
                               VALUES('$ar[0]','$ar[1]','$ar[2]','$ar[3]','$date','$ar[4]','$ar[5]')";
                $result = mysqli_query($db, $insert) or die('Error querying database.');
            }
        } //end of fgetcsv while loop
    } //end of fopen if statement

    // closes and deletes the file (path = dir + filename)
    fclose($file);
    //close database connection to $db
    mysqli_close($db);
    ?>

    <?php
}

?>

<!-- THIS PART IS WHERE THE PAGE DISPLAY STARTS -->
<!DOCTYPE html>
<html>
<!--
<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&display=swap');

    html * {
    font-size: 16px;
    line-height: 1.625;
    font-family: Lato, sans-serif;
    }

    btn{
        background-color: #dc3545;
        color: white;
        text-align: center;
        display:inline-block;
        font-weight:400;
        vertical-align:middle;
        cursor:pointer;
        border:1px solid transparent;
        padding:.375rem .75rem;
        font-size:1rem;
        line-height:1.5;
        border-radius:.25rem;
    }

    .topnav {
        background-color: #4f6d7a;
        overflow: hidden;
    }

    /* Style the links inside the navigation bar */
    .topnav a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        text-transform: capitalize;
    }

    /* Change the color of links on hover */
    .topnav a:hover {
        text-decoration: none;
        background-color:darkgray;
        color: black;
    }

    /* Add a color to the active/current link */
    .topnav a.active {
        background-color: #dd6e42;
        color: white;
    }

</style>
-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="css/create-table-style.css"/>
    <title>Class Selection</title>
</head>

<body>
<?php
// THIS IS WHERE WE CONNECT TO THE DATABASE OF THE TEACHER WHO SIGNED IN
// the session below is to pass the name to the other php file
//$teacher_name = "christopher james m labrador";
//$_SESSION["Teacher name"] = $teacher_name;

//<!--- CREATE THE TEACHER NAME FOR GETTING THEIR CORRESPONDING ATTENDANCE LOGS --->
/* similar to this: https://www.javatpoint.com/php-mysql-login-system */

//database credentials, running MySQL with default setting (user 'root' with no password)
//attempt to connect to MySQL "teacher" database
$teacherLoginDB = mysqli_connect('localhost', 'root', '', 'teacher');

//check the connection to the database
if ($teacherLoginDB->connect_error) {
    //die() kinda functions like an exit() function
    exit('Error connecting to the teacher database in the server.');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//on the 'teacher' database, 'login' table in phpmyadmin, search for the id number in the session array
//mysql and sessions (use curly braces) https://stackoverflow.com/questions/5746614/session-variable-in-mysql-query
$sqlStatement = $teacherLoginDB->prepare("SELECT * FROM login WHERE IDNumber = ?");
$sqlStatement->bind_param("s", $_SESSION["currentUser"]);
$sqlStatement->execute();

//<!---THIS PART CREATES THE TEACHER NAME --->
//from: https://websitebeaver.com/prepared-statements-in-php-mysqli-to-prevent-sql-injection
//this uses sql prepared statements
$result = $sqlStatement->get_result();
if ($result->num_rows == 0) {
    exit('The teacher is not registered in the database.');
}
while ($row = $result->fetch_assoc()) {
    //set the $row[""] to the column you want to use
    $firstName = $row["firstName"];
    $lastName = $row["lastName"];
}
$sqlStatement->close();
mysqli_close($teacherLoginDB);

$teacher_name = $firstName . " " . $lastName;
$_SESSION["teacherName"] = $teacher_name;
include('0-connect.php');
?>
<!-- THIS SECTION IS FOR THE TOP NAVIGATION OF THE CLASS MONITORING LANDING PAGE -->
<div>
    <nav class="topnav">
        <a style="color:white;background-color: #4f6d7a;text-decoration:none"><?php echo "Welcome, " . $teacher_name . "!"?></a>
        <a style = "color:white" href="/teacher-main.php">Home</a>
        <a style = "color:white" href="/class-list-upload.php">Upload Class Lists</a>
        <a style="float:right;color:white" href="/logout.php"> Log Out</a>
    </nav>
    <!--title instructions-->
    <h1> SELECT THE CLASS YOU WANT TO MONITOR: </h1>
    <?php
    $show_tables = $db->query("SHOW TABLES");
    //<!-- THIS PART DISPLAYS THE TABLES AS BUTTONS -->
    while ($table_name = $show_tables->fetch_assoc()) {
        foreach ($table_name as $table) {
            ?>
            <div class="tableBtnCon">
                <!--once the user clicks the buttons it will redirect to 3-display-selection.php-->
                <form method="get" action="3-display-selection.php">
                    <input class="btnInput" type="submit" name="table" value="<?php echo $table; ?>"/>
                </form>
            </div>
            <?php
        }
    }
    ?>
</div>
</body>
</html>
