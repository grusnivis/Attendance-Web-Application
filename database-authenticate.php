<!-- 
    Kathryn Marie P. Sigaya - 220707

    based on these tutorials:
    https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
    https://www.javatpoint.com/php-mysql-login-system
-->

<?php
session_start();
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
$sql = "SELECT *FROM login WHERE IDNumber = '$IDNum' AND password = '$password'";
$result = mysqli_query($teacherDB, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$countLogin = mysqli_num_rows($result);

//if username and password
if ($countLogin == 1) {
    //see solution here for sessions: https://www.simplilearn.com/tutorials/php-tutorial/php-login-form
    //$_SESSION['currentUser'] = $IDNum;
    $_SESSION['currentUser'] = $IDNum;
    header("location: 2-create-table.php");
} else {
    $_SESSION["teacherLoginMsg"] = "Invalid ID number or password!";
    header("location: teacher-login.php");
}

mysqli_close($teacherDB);
?>