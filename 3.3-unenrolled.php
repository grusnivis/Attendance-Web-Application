<?php
ob_start(); //important to retain the inputs in the textboxes

include '0-connect.php';
$conn = new mysqli("localhost", "root", "", "temp");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT val FROM temptb WHERE varname = 'table' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $tempvar1 = $row["val"];
}
$cg = $tempvar1;

$sql = "SELECT val FROM temptb WHERE varname = 'findStudentRFID' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $te2 = $row["val"];
    $findStudentRFID = $te2;
} else {
    $findStudentRFID = '';
}

$sql = "INSERT INTO temptb (varname, val) VALUES ('table', '$tempvar1')";

if (mysqli_query($conn, $sql)) {
    mysqli_close($conn);
}
//$_SESSION['table'] = $cg;
$studentIDNumber = "";
$studentLastName = "";
$studentFirstName = "";
//$class = $_SESSION['Class Selected'];
?>

<html>
<head>
    <meta http-equiv="Content-Type"
          content="text/html; charset=UTF-8">

    <title>View Attendance</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="css/unenrolled-monitoring-style.css"/>

    <!--<link rel="stylesheet" type="text/css"
        href="css/monitoring-style.css"> -->
</head>


<body style="background-color: #eaeaea">

<!--THIS PART WILL EXECUTE IF THE USER CLICKS THE "UPDATE STUDENT RFID INFORMATION" BUTTON -->
<?php
if (isset($_POST['update-info']) && $_POST['update-info'] == "UPDATE STUDENT RFID INFORMATION") {
    $rfid_tag = $findStudentRFID;
    $id_number = trim(strtoupper($_POST['studentIDNum']));
    $lastname = trim(strtoupper($_POST['studentLName']));
    $firstname = trim(strtoupper($_POST['studentFName']));


    $query = "UPDATE `$cg` SET ID = '$id_number', Surname = '$lastname', Name = '$firstname' WHERE RFID = '$rfid_tag'";
    $query_run = mysqli_query($db, $query);

    if ($query_run) {
        //echo '<script> alert("Data Updated"); </script>';
        $conn = new mysqli("localhost", "root", "", "temp");
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO temptb (varname, val) VALUES ('updateStudentRFIDMsg', 'Updated the selected RFID successfully!')";

        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);
        }
        //$_SESSION["updateStudentRFIDMsg"] = "Updated the selected RFID successfully!";
    } else {
        $conn = new mysqli("localhost", "root", "", "temp");
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO temptb (varname, val) VALUES ('updateStudentRFIDMsg', 'Updating the selected RFID unsuccessful. Please try again.')";

        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);
        }
        //$_SESSION["updateStudentRFIDMsg"] = "Updating the selected RFID unsuccessful. Please try again.";
        //echo '<script> alert("Data Not Updated"); </script>';
    }
    header("Location: #findStudentRFIDPopup");
}
?>

<nav class="topnav">
    <a href="2-create-table.php" style="color: #f2f2f2;"><i class="fa fa-home"
                                                            style="font-size: 27px;text-align:center"></i></a>
    <a href="3-display-selection.php" style="color: #f2f2f2">Overall Attendance</a>
    <a href="3.1-class-list.php" style="color: #f2f2f2">Class List</a>
    <a href="3.2-date-filter.php" style="color: #f2f2f2">Date Filter</a>
    <a class="active" href="3.3-unenrolled.php" style="color: #f2f2f2">Unenrolled RFIDs</a>
    <a href="logout.php" style="color: #f2f2f2; float:right">Log Out</a>
</nav>

<div class="container pt-5" style="text-align:center">
    <!-- class course code display -->
    <div style="text-align:center">
        <h1 style="color:#000000;font-size: 28px;"> <?php echo $cg; ?> Attendance</h1>
    </div>

    <div class=tab style=margin-top:50px>
        <h6 style="padding-top:8px; color: #4f6d7a">Select date to view details</h6>

        <?php
        // query to get dates from database (no duplicates)
        $append_date = array();
        $d = "select Date from `$cg` where ID='' and Surname='' group by Date";
        $get_date = mysqli_query($db, $d);

        while ($date_array = $get_date->fetch_assoc()) {
            foreach ($date_array as $date_for_tab) {
                $append_date[] = $date_for_tab;
                ?>
                <!-- div for dates (within range) in vertical navigation bar -->
                <button id="defaultOpen" class="tablinks"
                        onclick="openTab(event, '<?php echo $date_for_tab; ?>')"><?php echo $date_for_tab ?></button>
                <?php
            }
        }
        ?>
    </div>

    <?php
    foreach ($append_date as $tab_date) {
        ?>
        <div id=<?php echo $tab_date ?> class="tabcontent">
            <?php
            show_unenrolled($db, $cg, $tab_date);
            ?>
        </div>
        <?php
    }
    ?>

    <div style="float:right">
        <a class="btn btn-info" href="#findStudentRFIDPopup" title="Edit RFID"
           style="border-radius: 50%; padding: 16px;position: fixed;bottom: 70px;right: 80px;">
            <i class="fa fa-pencil"></i>
        </a>
    </div>
</div>

<div id="findStudentRFIDPopup" class="overlay">
    <div class="popup">
        <h2 style="color:#000000;">EDIT ATTENDANCE<br/>(for Unenrolled RFIDs)</h2>
        <a class="close" href="#">&times;</a>
        <h3 style="font-size:medium;color:#dd6e42;text-align:left;padding-left:30px">
        </h3>

        <!-- THIS PART ACCESSES THE MASTERLIST DATABASE AND SEARCHES FOR THE CORRESPONDING STUDENT-->
        <?php
        if (isset($_POST['find-student-rfid']) && $_POST['find-student-rfid'] == 'FIND STUDENT RFID') {
            //KATHY SOLUTION
            //default texts if not clicking "find" button
            $temp = $_POST["rfidStudent"];
            $conn = new mysqli("localhost", "root", "", "temp");
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "INSERT INTO temptb (varname, val) VALUES ('findStudentRFID', '$temp')";

            if (mysqli_query($conn, $sql)) {
                mysqli_close($conn);
            }
            //database credentials, running MySQL with default setting (user 'root' with no password)
            //check first if the masterlist database does not exist
            $checkMasterListDB = mysqli_connect("localhost", "root", "");
            $dbName = "masterlist";

            $query = "SHOW DATABASES LIKE '$dbName'";
            $sqlStatement = $checkMasterListDB->query($query);

            //if there is no "masterlist" database
            if (!($sqlStatement->num_rows == 1)) {
                $conn = new mysqli('localhost', 'root', '', 'temp');
                // Obtain last value of variable user as 1 row
                // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
                $sql = "INSERT INTO temptb (varname, val) VALUES ('findStudentRFIDMsg', 'The masterlist database does not exist. Upload class lists to enroll the students into the system.')";
                if (mysqli_query($conn, $sql)) {
                    mysqli_close($conn);
                }
                mysqli_close($checkMasterListDB);
                header("location: #findStudentRFIDPopup");
            } else {
                mysqli_close($checkMasterListDB);
            }

            //attempt to connect to MySQL "masterlist" database
            $rfidFindDB = mysqli_connect('localhost', 'root', '', 'masterlist');

            if ($rfidFindDB->connect_error) {
                //die() kinda functions like an exit() function
                exit('Error connecting to the masterlist database in the server.');
            }
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            //on the "masterlist" database, "student" table in phpmyadmin DBMS, search for the equivalent RFID
            $sqlStatement = $rfidFindDB->prepare("SELECT * FROM student WHERE RFID = ?");

            $conn = new mysqli("localhost", "root", "", "temp");
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT val FROM temptb WHERE varname = 'findStudentRFID' ORDER BY id DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $tm3 = $row["val"];
            }
            $findStudentRFID = $tm3;

            $sqlStatement->bind_param("s", $findStudentRFID);
            $sqlStatement->execute();

            $result = $sqlStatement->get_result();
            //if no information on the rfid is found, return back to the edit menu with the error message
            if ($result->num_rows == 0) {

                $conn = new mysqli("localhost", "root", "", "temp");
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "INSERT INTO temptb (varname, val) VALUES ('findStudentRFIDMsg', 'The selected student is not enrolled in the Attendance Logging System.')";

                if (mysqli_query($conn, $sql)) {
                    mysqli_close($conn);
                }
                //$_SESSION["findStudentRFIDMsg"] = "The selected student is not enrolled in the portable attendance device.";

                $sqlStatement->close();
                mysqli_close($rfidFindDB);

                header("location: #findStudentRFIDPopup");
            } //if there is information on the rfid, retrieve the contents
            else {
                while ($row = $result->fetch_assoc()) {
                    //set the $row[""] to the column you want to use
                    $ID = $row["ID"];
                    $LN = $row["Lastname"];
                    $FN = $row["Firstname"];

                    $conn = new mysqli("localhost", "root", "", "temp");
                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $sql = "INSERT INTO temptb (varname, val) VALUES ('studentIDNumber', '$ID')";

                    //EDITED BY KATH
                    if (mysqli_query($conn, $sql)) {
                        $sql = "INSERT INTO temptb (varname, val) VALUES ('studentLastName', '$LN')";
                        mysqli_query($conn, $sql);
                        $sql = "INSERT INTO temptb (varname, val) VALUES ('studentFirstName', '$FN')";
                        mysqli_query($conn, $sql);
                        mysqli_close($conn);

                    }
                }
            }

            $sqlStatement->close();
            mysqli_close($rfidFindDB);

            //go to the modify student rfid info display
            header("location: #fillOutStudentRFID");
        }
        ?>

        <!--THIS PART IS TO SELECT THE UNENROLLED RFID TO EDIT INFORMATION WITH-->
        <div class="content">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for=""> Select the RFID to be edited in the dropdown box below. </label>
                    <div style="display:flex;">
                        <?php
                        $rfid = $db->query("select RFID from `$cg` WHERE Surname = '' group by RFID order by Date");

                        echo "<select name = 'rfidStudent' class='form-control' required>";
                        echo "<option disabled value = '' selected> Select RFID </option>";
                        while ($row = mysqli_fetch_array($rfid)) {
                            echo "<option value = '" . $row['RFID'] . "'>" . $row['RFID'] . "</option>";
                        }

                        echo "</select>";
                        mysqli_close($db);
                        ?>
                    </div>
                    <div class="form-group" style="text-align: center;padding-top:30px">
                        <input type="submit" name="find-student-rfid" class="btn btn-info"
                               style="font-size: 13px;" value="FIND STUDENT RFID"/>
                    </div>

                    <?php
                    // THIS PART SHOWS IF THE RFID SEARCH IN MASTERLIST IS UNSUCCESSFUL
                    // Create connection directly to specific database
                    $conn = new mysqli('localhost', 'root', '', 'temp');
                    // Obtain last value of variable user as 1 row
                    // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
                    $sql = "SELECT val FROM temptb WHERE varname = 'findStudentRFIDMsg' ORDER BY id DESC LIMIT 1";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $tp5 = $row["val"];
                        mysqli_close($conn);
                    }
                    if (isset($tp5) && $tp5) {
                        if ($tp5 == "The selected student is not enrolled in the Attendance Logging System."
                            || $tp5 == "The masterlist database does not exist. Upload class lists to enroll the students into the system.") {
                            echo '<p class = "notification" style =  "color:#dc3545;">' . $tp5 . '</p>';
                        } else {
                            echo '<p class = "notification" style = "color:#0b8f47;">' . $tp5 . '</p>';
                        }

                        $conn = new mysqli("localhost", "root", "", "temp");
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $sql = "INSERT INTO temptb (varname, val) VALUES ('findStudentRFIDMsg', '')";

                        if (mysqli_query($conn, $sql)) {
                            mysqli_close($conn);
                        }
                    }

                    // <-- THIS PART WILL EXECUTE FOR THE EDITING OF THE STUDENT RFIDS PROMPT -->
                    // Create connection directly to specific database
                    $conn = new mysqli('localhost', 'root', '', 'temp');
                    // Obtain last value of variable user as 1 row
                    // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
                    $sql = "SELECT val FROM temptb WHERE varname = 'updateStudentRFIDMsg' ORDER BY id DESC LIMIT 1";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $tp6 = $row["val"];
                        mysqli_close($conn);
                    }
                    if (isset($tp6) && $tp6) {
                        if ($tp6 == "Updated the selected RFID successfully!") {
                            echo '<p class = "notification" style = "color:#0b8f47;">' . $tp6 . '</p>';
                        } else {
                            echo '<p class = "notification" style =  "color:#dc3545;">' . $tp6 . '</p>';
                        }

                        $conn = new mysqli("localhost", "root", "", "temp");
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $sql = "INSERT INTO temptb (varname, val) VALUES ('updateStudentRFIDMsg', '')";

                        if (mysqli_query($conn, $sql)) {
                            mysqli_close($conn);

                        }
                    }

                    ?>
                </div>
            </form>
        </div>
        <!--END RFID PART-->
    </div>
</div>

<!-- THIS PART WILL EXECUTE IF THE RFID IS FOUND IN THE MASTERLIST -->
<div id="fillOutStudentRFID" class="overlay">
    <div class="popup">
        <h2 style="color:#000000;">Edit Student RFID Information</h2>
        <a class="close" href="#">&times;</a>
        <h3 style="font-size:medium;color:#dd6e42;text-align:left;padding-left:30px">
        </h3>

        <div class="content">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

            <form method="POST" enctype="multipart/form-data">
                <center>
                    <table>
                        <?php
                        // Create connection directly to specific database
                        $conn = new mysqli('localhost', 'root', '', 'temp');
                        // Obtain last value of variable user as 1 row
                        // format goes "SELECT value column FROM temptb table WHERE variable is user ORDER BY last input of id in descending with 1 row
                        $sql = "SELECT val FROM temptb WHERE varname = 'studentIDNumber' ORDER BY id DESC LIMIT 1";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $tv2 = $row["val"];
                        }
                        $studentIDNumber = $tv2;

                        $sql = "SELECT val FROM temptb WHERE varname = 'studentLastName' ORDER BY id DESC LIMIT 1";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $tv3 = $row["val"];
                        }
                        $studentLastName = $tv3;

                        $sql = "SELECT val FROM temptb WHERE varname = 'studentFirstName' ORDER BY id DESC LIMIT 1";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $ta2 = $row["val"];
                        }
                        $studentFirstName = $ta2;
                        ?>
                        <tr>
                            <div class="form-group">
                                <td>
                                    <label for=""><b>ID Number</b></label>
                                </td>
                                <td>
                                    <input type="text" name="studentIDNum" class="idnumber"
                                           style="text-transform: capitalize;" placeholder="Enter ID Number"
                                           value="<?php echo $studentIDNumber ?>" required/>
                                </td>

                            </div>
                        </tr>

                        <tr>
                            <div class="form-group">
                                <td>
                                    <label for=""><b>Surname</b></label>
                                </td>
                                <td>
                                    <input type="text" name="studentLName" class="lastname"
                                           style="text-transform: capitalize;" placeholder="Enter Surname"
                                           value="<?php echo $studentLastName ?>" required/>

                                </td>

                            </div>
                        </tr>

                        <tr>
                            <div class="form-group">
                                <td>
                                    <label for=""><b>First Name</b></label>
                                </td>
                                <td>
                                    <input type="text" name="studentFName" class="firstname"
                                           style="text-transform: capitalize;" placeholder="Enter Name"
                                           value="<?php echo $studentFirstName ?>" required/>

                                </td>
                            </div>
                        </tr>

                    </table>
                </center>
                <div class="form-group" style="text-align: center;padding-top:30px">
                    <input type="submit" name="update-info" class="btn btn-info"
                           value="UPDATE STUDENT RFID INFORMATION"/>
                </div>

            </form>

        </div>
    </div>

</div>


<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>

</body>
</html>


<?php
function show_unenrolled($db, $cg, $tab_date)
{
    // this query displays only the empty ID and name columns (sort by status)
    $info = $db->query("select RFID,Date,Status,Time from `$cg` WHERE Surname='' AND Date='$tab_date'");

    echo "<table style=margin-left:auto;margin-right:auto;text-align:center>";
    echo "<th> RFID </th>" . "<th> Date </th>" . "<th> Status </th>" . "<th> Time-in </th>";

    while ($details = $info->fetch_assoc()) {
        echo "<tr>";
        foreach ($details as $d) {
            echo "<td>" . $d . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

?>
