<?php
session_start();
ob_start();

if (isset($_POST['update-admin-password']) && $_POST['update-admin-password'] == 'Update to New Administrator Password'){
    //$newIDNum = $_POST["IDNum"];
    $newPassword = $_POST["password"];

    $hashedPassWord = password_hash($newPassword, PASSWORD_BCRYPT);

    $adminLoginDB = mysqli_connect('localhost','root','', 'admin');

    if ($adminLoginDB->connect_error){
        exit('Error connecting to the admin database in the server.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $updateAdminStmt = $adminLoginDB->prepare("UPDATE credentials SET password = ? WHERE username = ?");
    $updateAdminStmt->bind_param("ss", $hashedPassWord, $_SESSION["referenceUsername"]);

    //execute returns true on success and false on failure
    if ($updateAdminStmt->execute()){
        $_SESSION["changeAccMsg"] = "The selected administrator's password is now updated!";
    }
    else{
        $_SESSION["changeAccMsg"] = "Updating the administrator's password failed.";
    }

    $updateAdminStmt->close();
    mysqli_close($adminLoginDB);
    header("Location: admin-account-select.php");
}

ob_end_clean();
?>
