<?php
	$conn = new mysqli("localhost", "root", "", "temp");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT val FROM temptb WHERE varname = 'table' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$table = $row["val"];
	}
	
	$sql = "SELECT val FROM temptb WHERE varname = 'teacherName' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$teacher = $row["val"];
	}
	
	$sql = "SELECT val FROM temptb WHERE varname = 'array_copy' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$array_copy = unserialize($row["val"]);
		mysqli_close($conn);
	}
	
	
$filename = $teacher . "_" . $table . "_SpecificDate" . ".csv";

$array = $array_copy;

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array("ID#", "Lastname", "Name", "Date", "Status", "Time-in"));
foreach($array as $row){
    fputcsv($output, $row);
}
fclose($output);
?>