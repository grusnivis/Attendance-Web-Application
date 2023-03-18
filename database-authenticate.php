<!-- 
    Kathryn Marie P. Sigaya - 220707

    based on these tutorials:
    https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
    https://www.javatpoint.com/php-mysql-login-system
-->

<?php
ob_start();
//include('database-config.php');

$IDNum = $_POST['IDNum'];
$password = $_POST['password'];

//MySQLI injection prevention - clean up data retrieved from an HTML form
$username = stripcslashes($IDNum);
$password = stripcslashes($password);

//attempt to connect to MySQL database. this part creates the "teacher" database with the
//"login" table
$link = mysqli_connect("localhost", "root", "");
$dbName = "teacher";

if ($link->connect_error) {
    //die() kinda functions like an exit() function
    exit('Error connecting to the teacher database in the server.');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//create the "teacher" database if it does not exist
$sqlStatement = $link->prepare("CREATE DATABASE IF NOT EXISTS teacher");
$sqlStatement->execute();
$sqlStatement->close();
mysqli_close($link);

//this part creats the "login" table if it does not exist
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
            email VARCHAR(255) NOT NULL)
            DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$createLoginTableStmt->execute();
$createLoginTableStmt->close();

$username = mysqli_real_escape_string($teacherDB, $username); //$link can be located at database-config.php
$password = mysqli_real_escape_string($teacherDB, $password);

//on the 'login' table in phpmyadmin, search for the username and password inputted
$sql = $teacherDB->prepare("SELECT * FROM login WHERE IDNumber = ?");
$sql->bind_param("s", $IDNum);
$sql->execute();
//$result = mysqli_query($teacherDB, $sql);
//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
//$countLogin = mysqli_num_rows($result);

$result = $sql->get_result();

//check first if the id number exists in the database
if ($result->num_rows == 0){
	$conn = new mysqli("localhost", "root", "", "temp");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO temptb (varname, val) VALUES ('teacherLoginMsg', 'Invalid ID number!')";
	
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
//    $_SESSION["teacherLoginMsg"] = "Invalid ID number!";
    header("location: teacher-login.php");
}
//if the id number exists in the database, check if the passwords match
else{
    //fetch the password from the db
    while ($row = $result->fetch_assoc()){
        $verifyPassword = $row["password"];
    }

    if (password_verify($password, $verifyPassword)){
	    $conn = new mysqli("localhost", "root", "", "temp");
	    // Check connection
	    if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
	    }
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('currentUser', '$IDNum')";
	
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
//        $_SESSION['currentUser'] = $IDNum;
        header("location: 2-create-table.php");
    }
    else{
	    $conn = new mysqli("localhost", "root", "", "temp");
	    // Check connection
	    if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
	    }
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('teacherLoginMsg', 'Invalid password!')";
	
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
//        $_SESSION["teacherLoginMsg"] = "Invalid password!";
        header("location: teacher-login.php");
    }
}
mysqli_close($teacherDB);
ob_end_clean();
?>