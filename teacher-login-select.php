<!--
THIS PAGE (teacher-login-select.php) IS FOR DISPLAYING THE TEACHER DROPDOWN MENU TO BE SELECTED.
it will go to teacher-login-reset.php for the sending of the randomly generated password.
-->

<?php

$checkTeacherLoginDB = mysqli_connect("localhost", "root", "");
$dbName = "teacher";

$query = "SHOW DATABASES LIKE '$dbName'";
$sqlStatement = $checkTeacherLoginDB->query($query);

if (!($sqlStatement->num_rows == 1)){ //if there are no databases with "teacher attendance" in the name
	$conn = new mysqli('localhost', 'root', '', 'temp');
	// Obtain last value of variable user as 1 row
	// format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	$sql = "INSERT INTO temptb (varname, val) VALUES ('checkTeacherAttendanceDB', 'There are no registered teachers in the teacher database.')";
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
//    $_SESSION["checkTeacherAttendanceDB"] = "There are no registered teachers in the web application.";
    mysqli_close($checkTeacherLoginDB);
    header("location: admin-main.php");
}
else{
    //proceed with the next processes. close the current database connection
    mysqli_close($checkTeacherLoginDB);
}
?>

<!-- HTML START -->
<html lang = 'en'>
<head>
    <title> Reset Teacher Password </title>
    <link type = "text/css" rel="stylesheet" href ="css/register-teacher-style.css"/>
</head>

<body>
<div class = "selectTeacherPasswordCon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"></script>

    <form method = "POST" action = "teacher-login-reset.php">
        <h1>Reset Teacher Password</h1>
        <p class = "instructions">
            Please select the registered teacher that you want to reset their password in
            the dropdown box below.<br/><u><i>A randomly generated password will be sent to the selected
                    teacher's registered email address.</i></u></p>

            <!-- THIS PART CONNECTS TO THE TEACHER DATABASE - LOGIN TABLE AND OUTPUTS IT TO THE DROPDOWN BOX -->
            <?php
            //https://stackoverflow.com/questions/5189662/populate-a-drop-down-box-from-a-mysql-table-in-php
            $loginTableCon = new mysqli("localhost", "root", "", 'teacher');
            // Check connection
            if ($loginTableCon->connect_error) {
                die("Connection failed: " . $loginTableCon->connect_error);
            }

            $sqlQuery = "SELECT IDNumber, firstName, lastName FROM login ORDER BY lastName ASC";
            $result = mysqli_query($loginTableCon, $sqlQuery);

            echo "<select name = 'teacherSelect' class='dropup center-block' style='margin-left: 0%;padding: 5px;font-size:17px' required>";
            echo "<option disabled value = '0'>Select a Teacher</option>";
            while ($row = mysqli_fetch_array($result)){
                echo "<option value = '". $row['IDNumber'] ."'>". $row['firstName'] . " " . $row['lastName'] ."</option>";
            }
            echo "</select>";
            mysqli_close($loginTableCon);
            ?>
        </p>
        <div class="form-group">
            <!-- change button text through the value attribute -->
            <center><input type="submit" name="reset-password" class="btn btn-info" value="Reset Teacher Password"/></center>
            <hr/>
            <input type="submit" name="return-to-admin-main" class="btn btn-info" style=";color: white; background-color:#0b8f47;" value="Return to Administrator Menu" formnovalidate/>
        </div>
        <?php
	        $conn = new mysqli('localhost', 'root', '', 'temp');
	        // Obtain last value of variable user as 1 row
	        // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	        $sql = "SELECT val FROM temptb WHERE varname = 'modifyLoginMsg' ORDER BY id DESC LIMIT 1";
	        $result = mysqli_query($conn, $sql);
	        if (mysqli_num_rows($result) > 0) {
		        $row = mysqli_fetch_assoc($result);
		        $tempvar1 = $row["val"];
	        }
	        if (isset($tempvar1) && $tempvar1) {
	        echo '<p class = "notification">';
	        echo $tempvar1;
	        echo '</p>';
	        $sql = "INSERT INTO temptb (varname, val) VALUES ('modifyLoginMsg', '')";
	
	        if (mysqli_query($conn, $sql)) {
		        mysqli_close($conn);
	        }
        }
        ?>
    </form>
</div>
</body>
</html>

