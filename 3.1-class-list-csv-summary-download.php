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
    
    $sql = "SELECT val FROM temptb WHERE varname = 'array_s_copy' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $array_s_copy = unserialize($row["val"]);
        mysqli_close($conn);
    }
//premade file for summary
$filename = $teacher . "_" . $table . "_ClassListSummary" . ".csv";

$array_s = $array_s_copy;

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array("Name", "Present", "Late", "Excused", "Absent", "Attendance Days", "% Presence"));
foreach($array_s as $row){
    fputcsv($output, $row);
}
fclose($output);
?>
