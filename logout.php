<?php
ob_start();
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
	ob_end_clean();
	header('location:teacher-login.php');
}
?>