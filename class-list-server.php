<!-- reference page: https://code.tutsplus.com/tutorials/how-to-upload-a-file-in-php-with-example--cms-31763 -->

<?php
header('Content-Encoding: utf-8');
header('Content-Type: text/csv; charset=utf-8mb4');

//session_start();
	$conn = new mysqli("localhost", "root", "", "temp");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT val FROM temptb WHERE varname = 'IDNum' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$tempvar1 = $row["val"];
		mysqli_close($conn);
	}
//print_r($_SESSION);

$message = '';
if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload Class List and Set Configurations') {
    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
        // get details of the uploaded file
        $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
        $fileName = $_FILES['uploadedFile']['name'];
        $fileSize = $_FILES['uploadedFile']['size'];
        $fileType = $_FILES['uploadedFile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('csv'); //array('txt', 'xls', 'csv');

        if (!in_array($fileExtension, $allowedfileExtensions)){
            $message = 'The file uploaded is not a .csv file. Please make sure the class list file uploaded is in the .csv format.';
        }
        else {
            //<!--- GET THE "CLASS LIST" PART OF THE CLASS LIST FILE --->
            //set the row to get the schedule and the time in the class list
            $row = 1;

            //counters for the loop and array index
            $i = 1;
            $arrayCount = 0;

            //create class list check array
            $classListCheck = array();

            //row first, then column
            if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($i >= $row) {
                        //should be like that. ex. if get 2nd column then
                        //$column = 1; $column < 2
                        for ($column = 6; $column < 7; $column++) {
                            //next line automatically assigns them to the designated array indexes
                            //explode function: string to array via the separator/delimiter
                            $classListCheck[$arrayCount] = $data[$column];
                            $arrayCount++;
                        }
                    }
                    $i++;
                }
            }
            fclose($handle);

            //<!--- GET THE "UNIVERSITY OF SAN CARLOS" PART OF CLASS LIST --->
            //set the row to get the schedule and the time in the class list
            $row = 1;

            //counters for the loop and array index
            $i = 1;
            $arrayCount = 0;

            //create schedule array
            $uniCheck = array();

            //row first, then column
            if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($i >= $row) {
                        //should be like that. ex. if get 2nd column then
                        //$column = 1; $column < 2
                        for ($column = 8; $column < 9; $column++) {
                            //next line automatically assigns them to the designated array indexes
                            //explode function: string to array via the separator/delimiter
                            $uniCheck[$arrayCount] = $data[$column];
                            $arrayCount++;
                        }
                    }
                    $i++;
                }
            }
            fclose($handle);


            //<!--- [IF STMT] THIS PART CHECKS IF THE UPLOADED FILE IS REALLY THE CLASS LIST--->
            if (($classListCheck[0] == "Class List") && ($uniCheck[1] == "UNIVERSITY OF SAN CARLOS"))  {
                //<!--- MOVE THE UPLOADED FILE TO THE CURRENTLY LOGGED-IN USER'S FOLDER --->
                /* similar to this: https://www.javatpoint.com/php-mysql-login-system */

                //database credentials, running MySQL with default setting (user 'root' with no password)
                //attempt to connect to MySQL "teacher" database
                $databaseLink = mysqli_connect('localhost', 'root', '', 'teacher');

                //check the connection to the database
                if ($databaseLink->connect_error) {
                    //die() kinda functions like an exit() function
                    exit('Error connecting to the server.');
                }
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                //on the 'teacher' database, 'login' table in phpmyadmin, search for the id number in the session array
                //mysql and sessions (use curly braces) https://stackoverflow.com/questions/5746614/session-variable-in-mysql-query
                $sqlStatement = $databaseLink->prepare("SELECT * FROM login WHERE IDNumber = ?");
                $sqlStatement->bind_param("s", $tempvar1);
                $sqlStatement->execute();

                //<!---THIS PART CREATES THE TEACHER NAME FOR THE FILEPATH --->
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
                mysqli_close($databaseLink);

                $teamTeachPartner = $_POST['partner'];
                //echo "ID Number Selected: " . $teamTeachPartner;

                // sanitize file-name
                //$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                //$newFileName = $fileName . '.' . $fileExtension;

                //<!--- THIS PART CREATES THE FILENAME OF THE CLASS LISTS --->
                //<!--- GET THE SCHEDULE PART OF THE FILENAME --->
                //set the row to get the schedule and the time in the class list
                $row = 4;

                //counters for the loop and array index
                $i = 1;
                $arrayCount = 0;

                //create schedule array
                $schedule = array();

                //row first, then column
                if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if ($i >= $row) {
                            //should be like that. ex. if get 2nd column then
                            //$column = 1; $column < 2
                            for ($column = 4; $column < 5; $column++) {
                                //next line automatically assigns them to the designated array indexes
                                //explode function: string to array via the separator/delimiter
                                $schedule[] = explode(" ", $data[$column]);
                            }
                        }
                        $i++;
                    }
                }
                fclose($handle);

                //<!--- GET THE TIME PART OF THE FILENAME --->
                //set the row to get the schedule and the time in the class list
                $row = 4;

                //counters for the loop and array index
                $i = 1;
                $arrayCount = 0;

                //create time array
                $time = array();

                //row first, then column
                if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if ($i >= $row) {
                            //should be like that. ex. if get 2nd column then
                            //$column = 1; $column < 2
                            for ($column = 9; $column < 10; $column++) {
                                //next line automatically assigns them to the designated array indexes
                                $time[] = explode(" ", $data[$column]);
                            }
                        }
                        $i++;
                    }
                }
                fclose($handle);

                //removes any special characters in the schedule and time strings (the file can't be created if they are there)
                //$finalSchedule = str_replace(array('\'', '"', ',', ';', ':', '<', '>'), '', $schedule[0][0]);
                //formatting is: COURSE CODE COURSE NUMBER_G(GROUP NUMBER)
                $finalSchedule = $schedule[0][2] . " " . $schedule[0][3] . "_G" . $schedule[0][1];
                $startingTime = str_replace(array('\'', '"', ',', ';', ':', '<', '>'), '', $time[0][2]);
                $endingTime = str_replace(array('\'', '"', ',', ';', ':', '<', '>'), '', $time[0][5]);
                //formatting is: STARTING TIME AM/PM - ENDING TIME AM/PM
                $finalTime = $time[0][0] . " - " . $startingTime . " " . $time[0][3] . " - " . $endingTime . " ". $time[0][6];
                //$finalTime = trim($finalTime); //removes whitespaces before and after the time string

                //uses the teamteach selection to see if the file needs to prepend a "TM -" before the new filename
                if ($teamTeachPartner != '0') {
                    //$newFileName = "TM - " . $finalSchedule . "_" . $finalTime;
                    //course code - group number - schedule
                    $newFileName = "TM - " . $finalSchedule . "_" . $finalTime;
                } else {
                    $newFileName = $finalSchedule . "_" . $finalTime;
                }

                // directory in which the uploaded file will be moved
                $uploadFileDir = './' . $firstName . " " . $lastName . '/';

                //concatenate file directory to file name. i.e. ./firstName lastName/filename.csv
                $dest_path_temp = $uploadFileDir . $newFileName . ".csv";

                //<!-- THIS PART MOVES THE CSV FILE FROM THE TEMPORARY PATH TO THE SET DESTINATION PATH -->
                if (move_uploaded_file($fileTmpPath, $dest_path_temp)) {
                    //answer is a combo of both links!
                    //https://stackoverflow.com/questions/35740176/read-specific-column-in-csv-to-array
                    //https://webdiretto.it/to-extract-single-column-values-from-csv-file-php/
                    //set the row (see class list formatting for the reason!)
                    $row = 6;

                    //counters for the loop and array index
                    $i = 1;
                    $arrayCount = 0;

                    //multidimensional arrays to store the student data
                    $names = array();
                    $idNumbers = array();

                    //row first, then column
                    //<!--- THIS PART IS FOR STORING THE NAMES TO THE NAMES ARRAY --->
                    if (($handle = fopen($dest_path_temp, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            if ($i >= $row) {
                                //should be like that. example: if you want to get the 2nd column then
                                //$column = 1; $column < 2
                                for ($column = 5; $column < 6; $column++) {
                                    //next line automatically assigns them to the designated array indexes
                                    $names[$arrayCount] = explode(",", $data[$column]);
                                    //trim removes whitespace!
                                    $names[$arrayCount][1] = trim($names[$arrayCount][1]);
                                    $arrayCount++;
                                }
                            }
                            $i++;
                        }
                    }
                    fclose($handle);

                    //<!--- THIS PART IS FOR STORING THE ID NUMBER OF THE STUDENTS! --->
                    //setting the row to start at the 6th (see class list formatting for the reason)
                    $row = 6;

                    //counters for the array index and the loop
                    $i = 1;
                    $arrayCount = 0;

                    //row first, then column
                    if (($handle = fopen($dest_path_temp, "r")) !== FALSE) {
                        while (($data2 = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            if ($i >= $row) {
                                //should be like that. example: if you want to get the 2nd column then
                                //$column = 1; $column < 2
                                for ($column = 1; $column < 2; $column++) {
                                    $idNumbers[$arrayCount] = trim($data2[$column]);
                                    $arrayCount++;
                                }
                            }
                            $i++;
                        }
                    }
                    fclose($handle);

                    //<!--- THIS PART CREATES THE FORMATTED CLASS LIST CSV FILE --->
                    $idNumIndex = 0;
                    //prepend the id numbers of the students to the names array!
                    foreach ($names as &$line) { //& reference: https://stackoverflow.com/questions/25198792/array-unshift-in-multidimensional-array-insert-at-first-element-in-all-arrays
                        array_unshift($line, $idNumbers[$idNumIndex]);
                        $idNumIndex++;
                    }

                    //make a copy of the array $name to use in updating and creating the masterlist csv file
                    $namesCopy = array();
                    $namesCopy = $names;

                    //prepare header titles array at the first row of the class list csv file
                    $header_csv = array("IDNumber", "Lastname", "Firstname");

                    //create the formatted class list csv in the logged-in user's folder and write the prepared array to the file
                    //this process overwrites the uploaded class list and will be formatted to the id number, last name and first name standard
                    $handle = fopen($dest_path_temp, "w");
                    //write UTF-8 byte order mark for outputting special characters to the csv file
                    //from: https://stackoverflow.com/questions/4348802/how-can-i-output-a-utf-8-csv-in-php-that-excel-will-read-properly
                    $BOM = chr(0xEF) . chr(0xBB) . chr(0xBF);
                    fputs($handle, $BOM);

                    //write the header titles to the first row of the csv
                    fwrite($handle, implode(",", $header_csv) . "\r\n");

                    //use for loop pls. foreach loop somehow duplicates the second to last
                    //row values for some reason https://stackoverflow.com/questions/1293896/php-array-printing-using-a-loop

                    //counter for the while loop
                    $i = 0;

                    //write to the prepared class list csv file
                    while ($i < count($names)) {
                        //IMPORTANT! use utf8_encode ONCE only to prevent wonky characters when outputting and writing!
                        fwrite($handle, utf8_encode(implode(",", $names[$i])) . "\r\n");
                        $i++;
                    }
                    /*
                    foreach ($names as $line){
                        fputcsv($handle, $line,',');
                    }
                    */
                    fclose($handle);

                    //<!--- THIS PART CREATES THE FORMATTED CLASS LIST CSV FILE --->
                    $idNumIndex = 0;
                    //prepend the id numbers of the students to the names array!
                    foreach ($names as &$line) { //& reference: https://stackoverflow.com/questions/25198792/array-unshift-in-multidimensional-array-insert-at-first-element-in-all-arrays
                        array_unshift($line, $idNumbers[$idNumIndex]);
                        $idNumIndex++;
                    }

                    //make a copy of the array $name to use in updating and creating the masterlist csv file
                    $namesCopy = array();
                    $namesCopy = $names;

                    //prepare header titles array at the first row of the class list csv file
                    $header_csv = array("IDNumber", "Lastname", "Firstname");

                    //create the formatted class list csv in the logged-in user's folder and write the prepared array to the file
                    //this process overwrites the uploaded class list and will be formatted to the id number, last name and first name standard
                    $handle = fopen($dest_path_temp, "w");
                    //write UTF-8 byte order mark for outputting special characters to the csv file
                    //from: https://stackoverflow.com/questions/4348802/how-can-i-output-a-utf-8-csv-in-php-that-excel-will-read-properly
                    $BOM = chr(0xEF) . chr(0xBB) . chr(0xBF);
                    fputs($handle, $BOM);

                    //write the header titles to the first row of the csv
                    fwrite($handle, implode(",", $header_csv) . "\r\n");

                    //use for loop pls. foreach loop somehow duplicates the second to last
                    //row values for some reason https://stackoverflow.com/questions/1293896/php-array-printing-using-a-loop

                    //counter for the while loop
                    $i = 0;

                    //write to the prepared class list csv file
                    while ($i < count($names)) {
                        //IMPORTANT! use utf8_encode ONCE only to prevent wonky characters when outputting and writing!
                        fwrite($handle, utf8_encode(implode(",", $names[$i])) . "\r\n");
                        $i++;
                    }
                    /*
                    foreach ($names as $line){
                        fputcsv($handle, $line,',');
                    }
                    */
                    fclose($handle);

                    //<!--- THIS PART WILL BE EXECUTED IF A PARTNER IS SELECTED ASIDE FROM "This class is not a team teach course"
                    if ($teamTeachPartner != '0') {
                        //<!--- PART 1: copying the formatted class list to the partner's folder --->
                        //move the created file to the currently logged-in user's designated folder
                        /* similar to this: https://www.javatpoint.com/php-mysql-login-system */

                        //database credentials, running MySQL with default setting (user 'root' with no password)
                        //attempt to connect to MySQL "teacher" database
                        $databaseLink = mysqli_connect('localhost', 'root', '', 'teacher');

                        //check the connection to the database
                        if ($databaseLink->connect_error) {
                            //die() kinda functions like an exit() function
                            exit('Error connecting to the teacher database in the server.');
                        }
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                        //on the 'teacher' database, 'login' table in phpmyadmin, search for the id number in the session array (of the logged-in user)
                        //mysql and sessions (use curly braces) https://stackoverflow.com/questions/5746614/session-variable-in-mysql-query
                        $sqlStatement = $databaseLink->prepare("SELECT * FROM login WHERE IDNumber = ?");
                        $sqlStatement->bind_param("s", $teamTeachPartner);
                        $sqlStatement->execute();
                        //$result = mysqli_query($databaseLink, $sql);
                        //$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        //$count = mysqli_num_rows($result);

                        //<!--- THIS PART FETCHES THE PARTNER'S FIRST AND LAST NAME TO CREATE THE FILE PATH --->
                        //from: https://websitebeaver.com/prepared-statements-in-php-mysqli-to-prevent-sql-injection
                        //this uses sql prepared statements
                        $result = $sqlStatement->get_result();
                        if ($result->num_rows == 0) {
                            exit('The teacher is not registered in the database. Failed in copying the file to the partner folder.');
                        }
                        while ($row = $result->fetch_assoc()) {
                            //set the $row[""] to the column you want to use
                            $partnerFirstName = $row["firstName"];
                            $partnerLastName = $row["lastName"];
                        }
                        $sqlStatement->close();
                        mysqli_close($databaseLink);

                        if (!copy($dest_path_temp, './' . $partnerFirstName . " " . $partnerLastName . '/' . $newFileName . ".csv")) {
                            die("Copying to the selected partner's folder failed.");
                        }
                        else {
                            echo("The formatted class list has been copied to the partner's folder!");
                        }

                        //<!--- PART 2: creating teamTeach database with teamteach table --->
                        $teamTeachDB = mysqli_connect('localhost', 'root', '');
                        $dbName = "teamteach";

                        //check the connection to the localhost host
                        if ($teamTeachDB->connect_error) {
                            //die() kinda functions like an exit() function
                            exit('Error connecting to the server.');
                        }
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                        //create the "teamteach" database if it does not exist
                        //mysql and sessions (use curly braces) https://stackoverflow.com/questions/5746614/session-variable-in-mysql-query
                        $sqlStatement = $teamTeachDB->prepare("CREATE DATABASE IF NOT EXISTS " . $dbName);
                        $sqlStatement->execute();
                        $sqlStatement->close();
                        mysqli_close($teamTeachDB);

                        //connect to the teamteach database
                        $teamTeachDB = mysqli_connect('localhost', 'root', '', 'teamteach');
                        if ($teamTeachDB->connect_error) {
                            //die() kinda functions like an exit() function
                            exit('Error connecting to the teamteach database in the server.');
                        }
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                        //create the "teamteach" table in the "teamteach" database if it does not exist, while setting utf-8 formatting
                        $sqlStatement = $teamTeachDB->prepare("CREATE TABLE IF NOT EXISTS teamteach(
                                    Teacher VARCHAR(255),
                                    Partner VARCHAR(255),
                                    Course VARCHAR(255))
                                    DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                        $sqlStatement->execute();

                        //create the teacher name for inserting the currently logged-in user's name to the teamteach database
                        $teacherFullname = $firstName . " " . $lastName;
                        //create the partner's name for inserting them to the teamteach database
                        $partnerFullname = $partnerFirstName . " " . $partnerLastName;

                        //[CHECK] what if same teacher but different courses/partners
                        $teamTeachSchedule = $schedule[0][2] . " " . $schedule[0][3] . "-G" . $schedule[0][1];
                        $sqlStatement = $teamTeachDB->prepare("INSERT INTO teamteach(Teacher, Partner, Course) VALUES ('$teacherFullname','$partnerFullname','$teamTeachSchedule')");
                        $sqlStatement->execute();
                        $sqlStatement->close();

                        //close connection to the teamteach database
                        mysqli_close($teamTeachDB);
                    }

                    //<!--- THIS PART IS TO CREATE/UPDATE STUDENT MASTERLIST DATABASE AND CSV FILE --->

                    //check if file exists: https://www.w3schools.com/php/func_filesystem_file_exists.asp

                    //<!--- CREATE THE STUDENT MASTERLIST DIRECTORY IF IT DOES NOT EXIST --->
                    if (!file_exists("./Student Masterlist/")){
                        mkdir("./Student Masterlist/", 0777, true);
                    }

                    //<!--- THIS PART CREATES THE STUDENT MASTERLIST CSV FILE IF IT DOES NOT EXIST AND INSERTS THE CLASS LIST ARRAY --->
                    //if the studentMasterlist.csv does not exist, make the masterlist database with student table,
                    //insert the first class list contents to the database, and create the csv file
                    if (!file_exists('./Student Masterlist/StudentMasterlist.csv')) {
                        $studentMasterlistDB = mysqli_connect('localhost', 'root', '');
                        $dbName = "masterlist";

                        //check the connection to the masterlist database
                        if ($studentMasterlistDB->connect_error) {
                            //die() kinda functions like an exit() function
                            exit('Error connecting to the masterlist database in the server.');
                        }
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                        //create the database "masterlist"
                        $sqlStatement = $studentMasterlistDB->prepare("CREATE DATABASE IF NOT EXISTS " . $dbName);
                        $sqlStatement->execute();
                        $sqlStatement->close();

                        //close connection to the "masterlist" database
                        mysqli_close($studentMasterlistDB);

                        //create a connection to the "masterlist" database
                        $studentMasterlistDB = mysqli_connect('localhost', 'root', '', 'masterlist');

                        //check if the connection is established
                        if ($studentMasterlistDB->connect_error) {
                            //die() kinda functions like an exit() function
                            exit('Error connecting to the masterlist database in the server.');
                        }
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                        //create the "student" table if it does not exist. with utf-8 format
                        $sqlStatement = $studentMasterlistDB->prepare("CREATE TABLE IF NOT EXISTS student(
                                    RFID VARCHAR(255),
                                    ID VARCHAR(255) PRIMARY KEY,
                                    Lastname VARCHAR(255),
                                    Firstname VARCHAR(255))
                                    DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                        $sqlStatement->execute();
                        $sqlStatement->close();

                        //prepend a blank value for the rfid column in the names (class list) array
                        foreach ($namesCopy as &$line) { //& reference: https://stackoverflow.com/questions/25198792/array-unshift-in-multidimensional-array-insert-at-first-element-in-all-arrays
                            array_unshift($line, "");
                        }

                        //insert the class list array to the student table in the masterlist database
                        //insert multidimensional array to mysql https://stackoverflow.com/questions/7746720/inserting-a-multi-dimensional-php-array-into-a-mysql-database
                        //try: https://stackoverflow.com/questions/39818418/using-php-to-insert-array-into-mysql-database
                        $sqlStatement = $studentMasterlistDB->prepare("INSERT INTO `student` (RFID, ID, Lastname, Firstname) VALUES (?,?,?,?)");
                        $sqlStatement->bind_param("ssss", $rfid, $id, $lastnm, $firstnm);

                        //loop each execution to insert each student data
                        foreach ($namesCopy as $row) {
                            $rfid = $row[0];
                            $id = $row[1];
                            $lastnm = utf8_encode($row[2]); //IMPORTANT! use utf8_encode once only in every database to prevent wonky display of special characters
                            $firstnm = utf8_encode($row[3]);
                            $sqlStatement->execute();
                        }
                        $sqlStatement->close();
                        mysqli_close($studentMasterlistDB);

                        //echo "Done inserting to masterlist database!" . "<br/>";

                        //opens the StudentMasterlist.csv file. it will create it if it does not exist. puts pointer to the start of the file
                        $handle = fopen("./Student Masterlist/StudentMasterlist.csv", "w");
                        //put utf-8 byte order mark to set the csv file as csv utf-8
                        $BOM = chr(0xEF) . chr(0xBB) . chr(0xBF);
                        fputs($handle, $BOM);

                        //write the titles header in the first row of the csv file
                        //implode function: array to string
                        $header_csv = array("RFID", "IDNumber", "Lastname", "Firstname");
                        fwrite($handle, implode(",", $header_csv) . "\r\n");

                        //write to the prepared masterlist csv file!
                        $i = 0;
                        while ($i < count($namesCopy)) {
                            //\r is moving the cursor to the leftmost position. \n is new line
                            fwrite($handle, utf8_encode(implode(",", $namesCopy[$i])) . "\r\n");
                            $i++;
                        }
                    }

                    //<!--- IF THE STUDENT MASTERLIST DIRECTORY EXISTS, UPDATE THE EXISTING STUDENT MASTERLIST CSV FILE --->

                    else {
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
                        $studentMasterlistDB = mysqli_connect('localhost', 'root', '', 'masterlist');

                        //check the connection to the database
                        if ($studentMasterlistDB->connect_error) {
                            //die() kinda functions like an exit() function
                            exit('Error connecting to the masterlist database in the server.');
                        }
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                        //prepare the query to update the rfid column in the student table using the primary key ID
                        $sqlStatement = $studentMasterlistDB->prepare("UPDATE student SET RFID = ? WHERE ID = ?");
                        $sqlStatement->bind_param("ss", $rfid,  $id);

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

                        //echo "Done updating masterlist database from masterlist csv array!" . "<br/>";

                        //prepare the query to insert the class list array contents to the masterlist database with no duplicates
                        $sqlStatement = $studentMasterlistDB->prepare("INSERT IGNORE INTO student (ID, Lastname, Firstname) VALUES (?,?,?)");
                        $sqlStatement->bind_param("sss", $idnum, $lastnm, $firstnm);

                        //insert the class list array to the database while skipping the duplicate ones
                        foreach ($namesCopy as $row) {
                            //id number, last name, first name
                            //if NULL (empty) is in the particular rfid column, it will be skipped. hence the indexing starts at the id number (zero) to the firstnm (2)
                            $idnum = $row[0];
                            $lastnm = utf8_encode($row[1]);
                            $firstnm = utf8_encode($row[2]);
                            $sqlStatement->execute();
                        }
                        $sqlStatement->close();
                        /*
                        echo "<pre>";
                        print_r ($namesCopy);
                        echo "</pre>";
                        */

                        //echo "Done inserting non-duplicate class list array values to the masterlist database!" . "<br/>";

                        //get the current masterlist database contents while sorting by id number! and putting them to an array
                        $query = mysqli_query($studentMasterlistDB, "SELECT * FROM student ORDER BY ID ASC");
                        $masterlistDatabaseArr = array();

                        while($row = mysqli_fetch_assoc($query)){
                            $masterlistDatabaseArr[] = $row;
                        }

                        //open the student masterlist csv file
                        $handle = fopen("./Student Masterlist/StudentMasterlist.csv", "w");
                        //write the utf-8 byte order mark
                        $BOM = chr(0xEF) . chr(0xBB) . chr(0xBF);
                        fputs($handle, $BOM);

                        //write the header titles to the masterlist csv file
                        $header_csv = array("RFID", "IDNumber", "Lastname", "Firstname");
                        fwrite($handle, implode(",", $header_csv) . "\r\n");

                        //fputcsv has double quotations within the strings WITH SPACES. normal behavior!
                        //https://stackoverflow.com/questions/24591767/php-reading-csv-issue-with-double-quotes
                        //https://stackoverflow.com/questions/11516811/avoid-default-quotes-from-csv-file-when-using-fputcsv
                        //working solution: https://community.adobe.com/t5/coding-corner-discussions/strip-quotes-from-fputcsv-results/m-p/7763343

                        //write the updated masterlist to the csv file
                        $i = 0;
                        while ($i < count($masterlistDatabaseArr)) {
                            fwrite($handle, implode(",", $masterlistDatabaseArr[$i]) . "\r\n");
                            $i++;
                        }
                        fclose($handle);

                        //close the connection to the student masterlist database
                        mysqli_close($studentMasterlistDB);
                    }

                    //<!--- THIS PART CREATES THE ACCOMPANYING CONFIGURATION FILE! --->

                    $markTeacher = $_POST["mark-teacher"];
                    $teacherLate = $_POST["teacher-late"];
                    $teacherAbsent = $_POST["teacher-absent"];
                    $studentAttendance = $_POST["student-attendance"];
                    $studentLate = $_POST["student-late"];
                    $studentAbsent = $_POST["student-absent"];
                    $classStart = $time[0][2] . " " . $time[0][3];
                    $classEnd = $time[0][5] . " " . $time[0][6];

                    //https://stackoverflow.com/questions/15501463/creating-csv-file-with-php
                    //working with date and time - https://code.tutsplus.com/tutorials/working-with-date-and-time-in-php--cms-31768
                    //$hourStart = date('G:i', strtotime($classStart));
                    //$hourEnd = date('G:i', strtotime($classEnd));

                    $hourStart = date('G:i', strtotime($classStart));
                    $hourEnd = date('G:i', strtotime($classEnd));

                    //str_replace(find, replace, string, count). count is optional
                    //$hourStart = str_replace(':', '', $hourStart);
                    //$hourEnd = str_replace(":", '', $hourEnd);

                    //'a' mode lets file pointer to the end of file
                    //one array = one row!
                    $config_csv[0] = array("MARK TEACHER ATTENDANCE", 'mark-teacher' => $markTeacher);
                    $config_csv[1] = array("TEACHER LATE", 'teacher-late' => $teacherLate);
                    $config_csv[2] = array("TEACHER ABSENT", 'teacher-absent' => $teacherAbsent);
                    $config_csv[3] = array("BASE STUDENT ATTENDANCE ON TEACHER TAP", 'base-student' => $studentAttendance);
                    $config_csv[4] = array("STUDENT LATE", 'student-late' => $studentLate);
                    $config_csv[5] = array("STUDENT ABSENT", 'student-absent' => $studentAbsent);
                    $config_csv[6] = array("CLASS START", 'class-start' => $hourStart);
                    $config_csv[7] = array("CLASS END", 'class-end' => $hourEnd);

                    $configFile = $newFileName . "_config.csv";

                    $file_open = fopen($configFile, "w");
                    $i = 0;
                    while ($i < count($config_csv)) {
                        //\r is moving the cursor to the leftmost position. \n is new line
                        fwrite($file_open, utf8_encode(implode(",", $config_csv[$i])) . "\r\n");
                        $i++;
                    }
                    fclose($file_open);

                    //move the file to the logged-in user's folder
                    rename($configFile, "./" . $firstName . " " . $lastName . "/" . $configFile);

                    if ($teamTeachPartner != '0'){
                        if (!copy($dest_path_temp, './' . $partnerFirstName . " " . $partnerLastName . '/' . $configFile)) {
                            die("Copying the configuration file to the selected partner's folder failed.");
                        }
                        else {
                            echo("The configuration file has been copied to the partner's folder!");
                        }
                    }



                    $message = "Uploading done!";

                } else{
                    $message = "Uploading to the teacher's folder failed";
                }
            }
            else{
                $message = "The file uploaded is not a valid class list. Please make sure the class list is downloaded directly from the ISMIS website.";
            }

        }
    } else{
            $message = "An error was encountered in uploading the file!";
    }
} else{
    $message = "File failed to upload!!! Check the button settings";
}
	$conn = new mysqli("localhost", "root", "", "temp");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO temptb (varname, val) VALUES ('message', '$message')";
	
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
//$_SESSION['message'] = $message;

header("Location: class-list-upload.php");
?>