<?php

session_start();

?>

<html>
<head>
<title> Administrator Menu</title>
    <link type = "text/css" rel = "stylesheet" href = "css/admin-main-style.css";
</head>
<body>
<div class = "adminMaincon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <form method="POST" action="admin-main-redirect.php" enctype="multipart/form-data">

    <h1> Administrator Menu</h1>
    <p> Welcome to the administrator account! Please choose from the options below.</p>
        <input type="submit" name="register-teacher" class="btn btn-info" style="width:300px" value="Register Teacher"/>
    <br/>
        <input type="submit" name="teacher-password" class="btn btn-info" style="width:300px" value="Change Teacher Password"/>
    <br/>
        <input type="submit" name="teacher-attendance" class="btn btn-info" style="width:300px" value="View Teacher Attendance"/>
    <br/>
        <input type="submit" name="drop-export-tables" class="btn btn-info" style="width:300px" value="Delete and Export Databases"/>
    <br/>
        <hr/>
        <input type="submit" name="admin-logout" class="btn btn-info" style="width:300px" value="Log out"/>
    </form>
</div>
</body>
</html>
