<?php
$file_url = 'User Manual.pdf';
header("Cache-Control: public");
header("Content-Description: File Transfer");
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: utf-8");
header("Content-disposition: attachment; filename=\"" . $file_url . "\"");
readfile($file_url);
//header("location:admin-main.php");        needs to be removed to allow download in canvas access
?>