<!--
THIS PAGE IS FOR UPDATING THE SELECTED TEACHER'S LOGIN CREDENTIALS.
after updating the credentials, it will go back to teacher-login-select.php
-->
<?php
session_start();

if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Update Teacher Login Credentials'){
    //$newIDNum = $_POST["IDNum"];
    $newPassword = $_POST["password"];

    $teacherLoginDB = mysqli_connect('localhost','root','', 'teacher');

    if ($teacherLoginDB->connect_error){
        exit('Error connecting to the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $updateTeacherStmt = $teacherLoginDB->prepare("UPDATE login SET password = ? WHERE IDNumber = ?");
    $updateTeacherStmt->bind_param("ss", $newPassword, $_SESSION["referenceIDNum"]);
    $updateTeacherStmt->execute();
    $updateTeacherStmt->close();

    mysqli_close($teacherLoginDB);

    $_SESSION["modifyLoginMsg"] = "The selected teacher's password is now updated!";
}
else{
    $_SESSION["modifyLoginMsg"] = "Updating the teacher's password failed.";
}
header("Location: teacher-login-select.php");
?>
