<?php
session_start();

//premade file for detailed
$filename = strtoupper($_SESSION["teacherName"]) . "_" . $_SESSION["table"] . "_DetailedStudent" . ".csv";

$arrayStudent = $_SESSION['array_student'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array("ID#", "Lastname", "Name", "Date", "Status", "Time-in"));
foreach($arrayStudent as $row){
    fputcsv($output, $row);
}
fclose($output);
?>
