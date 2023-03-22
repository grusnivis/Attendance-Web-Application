<?php
session_start();
if (isset($_POST["download_csv"])){
    header("Location: 4-monitoring-download-csv.php");
}
if (isset($_POST["download_pdf"])){
    include '5-pdf-detailed.php';
}
?>
