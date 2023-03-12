<!-- 
    Kathryn Marie P. Sigaya - 220714

    based on these tutorials:
    https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
    https://www.javatpoint.com/php-mysql-login-system
-->

<?php
session_start();
//include('admin-database-config.php');

$username = $_POST['username'];
$password = $_POST['password'];

//create "admin" database if not exists
$link = mysqli_connect("localhost", "root", "");
$dbName = "admin";

if ($link->connect_error) {
    //die() kinda functions like an exit() function
    exit('Error connecting to the admin database in the server.');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//create the "admin" database if it does not exist
$sqlStatement = $link->prepare("CREATE DATABASE IF NOT EXISTS admin DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$sqlStatement->execute();
$sqlStatement->close();
mysqli_close($link);

//this part creates the "credentials" table if it does not exist
//connect to admin database
$link = mysqli_connect("localhost", "root", "", $dbName);

if ($link->connect_error) {
    //die() kinda functions like an exit() function
    exit('Error connecting to the admin database in the server.');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//create the "credentials" table if it does not exist
//columns are: IDNumber, password, firstName, lastName, email
//character is set to utf8mb4_unicode_ci on default
$createLoginTableStmt = $link->prepare("CREATE TABLE IF NOT EXISTS credentials(
            username VARCHAR(255) PRIMARY KEY NOT NULL,
            password VARCHAR(255) NOT NULL)
            DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$createLoginTableStmt->execute();
$createLoginTableStmt->close();

//MySQLI injection prevention - clean up data retrieved from an HTML form
$username = stripcslashes($username);
$password = stripcslashes($password);
$username = mysqli_real_escape_string($link, $username);
$password = mysqli_real_escape_string($link, $password);

//on the 'credentials' table in phpmyadmin, search for the username and password inputted
$sql = "SELECT *FROM credentials WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$count = mysqli_num_rows($result);

//if username and password
if ($count == 1) {
    $_SESSION["adminCurrentUser"] = $username;
    mysqli_close($link);
    header("location: admin-main.php");
} else {
    $_SESSION["adminLoginMsg"] = "Invalid username or password!";
    mysqli_close($link);
    header("location: admin-login.php");
}
?>