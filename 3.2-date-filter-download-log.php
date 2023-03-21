<?php
session_start();

if (isset($_POST["download_s_csv"])){
    header("Location: 3.2-date-filter-csv-summary.php");
}

if (isset($_POST["download_csv"])){
    header("Location: 3.2-date-filter-csv-detailed.php");
}

//specific date
if (isset($_POST["download"])){
    header("Location: 3.2-date-filter-csv-specificdate.php");
}

//SUMMARY PDF
if (isset($_POST["download_s_pdf"])){
    $teacher_name = strtoupper($_SESSION["teacherName"]);
    include '5-pdf-summary.php';
}

if (isset($_POST["download_pdf"])){
    $teacher_name = strtoupper($_SESSION["teacherName"]);
    include '5-pdf-detailed.php';
}
?>
