<!-- HTML start -->
<html lang='en'>
<head>
    <title> Register Teacher </title>
    <link type="text/css" rel="stylesheet" href="css/register-teacher-style.css"/>
    <!-- this PHP file is responsible for registering teachers!
    stylesheet is from the Uploading Class List style. -->
</head>

<body>

<div class="registerTeacherCon">
    <!-- important for the register teacher form! -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <form method="POST" action="register-teacher-server.php" enctype="multipart/form-data" accept-charset="utf-8">
        <table>
            <h1> Register Teacher </h1>
            <p class="instructions">
                Fill up all the text fields below for registering <br/> the teacher into the Attendance Monitoring System.</br>
                <i>A randomly generated password will be sent to the teacher's email address after clicking the <b>Register</b> button.</i></a>
            </p>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="teacherInfo"><b>FIRST NAME</b></p>
                    </td>
                    <td>
                        <input type="text" class="firstName" placeholder="First Name" name="first-name" required/>
                    </td>
                </div>
            </tr>

            <div class="form-group">
                <tr>
                    <td>
                        <p class="teacherInfo"><b>LAST NAME</b></p>
                    </td>
                    <td>
                        <input type="text" class="lastName" placeholder="Last Name" name="last-name" required/>
                    </td>
                </tr>
            </div>

            <div class="form-group">
                <tr>
                    <td>
                        <p class="teacherInfo"> <b>ID NUMBER</b></p>
                    </td>
                    <td>
                        <!-- retain type = "text" due to teachers having letters in their ID number -->
                        <input type="text" class="IDNum" placeholder="ID Number" name="IDNum" required/>
                    </td>
                </tr>
            </div>

            <!--
            <div class="Password">
                <tr>
                    <td>
                        <p class="teacherInfo"><b>PASSWORD</b></p>
                    </td>
                    <td>
                        <input type="password" class="password" placeholder="Password" name="password" required/>
                    </td>
                </tr>
            </div>
            -->

            <div class="form-group">
                <tr>
                    <td>
                        <p class="teacherInfo"><b>EMAIL</b></p>
                    </td>
                    <td>
                        <!-- use input type = "email" for automatic email validation -->
                        <input type="email" class="email" placeholder="Email" name="email" required/>
                    </td>
                </tr>
            </div>

            <div class="form-group">
                <tr>
                    <td colspan="2" align="center">
                        <!-- change button text through the value attribute -->
                        <input type="submit" name="register" class="btn btn-info" style="width:230px" value="Register"/>
                    </td>
                </tr>
            </div>
    </form>
    </table>
    <br/>
    <hr/>
    <input type="submit" name="return-to-admin-main" class="btn btn-info" style=";color: white; background-color:#0b8f47;" value="Return to Administrator Menu" formnovalidate/>
    <!-- THIS PART IS FOR DISPLAYING IF THE PUSHING TO LOGIN TABLE AND CREATING USER DATABASE IS SUCCESSFUL -->
    <?php
	    // Create connection directly to specific database
	    $conn = new mysqli('localhost', 'root', '', 'temp');
	    // Obtain last value of variable user as 1 row
	    // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
	    $sql = "SELECT val FROM temptb WHERE varname = 'registerTeacherMsg' ORDER BY id DESC LIMIT 1";
	    $result = mysqli_query($conn, $sql);
	    if (mysqli_num_rows($result) > 0) {
		    $row = mysqli_fetch_assoc($result);
		    $tempvar1 = $row["val"];
	    }
	    if (isset($tempvar1) && $tempvar1) {
            if ($tempvar1 == "The ID Number is already registered in either the Teacher or the Authorized Users databases."){
                echo '<p class = "notification" style = "color:#d9534f"><b>';
                echo $tempvar1;
                echo '</b></p>';
            }
            else{
                echo '<p class = "notification" style = "color:#0b8f47"><b>';
                echo $tempvar1;
                echo '</b></p>';
            }
	    
	    $sql = "INSERT INTO temptb (varname, val) VALUES ('registerTeacherMsg', '')";
	    if (mysqli_query($conn, $sql)) {
		    mysqli_close($conn);
	    }
    }
    ?>
</div>
</body>
</html>