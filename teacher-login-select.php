<!--
THIS PAGE (teacher-login-select.php) IS FOR DISPLAYING THE TEACHER DROPDOWN MENU TO BE SELECTED.
After selecting the teacher to be updated, it will
redirect to teacher-login-modify.php
-->

<!-- HTML START -->
<html lang = 'en'>
<head>
    <title> Modify Teacher Credentials </title>
    <link type = "text/css" rel="stylesheet" href ="css/register-teacher-style.css"/>
</head>

<body>
<div class ="selectLoginCon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <form method = "POST" action = "teacher-login-modify.php">
        <h1>Change Teacher Password</h1>
        <p class = "instructions">
            Please select the registered teacher that you want to modify the details from in
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

            echo "<select name = 'teacherSelect' class='dropup center-block' style='margin-left: 0%'>";
            echo "<option value = '0'>Select a Teacher</option>";
            while ($row = mysqli_fetch_array($result)){
                echo "<option value = '". $row['IDNumber'] ."'>". $row['firstName'] . " " . $row['lastName'] ."</option>";
            }
            echo "</select>";
            mysqli_close($loginTableCon);
            ?>
        </p>
        <div class="form-group">
            <!-- change button text through the value attribute -->
            <center><input type="submit" name="uploadBtn" class="btn btn-info" value="Modify Teacher Details"/></center>
        </div>
        <?php
	        $conn = new mysqli('localhost', 'root', '', 'temp');
	        // Obtain last value of variable user as 1 row
	        // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	        $sql = "SELECT val FROM temptb WHERE varname = 'modifyLoginMsg' ORDER BY id DESC LIMIT 1";
	        $result = mysqli_query($conn, $sql);
	        if (mysqli_num_rows($result) > 0) {
		        $row = mysqli_fetch_assoc($result);
		        $tempvar1 = $row["val"];
		        mysqli_close($conn);
	        }
	        if (isset($tempvar1) && $tempvar1) {
	        echo '<p class = "notification">';
	        echo $tempvar1;
	        echo '</p>';
	        unset ($tempvar1);
	        $conn = new mysqli("localhost", "root", "", "temp");
	        // Check connection
	        if ($conn->connect_error) {
		        die("Connection failed: " . $conn->connect_error);
	        }
	        $sql = "INSERT INTO temptb (varname, val) VALUES ('modifyLoginMsg', '')";
	
	        if (mysqli_query($conn, $sql)) {
		        mysqli_close($conn);
	        }
        }
        ?>
    </form>
</div>
</body>
</html>

