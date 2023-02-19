<!--
This page is for changing the teacher's password, in that they know their password
but they want to change it. this page is located at 2-create-table.php
-->
<?php
session_start();
?>

<html lang="en">
<head>
    <title> Change Teacher Password </title>
    <link type="text/css" rel="stylesheet" href="css/register-teacher-style.css"/>
</head>

<body>
<div class="modifyLoginCon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"></script>

    <form method="POST" action="teacher-change-password-server.php" enctype="multipart/form-data">
        <p class="instructions">
        <div class="form-group">
            <h1> Change Teacher Password</h1>
            <p class="notification">Change your password in the text field below.</p>
            <input type="password" class="password" name="teacherPassword" placeholder="New Password" required/>
        </div>
        <div class="form-group">
            <input type="submit" name="change-teacher-password" class="btn btn-info" value="Update Password"/>
        </div>
        </p>
        <hr/>
        <input type="submit" name="return-to-create-table" class="btn btn-info"
               style="color: white; background: #dc3545;" value="Return to Teacher Main Menu" formnovalidate/>
    </form>
    <!-- text prompt if updating the password is successful or not -->
    <?php
    if (isset($_SESSION["teacherPasswordMsg"]) && $_SESSION["teacherPasswordMsg"]) {
        echo '<p class = "notification">' . $_SESSION["teacherPasswordMsg"] . '</p>';
        unset($_SESSION["teacherPasswordMsg"]);
    }
    ?>
</div>
</body>
</html>
