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
    <p> Choose from the options below.</p>
        <input type="submit" name="register-teacher" class="btn btn-info" value="Register Teacher on Web Application"/>
    <br/>
        <input type="submit" name="teacher-password" class="btn btn-info" value="Reset Teacher Password"/>
    <br/>
        <input type="submit" name="drop-export-tables" class="btn btn-info" value="Drop and Export Databases"/>
    <br/>
        <input type="submit" name="admin-logout" class="btn btn-info" value="Log out Administrator Account"/>
    </form>
</div>
</body>
</html>
