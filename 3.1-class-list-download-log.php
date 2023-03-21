<?php
session_start();
ob_start();

//DETAILED
//DETAILED CSV
if (isset($_POST["download_csv"])){
    header("Location: 3.1-class-list-csv-detailed-download.php");
}

//DETAILED PDF
if (isset($_POST["download_pdf"])){
    $teacher_name = strtoupper($_SESSION["teacherName"]);
    include '5-pdf-detailed.php';
}

if (isset($_POST["download_pdf"])){
    $teacher_name = strtoupper($_SESSION["teacherName"]);
    include '5-pdf-detailed.php';
}

//SUMMARY
//SUMMARY CSV
if (isset($_POST["download_s_csv"])){
    header("Location: 3.1-class-list-csv-summary-download.php");
}

//SUMMARY PDF
if (isset($_POST["download_s_pdf"])){
    $teacher_name = strtoupper($_SESSION["teacherName"]);
    include '5-pdf-summary.php';
}

ob_end_clean();
?>
