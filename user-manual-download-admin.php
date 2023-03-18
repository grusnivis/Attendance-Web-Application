<?php
$file_url = 'C:/Users/DELL/Desktop/User Manual.pdf';
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: utf-8");
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
readfile($file_url);
header("location:admin-main.php");
?>