<!--
This page is for changing the teacher's password, in that they know their password
but they want to change it. this page is located at 2-create-table.php
-->
<?php
session_start();
?>

<html lang="en">

<head>
    <title> Change Teacher Password </title>
    <link type="text/css" rel="stylesheet" href="css/register-teacher-style.css"/>
</head>

<body>
<div class="modifyTeacherPasswordCon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"></script>

    <form method="POST" action="teacher-change-password-server.php" enctype="multipart/form-data">
        <p class="instructions">
        <div class="form-group">
            <h1> Change Teacher Password</h1>
            <p class="instructions">Change your password in the text field below.</p>
            <input type="password" class="password fieldSettings" name="teacherPassword" placeholder="Password" required/>
            <p class="instructions">Please confirm your password.</p>
            <input type="password" class="password fieldSettings" name="teacherPasswordVerify" placeholder="Confirm Password" required/>
        </div>
        <div class="form-group">
            <input type="submit" name="change-teacher-password" class="btn btn-info" value="Update Password"/>
        </div>
        </p>
        <hr/>
        <input type="submit" name="return-to-create-table" class="btn btn-info"
               style="color: white; background: #0b8f47;" value="Return to Teacher Main Menu" formnovalidate/>
    </form>
    <!-- text prompt if updating the password is successful or not -->
    <?php
	    // Create connection directly to specific database
	    $conn = new mysqli('localhost', 'root', '', 'temp');
	    // Obtain last value of variable user as 1 row
	    // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	    $sql = "SELECT val FROM temptb WHERE varname = 'teacherPasswordMsg' ORDER BY id DESC LIMIT 1";
	    $result = mysqli_query($conn, $sql);
	    if (mysqli_num_rows($result) > 0) {
		    $row = mysqli_fetch_assoc($result);
		    $tempvar1 = $row["val"];
	    }
    if (isset($tempvar1) && $tempvar1) {
        if ($tempvar1 == "Your password is now updated!"){
            echo '<p class = "notification" style = "color:#0b8f47;">' . $tempvar1 . '</p>';
        }
        else{
            echo '<p class = "notification" style = "color:#d9534f;">' . $tempvar1 . '</p>';
        }
    }
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('teacherPasswordMsg', '')";
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
    ?>
</div>
</body>
</html>
