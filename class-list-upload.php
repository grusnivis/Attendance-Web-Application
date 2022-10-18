<?php
//https://www.youtube.com/watch?v=4ytPkwCV05A
//https://www.webslesson.info/2017/09/how-to-store-form-data-in-csv-file-using-php.html
//https://stackoverflow.com/questions/9571125/cant-pass-php-session-variables-to-multiple-pages

//important for part 2
session_start();

//<!--- PART 1: CONFIG FILE
$error = '';

$markTeacher = '';
$teacherLate = '';
$teacherAbsent = '';
$baseStudent = '';
$studentLate = '';
$studentAbsent = '';
$classStart = '';
$classEnd = '';
$courseCode = '';
$groupNumber = '';

function clean_text($string)
{
    $string = trim($string); //remove whitespace from left and right side of string
    $string = stripslashes($string); //removes backslashes from string
    $string = htmlspecialchars($string); //converts predefined chars to html entities and store to string
    return $string;
}

if (isset ($_POST["submit"])) {
    //mark teacher = yes or no
    if (empty($_POST['mark-teacher'])) {
        $error .= '<p> No MARK TEACHER input </p>';
    } else if (strtoupper($_POST['mark-teacher']) != ("YES" or "NO")) { //idk if this works tho LMAOOOO
        $error .= '<p> Wrong MARK TEACHER input. Only YES and NO are accepted. </p>';
    } else {
        $markTeacher = strtoupper($_POST['mark-teacher']);
    }

    //teacher late = minutes
    if (empty($_POST['teacher-late'])) {
        $error .= '<p> No Teacher Late Input </p>';
    } else if (!is_numeric($_POST['teacher-late'])) {
        $error .= '<p> Input TEACHER LATE time in minutes only. </p>';
    } else {
        $teacherLate = $_POST['teacher-late'];
    }

    //teacher absent = minutes
    if (empty($_POST['teacher-absent'])) {
        $error .= '<p> No TEACHER ABSENT Input </p>';
    } else if (!is_numeric($_POST['teacher-absent'])) {
        $error .= '<p> Input TEACHER ABSENT time in minutes only. </p>';
    } else {
        $teacherAbsent = $_POST['teacher-absent'];
    }

    //base-student = yes or no
    if (empty($_POST['base-student'])) {
        $error .= '<p> No BASE STUDENT ON TEACHER ATTENDANCE input </p>';
    } else if (strtoupper($_POST['base-student']) != ("YES" or "NO")) { //idk if this works tho LMAOOOO
        $error .= '<p> Wrong BASE STUDENT ON TEACHER ATTENDANCE input. Only YES and NO are accepted. </p>';
    } else {
        $baseStudent = strtoupper($_POST['base-student']);
    }

    //student late = minutes
    if (empty($_POST['student-late'])) {
        $error .= '<p> No STUDENT LATE Input </p>';
    } else if (!is_numeric($_POST['student-late'])) {
        $error .= '<p> Input STUDENT LATE time in minutes only. </p>';
    } else {
        $studentLate = $_POST['student-late'];
    }

    //student absent = minutes
    if (empty($_POST['student-absent'])) {
        $error .= '<p> No STUDENT ABSENT Input </p>';
    } else if (!is_numeric($_POST['student-absent'])) {
        $error .= '<p> Input STUDENT ABSENT time in minutes only. </p>';
    } else {
        $studentAbsent = $_POST['student-absent'];
    }

    //tbh i don't know how to check for the time?
    $classStart = $_POST['class-start'];
    $classEnd = $_POST['class-end'];

    //course-code - the course name in acronym and number
    if (empty($_POST['course-code'])) {
        $error .= '<p> No COURSE CODE Input </p>';
    } else {
        $courseCode = $_POST['course-code'];
    }

    //group-number = number
    if (empty($_POST['group-number'])) {
        $error .= '<p> No GROUP NUMBER Input </p>';
    } else if (!is_numeric($_POST['group-number'])) {
        $error .= '<p> Input GROUP NUMBER as a number only. </p>';
    } else {
        $groupNumber = $_POST['group-number'];
    }

    //'a' mode lets file pointer to the end of file
    //one array = one row!
    $config_csv[0] = array("MARK TEACHER ATTENDANCE", 'mark-teacher' => $markTeacher);
    $config_csv[1] = array("TEACHER LATE", 'teacher-late' => $teacherLate);
    $config_csv[2] = array("TEACHER ABSENT", 'teacher-absent' => $teacherAbsent);
    $config_csv[3] = array("BASE STUDENT ON TEACHER ATTENDANCE", 'base-student' => $baseStudent);
    $config_csv[4] = array("STUDENT LATE", 'student-late' => $studentLate);
    $config_csv[5] = array("STUDENT ABSENT", 'student-absent' => $studentAbsent);
    $config_csv[6] = array("CLASS START", 'class-start' => $classStart);
    $config_csv[7] = array("CLASS END", 'class-end' => $classEnd);

    //https://stackoverflow.com/questions/15501463/creating-csv-file-with-php
    //working with date and time - https://code.tutsplus.com/tutorials/working-with-date-and-time-in-php--cms-31768

    $hourStart = date('g:i A', strtotime($classStart));
    $hourEnd = date('g:i A', strtotime($classEnd));

    //str_replace(find, replace, string, count). count is optional
    $hourStart = str_replace(':', '', $hourStart);
    $hourEnd = str_replace(":", '', $hourEnd);

    $fileOutput = $courseCode . "_g" . $groupNumber . "_S-" . $hourStart . "-" . $hourEnd . "-config" . ".csv";
    if ($error == '') {
        $file_open = fopen($fileOutput, "w+");
        foreach ($config_csv as $line) {
            fputcsv($file_open, $line, ',');
        }

        fclose($file_open);

        //move the created file to the currently logged in user's designated folder
        /* similar to this: https://www.javatpoint.com/php-mysql-login-system */
        //database credentials, running MySQL with default setting (user 'root' with no password)
        define('DB_SERVER', 'localhost'); //host name
        define('DB_USERNAME', 'root'); //host password
        define('DB_PASSWORD', ''); //database password
        define('DB_NAME', 'teacher'); //database name to connect to (teacher)

        //moving to the logged in user's folder
        //attempt to connect to MySQL database
        $databaseLink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        //check the connection to the database
        if ($databaseLink == false) {
            //die() kinda functions like an exit() function
            die("Error connecting to the server." . mysqli_connect_error());
        }

        //on the 'teacher' database, 'login' table in phpmyadmin, search for the id number in the session array
        //mysql and sessions (use curly braces) https://stackoverflow.com/questions/5746614/session-variable-in-mysql-query
        $sql = "SELECT *FROM login WHERE IDNumber = {$_SESSION['currentUser']}";
        //$result = mysqli_query($databaseLink, $sql);
        //$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$count = mysqli_num_rows($result);

        if ($result = $databaseLink->query($sql)) {
            //test later: https://www.tutorialspoint.com/fetch-a-specific-column-value-name-in-mysql
            //see solution here for sessions: https://www.simplilearn.com/tutorials/php-tutorial/php-login-form
            while ($row = $result->fetch_assoc()) {
                //set the $row[""] to the column you want to use
                $firstName = $row["firstName"];
                $lastName = $row["lastName"];
            }
        }

        //move the file to the logged-in user's folder
        rename("$fileOutput", "./" . "$firstName" . " " . "$lastName" . "/" . "$fileOutput");


        $error = '<p>Personal configurations set!</p>';

        $markTeacher = '';
        $teacherLate = '';
        $teacherAbsent = '';
        $baseStudent = '';
        $studentLate = '';
        $studentAbsent = '';
        $classStart = '';
        $classEnd = '';
    }
}

/**
 * //<!--- PART 2: Move the created CSV file to the Teacher (First Name Last Name) folder. see line 7
 * $sqliConnect = mysqli_connect("localhost", "root", "", "teacher");
 *
 * if ($sqliConnect->connect_error){
 * die("Connection failed: " . $sqliConnect->connect_error);
 * }
 *
 * //put to the database
 * //nts: https://www.w3schools.com/php/php_mysql_select_where.asp
 * $sqliSelect = "SELECT first_name, last_name FROM login WHERE username = '$currentUser'";
 * $result =
 **/

?>

<html lang='en'>
<head>
    <title> Upload Class Lists </title>
    <link type="text/css" rel="stylesheet" href="css/class-list-upload-style.css"/>
    <!-- this PHP file is responsible for displaying the file upload form!
         the file for uploading the lists to the server is the class-list-server.php -->
</head>
<body>
<?php
if (isset($_SESSION['message']) && $_SESSION['message']) {
    echo '<p class = "notification">' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']);
}
?>
<!--
When you use the multipart/form-data value for the enctype attribute,
it allows you to upload files using the POST method.
Also, it makes sure that the characters are not encoded
when the form is submitted.
https://code.tutsplus.com/tutorials/how-to-upload-a-file-in-php-with-example--cms-31763
-->

<!-- <div class = "upload-wrapper"> -->
<div class="main-con">
    <form method="POST" action="class-list-server.php" enctype="multipart/form-data">
        <h1> Class List Uploading </h1>
        <p class="instructions"> Return to the Teacher Menu <a href="teacher-main.php">here</a>.</p>
        <p class="instructions"> Upload your Class List File that is in .CSV format. </p>
        <div class="upload-con">
            <label for="file-upload"> Browse <input type="file" id="file-upload" name="uploadedFile"> </label>
        </div>
        <br>
        <input type="submit" name="uploadBtn" value="Upload"/>
    </form>

    <hr>

    <!-- important for the configuration file form! -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <form method="post">
        <h1>
            <center> Set Personal Configurations</center>
        </h1>
        <p class="instructions">
            This is where you will configure your class settings for the Attendance Logging Device. A Comma-Separated
            Values file (.CSV)
            will be generated at the set directory after you click the Submit button.
        </p>
        <br>
        <table>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u> <b>MARK TEACHER</b></u></p>
                        <p class="instructions"> This is where the (???). Please input <i><b>YES or NO</b></i> only.
                        </p>
                    </td>
                    <td>
                        <input type="text" class="fieldSettings" placeholder="YES or NO" name="mark-teacher"
                               value="<?php echo $markTeacher; ?>"/>
                    </td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>TEACHER LATE</b></u></p>
                        <p class="instructions"> This is where the (???). Please input the time in
                            <i><b>minutes.</b></i></p>
                    </td>
                    <td><input type="text" class="fieldSettings" placeholder="Time in Minutes" name="teacher-late"
                               value="<?php echo $teacherLate; ?>"/></td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>TEACHER ABSENT</b></u></p>
                        <p class="instructions">This is where the (???). Please input the time in <i><b>minutes.</b></i>
                        </p>
                    </td>
                    <td><input type="text" class="fieldSettings" placeholder="Time in Minutes" name="teacher-absent"
                               value="<?php echo $teacherAbsent; ?>"/></td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>BASE STUDENT ATTENDANCE ON TEACHER TAP</b></u></p>
                        <p class="instructions">This is where the (???). Please input <i><b>YES or NO </b></i>only.</p>
                    </td>
                    <td><input type="text" class="fieldSettings" placeholder="YES or NO" name="base-student"
                               value="<?php echo $baseStudent; ?>"/> <br></td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>STUDENT LATE</b></u></p>
                        <p class="instructions">This is where the (???). Please input the time in <i><b>minutes.</b></i>
                        </p>
                    </td>
                    <td><input type="text" class="fieldSettings" placeholder="Time in Minutes" name="student-late"
                               value="<?php echo $studentLate; ?>"/></td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>STUDENT ABSENT</b></u></p>
                        <p class="instructions">This is where the (???). Please input the time in <i><b>minutes.</b></i>
                        </p>
                    </td>
                    <td><input type="text" class="fieldSettings" placeholder="Time in Minutes" name="student-absent"
                               value="<?php echo $studentAbsent; ?>"/></td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>CLASS START</b></u></p>
                        <p class="instructions">This should be the start time of the class. Input the time in <i><b>24-hour
                                    format.</b></i></p>
                    </td>
                    <td><input type="text" class="fieldSettings" placeholder="Time in 24-hour Format" name="class-start"
                               value="<?php echo $classStart; ?>"/></td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>CLASS END</b></u></p>
                        <p class="instructions">This should be the end time of the class. Input the time in <i><b>24-hour
                                    format.</b></i></p>
                    </td>
                    <td><input type="text" class="fieldSettings" placeholder="Time in 24-hour Format" name="class-end"
                               value="<?php echo $classEnd; ?>"/></td>
                </div>
            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>COURSE CODE</b></u></p>
                        <p class="instructions">This is the course code for the class. Format should be: e.g. <i><b>CPE-3202,
                                    EM-1202</b></i></p>
                    </td>
                    <td><input type="text" class="fieldSettings" placeholder="Course Code Number" name="course-code"
                               value="<?php echo $courseCode; ?>"/></td>
                </div>


            </tr>

            <tr>
                <div class="form-group">
                    <td>
                        <p class="config-titles"><u><b>GROUP NUMBER</b></u></p>
                        <p class="instructions">This is the group number for the class. Please input
                            <i><b>numbers</b></i> only.</p>
                    </td>
                    <td><input type="text" class="fieldSettings" placeholder="Group Number" name="group-number"
                               value="<?php echo $groupNumber; ?>"/></td>
                </div>
            </tr>
        </table>

        <div class="form-group">
            <!-- change button text through the value attribute -->
            <input type="submit" name="submit" class="btn btn-info" value="Set Configurations"/>
        </div>
    </form>
</div>
</body>
</html>