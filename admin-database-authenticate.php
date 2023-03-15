<!-- 
    Kathryn Marie P. Sigaya - 220714

    based on these tutorials:
    https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
    https://www.javatpoint.com/php-mysql-login-system
-->

<?php
ob_start();
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

//on the 'login' table in phpmyadmin, search for the username and password inputted
$sql = $link->prepare("SELECT * FROM credentials WHERE username = ?");
$sql->bind_param("s", $username);
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
	$sql = "INSERT INTO temptb (varname, val) VALUES ('adminLoginMsg', 'Invalid username!')";
	
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
//    $_SESSION["adminLoginMsg"] = "Invalid username!";
    header("location: admin-login.php");
}
//if the username exists in the database, check if the passwords match
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
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('IDNum', '$username')";
	
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
//        $_SESSION['currentUser'] = $username;
        header("location: admin-main.php");
    }
    else{
//        $_SESSION["adminLoginMsg"] = "Invalid password!";
	    $conn = new mysqli("localhost", "root", "", "temp");
	    // Check connection
	    if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
	    }
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('adminLoginMsg', 'IInvalid password!')";
	
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
        //$_SESSION["adminLoginMsg"] = "Invalid username or password!";
        header("location: admin-login.php");
    }
}

$sql->close();

mysqli_close($link);
ob_end_clean();
?>