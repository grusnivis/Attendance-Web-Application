<?php
session_start();

//return to 2-create-table.php
if (isset($_POST['return-to-create-table']) && $_POST['return-to-create-table'] == 'Return to Teacher Main Menu') {
    header("Location: 2-create-table.php");
}

if (isset($_POST['change-teacher-password']) && $_POST['change-teacher-password'] == 'Update Password') {
    $newPassword = $_POST["teacherPassword"];

    $teacherLoginDB = mysqli_connect('localhost', 'root', '', 'teacher');

    if ($teacherLoginDB->connect_error) {
        exit('Error connecting to the teacher database in the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $updateTeacherStmt = $teacherLoginDB->prepare("UPDATE login SET password = ? WHERE IDNumber = ?");
    //$_SESSION['currentUser'] is the logged in user's ID number. this is found at database-authenticate.php
    $updateTeacherStmt->bind_param("ss", $newPassword, $_SESSION['currentUser']);
    $updateTeacherStmt->execute();
    $updateTeacherStmt->close();

    mysqli_close($teacherLoginDB);

    $_SESSION["teacherPasswordMsg"] = "Your password is now updated!";
    header("Location: teacher-change-password.php");
}
?>