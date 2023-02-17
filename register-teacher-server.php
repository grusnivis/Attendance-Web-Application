<?php
header('Content-Encoding: utf-8');
header('Content-Type: text/csv; charset=utf-8mb4');

session_start();

$firstName = '';
$lastName = '';
$IDNum = '';
$password = '';
$email = '';

if (isset ($_POST["register"])) {
    //ucfirst - returns the first character of the string capitalized (https://www.php.net/manual/en/function.ucfirst.php)
    $firstName = strtoupper($_POST["first-name"]);
    $lastName = strtoupper($_POST["last-name"]);
    $IDNum = $_POST["IDNum"];
    $password = $_POST["password"];
    //check for email input validity?
    $email = $_POST["email"];

    //<--- PART 1: insert username and password fields into database. Database: Teacher. Table: login --->
    //(from admin-database-config.php)

    //create the database if it does not exist
    $dbConnect = mysqli_connect("localhost", "root", "");
    $dbName = "teacher";

    if ($dbConnect->connect_error){
        //die() kinda functions like an exit() function
        exit('Error connecting to the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //create the "teacher" database if it does not exist
    $sqlStatement = $dbConnect->prepare("CREATE DATABASE IF NOT EXISTS teacher");
    $sqlStatement->execute();
    $sqlStatement->close();
    mysqli_close($dbConnect);

    //connect to teacher database
    $teacherDB= mysqli_connect("localhost", "root", "", $dbName);

    if ($teacherDB->connect_error) {
        //die() kinda functions like an exit() function
        exit('Error connecting to the teacher database in the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //create the "login" table if it does not exist
    //columns are: IDNumber, password, firstName, lastName, email
    //character is set to utf8mb4_unicode_ci on default
    $createLoginTableStmt = $teacherDB->prepare("CREATE TABLE IF NOT EXISTS login(
            IDNumber VARCHAR(255) PRIMARY KEY,
            password VARCHAR(255),
            firstName VARCHAR(255),
            lastName VARCHAR(255),
            email VARCHAR(255))
            DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $createLoginTableStmt->execute();
    $createLoginTableStmt->close();

    //prepare the query to insert the registered teacher to the teacher database.
    //it does duplicate checking first to see if the teacher is registered already
    $checkTeacherDBDuplicate = "SELECT * FROM login WHERE IDNumber = '$IDNum'";
    $statementDuplicate = mysqli_query($teacherDB, $checkTeacherDBDuplicate);
    $rowResults = mysqli_fetch_array($statementDuplicate, MYSQLI_ASSOC);
    $countDup = mysqli_num_rows($statementDuplicate);

    //if there is a duplicate id number
    if($countDup == 1){
        $_SESSION['registerTeacherMsg'] = "The ID Number inputted is already registered in the database.";
        mysqli_close($teacherDB);
        //returns to the register teacher page. it "aborts" the rest of the process
        header("location:register-teacher.php");
    }
    //if there is NO duplicate id number, then you insert the teacher data to the login table
    else{
        $statementInsert = $teacherDB->prepare("INSERT INTO `login` (IDNumber, password, firstName, lastName, email) VALUES (?,?,?,?,?)");
        $statementInsert->bind_param("sssss", $IDNum, $password, $firstName, $lastName, $email);
        $statementInsert->execute();
        $statementInsert->close();

        //close the connection to the teacher database
        mysqli_close($teacherDB);

        //<--- PART 1.1: connect to the authorized users database and insert the registered teacher
        //using the AuthorizedUsers.csv specific format

        //creating the Authorized Users Masterlist folder
        //take note of the slashes and the period!
        $authorizedUsersFolder = "./Authorized User Masterlist/";
        if (file_exists($authorizedUsersFolder)) {
            //do nothing
        } else {
            //https://www.php.net/manual/en/function.mkdir.php
            mkdir($authorizedUsersFolder, 0777, true);
        }

        //create the "authorized users" database if it does not exist
        $dbConnect = mysqli_connect("localhost", "root", "");
        $dbName = "authorized users";

        if ($dbConnect->connect_error){
            //die() kinda functions like an exit() function
            exit('Error connecting to the server.');
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $sqlStatement = $dbConnect->prepare("CREATE DATABASE IF NOT EXISTS `$dbName`");
        $sqlStatement->execute();
        $sqlStatement->close();
        mysqli_close($dbConnect);

        //connect to authorized users database
        $authorizedUsersDB = mysqli_connect("localhost", "root", "", $dbName);

        if ($authorizedUsersDB->connect_error) {
            //die() kinda functions like an exit() function
            exit('Error connecting to the authorized users database in the server.');
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        //create the "users" table on the "authorized users" database if it does not exist
        $sqlStatement = $authorizedUsersDB->prepare("CREATE TABLE IF NOT EXISTS users (
                    RFID VARCHAR(255),
                    IDNumber VARCHAR(255) PRIMARY KEY,
                    Lastname VARCHAR(255),
                    Firstname VARCHAR(255)) 
                    DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $sqlStatement->execute();
        $sqlStatement->close();

        //insert the newly registered teacher to the authorized users database
        //the rfid variable is empty by default to the database
        $RFID = "";

        $sqlStatement = $authorizedUsersDB->prepare("INSERT INTO `users` (RFID, IDNumber, Lastname, Firstname) VALUES (?,?,?,?)");
        $sqlStatement->bind_param("ssss", $RFID, $IDNum, $lastName, $firstName);
        $sqlStatement->execute();

        //<!-- THIS PART WILL EXECUTE IF AUTHORIZEDUSERS.CSV FILE DOES NOT EXIST -->
        if (!file_exists('./Authorized User Masterlist/AuthorizedUsers.csv')) {
            //fopen creates the file if it does not exist. mode is set to write
            $authorizedUsersCSV = fopen("./Authorized User Masterlist/AuthorizedUsers.csv", "w");
            //put utf-8 byte order mark to set the csv file as csv utf-8
            $BOM = chr(0xEF) . chr(0xBB) . chr(0xBF);
            fputs($authorizedUsersCSV, $BOM);

            //write the titles header in the first row of the csv file
            //implode function: array to string
            $header_csv = array("RFID", "IDNumber", "Lastname", "Firstname");
            //\r is moving the cursor to the leftmost position. \n is new line
            fwrite($authorizedUsersCSV, implode(",", $header_csv) . "\r\n");


            //prepare the array for inserting to the authorized users csv file. RFID is empty by default
            $teacher_csv[0] = array("", $IDNum, $lastName, $firstName);
            //\r is moving the cursor to the leftmost position. \n is new line
            fwrite($authorizedUsersCSV, utf8_encode(implode(",", $teacher_csv[0])) . "\r\n");
            fclose($authorizedUsersCSV);
        }
        //<-- THIS PART WILL EXECUTE IF THE AUTHORIZEDUSERS.CSV FILE EXISTS -->
        else{
            //open the file as reading it to get the current contents. "r" mode places
            //file pointer to the start of the file
            $authorizedUsersCSV = fopen("./Authorized User Masterlist/AuthorizedUsers.csv", "r");
            $authorizedUsersCSVArr = array();

            //skips the first reading of the first line from csv file (first line is the header (rfid, id, last name, first name)
            //https://stackoverflow.com/questions/10901113/php-dynamically-create-csv-skip-the-first-line-of-a-csv-file
            fgetcsv($authorizedUsersCSV);
            //continue to read the authorized users csv to put them into the array
            while (($data = fgetcsv($authorizedUsersCSV, 1000, ',')) !== FALSE) {
                $authorizedUsersCSVArr[] = $data;
            }
            fclose($authorizedUsersCSV);

            //update the database with the current data from the authorized users array
            $sqlStatement = $authorizedUsersDB->prepare("UPDATE users SET RFID = ? WHERE IDNumber = ?");
            $sqlStatement->bind_param("ss", $rfidNum, $idNum);

            foreach($authorizedUsersCSVArr as $row){
                $rfid = $row[0];
                $idNum = $row[1];
                $sqlStatement->execute();
            }
            $sqlStatement->close();

            //after updating the authorized users database, it will insert the registered teacher
            //"a" mode places file pointer to the END of the file
            $authorizedUsersCSV = fopen("./Authorized User Masterlist/AuthorizedUsers.csv", "a");

            $teacher_csv[0] = array("", $IDNum, $lastName, $firstName);
            fwrite($authorizedUsersCSV, utf8_encode(implode(",", $teacher_csv[0])) . "\r\n");
            fclose($authorizedUsersCSV);
        }
        mysqli_close($authorizedUsersDB);
    }

    //<--- PART 2: make the folder of the corresponding teacher that registered --->
    //folder name should be "./firstName lastName/". take note of the slashes and the period
    $teacherFolderName = "./" . $firstName . " " . $lastName . "/";

    if (file_exists($teacherFolderName)) {
        //do nothing
    } else {
        //https://www.php.net/manual/en/function.mkdir.php
        mkdir($teacherFolderName, 0777, true);
    }

    //<--- PART 3: Creating the registered teacher's database --->
    //connect to server
    $creatingTeacherDB = mysqli_connect("localhost", "root", "");

    if ($creatingTeacherDB->connect_error) {
        //die() kinda functions like an exit() function
        exit('Error connecting to the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //create the registered teacher database if it does not exist
    $registeredTeacher = $firstName . " " . $lastName;
    //take note of the small quotations! use the symbol before the 1 key on keyboard (`)
    //solution: https://stackoverflow.com/questions/21032122/how-to-name-a-mysql-database-after-user-input
    $sqlStatement = $creatingTeacherDB->prepare("CREATE DATABASE IF NOT EXISTS `$registeredTeacher`");
    $sqlStatement->execute();
    $sqlStatement->close();

    mysqli_close($creatingTeacherDB);

    $_SESSION["registerTeacherMsg"] = "Teacher registration successful!";
    header("Location: register-teacher.php");
}

if (isset ($_POST["return-to-admin-main"])){
    header("location: admin-main.php");
}
?>
