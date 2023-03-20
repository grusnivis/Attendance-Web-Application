<?php
$file_url = 'User Manuel.pdf';
header("Cache-Control: public");
header("Content-Description: File Transfer");
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: utf-8");
header("Content-disposition: attachment; filename=\"" . $file_url . "\"");
readfile($file_url);
?>