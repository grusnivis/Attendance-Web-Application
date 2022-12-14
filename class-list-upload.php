<?php
//https://www.youtube.com/watch?v=4ytPkwCV05A
//https://www.webslesson.info/2017/09/how-to-store-form-data-in-csv-file-using-php.html
//https://stackoverflow.com/questions/9571125/cant-pass-php-session-variables-to-multiple-pages

//important for part 2
session_start();
header("Cache-Control: no-cache, must-revalidate");
?>

<html lang='en'>
<head>
    <title> Upload Class Lists </title>
    <link type="text/css" rel="stylesheet" href="css/class-list-upload-style.css"/>
    <!-- this PHP file is responsible for displaying the file upload form!
         the file for uploading the lists to the server is the class-list-server.php -->
</head>
<body>

<!--
When you use the multipart/form-data value for the enctype attribute,
it allows you to upload files using the POST method.
Also, it makes sure that the characters are not encoded
when the form is submitted.
https://code.tutsplus.com/tutorials/how-to-upload-a-file-in-php-with-example--cms-31763
-->
<!-- <div class = "upload-wrapper"> -->
<div class= "topnav">
    <!-- Search: how to upload things here -->
    <a href="class-list-upload.php">Upload Class List </a>
    <a href="2-create-table.php"> Class Monitoring </a>
    <a href="logout.php"> Log Out </a> &nbsp;
    <?php
    echo "<p class = 'welcome'> Welcome, " . $_SESSION['currentUser'] . "! </p>";
    ?>
</div>
    <form method="POST" action="class-list-server.php" enctype="multipart/form-data">
        <input type ="hidden" name="MAX_FILE_SIZE" value = 30000"/>
        <h1> <center> Class List Uploading </center></h1>
        <div class= "topnav">
            <!-- Search: how to upload things here -->
            <a href="class-list-upload.php">Upload Class List </a>
            <a href="2-create-table.php"> Class Monitoring </a>
            <a href="logout.php"> Log Out </a> &nbsp;
            <?php
            echo "<p class = 'welcome'> Welcome, " . $_SESSION['currentUser'] . "! </p>";
            ?>
        </div>
        <p class="instructions"> Upload your Class List File that is in .CSV format. </p>
        <?php
        if (isset($_SESSION['message']) && $_SESSION['message']) {
            echo '<p class = "notification">' . $_SESSION['message'] . '</p>';
            unset($_SESSION['message']);
        }
        ?>
        <div class="upload-con">
            <table>
                <tr>
                    <td>
                        <p class="instructions">Select the team teach partner for this particular class:</p>
                        <?php
                        //https://stackoverflow.com/questions/5189662/populate-a-drop-down-box-from-a-mysql-table-in-php
                        $conn = new mysqli("localhost", "root", "", 'teacher');
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sqlQuery = "SELECT IDNumber, firstName, lastName FROM login ORDER BY lastName ASC";
                        $result = mysqli_query($conn, $sqlQuery);

                        echo "<select name = 'partner' class='dropup center-block' style='margin-left: 0%'>";
                        echo "<option value = '0'>NOT A TEAM TEACH CLASS</option>";
                        while ($row = mysqli_fetch_array($result)){
                            //exclude current user in display: https://stackoverflow.com/questions/1248641/php-how-to-exclude-data-from-mysql
                            if($row['IDNumber'] == $_SESSION["currentUser"]){
                                continue;
                            };
                            echo "<option value = '". $row['IDNumber'] ."'>". $row['IDNumber'] . " - " .$row['firstName'] . " " . $row['lastName'] ."</option>";
                        }
                        echo "</select>";
                        mysqli_close($conn);
                        ?>
                    </td>

                    <td>
                        <label for="file-upload" > Browse <input type="file" id="file-upload" name="uploadedFile"> </label>
                    </td>
                </tr>
            </table>
            <br>
        </div>
        <br>
    <hr>

    <!-- important for the configuration file form! -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <h1>
            <center> Set Personal Configurations</center>
        </h1>
        <p class="instructions">
            This is where you will configure your uploaded class list settings for the Attendance Logging Device to use. A Comma-Separated
            Values file (.CSV) will be generated at the set directory after you click the <b><u>Upload Class List and Set Configurations</u></b> button below.
        </p>
        <br>
        <table>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u> <b>MARK TEACHER ATTENDANCE</b></u></p>
                        <p class="instructions">
                            If you wish to mark your personal attendance based on the <u>time of your class</u>,
                            input <b><i>YES</i></b>. If you wish for your attendance to always be <u>PRESENT</u>
                            regardless of the time from the start of your class, input <b><i>NO</i></b>.
                        </p>
                    </td>
                    <td>
                        <div class = 'dropdown-con'>
                            <?php
                            echo "<select name = 'mark-teacher' style = 'width: 150px'>";
                            echo "<option value = 'NO'>NO </option>";
                            echo "<option value = 'YES'>YES</option>";
                            echo "</select>";
                            ?>
                        </div>
                        <p class = "instructions"> <i>The default selection for this setting is  <b><u>NO</u></b></i>.</p>
                    </td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>TEACHER LATE</b></u></p>
                        <p class="instructions">
                            If MARK TEACHER is set as YES, what time would you be marked LATE? The time is set in <b>minutes</b>.
                        </p>
                    </td>
                    <td>
                        <input type="number" min = "00" max = "59" class="fieldSettings" name="teacher-late" value="10"/>
                        <p class = "instructions"> <i>The default time for this setting is <b><u>10</u></b> minutes.</i></p>
                    </td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>TEACHER ABSENT</b></u></p>
                        <p class="instructions">
                            If MARK TEACHER is set as YES, what time would you be marked ABSENT? The time is set in <b>minutes</b>.
                        </p>
                    </td>
                    <td>
                        <input type="number" min = "00" max = "59" class="fieldSettings" name="teacher-absent" value="15"/>
                        <p class = "instructions"> <i>The default time for this setting is <b><u>15</u></b> minutes.</i></p>
                    </td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>BASE STUDENT ATTENDANCE ON TEACHER TAP</b></u></p>
                        <p class="instructions">
                            If you would like your students' attendance status (PRESENT, LATE, ABSENT)
                            to be based on the class start time, input <b><i>NO</i></b>. If you want their attendance to be
                            based on your initial ID tap on the device, input <b><i>YES</i></b>.
                    </td>
                    <td>
                        <div class = 'dropdown-con'>
                            <?php
                            echo "<select name = 'student-attendance' style = 'width: 150px'>";
                            echo "<option value = 'YES'>YES</option>";
                            echo "<option value = 'NO'>NO</option>";
                            echo "</select>";
                            ?>
                        </div>
                        <p class = "instructions"> <i>The default selection for this setting is <b><u>YES</u></b></i>.</p>
                    </td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>STUDENT LATE</b></u></p>
                        <p class="instructions">
                            How many minutes from the start of attendance until the student is marked as <u>LATE</u>? The time
                            is set in <b>minutes</b>.
                        </p>
                    </td>
                    <td><input type="number" min = "00" max = "59" class="fieldSettings" name="student-late" value="15"/>
                        <p class = "instructions"> <i>The default time for this setting is <b><u>15</u></b> minutes.</i></p>
                    </td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>STUDENT ABSENT</b></u></p>
                        <p class="instructions">
                            How many minutes from the start of attendance until the student is marked as <u>ABSENT</u>? The time
                            is set in <b>minutes</b>.
                        </p>
                    </td>
                    <td>
                        <input type="number" min = "00" max = "59" class="fieldSettings" name="student-absent" value="30"/>
                        <p class = "instructions"> <i>The default time for this setting is <b><u>30</u></b> minutes.</i></p>
                    </td>
                </div>
            </tr>
        </table>

        <div class="form-group">
            <!-- change button text through the value attribute -->
            <center><input type="submit" name="uploadBtn" class="btn btn-info" value="Upload Class List and Set Configurations"/></center>
        </div>
    </form>
</body>
</html>