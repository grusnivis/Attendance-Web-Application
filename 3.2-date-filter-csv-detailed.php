<?php
session_start();
$filename = $_SESSION["teacherName"] . "_" . $_SESSION["table"] . "_DateDetailed" . ".csv";

$array = $_SESSION['array_copy'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array("Start date:", $_SESSION['sd_copy'], " ", "End date:", $_SESSION['ed_copy']));
fputcsv($output, array("ID#", "Lastname", "Name", "Date", "Status", "Time-in"));
foreach($array as $row){
    fputcsv($output, $row);
}
fclose($output);
?>