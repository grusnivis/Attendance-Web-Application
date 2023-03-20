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
    header("Location: 2-create-table.php");
}

if (isset($_POST['change-teacher-password']) && $_POST['change-teacher-password'] == 'Update Password') {
    $newPassword = $_POST["teacherPassword"];
    //turns the password into a hash
    $hashedPassWord = password_hash($newPassword, PASSWORD_BCRYPT);

    $teacherLoginDB = mysqli_connect('localhost', 'root', '', 'teacher');

    if ($teacherLoginDB->connect_error) {
        exit('Error connecting to the teacher database in the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $updateTeacherStmt = $teacherLoginDB->prepare("UPDATE login SET password = ? WHERE IDNumber = ?");
    //$_SESSION['currentUser'] is the logged in user's ID number. this is found at database-authenticate.php
    $updateTeacherStmt->bind_param("ss", $hashedPassWord, $currentUser);
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
}
?>