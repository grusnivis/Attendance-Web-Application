<?php
session_start();
$filename = $_SESSION["teacherName"] . "_" . $_SESSION["table"] . "_DateSummary" . ".csv";
$array_s = $_SESSION["array_date_summary"];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array("Start date:", $_SESSION['sd_copy'], " ", "End date:", $_SESSION['ed_copy']));
fputcsv($output, array("Name", "Present", "Late", "Excused", "Absent", "Attendance Days", "% Presence"));
foreach($array_s as $row){
    fputcsv($output, $row);
}
fclose($output);

?>
