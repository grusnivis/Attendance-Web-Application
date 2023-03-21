<?php
session_start();

//premade file for summary
$filename = $_SESSION["teacherName"] . "_" . $_SESSION["table"] . "_ClassListSummary" . ".csv";

$array_s = $_SESSION['array_s_copy'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array("Name", "Present", "Late", "Excused", "Absent", "Attendance Days", "% Presence"));
foreach($array_s as $row){
    fputcsv($output, $row);
}
fclose($output);
?>
