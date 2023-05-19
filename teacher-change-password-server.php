<?php
// Create connection directly to specific database
	$conn = new mysqli('localhost', 'root', '', 'temp');
	// Obtain last value of variable user as 1 row
	// format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	$sql = "SELECT val FROM temptb WHERE varname = 'currentUser' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$currentUser = $row["val"];
		mysqli_close($conn);
	}
	
//return to 2-create-table.php
if (isset($_POST['return-to-create-table']) && $_POST['return-to-create-table'] == 'Return to Teacher Main Menu') {
    //connect to teacher database
    $dbName = "teacher";
    $teacherDB = mysqli_connect("localhost", "root", "", $dbName);

    if ($teacherDB->connect_error) {
        //die() kinda functions like an exit() function
        exit('Error connecting to the teacher database in the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //prepare the query to check if the teacher already changed their password.
    $checkPasswordChangeStmt = "SELECT * FROM login WHERE IDNumber = '$currentUser'";
    $statementPasswordCheck = mysqli_query($teacherDB, $checkPasswordChangeStmt);

    //check if the isPasswordChanged column for the particular teacher has already changed their password
    if (mysqli_num_rows($statementPasswordCheck) > 0) {
        $row = mysqli_fetch_assoc($statementPasswordCheck);
        $passwordCheck = $row["isPasswordChanged"];

        if ($passwordCheck == false){
            $conn = new mysqli('localhost', 'root', '', 'temp');
            // Obtain last value of variable user as 1 row
            // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
            $sql = "INSERT INTO temptb (varname, val) VALUES ('teacherPasswordMsg', 'Please change your password before proceeding.')";
            if (mysqli_query($conn, $sql)) {
                mysqli_close($conn);
            }
            //go back to the teacher-change-password page since the user hasn't changed their password yet.
            header("Location: teacher-change-password.php");
            exit;
        }
        else {
            //go back to the 2-create-table page
            header("Location: 2-create-table.php");
            exit;
        }
    }
}

if (isset($_POST['change-teacher-password']) && $_POST['change-teacher-password'] == 'Update Password') {
    $newPassword = $_POST["teacherPassword"];
    $newPasswordVerify = $_POST["teacherPasswordVerify"];

    if ($newPassword != $newPasswordVerify){
        $conn = new mysqli('localhost', 'root', '', 'temp');
        // Obtain last value of variable user as 1 row
        // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
        $sql = "INSERT INTO temptb (varname, val) VALUES ('teacherPasswordMsg', 'The two passwords are not the same. Please try again.')";
        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);
        }
        //go back to the teacher-change-password page since the user hasn't changed their password yet.
        header("Location: teacher-change-password.php");
        exit;
    }

    //turns the password into a hash
    $hashedPassWord = password_hash($newPassword, PASSWORD_BCRYPT);

    $teacherLoginDB = mysqli_connect('localhost', 'root', '', 'teacher');

    if ($teacherLoginDB->connect_error) {
        exit('Error connecting to the teacher database in the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $changedPassword = 1;
    $updateTeacherStmt = $teacherLoginDB->prepare("UPDATE login SET password = ?, isPasswordChanged = ? WHERE IDNumber = ?");
    //$_SESSION['currentUser'] is the logged in user's ID number. this is found at database-authenticate.php
    //s = string, i = integer (tinyint in this case since it's supposed to be boolean)
    $updateTeacherStmt->bind_param("sis", $hashedPassWord,$changedPassword, $currentUser);
    $updateTeacherStmt->execute();
    $updateTeacherStmt->close();

    mysqli_close($teacherLoginDB);
	
	$conn = new mysqli('localhost', 'root', '', 'temp');
	// Obtain last value of variable user as 1 row
	// format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	$sql = "INSERT INTO temptb (varname, val) VALUES ('teacherPasswordMsg', 'Your password is now updated!')";
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
//    $_SESSION["teacherPasswordMsg"] = "Your password is now updated!";
    header("Location: teacher-change-password.php");
    exit;
}
?>