<?php
//You only assign a session variable once, so page 1 is correct,
//then it is available for the whole session, uses session_start()
//at the top of each page.
// https://stackoverflow.com/questions/9571125/cant-pass-php-session-variables-to-multiple-pages
	// Create connection
	$conn = new mysqli('localhost', 'root', '');
	$create = $conn->query("CREATE Database IF NOT EXISTS `temp`
	        DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    mysqli_close($conn);
    
	// Create connection with database in server to create table
	$conn = new mysqli('localhost', 'root', '', 'temp');
	$create = $conn->query("CREATE TABLE IF NOT EXISTS temptb (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            varname VARCHAR(255) NOT NULL, val VARCHAR(1000) NOT NULL)
            DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    mysqli_close($conn);
?>

<html>
<head>
    <title>Attendance Monitoring System</title>
    <link type="text/css" rel="stylesheet" href="css/teacher-login-style.css"/>
</head>
<body>
<?php
/*
    if ($lti -> valid) {
?>
<?php
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
*/
?>
<br/>
<div class="con-main">
    <h1>Attendance Monitoring System <i><u> [Teacher] </u></i></h1>
    <h3> Welcome! </h3>
    <h4> This is the <b> teacher login page </b>for <br/>Group H's thesis entitled
        <i> Development of an <br/>Attendance Monitoring System with a <br/>Portable RFID-based Logging Device.</i>
        <br/>Please input your credentials below.
    </h4>
    <p> <b> <i>Are you an administrator? <a href = "admin-login.php" > Log in here! </a> </i></b></p>
    <hr>

    <div class="con-form">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"></script>

        <form class="form-signin" role="form" action="database-authenticate.php" method="post">
            <h4 class="form-signin-heading"><?php echo $msg = ''; ?></h4>
            <!-- use "placeholder" for the filler text in boxes -->
            <input type="text" class="form-control" name="IDNum" placeholder="ID Number" required autofocus>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <br class="breakspace"/>
            <input type="submit" name="buttonLogin" class="btn btn-info" value="Login"/>
            <br class="breakspaceForNotif"/>
            <?php
	            // Create connection directly to specific database
	            $conn = new mysqli('localhost', 'root', '', 'temp');
	            // Obtain last value of variable user as 1 row
	            // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	            $sql = "SELECT val FROM temptb WHERE varname = 'teacherLoginMsg' ORDER BY id DESC LIMIT 1";
	            $result = mysqli_query($conn, $sql);
	            if (mysqli_num_rows($result) > 0) {
		            $row = mysqli_fetch_assoc($result);
		            $tempvar1 = $row["val"];
		            mysqli_close($conn);
	            }
	            if (isset($tempvar1) && $tempvar1) {
	            echo '<p class = "notification">';
	            echo $tempvar1;
	            echo '</p>';
	            $conn = new mysqli("localhost", "root", "", "temp");
	            // Check connection
	            if ($conn->connect_error) {
		            die("Connection failed: " . $conn->connect_error);
	            }
	            $sql = "INSERT INTO temptb (varname, val) VALUES ('teacherLoginMsg', '')";
	
	            if (mysqli_query($conn, $sql)) {
		            mysqli_close($conn);
	            }
            }
            ?>
        </form>
    </div>
</div>

</body>
</html> 