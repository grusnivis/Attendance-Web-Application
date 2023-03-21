<?php
session_start();

//premade file for detailed
$filename = strtoupper($_SESSION["teacherName"]) . "_" . $_SESSION["table"] . "_ClassListDetailed" . ".csv";

$array = $_SESSION['array_copy'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array("ID#", "Lastname", "Name", "Date", "Status", "Time-in"));
foreach($array as $row){
    fputcsv($output, $row);
}
fclose($output);
?>
