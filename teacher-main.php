<?php
session_start();
print_r($_SESSION);
?>

<html>
<head>
    <title> Teacher Menu </title>
    <link type="text/css" rel="stylesheet" href="css/teacher-main-style.css"/>
</head>
<body>
<table class="tablelogin">
    <tr>
        <th class="headerColumn">
            <!-- temporary icon pls don't judge -->
            </br>
            <div class="logo">
                <img src="img/Group-H-Logo.png"/>
            </div>
            <h3> Teacher Menu </h3>
            <hr>
        </th>

        <!-- put the "page" thingy here that changes accordingly when user interacts with the placeholder links. how though???? -->
        <th rowspan="6">
            (temporary text. figure out how to display content upon link clicks)
        </th>
    </tr>
    <tr>
        <!-- Search: how to upload things here -->
        <td class="firstColumn"> &nbsp; </br> <a href="class-list-upload.php">Upload Class List </a></br> &nbsp;</td>
    </tr>
    <tr>
        <!-- should be a href link -->
        <td class="firstColumn"> &nbsp; </br> <a href="create-database.php"> Class Monitoring </a> </br> &nbsp;</td>
    </tr>
    <tr>
        <!-- should be a href link -->
        <td class="firstColumn"> &nbsp; </br> <a href="/"> Settings </a> </br> &nbsp;</td>
    </tr>
    <tr>
        <!-- i think this should be integrated with the mysql database? -->
        <!-- temporary logout php file -->
        <td class="firstColumn">
            &nbsp; </br> <a href="logout.php"> Log Out </a></br> &nbsp;
        </td>
    </tr>
    <tr>
        <td class="firstColumn">
            <hr>
            Group H - Web Application </br> For testing phase only </br> &nbsp;
        </td>
    </tr>
</table>
</body>
</html>