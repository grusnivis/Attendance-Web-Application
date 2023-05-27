<?php
//$dir = 'C:/Users/Amber/PycharmProjects/ALS_SHARED/Attendance Logs/';
//
$dir = './ALS_SHARED/Attendance Logs/';

// Check if the directory exists and if the path is a directory
if (file_exists($dir) && is_dir($dir)) {
    // Get the files of the directory as an array
    $scan_arr = scandir($dir);

    // file name index at 0 is . and at index at 1 is ..
    $files_arr = array_diff($scan_arr, array('.', '..'));

    foreach ($files_arr as $key => $value) {
        //remove words containing "conflict"
        if (strpos($value, 'conflict') !== false) {
            unset($files_arr[$key]);
            unlink($dir . $value);
        }
    }
} else {
    echo '<bp style = "color:#dc3545;"><b>The Attendance Logs Directory does not exist!</b></p>';
}

//return to the class monitoring page
?>
