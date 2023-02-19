<?php
$file_url = 'User Manual.pdf';
header("Cache-Control: public");
header("Content-Description: File Transfer");
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: utf-8");
header("Content-disposition: attachment; filename=\"" . basename("C:/Users/Kath/Desktop/Web Application/User Manual/".$file_url) . "\"");
readfile($file_url);
?>