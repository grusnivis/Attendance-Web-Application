<!--
THIS PAGE (teacher-login-select.php) IS FOR DISPLAYING THE TEACHER DROPDOWN MENU TO BE SELECTED.
After selecting the teacher to be updated, it will
redirect to teacher-login-modify.php
-->

<?php
    session_start();

$checkTeacherLoginDB = mysqli_connect("localhost", "root", "");
$dbName = "teacher";

$query = "SHOW DATABASES LIKE '$dbName'";
$sqlStatement = $checkTeacherLoginDB->query($query);

if (!($sqlStatement->num_rows == 1)){ //if there are no databases with "teacher attendance" in the name
    $_SESSION["checkTeacherAttendanceDB"] = "There are no registered teachers in the web application.";
    mysqli_close($checkTeacherLoginDB);
    header("location: admin-main.php");
}
else{
    //proceed with the next processes. close the current database connection
    mysqli_close($checkTeacherLoginDB);
}
?>

<!-- HTML START -->
<html lang = 'en'>
<head>
    <title> Change Teacher Password </title>
    <link type = "text/css" rel="stylesheet" href ="css/register-teacher-style.css"/>
</head>

<body>
<div class = "selectTeacherPasswordCon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"></script>

    <form method = "POST" action = "teacher-login-modify.php">
        <h1>Change Teacher Password</h1>
        <p class = "instructions">
            Please select the registered teacher that you want to modify the password from in
            the dropdown box below:
            <br/>
            <br/>
            <!-- THIS PART CONNECTS TO THE TEACHER DATABASE - LOGIN TABLE AND OUTPUTS IT TO THE DROPDOWN BOX -->
            <?php
            //https://stackoverflow.com/questions/5189662/populate-a-drop-down-box-from-a-mysql-table-in-php
            $loginTableCon = new mysqli("localhost", "root", "", 'teacher');
            // Check connection
            if ($loginTableCon->connect_error) {
                die("Connection failed: " . $loginTableCon->connect_error);
            }

            $sqlQuery = "SELECT IDNumber, firstName, lastName FROM login ORDER BY lastName ASC";
            $result = mysqli_query($loginTableCon, $sqlQuery);

            echo "<select name = 'teacherSelect' class='dropup center-block' style='margin-left: 0%;padding: 5px;font-size:17px' required>";
            echo "<option disabled value = '0'>Select a Teacher</option>";
            while ($row = mysqli_fetch_array($result)){
                echo "<option value = '". $row['IDNumber'] ."'>". $row['firstName'] . " " . $row['lastName'] ."</option>";
            }
            echo "</select>";
            mysqli_close($loginTableCon);
            ?>
        </p>
        <div class="form-group">
            <!-- change button text through the value attribute -->
            <center><input type="submit" name="uploadBtn" class="btn btn-info" value="Modify Teacher Password"/></center>
            <hr/>
            <input type="submit" name="return-to-admin-main" class="btn btn-info" style="color:300px; color: white; background: #dc3545;" value="Return to Administrator Menu" formnovalidate/>
        </div>
        <?php
        if (isset($_SESSION['modifyLoginMsg']) && $_SESSION['modifyLoginMsg']) {
            echo '<p class = "notification">';
            echo $_SESSION["modifyLoginMsg"];
            echo '</p>';
            unset ($_SESSION["modifyLoginMsg"]);
        }
        ?>
    </form>
</div>
</body>
</html>

