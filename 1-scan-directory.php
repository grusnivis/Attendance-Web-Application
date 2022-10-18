<?php
//change the address to the local computer/pc when working in different devices
$dir = 'C:/Users/Kath/Desktop/Web Application/Attendance Logs/';

// Check if the directory exists
if (file_exists($dir) && is_dir($dir)) {

    // Get the files of the directory as an array
    $scan_arr = scandir($dir);

    // file name index at 0 is . and at index at 1 is ..
    $files_arr = array_diff($scan_arr, array('.', '..'));

} else {
    echo "Directory does not exist";
}
?>
