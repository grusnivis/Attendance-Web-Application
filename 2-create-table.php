<?php
//initializing variables from the 1-scan-directory.php
$files_arr = array();
$dir = './Attendance Logs/';

//THIS PART JUST CALLS THE PHP FILE FOR SCANNING OF ATTENDANCE LOG FOLDER
include('1-scan-directory.php');

//THIS PART UPDATES THE MASTERLIST DATABASE UPON LOGGING IN BY THE TEACHER

//<!--- CREATE THE STUDENT MASTERLIST DIRECTORY IF IT DOES NOT EXIST --->
//TAGS: CHANGE ADDRESS, DIRECTORY, SERVER PC
if (!file_exists("./Student Masterlist/")) {
    mkdir("./Student Masterlist/", 0777, true);
}

if (file_exists('./Student Masterlist/StudentMasterlist.csv')) {
    //getting the current masterlist contents to update the masterlist database with the rfid column
    $handle = fopen("./Student Masterlist/StudentMasterlist.csv", "r");
    $masterlistCSVArr = array();

    //skips the first reading of the first line from csv file (first line is the header (rfid, id, last name, first name)
    //https://stackoverflow.com/questions/10901113/php-dynamically-create-csv-skip-the-first-line-of-a-csv-file
    fgetcsv($handle);
    //continue to read the masterlist csv to put them into the array
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $masterlistCSVArr[] = $data;
    }
    fclose($handle);

    //IMPORTANT!! DO NOT UNSHIFT THE MASTERLIST CSV ARRAY FOR IT MIGHT HAVE THE RFID COLUMN FILLED OUT!

    //connect to masterlist database
    $creatingMasterlistDB = mysqli_connect('localhost', 'root', '');

    //check the connection to the database
    if ($creatingMasterlistDB->connect_error) {
        //die() kinda functions like an exit() function
        exit('Error connecting to the masterlist database in the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $sqlStatement = $creatingMasterlistDB->prepare("CREATE DATABASE IF NOT EXISTS masterlist DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $sqlStatement->execute();
    $sqlStatement->close();
    mysqli_close($creatingMasterlistDB);

    $studentMasterlistDB = mysqli_connect('localhost', 'root', '', 'masterlist');

    //check the connection to the database
    if ($studentMasterlistDB->connect_error) {
        //die() kinda functions like an exit() function
        exit('Error connecting to the masterlist database in the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $sqlStatement = $studentMasterlistDB->prepare("CREATE TABLE IF NOT EXISTS student (
                    RFID VARCHAR(255),
                    ID VARCHAR(255) PRIMARY KEY,
                    Lastname VARCHAR(255),
                    Firstname VARCHAR(255)) 
                    DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $sqlStatement->execute();
    $sqlStatement->close();

    //prepare the query to update the rfid column in the student table using the primary key ID
    $sqlStatement = $studentMasterlistDB->prepare("UPDATE student SET RFID = ? WHERE ID = ?");
    $sqlStatement->bind_param("ss", $rfid, $id);

    //update the student masterlist database using the masterlist csv array
    //insert multidimensional array to mysql https://stackoverflow.com/questions/7746720/inserting-a-multi-dimensional-php-array-into-a-mysql-database
    //try: https://stackoverflow.com/questions/39818418/using-php-to-insert-array-into-mysql-database
    foreach ($masterlistCSVArr as $row) {
        //rfid, id
        $rfid = $row[0];
        $id = $row[1];
        $sqlStatement->execute();
    }
    $sqlStatement->close();

    //close the connection to the student masterlist database
    mysqli_close($studentMasterlistDB);
}

?>

<!--- UPDATE THE AUTHORIZED USERS DATABASE FOR THE RFID COLUMN -->
<?php
$handle = fopen("./Authorized User Masterlist/AuthorizedUsers.csv", "r");
$AuthorizedUsersArr = array();

//skips the first reading of the first line from csv file (first line is the header (rfid, idnumber, last name, first name)
//https://stackoverflow.com/questions/10901113/php-dynamically-create-csv-skip-the-first-line-of-a-csv-file
fgetcsv($handle);
//continue to read the authorized users csv to put them into the array
while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
    $AuthorizedUsersArr[] = $data;
}
fclose($handle);

//connect to masterlist database
$authorizedUsersDB = mysqli_connect('localhost', 'root', '','authorized users');

//check the connection to the database
if ($authorizedUsersDB->connect_error) {
    //die() kinda functions like an exit() function
    exit('Error connecting to the authorized users database in the server.');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//prepare the query to update the rfid column in the student table using the primary key ID
$sqlStatement = $authorizedUsersDB->prepare("UPDATE users SET RFID = ? WHERE IDNumber = ?");
$sqlStatement->bind_param("ss", $rfidTeacher, $idnumTeacher);

//update the authorized users database using the authorized users csv array
//insert multidimensional array to mysql https://stackoverflow.com/questions/7746720/inserting-a-multi-dimensional-php-array-into-a-mysql-database
//try: https://stackoverflow.com/questions/39818418/using-php-to-insert-array-into-mysql-database
foreach ($AuthorizedUsersArr as $row) {
    //rfid, id
    $rfidTeacher = $row[0];
    $idnumTeacher = $row[1];
    $sqlStatement->execute();
}
$sqlStatement->close();

//close the connection to the student masterlist database
mysqli_close($authorizedUsersDB);

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
    $cg = $temp[3] . '-' . $temp[2] . ' ' . '(' . $temp[4] . ')';
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
    } //<!-- THE FUNCTION PUSHES THE ATTENDANCE LOG CONTENTS TO THE TEACHER'S DATABASE (NOT-TEAMTEACH VERSION)-->
    else {
        //the teacher variable corresponds to the teacher name in the csv file attendance log
        connect_to_db($date, $dir, $file_name, $cg, $teacher);
    }
}
?>

<?php
//<!-- THE FUNCTION PUSHES THE ATTENDANCE LOG CONTENTS TO THE TEACHER'S DATABASE-->
function connect_to_db($date, $dir, $file_name, $cg, $teacher){
	$conn = new mysqli("localhost", "root", "", "temp");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO temptb (varname, val) VALUES ('teacherName', '$teacher')";
	
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
    
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
            DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    
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

        // ADDED THIS BIT
        //creates teacher attendance database and table to hold teacher info from attendance logs
        $databaseConn = mysqli_connect("localhost", "root", "");
        $dbName = "teacher attendance";

        if ($databaseConn->connect_error) {
            //die() kinda functions like an exit() function
            exit('Error connecting to the server.');
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        //create the "teacher attendance" database if it does not exist
        $sqlStatement = $databaseConn->prepare("CREATE DATABASE IF NOT EXISTS `teacher attendance` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $sqlStatement->execute();
        $sqlStatement->close();
        mysqli_close($databaseConn);

        $teacherAttendanceDB = mysqli_connect("localhost", "root", "", $dbName);

        if ($teacherAttendanceDB->connect_error) {
            //die() kinda functions like an exit() function
            exit('Error connecting to the teacher attendance database in the server.');
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $attendanceTableStmt = $teacherAttendanceDB->prepare("CREATE TABLE IF NOT EXISTS teacher_attendance(
                    Course VARCHAR(255) NOT NULL,
                    RFID VARCHAR(255) NOT NULL,
                    ID VARCHAR(255),
                    Surname VARCHAR(255) NOT NULL,
                    Name VARCHAR(255) NOT NULL,
                    Date VARCHAR(255) NOT NULL,
                    Status VARCHAR(255) NOT NULL,
                    Time VARCHAR(255) NOT NULL)
                    DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        //PRIMARY KEY (`Course`,`ID`,`Date`))

        $attendanceTableStmt->execute();
        $attendanceTableStmt->close();

        // gets the teacher info line as string then explodes based on the comma delimiter
        $line = explode(',', fgets($file));

        while (($ar = fgetcsv($file)) !== FALSE) {
            // SQL query to store data in database
            // table name is teacher name-schedule-g#

            // checks if row already exists in the table, adds if yes, does nothing if no
            $check = mysqli_query($teacherAttendanceDB, "select * from `teacher_attendance` where
                        Course='$cg' and RFID='$line[0]' and ID='$line[1]' and Surname='$line[2]' and Name='$line[3]'
                        and Date='$date'");
            $checkrows = mysqli_num_rows($check);

            if ($checkrows > 0) {
                while ($row = $check->fetch_assoc()) {
                    if (empty($row['Time'])) {
                        $teacherAttendanceDB->query("UPDATE `teacher_attendance` 
                                        SET Status='$line[4]', Time='$line[5]'
                                        WHERE ID='$line[1]' AND Surname='$line[2]' AND Name='$line[3]' 
                                        AND Date='$date'");
                    }
                }
            }
            else {
                // adds row, no entry found
                $insert = "INSERT INTO `teacher_attendance`(Course,RFID,ID,Surname,Name,Date,Status,Time) 
                               VALUES('$cg','$line[0]','$line[1]','$line[2]','$line[3]','$date','$line[4]','$line[5]')";
                $result = mysqli_query($teacherAttendanceDB, $insert) or die('Error querying database.');
            }

            //skips the teacher info line in csv
            fgetcsv($file);

            // reads thru the rest of the csv file
            // SQL query to store data in database
            // table name is teacher name-schedule-g#

            // checks if row already exists in the table, adds if yes, does nothing if no
            $check = mysqli_query($db, "select * from `$cg` where
                        RFID='$ar[0]' and ID='$ar[1]' and Surname='$ar[2]' and Name='$ar[3]'
                        and Date='$date'");
            $checkrows = mysqli_num_rows($check);

            if ($checkrows > 0) {
                while ($row = $check->fetch_assoc()) {
                    if (empty($row['Time'])) {
                        $db->query("UPDATE `$cg` 
                                        SET Status='$ar[4]', Time='$ar[5]'
                                        WHERE ID='$ar[1]' AND Surname='$ar[2]' AND Name='$ar[3]' 
                                        AND Date='$date'");
                    }
                }
            }
            else {
                // adds row, no entry found
                $insert = "INSERT INTO `$cg`(RFID,ID,Surname,Name,Date,Status,Time) 
                               VALUES('$ar[0]','$ar[1]','$ar[2]','$ar[3]','$date','$ar[4]','$ar[5]')";
                $result = mysqli_query($db, $insert) or die('Error querying database.');
            }
        }
    }

    // closes and deletes the file
    fclose($file);
    //unlink($path);
    mysqli_close($db);
    mysqli_close($teacherAttendanceDB);

    //UNTIL HERE
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

    body {
        text-align: center;
        background-color: #eaeaea;
    }

    h1 {
        padding-top: 50px;
        padding-bottom:10px;
        color:#dd6e42;
        font-size: 28px;
        font-weight: 500;
        text-align: center;
    }

    .tableBtnCon{
        display:inline-block;
        margin-block-start: 30pt;
        margin: 40px 30px;
        justify-content: center;
    }

    .btnInput{
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
	
	// Create connection directly to specific database
	$conn = new mysqli('localhost', 'root', '', 'temp');
	// Obtain last value of variable user as 1 row
	// format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	$sql = "SELECT val FROM temptb WHERE varname = 'currentUser' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$tempvar1 = $row["val"];
        mysqli_close($conn);
	}
 
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
$sqlStatement->bind_param("s", $tempvar1);
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
	$conn = new mysqli("localhost", "root", "", "temp");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO temptb (varname, val) VALUES ('teacherName', '$teacher_name')";
	
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
//$_SESSION["teacherName"] = $teacher_name;
include('0-connect.php');
?>
<!-- THIS SECTION IS FOR THE TOP NAVIGATION OF THE CLASS MONITORING LANDING PAGE -->
<div>
    <nav class="topnav">
        <a style="color:white;background-color: #4f6d7a;text-decoration:none"><?php echo "Welcome, " . $teacher_name . "!" ?></a>
        <a href=".\class-list-upload.php" style="color:white">Upload Class Lists</a>
        <a href=".\teacher-change-password.php" style="color:white">Change Password
        <a href=".\user-manual-download-teacher.php" style="color:white">Download User Manual</a>
        <a href=".\logout.php" style="float:right;color:white">Log Out</a>
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
                    <input class="btnInput" type="submit" name="table" value="<?php echo strtoupper($table); ?>"/>
                </form>
            </div>
            <?php
        }
    }
    ?>
</div>
</body>
</html>
