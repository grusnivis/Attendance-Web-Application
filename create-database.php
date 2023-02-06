<?php
	$conn = new mysqli("localhost", "root", "", "temp");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT val FROM temptb WHERE varname = 'IDNum' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$tempvar = $row["val"];
		mysqli_close($conn);
	}
//THIS PART JUST CALLS THE PHP FILE FOR SCANNING OF ATTENDANCE LOG FOLDER
include('1-scan-directory.php');
include('connect.php');
?>

<?php
//session_start();
echo 'For display session only:' . $tempvar;

// THIS CREATES A TABLE FOR EVERY FILE FOUND IN FOLDER
foreach ($files_arr as $file_name) {

    // splits the filnename
    $temp = explode('_', $file_name);

    // Teacher's firstname lastname - Course code - Group number
    $cg = $temp[1] . '-' . $temp[2] . '-' . $temp[3];
	
    $conn = new mysqli("localhost", "root", "", "temp");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO temptb (varname, val) VALUES ('table', '$cg')";
	
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
    //$_SESSION["table"] = $cg;

    // path = directory + filename
    $path = $dir . $file_name;

    // check if table exists in database
    if ($result = $db->query("SHOW TABLES LIKE '" . $cg . "'")) {

        //if table exists ... , else create new table
        if ($result->num_rows == 1) {

            // THIS PART STORES THE NAME AND STATUS OF THE STUDENT INTO THE DATABASE
            if (($file = fopen($path, "r")) !== FALSE) {

                // skips the first line
                fgetcsv($file);

                while (($ar = fgetcsv($file)) !== FALSE) {
                    // SQL query to store data in database
                    // table name is teacher name-schedule-g#
                    //$db->query("INSERT INTO `" . $cg . "`
                    //VALUES ('".$ar[0]."','".$ar[1]."','".$ar[2]."','".$ar[3]."',
                    //'".$temp[0]."','".$ar[4]."','".$ar[5]."')");

                    // CHECKS FOR DUPLICATES IN THE TABLE
                    //$db->query("INSERT INTO `" . $cg . "` (RFID,ID,Surname,Name,Date,Status,Time)
                    //SELECT '$ar[0]','$ar[1]','$ar[2]','$ar[3]','$temp[0]','$ar[4]','$ar[5]' FROM DUAL
                    //WHERE NOT EXISTS (SELECT * FROM `$cg`
                    //WHERE RFID= ".$ar[0]." AND ID=".$ar[1]." AND Surname=".$ar[2]." AND Name=".$ar[3]." AND Date=".$temp[0]." AND Status=".$ar[4]." AND Time=".$ar[5]." LIMIT 1)");

                }
            }

            // closes and deletes the file
            fclose($file);
            //unlink($path);

        } else {
            // sql to create table if it doesn't exist yet
            $create_table = "CREATE TABLE IF NOT EXISTS `{$cg}` (
                    RFID VARCHAR(255) NOT NULL,
                    ID VARCHAR(255) NOT NULL,
                    Surname VARCHAR(255) NOT NULL,
                    Name VARCHAR(255) NOT NULL,
                    Date VARCHAR(255) NOT NULL,
                    Status VARCHAR(255) NOT NULL,
                    Time VARCHAR(255) NOT NULL)";

            // adds table to database
            $db->query($create_table);


            // THIS PART STORES THE NAME AND STATUS OF THE STUDENT INTO THE DATABASE
            if (($file = fopen($path, "r")) !== FALSE) {

                // skips the first line
                fgetcsv($file);

                while (($ar = fgetcsv($file)) !== FALSE) {
                    // SQL query to store data in database
                    // table name is teacher name-schedule-g#
                    $db->query("INSERT INTO `" . $cg . "`
                        VALUES ('" . $ar[0] . "','" . $ar[1] . "','" . $ar[2] . "','" . $ar[3] . "',
                                '" . $temp[0] . "','" . $ar[4] . "','" . $ar[5] . "')");

                }
            }

            // closes and deletes the file
            fclose($file);
            //unlink($path);

        }
    }
}

//search for the first name, last name in the teacher database
/* similar to this: https://www.javatpoint.com/php-mysql-login-system */
//database credentials, running MySQL with default setting (user 'root' with no password)
define('DB_SERVER', 'localhost'); //host name
define('DB_USERNAME', 'root'); //host password
define('DB_PASSWORD', ''); //database password
define('DB_NAME', 'teacher'); //database name to connect to (teacher)

//moving to the logged in user's folder
//attempt to connect to MySQL database
$databaseLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

//check the connection to the database
if ($databaseLink == false) {
    //die() kinda functions like an exit() function
    die("Error connecting to the server." . mysqli_connect_error());
}

//on the 'teacher' database, 'login' table in phpmyadmin, search for the id number in the session array
//mysql and sessions (use curly braces) https://stackoverflow.com/questions/5746614/session-variable-in-mysql-query
$sql = "SELECT *FROM login WHERE IDNumber = {$tempvar}";
//$result = mysqli_query($databaseLink, $sql);
//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
//$count = mysqli_num_rows($result);

if ($result = $databaseLink->query($sql)) {
    //test later: https://www.tutorialspoint.com/fetch-a-specific-column-value-name-in-mysql
    //see solution here for sessions: https://www.simplilearn.com/tutorials/php-tutorial/php-login-form
    while ($row = $result->fetch_assoc()) {
        //set the $row[""] to the column you want to use
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
    }
}

mysqli_close($databaseLink);
$dbname = "monitoring";
$dbtablename = $firstName . " " . $lastName;
$databaseLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, "$dbname");
if ($databaseLink == false) {
    //die() kinda functions like an exit() function
    die("Error connecting to the server." . mysqli_connect_error());
}

mysqli_select_db("monitoring") or die(mysql_error);

//221014 try later: https://www.datameer.com/blog/sql_how-to-display-the-tables-containing-particular-strings-in-sql/
//https://www.mytecbits.com/microsoft/sql-server/search-and-find-table-by-name
//https://www.w3schools.com/php/php_mysql_select_where.asp
//https://stackoverflow.com/questions/9898610/displaying-all-table-names-in-php-from-mysql-database
$filteredTables = "SHOW TABLES FROM $dbname like '%%'";




?>

    <!DOCTYPE html>
    <html>
    <head>
        <title> Class Monitoring </title>
        <link type="text/css" rel="stylesheet" href="css/monitoring-style.css"/>
    </head>
    <div class="monitoring-main-con">
        <h1>Class Monitoring</h1>
        <p class="instructions"> Please select your class to monitor.</p>
        <?php
        /* similar to this: https://www.javatpoint.com/php-mysql-login-system */
        //database credentials, running MySQL with default setting (user 'root' with no password)
        define('DB_SERVER', 'localhost'); //host name
        define('DB_USERNAME', 'root'); //host password
        define('DB_PASSWORD', ''); //database password
        define('DB_NAME', 'teacher'); //database name to connect to (teacher)

        //attempt to connect to MySQL database
        $databaseLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        //check the connection to the database
        if($databaseLink == false){
            //die() kinda functions like an exit() function
            die("Error connecting to the server." . mysqli_connect_error());
        }

        //on the 'teacher' database, 'login' table in phpmyadmin, search for the id number in the session array
        //mysql and sessions (use curly braces) https://stackoverflow.com/questions/5746614/session-variable-in-mysql-query
        $sql = "SELECT *FROM login WHERE IDNumber = {$tempvar}";
        //$result = mysqli_query($databaseLink, $sql);
        //$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$count = mysqli_num_rows($result);

        if ($result = $databaseLink->query($sql)){
            //test later: https://www.tutorialspoint.com/fetch-a-specific-column-value-name-in-mysql
            //see solution here for sessions: https://www.simplilearn.com/tutorials/php-tutorial/php-login-form
            while ($row = $result->fetch_assoc()) {
                //set the $row[""] to the column you want to use
                $field1name = $row["firstName"];
                $field2name = $row["lastName"];

                echo '<b>'.$field1name . '<br/>' . $field2name.'</b><br />';
            }
        }


        ?>




        <?php
        $show_tables = $db->query("SHOW TABLES");

        // displays the tables as buttons
        while ($table_name = $show_tables->fetch_assoc()) {
            foreach ($table_name as $table) {
                // !!! REMINDER TO UNCOMMENT LINES BELOW ONCE FINALIZED !!!
                //$temp = explode('-',$table);
                //$table = $temp[1] . "-" . $temp[2];
                ?>

                <form method="GET" action="3-display.php">
                    <input type="submit" name="table" value="<?php echo strtoupper($table); ?>"/>
                </form>
                <?php
            }
        }
        ?>


    </div>

    </body>
    </html>


<?php
// closes database
$db->close();
?>