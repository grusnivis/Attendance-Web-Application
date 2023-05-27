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
    
    $sql = "SELECT val FROM temptb WHERE varname = 'sd_copy' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $sd_copy = $row["val"];
    }
    
    $sql = "SELECT val FROM temptb WHERE varname = 'ed_copy' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $ed_copy = $row["val"];
    }
    
    $sql = "SELECT val FROM temptb WHERE varname = 'array_date_summary' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $array_date_summary = unserialize($row["val"]);
        mysqli_close($conn);
    }
    
$filename = $teacher . "_" . $table . "_DateSummary" . ".csv";
$array_s = $array_date_summary;

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array("Start date:", $sd_copy, " ", "End date:", $ed_copy));
fputcsv($output, array("ID#", "Name", "Present", "Late", "Excused", "Absent", "Attendance Days", "% Presence"));
foreach($array_s as $row){
    fputcsv($output, $row);
}
fclose($output);

?>
