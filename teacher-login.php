<?php
/*
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set("display_errors", 1);
  
    require_once 'ims-blti/blti.php';
    $lti = new BLTI("secret", false, false);

    ob_start();
    session_start();
    header('Content-Type: text/html; charset=utf-8'); 
    */
?>

<?php
//You only assign a session variable once, so page 1 is correct,
//then it is available for the whole session, uses session_start()
//at the top of each page.
// https://stackoverflow.com/questions/9571125/cant-pass-php-session-variables-to-multiple-pages
//session_start();
 
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "temp";
	
	// Create connection to server
	$conn = new mysqli($servername, $username, $password);
	
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	// To check for existing database
	$sql = "SHOW DATABASES LIKE '$dbname'";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
	}

// Creates database if there isn't one
	else {
		$sql = "CREATE DATABASE '$dbname'";
		if (mysqli_query($conn, $sql)) {
		} else {
			echo "Error creating database: " . mysqli_error($conn);
		}
	}
	
	// To Check for existing tables in database
	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql = "SHOW TABLES LIKE 'temptb'";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
	}

// Creates a Table if non-existent with 3 columns
// One column for row identifier, another to know what variable it is, and what value it has
	else {
		$sql = "CREATE TABLE temptb (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            varname VARCHAR(30) NOT NULL, val VARCHAR(30) NOT NULL)";
		if (mysqli_query($conn, $sql)) {
		} else {
			echo "Error creating table: " . mysqli_error($conn);
		}
	}
	
	$conn->close();

?>

<html>
<head>
    <title>Group H - Web Application</title>
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
    <h1>Attendance System</h1>
    <h3> Welcome! </h3>
    <h4> This is the web application of Group H <br/>
        designed for the thesis entitled <br/>
        <i> Development of an Attendance Monitoring System <br/>
            with a Portable RFID-based Logging Device. </i>
    </h4>

    <hr>
    <!-- commented this out. not needed anymore i think
            <div class = "container form-signin"> 
                <?php
    /*
        $msg = '';

        if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
            if ($_POST['username'] == 'admin' &&  $_POST['password'] == '1234') {
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = 'admin';

            $msg = 'Login Successful!';

            header("location:teacher-menu.php");
        }

        else {
            $msg = 'Wrong username or password!';
        }
        }
    */
    ?>
            </div>
            -->

    <div class="con-form">
        <form class="form-signin" role="form" action="database-authenticate.php" method="post">
            <h4 class="form-signin-heading"><?php echo $msg = ''; ?></h4>
            <!-- use "placeholder" for the filler text in boxes -->
            <?php
                $login_err = '';
            ?>
            <input type="text" class="form-control" name="IDNum" placeholder="ID Number" required autofocus>
            <input type="password" class="form-control" name="password" placeholder="password" required>
            <br class="breakspace"/>
            <button class="buttonLogin" type="submit" name="login">Login</button>
        </form>
        <p> Are you an administrator? <b> <a href="admin-login.php"> Log in here! </a> </b></p>
    </div>
</div>

</body>
</html> 