<?php
  //$dir = 'C:/Users/Amber/PycharmProjects/ALS_SHARED/Attendance Logs/';
  $dir = './Attendance Logs/';

  // Check if the directory exists
  if (file_exists($dir) && is_dir($dir)){
      // Get the files of the directory as an array
      $scan_arr = scandir($dir);

      // file name index at 0 is . and at index at 1 is ..
      $files_arr = array_diff($scan_arr, array('.','..') );
  }
  else {
    echo "Attendance Logs Directory does not exist!";
  }
?>