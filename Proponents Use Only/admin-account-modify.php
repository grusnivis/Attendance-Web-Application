<?php
session_start();

if (isset($_POST['return-to-register-admin']) && $_POST["return-to-register-admin"] == "Return to Register Administrator"){
    header("location: register-admin.php");
}

if (isset($_POST['admin-selected']) && $_POST['admin-selected'] == 'Change Administrator Password') {
    $adminSelected = $_POST['adminSelect'];

    if ($adminSelected == '0'){
        $_SESSION['changeAccMsg'] = "No administrator selected!";
        header("Location: admin-account-select.php");
    }
    else{
        $adminDB = mysqli_connect('localhost', 'root', '', 'admin');

        if ($adminDB->connect_error){
            exit('Error connecting to the administrator database in the server!');
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $searchAdminStmt = $adminDB->prepare("SELECT * FROM credentials WHERE username = ?");
        $searchAdminStmt->bind_param("s", $adminSelected);
        $searchAdminStmt->execute();

        $resultSearch = $searchAdminStmt->get_result();
        if ($resultSearch->num_rows == 0){
            $_SESSION["changeAccMsg"] = "The administrator is not registered in the database. Please
                        register them through the Register Administrator page.";
        }
        else{
            while($row = $resultSearch->fetch_assoc()){
                $adminUsername = $row["username"];
                //$adminPassword = $row["password"];
            }
        }

        $_SESSION["referenceUserName"] = $adminUsername;
        $searchAdminStmt->close();
        mysqli_close($adminDB);
    }
}

?>

<html lang = "en">
<head>
    <title> [FOR PROPONENTS USE ONLY] Change Administrator Password</title>
    <link type = "text/css" rel="stylesheet" href ="css/register-admin-style.css"/>
</head>

<body>
<div class = "modifyAdminCon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"></script>

    <form method = "POST" action = "admin-account-update.php">
        <p class = "instructions">
            <center>
                <div class = "form-group">
                    <h1> Input New Administrator Password </h1>
                    <p class = "notification"> Enter the new password below for <b><?php echo $adminUsername?>.</b></p>
                    <input type="password" class = "password" name = "password" placeholder="Enter new password" required/>
                </div>
        <br/>
                <div class = "form-group">
                    <input type="submit" name="update-admin-password" class="btn btn-info" value="Update to New Administrator Password"/>
                </div>
            </center>
        </p>
    </form>
</div>
</body>
</html>
