<?php
ob_start();
session_start();

header('Content-Encoding: utf-8');

$username = '';
$password = '';
unset($_SESSION['registerAdminMsg']);

if (isset ($_POST["register"])) {
    //ucfirst - returns the first character of the string capitalized (https://www.php.net/manual/en/function.ucfirst.php)
    $username = strtoupper($_POST["username"]); //retains the numbers
    $password = $_POST["password"];

    //turns the password into a hash for security
    $hashedPassWord = password_hash($password, PASSWORD_BCRYPT);

    //<--- PART 1: insert username and password fields into database. Database: Teacher. Table: login --->
    //(from admin-database-config.php)

    //create the database if it does not exist
    $dbConnect = mysqli_connect("localhost", "root", "");
    $dbName = "admin";

    if ($dbConnect->connect_error){
        //die() kinda functions like an exit() function
        exit('Error connecting to the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //create the "teacher" database if it does not exist
    $sqlStatement = $dbConnect->prepare("CREATE DATABASE IF NOT EXISTS admin DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $sqlStatement->execute();
    $sqlStatement->close();

    mysqli_close($dbConnect);

    //connect to admin database
    $adminDB= mysqli_connect("localhost", "root", "", $dbName);

    if ($adminDB->connect_error) {
        //die() kinda functions like an exit() function
        exit('Error connecting to the administrator database in the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //create the "credentials" table if it does not exist
    //columns are: IDNumber, password, firstName, lastName, email
    //character is set to utf8mb4_unicode_ci on default
    $createCredentialsTableStmt = $adminDB->prepare("CREATE TABLE IF NOT EXISTS credentials(
            username VARCHAR(255) PRIMARY KEY NOT NULL,
            password VARCHAR(255) NOT NULL)
            DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $createCredentialsTableStmt->execute();
    $createCredentialsTableStmt->close();

    //prepare the query to insert the registered admin to the admin database.
    //it does duplicate checking first to see if the teacher is registered already
    $checkAdminDBDuplicate = "SELECT * FROM credentials WHERE username = '$username'";
    $statementDuplicate = mysqli_query($adminDB, $checkAdminDBDuplicate);
    $rowResults = mysqli_fetch_array($statementDuplicate, MYSQLI_ASSOC);
    $countDup = mysqli_num_rows($statementDuplicate);
    //$countDup = $statementDuplicate->num_rows;

    //if there is a duplicate id number
    if($countDup >= 1){
        $_SESSION['registerAdminMsg'] = "The username is already registered in the database.";
        mysqli_close($adminDB);
        //returns to the register admin page. it "aborts" the rest of the process
        header("Location: register-admin.php");
        ob_end_clean();
    }
    //if there is NO duplicate username, then you insert the admin data to the login table
    else{
        $statementInsert = $adminDB->prepare("INSERT INTO `credentials` (username, password) VALUES (?,?)");
        $statementInsert->bind_param("ss", $username, $hashedPassWord);
        $statementInsert->execute();
        $statementInsert->close();

        //close the connection to the teacher database
        mysqli_close($adminDB);

        $_SESSION["registerAdminMsg"] = "Administrator registration successful!";
        header("Location: register-admin.php");
    }
}

if (isset ($_POST["change-admin-password"])){
    header("location: admin-account-select.php");
}
ob_end_clean();
?>
