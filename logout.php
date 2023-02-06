<!-- how did this work -->
<?php
    //session_start();
    //session_unset();
    //session_destroy();
	$conn = new mysqli('localhost', 'root', '', 'temp');
	
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	// Deletes only the data in the table
	// DROP command will delete the table and everything in it
	$sql = "DELETE FROM temptb";
	
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
		header('location:teacher-login.php');
	}
?>