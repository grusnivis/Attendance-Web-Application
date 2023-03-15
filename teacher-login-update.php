<!--
THIS PAGE IS FOR UPDATING THE SELECTED TEACHER'S LOGIN CREDENTIALS.
after updating the credentials, it will go back to teacher-login-select.php
-->
<?php
//session_start();
	$conn = new mysqli('localhost', 'root', '', 'temp');
	// Obtain last value of variable user as 1 row
	// format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	$sql = "SELECT val FROM temptb WHERE varname = 'referenceIDNum' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$tempvar1 = $row["val"];
		mysqli_close($conn);
	}

if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Update to New Teacher Password'){
    //$newIDNum = $_POST["IDNum"];
    $newPassword = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $teacherLoginDB = mysqli_connect('localhost','root','', 'teacher');

    if ($teacherLoginDB->connect_error){
        exit('Error connecting to the teacher server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $updateTeacherStmt = $teacherLoginDB->prepare("UPDATE login SET password = ? WHERE IDNumber = ?");
    $updateTeacherStmt->bind_param("ss", $newPassword, $tempvar1);
    $updateTeacherStmt->execute();
    $updateTeacherStmt->close();

    mysqli_close($teacherLoginDB);
	
	$conn = new mysqli("localhost", "root", "", "temp");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    $str = "The selected teacher's password is now updated!";
	$sql = "INSERT INTO temptb (varname, val) VALUES ('modifyLoginMsg', '$str')";
	
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
    //$_SESSION["modifyLoginMsg"] = "The selected teacher's password is now updated!";
}
else{
	$conn = new mysqli("localhost", "root", "", "temp");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$str = "Updating teacher's password failed.";
	$sql = "INSERT INTO temptb (varname, val) VALUES ('modifyLoginMsg', '$str')";
	
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
    //$_SESSION["modifyLoginMsg"] = "Updating the teacher's password failed.";
}
header("Location: teacher-login-select.php");
?>
