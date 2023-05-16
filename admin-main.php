<html>
<head>
    <title> Administrator Menu</title>
    <link type="text/css" rel="stylesheet" href="css/admin-main-style.css" ;
</head>
<body>
<div class="adminMaincon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"></script>

    <form method="POST" action="admin-main-redirect.php" enctype="multipart/form-data">

        <h1> Administrator Menu</h1>
        <p> Welcome to the administrator account! Please choose from the options below.</p>
        <input type="submit" name="register-teacher" class="btn btn-info" style="width:300px" value="Register Teacher"/>
        <br/>
        <input type="submit" name="teacher-password" class="btn btn-info" style="width:300px"
               value="Change Teacher Password"/>
        <br/>
        <input type="submit" name="teacher-attendance" class="btn btn-info" style="width:300px"
               value="View Teacher Attendance"/>
        <br/>
        <input type="submit" name="drop-export-tables" class="btn btn-info" style="width:300px"
               value="Delete and Export Teacher Databases"/>
        <br/>
        <input type="submit" name="download-manual" class="btn btn-info" style="width:300px"
               value="Download User Manual"/>
        <br/>
        <hr/>
        <input type="submit" name="admin-logout" class="btn btn-info" style="width:300px" value="Log out"/>
    </form>
    <?php
	    // Create connection directly to specific database
	    $conn = new mysqli('localhost', 'root', '', 'temp');
	    // Obtain last value of variable user as 1 row
	    // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	    $sql = "SELECT val FROM temptb WHERE varname = 'checkTeacherAttendanceDB' ORDER BY id DESC LIMIT 1";
	    $result = mysqli_query($conn, $sql);
	    if (mysqli_num_rows($result) > 0) {
		    $row = mysqli_fetch_assoc($result);
		    $tempvar1 = $row["val"];
	    }
    if (isset($tempvar1) && $tempvar1) {
        echo '<p class = "notification" style = "color:#d9534f;">';
        echo '<b><u>'. $tempvar1 .'</u></b>';
        echo '</p>';
    }
	
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('checkTeacherAttendanceDB', '')";
	
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
    ?>
</div>
</body>
</html>
