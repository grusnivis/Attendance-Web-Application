<?php
session_start();
?>

<html lang = 'en'>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title> Delete and Export Teacher Databases </title>
    <link type="text/css" rel="stylesheet" href="css/database-export-drop-style.css"/>
</head>

<body>
<div class = "database-export-drop-con">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <h1> Delete and Export Teacher Databases</h1>
    <form method="POST" action="database-actions.php" enctype="multipart/form-data">
        <h3><u>EXPORT TEACHER'S DATABASE</u></h3>
        <!-- THIS IS FOR EXPORTING DATABASES -->
        <p> This action will export the selected teacher's database in a .sql format.</p>

        <!-- THIS PART CONNECTS TO THE TEACHER DATABASE - LOGIN TABLE AND OUTPUTS IT TO THE DROPDOWN BOX -->
        <?php
        //https://stackoverflow.com/questions/5189662/populate-a-drop-down-box-from-a-mysql-table-in-php
        //connect to the server
        $serverConnect = mysqli_connect('localhost','root','');
        if ($serverConnect->connect_error){
            exit('Error connecting to the server.');
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        //put all existing databases in phpmyadmin in array
        $showDatabaseStmt = "SHOW DATABASES";
        $result = mysqli_query($serverConnect,$showDatabaseStmt);
        $databaseList = array();

        while ($row = $result->fetch_assoc()){
            //echo $databaseList['Database'] . "<br/>";
            //store the result array to the provided array
            $databaseList[] = $row['Database'];
        }

        $i = 0;
        $count = count($databaseList);

        echo "<select name = 'dbTeacherSelect' class='dropup center-block' style='margin-left: 0%;padding: 5px;font-size:17px' required>";
        echo "<option disabled value = '0'>Select a Teacher</option>";

        while ($i < $count){
            if ($databaseList[$i] != "admin" &&
                $databaseList[$i] != "teacher" &&
                $databaseList[$i] != "authorized users" &&
                $databaseList[$i] != "temp" &&
                $databaseList[$i] != "information_schema" &&
                $databaseList[$i] != "performance_schema" &&
                $databaseList[$i] != "phpmyadmin" &&
                $databaseList[$i] != "mysql" &&
                $databaseList[$i] != "teacher attendance" &&
                $databaseList[$i] != "teamteach" &&
                $databaseList[$i] != "masterlist"){
                //skip those databases. do not export them
                $dbListVar = $databaseList[$i];
                echo "<option value = '". $databaseList[$i] ."'>". mb_strtoupper($databaseList[$i]) ."</option>";
            }
            $i++;
        }
        echo "</select>";
        mysqli_close($serverConnect);
        ?>

        <input type="submit" name="database-export" class="btn btn-info" style="width:300px" value="Export Selected Teacher Database"/>
        <br class = "breakspaceForNotif"/>
        <!-- message prompt for exporting database -->

        <?php
	        // Create connection directly to specific database
	        $conn = new mysqli('localhost', 'root', '', 'temp');
	        // Obtain last value of variable user as 1 row
	        // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	        $sql = "SELECT val FROM temptb WHERE varname = 'exportTeacherDBMsg' ORDER BY id DESC LIMIT 1";
	        $result = mysqli_query($conn, $sql);
	        if (mysqli_num_rows($result) > 0) {
		        $row = mysqli_fetch_assoc($result);
		        $tp5 = $row["val"];
		        mysqli_close($conn);
	        }
        if (isset($tp5) && $tp5) {
	        echo '<p class = "notification">' . $tp5 . '</p>';
	        $conn = new mysqli("localhost", "root", "", "temp");
	        // Check connection
	        if ($conn->connect_error) {
		        die("Connection failed: " . $conn->connect_error);
	        }
	        $sql = "INSERT INTO temptb (varname, val) VALUES ('exportTeacherDBMsg', '')";
	
	        if (mysqli_query($conn, $sql)) {
		        mysqli_close($conn);
	        }
        }
        ?>
        <hr/>

        <!--THIS PART IS FOR THE DROPPING OF DATABASES-->
        <h3><u>DELETE ALL DATABASES</u></h3>
        <p> This action will delete <b><u>all currently enrolled teachers</u></b> in the attendance monitoring system.</p>
        <input type="submit" name="database-drop" class="btn btn-info" style="width:300px" value="Delete All Databases"/>
        <!-- message prompt for dropping databases-->
        <br class = "breakspaceForNotif"/>
        <?php
	        // Create connection directly to specific database
	        $conn = new mysqli('localhost', 'root', '', 'temp');
	        // Obtain last value of variable user as 1 row
	        // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	        $sql = "SELECT val FROM temptb WHERE varname = 'dropTeacherDBMsg' ORDER BY id DESC LIMIT 1";
	        $result = mysqli_query($conn, $sql);
	        if (mysqli_num_rows($result) > 0) {
		        $row = mysqli_fetch_assoc($result);
		        $tv2 = $row["val"];
		        mysqli_close($conn);
	        }
        if (isset($tv2) && $tv2) {
	        echo '<p class = "notification"><b>' . $tv2 . '</b></p>';
	
	        $conn = new mysqli("localhost", "root", "", "temp");
	        // Check connection
	        if ($conn->connect_error) {
		        die("Connection failed: " . $conn->connect_error);
	        }
	        $sql = "INSERT INTO temptb (varname, val) VALUES ('dropTeacherDBMsg', '')";
	
	        if (mysqli_query($conn, $sql)) {
		        mysqli_close($conn);
	        }
        }
        ?>

        <hr/>
        <!-- returns to the administrator menu-->
        <input type="submit" name="return-to-admin-main" class="btn btn-info" value="Return to Administrator Menu" formnovalidate/>
    </form>
</div>
</body>
</html>
