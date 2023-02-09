<!-- 
    Kathryn Marie P. Sigaya - 220714

    based on these tutorials:
    https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
    https://www.javatpoint.com/php-mysql-login-system
-->

<?php
    include ('admin-database-config.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    //MySQLI injection prevention - clean up data retrieved from an HTML form
    $username = stripcslashes($username);
    $password = stripcslashes($password);
    $username = mysqli_real_escape_string($link, $username);
    $password = mysqli_real_escape_string($link, $password);

    //on the 'login' table in phpmyadmin, search for the username and password inputted
    $sql = "SELECT *FROM credentials WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);

    //if username and password 
    if ($count == 1){
	    $conn = new mysqli("localhost", "root", "", "temp");
	    // Check connection
	    if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
	    }
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('adminCurrentUser', '$username')";
	
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
        //$_SESSION["adminCurrentUser"] = $username;
        header("location: admin-main.php");
    }
    else{
	    $conn = new mysqli("localhost", "root", "", "temp");
	    // Check connection
	    if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
	    }
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('adminLoginMsg', 'Invalid username or password!')";
	
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
        //$_SESSION["adminLoginMsg"] = "Invalid username or password!";
        header("location: admin-login.php");
    }

    mysqli_close($link);
?>