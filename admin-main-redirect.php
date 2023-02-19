<?php
session_start();

//goes to Register-Teacher page
if (isset($_POST['register-teacher']) && $_POST['register-teacher'] == 'Register Teacher'){
    header("location: register-teacher.php");
}
//goes to teacher-login-select.php
else if (isset($_POST['teacher-password']) && $_POST['teacher-password'] == 'Change Teacher Password') {
    header("location: teacher-login-select.php");
}
//goes to 6-teacher-attendance.php
else if (isset($_POST['teacher-attendance']) && $_POST['teacher-attendance'] == 'View Teacher Attendance') {
    header("location: 6-teacher-attendance.php");
}
//goes to drop-export-tables.php
else if (isset($_POST['drop-export-tables']) && $_POST['drop-export-tables'] == 'Delete and Export Databases') {
    header("location: database-export-drop.php");
}
//goes to user-manual-download-admin.php
else if (isset($_POST['download-manual']) && $_POST['download-manual'] == 'Download User Manual') {
    header("location:user-manual-download-admin.php");
}
//goes to admin-logout.php (if the logout button is selected)
else{
    header("location: admin-logout.php");
}
?>
