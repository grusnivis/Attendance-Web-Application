<?php
ob_start();

header('Content-Encoding: utf-8');
header('Content-Type: text/csv; charset=utf-8mb4');

$firstName = '';
$lastName = '';
$IDNum = '';
$password = '';
$email = '';
$conn = new mysqli("localhost", "root", "", "temp");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "INSERT INTO temptb (varname, val) VALUES ('registerTeacherMsg', '')";

if (mysqli_query($conn, $sql)) {
    mysqli_close($conn);
}


if (isset ($_POST["register"])) {
    //ucfirst - returns the first character of the string capitalized (https://www.php.net/manual/en/function.ucfirst.php)
    $firstName = strtoupper($_POST["first-name"]);
    $lastName = strtoupper($_POST["last-name"]);
    $IDNum = strtoupper($_POST["IDNum"]); //retains the numbers
    //$password = $_POST["password"];
    $email = $_POST["email"]; //already validated if its in email format through bootstrap

    //creates randomized password
    //https://www.geeksforgeeks.org/php-random_bytes-function/
    //https://paragonie.com/blog/2015/07/how-safely-generate-random-strings-and-integers-in-php
    try {
        $password = bin2hex(random_bytes('4'));
    } catch (Exception $e) {
        echo "Failed to find randomness for password.";
    }

    //turns the password into a hash for security
    $hashedPassWord = password_hash($password, PASSWORD_BCRYPT);

    //<--- PART 1: insert username and password fields into database. Database: Teacher. Table: login --->
    //(from admin-database-config.php)

    //create the database if it does not exist
    $dbConnect = mysqli_connect("localhost", "root", "");
    $dbName = "teacher";

    if ($dbConnect->connect_error) {
        //die() kinda functions like an exit() function
        exit('Error connecting to the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //create the "teacher" database if it does not exist
    $sqlStatement = $dbConnect->prepare("CREATE DATABASE IF NOT EXISTS teacher DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $sqlStatement->execute();
    $sqlStatement->close();

    mysqli_close($dbConnect);

    //connect to teacher database
    $teacherDB = mysqli_connect("localhost", "root", "", $dbName);

    if ($teacherDB->connect_error) {
        //die() kinda functions like an exit() function
        exit('Error connecting to the teacher database in the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //create the "login" table if it does not exist
    //columns are: IDNumber, password, firstName, lastName, email
    //character is set to utf8mb4_unicode_ci on default
    $createLoginTableStmt = $teacherDB->prepare("CREATE TABLE IF NOT EXISTS login(
            IDNumber VARCHAR(255) PRIMARY KEY NOT NULL,
            password VARCHAR(255) NOT NULL,
            firstName VARCHAR(255) NOT NULL,
            lastName VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            isPasswordChanged BOOLEAN NOT NULL)
            DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $createLoginTableStmt->execute();
    $createLoginTableStmt->close();

    //prepare the query to insert the registered teacher to the teacher database.
    //it does duplicate checking first to see if the teacher is registered already
    $checkTeacherDBDuplicate = "SELECT * FROM login WHERE IDNumber = '$IDNum'";
    $statementDuplicate = mysqli_query($teacherDB, $checkTeacherDBDuplicate);
    $rowResults = mysqli_fetch_array($statementDuplicate, MYSQLI_ASSOC);
    $countDup = mysqli_num_rows($statementDuplicate);
    //$countDup = $statementDuplicate->num_rows;


    //if there is a duplicate id number
    if ($countDup >= 1) {
        $conn = new mysqli("localhost", "root", "", "temp");
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO temptb (varname, val) VALUES ('registerTeacherMsg', 'The ID Number is already registered in the database.')";

        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);
        }
//        $_SESSION['registerTeacherMsg'] = "The ID number is already registered in the database.";
        mysqli_close($teacherDB);
        //returns to the register teacher page. it "aborts" the rest of the process
        header("Location: register-teacher.php");
        ob_end_clean();
    } //if there is NO duplicate id number, then you insert the teacher data to the login table
    else {
        $passwordChanged = 0;

        $statementInsert = $teacherDB->prepare("INSERT INTO `login` (IDNumber, password, firstName, lastName, email, isPasswordChanged) VALUES (?,?,?,?,?,?)");
        $statementInsert->bind_param("sssssi", $IDNum, $hashedPassWord, $firstName, $lastName, $email, $passwordChanged);
        $statementInsert->execute();
        $statementInsert->close();

        //close the connection to the teacher database
        mysqli_close($teacherDB);

        //<--- PART 1.1: connect to the authorized users database and insert the registered teacher
        //using the AuthorizedUsers.csv specific format

        //creating the Authorized User Masterlist folder
        //take note of the slashes and the period!
        //TAGS: FILE ADDRESS, DIRECTORY, FOLDER
        $authorizedUsersFolder = "./ALS_SHARED/Authorized User Masterlist/";
        if (file_exists($authorizedUsersFolder)) {
            //do nothing
        } else {
            //https://www.php.net/manual/en/function.mkdir.php
            mkdir($authorizedUsersFolder, 0777, true);
        }

        //create the "authorized users" database if it does not exist
        $dbConnect = mysqli_connect("localhost", "root", "");
        $dbName = "authorized users";

        if ($dbConnect->connect_error) {
            //die() kinda functions like an exit() function
            exit('Error connecting to the server.');
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $sqlStatement = $dbConnect->prepare("CREATE DATABASE IF NOT EXISTS `$dbName` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
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
        //TAGS: FILE ADDRESS, DIRECTORY, FOLDER
        if (!file_exists('./ALS_SHARED/Authorized User Masterlist/AuthorizedUsers.csv')) {
            //fopen creates the file if it does not exist. mode is set to write
            //TAGS: FILE ADDRESS, DIRECTORY, FOLDER
            $authorizedUsersCSV = fopen("./ALS_SHARED/Authorized User Masterlist/AuthorizedUsers.csv", "w");
            //put utf-8 byte order mark to set the csv file as csv utf-8
            $BOM = chr(0xEF) . chr(0xBB) . chr(0xBF);
            fputs($authorizedUsersCSV, $BOM);

            //write the titles header in the first row of the csv file
            //implode function: array to string
            $header_csv = array("RFID", "ID Number", "Surname", "Firstname");
            //\r is moving the cursor to the leftmost position. \n is new line
            fwrite($authorizedUsersCSV, implode(",", $header_csv));


            //prepare the array for inserting to the authorized users csv file. RFID is empty by default
            $teacher_csv[0] = array("", $IDNum, $lastName, $firstName);
            //\r is moving the cursor to the leftmost position. \n is new line
            fwrite($authorizedUsersCSV, "\n");
            fwrite($authorizedUsersCSV, utf8_decode(implode(",", $teacher_csv[0])));
            fclose($authorizedUsersCSV);
        } //<-- THIS PART WILL EXECUTE IF THE AUTHORIZEDUSERS.CSV FILE EXISTS -->
        else {
            //open the file as reading it to get the current contents. "r" mode places
            //file pointer to the start of the file
            //TAGS: FILE ADDRESS, DIRECTORY, FOLDER
            $authorizedUsersCSV = fopen("./ALS_SHARED/Authorized User Masterlist/AuthorizedUsers.csv", "r");
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

            foreach ($authorizedUsersCSVArr as $row) {
                $rfidNum = $row[0];
                $idNum = $row[1];
                $sqlStatement->execute();
            }
            $sqlStatement->close();

            //after updating the authorized users database, it will insert the registered teacher
            //"a" mode places file pointer to the END of the file
            //TAGS: FILE ADDRESS, DIRECTORY, FOLDER
            $authorizedUsersCSV = fopen("./ALS_SHARED/Authorized User Masterlist/AuthorizedUsers.csv", "a");

            $teacher_csv[0] = array("", $IDNum, $lastName, $firstName);
            fwrite($authorizedUsersCSV, "\n");
            fwrite($authorizedUsersCSV, utf8_encode(implode(",", $teacher_csv[0])));
            fclose($authorizedUsersCSV);
        }
        mysqli_close($authorizedUsersDB);

        //<--- PART 2: make the folder of the corresponding teacher that registered --->
        //folder name should be "./ALS_SHARED/firstName lastName/". take note of the slashes and the period
        //TAGS: FILE ADDRESS, DIRECTORY, FOLDER
        $teacherFolderName = "./ALS_SHARED/" . $firstName . " " . $lastName . "/";

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
        $sqlStatement = $creatingTeacherDB->prepare("CREATE DATABASE IF NOT EXISTS `$registeredTeacher` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $sqlStatement->execute();
        $sqlStatement->close();

        mysqli_close($creatingTeacherDB);

        //send email for teacher's password
        // the necessary email addresses
        // edit the email address here!
        $from = '19102579@usc.edu.ph';
        $to = $email;

        //$boundary = md5("random"); // define boundary with a md5 hashed value

        //header
        //$headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
        $headers = "From:" . $from; // Sender Email
        //$headers .= "Content-Type: text/plain;"; // Defining Content-Type
        //$headers .= "boundary = $boundary\r\n"; //Defining the Boundary

        //plain text
        //$body = "--$boundary\r\n";
        //$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        //$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body = "Welcome, $firstName $lastName! 
        
Your password to access the Attendance Monitoring System is: $password. 
Please change your password after logging in. 
Thank you!";

        $sentMailResult = mail($to, "Your Teacher Account Password for the Attendance Monitoring System", $body, $headers);

        if ($sentMailResult) {
            $conn = new mysqli("localhost", "root", "", "temp");
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "INSERT INTO temptb (varname, val) VALUES ('registerTeacherMsg', 'Teacher registration successful!')";

            if (mysqli_query($conn, $sql)) {
                mysqli_close($conn);
            }

            header("Location: register-teacher.php");
        } else {
            die("Failed to register the teacher. Check related settings and try again.");
        }
    }
}

if (isset ($_POST["return-to-admin-main"])) {
    header("location: admin-main.php");
}

ob_end_clean();
?>