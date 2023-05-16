<?php
    ob_start();
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
 
	$sql = "SELECT val FROM temptb WHERE varname = 'teacherName' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$tempvar2 = $row["val"];
	}
	$teacher_name = strtoupper($tempvar2);
 
	$sql = "SELECT val FROM temptb WHERE varname = 'teacherEmail' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$tempvar3= $row["val"];
	}
	$teacherEmail = $tempvar3;
	
	$sql = "INSERT INTO temptb (varname, val) VALUES ('table', '$tempvar1')";
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}

	$fullname = @$_GET["name"];
	//separate surname and name
	$name = explode(', ', $fullname);
	$array = array();

$show_col = $db->query("SHOW COLUMNS FROM `$cg`");
while ($row = $show_col->fetch_assoc()) {
    $columns[] = $row['Field'];
}
$show_col = $db->query("SELECT * FROM `$cg`");

if (isset($_GET['download_pdf'])) {
    include '5-pdf-detailed.php';
}
?>

<html>
<head>
    <meta http-equiv="Content-Type"
          content="text/html; charset=UTF-8">

    <title>View Attendance of Selected Student</title>
    <!--the inline css is located BELOW this document-->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>


<body style="background-color: #eaeaea; text-align:center;">

<?php
if (isset($_POST['update'])) {
    $send_date = (date("Y-n-j", strtotime($_POST['send_date'])));
    $stat = strtoupper($_POST['status']);

    // checks if the person has logs from that date
    $exist = "SELECT * from `$cg` WHERE Name='$name[1]' AND Surname='$name[0]' AND Date='$send_date'";
    $exists = mysqli_query($db, $exist);

    // updates if they have logs and creates new row if not
    if (mysqli_num_rows($exists) > 0) {

        $query = "UPDATE `$cg` SET Status='$stat' WHERE Name='$name[1]' AND Surname='$name[0]' AND Date='$send_date'";
    } else {
        $query = "INSERT INTO `$cg` (RFID, ID, Surname, Name, Date, Status, Time)  
					  VALUES ('$name[2]', '$name[3]', '$name[0]', '$name[1]', '$send_date', '$stat', '' )";
    }

    $query_run = mysqli_query($db, $query);

    if ($query_run) {
        //echo '<script> alert("Data Updated"); </script>';
        header("Location: 4-monitoring.php/?name=$fullname#");
        exit;
    } else {
        echo '<script> alert("Data Not Updated"); </script>';
    }

}
?>
<nav class="topnav">
    <a href="/2-create-table.php"><i class="fa fa-home"
                                                               style="font-size: 27px;text-align:center"></i></a>
    <a href="/3-display-selection.php" style="color: #f2f2f2">Overall Attendance</a>
    <a href="/3.1-class-list.php" style="color: #f2f2f2">Class List</a>
    <a href="/3.2-date-filter.php" style="color: #f2f2f2">Date Filter</a>
    <a href="/3.3-unenrolled.php" style="color: #f2f2f2">Unenrolled RFIDs</a>
    <a href="/logout.php" style="color: #f2f2f2; float:right">Log Out</a>
</nav>

<div>
    <a class="btn btn-info" href="#indiv" title="indiv"
       style="border-radius: 7px; padding: 6px 10px; float:right; margin-right:10px; margin-top:10px; text-align:center">
        Export
    </a>


    <div class="container pt-5" style="text-align:center">
        <!-- class course code display -->
        <div style="text-align:center">
            <h1 style="color:#000000;font-size: 28px;"> <?php echo $cg; ?> Attendance</h1>
        </div>
    </div>

    <div class="tab" style="margin-top:30px">
        <h1 style=color:#e1af30;font-size:22px;>Viewing Attendance of: <?php echo "<b>" . $name[1] . " " . $name[0] . "</b>"; ?></h1>
        <?php
        // query to get dates from database (no duplicates)
        $append_date = array();
        $months = array();
        $d = "select Date,Status,Time from `$cg` group by Date order by Date";
        $get_date = mysqli_query($db, $d);

        while ($date_array = $get_date->fetch_assoc()) {
            $append_date[] = $date_array['Date'];
        }

        foreach ($append_date as $m) {
            // to get the month of the date
            $months[] = date("F(Y)", strtotime($m));
            $months = array_unique($months);
        }

        foreach ($months as $months) {
            ?>
            <button onclick="myFunction('<?php echo $months; ?>')" class="accordion"><?php echo $months; ?></button>

            <div class="panel">
                <div class="hide" id=<?php echo $months; ?>>
                    <?php

                    for ($i = 0; $i < count($append_date); $i++) {
                        if (date("F(Y)", strtotime($append_date[$i])) === $months) {
                            ?>
                            <a><?php
                                // displays the date and day
                                echo "<b>" . date("M j", strtotime($append_date[$i])) . " (" . date("D", strtotime($append_date[$i])) . ")" . "</b>";

                                // queries the columns with the specific date
                                $st = "select * from `$cg` where Date='$append_date[$i]'";

                                $details = mysqli_query($db, $st);
                                while ($detail_array = $details->fetch_assoc()) {
                                    $check_first = strpos($fullname, $detail_array['Name']);
                                    $check_last = strpos($fullname, $detail_array['Surname']);

                                    if ($check_first !== FALSE and $check_last !== FALSE and $detail_array['ID'] !== "") {
                                        if ($detail_array['Status'] === "") {
                                            echo "<br/>ABSENT";
                                        } else {
                                            //echo "<div style=background-color:#4f6d7a>" ."<br/>" . $detail_array['Status'] . "</div>";

                                            if ($detail_array['Status'] === "PRESENT") {
                                                echo "<div style=background-color:#05a750;color:white;margin:0;padding:0;border-radius:5px>" . $detail_array['Status'] . "</div>";
                                            } elseif ($detail_array['Status'] === "LATE") {
                                                echo "<div style=background-color:#f1be36;color:white;margin:0;padding:0;border-radius:5px>" . $detail_array['Status'] . "</div>";
                                            } elseif ($detail_array['Status'] === "EXCUSED") {
                                                echo "<div style=background-color:#039fe2;color:white;margin:0;padding:0;border-radius:5px>" . $detail_array['Status'] . "</div>";
                                            } elseif ($detail_array['Status'] === "ABSENT") {
                                                echo "<div style=background-color:#f22f22;color:white;margin:0;padding:0;border-radius:5px>" . $detail_array['Status'] . "</div>";
                                            }
                                        }

                                        echo "<div style=margin:0;padding:0>Time in: " . $detail_array['Time'] . "</div>";
                                    }
                                }

                                ?></a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <?php
        }
        ?>

        <script>
            var acc = document.getElementsByClassName("accordion");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", function () {
                    this.classList.toggle("active");
                    var panel = this.nextElementSibling;
                    if (panel.style.maxHeight) {
                        panel.style.maxHeight = null;
                    } else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                    }
                });
            }

            function myFunction(id) {
                var x = document.getElementById(id);
                if (x.className.indexOf("show") == -1) {
                    x.className += "show";
                } else {
                    x.className = x.className.replace("show", "");
                }
            }
        </script>
    </div>


    <div style="display:inline;">
        <div class="tab"
             style="float:left; width:40%;height:fit-content;margin-right:0;margin-bottom:50px">
            <?php
            $present = 0;
            $late = 0;
            $absent = 0;
            $excused = 0;
            $total = 0;

            $d = "select Date from `$cg` group by Date";
            $dates = mysqli_query($db, $d);
            while ($test = $dates->fetch_assoc()) {
                foreach ($test as $t) {
                    $total++;
                }
            }

            if ($show_col->num_rows > 0) {

                // fetches this data if database is not empty
                while ($row = $show_col->fetch_assoc()) {

                    // gets the name of selected student from 3-display.php
                    // index (0:RFID, 1:ID, 2:Surname, 3:Name, 4:Date, 5:Status, 6:Time)
                    $id_num = $row[$columns[1]];
                    $firstname = $row[$columns[3]];
                    $lastname = $row[$columns[2]];
                    $status = $row[$columns[5]];

                    // matches the selected names with entries in database 
                    // checks if first and last name (from database) are found in fullname clicked on web app
                    $check_first = strpos($fullname, $firstname);
                    $check_last = strpos($fullname, $lastname);

                    if ($check_first !== FALSE and $check_last !== FALSE and $id_num !== "") {
                        //echo "<h1 style=color:#4f6d7a;font-size:22px;>";                        
                        //echo "Viewing Attendance of: " . $firstname . " " . $lastname;        
                        //echo "</h1>";

                        if ($status === "PRESENT") {
                            $present++;
                        }

                        if ($status === "LATE") {
                            $late++;
                        }

                        if ($status === "ABSENT") {
                            $absent++;
                        }

                        if ($status === "EXCUSED") {
                            $excused++;
                        }
                    }
                }
                // compares the total number of days with the attendance status count
                while ($total !== ($present + $late + $absent + $excused)) {
                    $absent++;
                }

                $present_pie = ($present / $total) * 360;
                $late_pie = (($late / $total) * 360) + $present_pie;
                $excused_pie = (($excused / $total) * 360) + $late_pie;
            } else {
                echo "No record has been found!";
            }
            echo "<h1 style=color:#000000;font-size:22px;>Attendance Status</h1>";
            ?>

            <div class="count_display">
                <?php
                echo "<h1>" . $present . "</h1>";
                echo "<h4>" . "Days Present" . "</h4>";
                ?>
            </div>

            <div class="count_display">
                <?php
                echo "<h1>" . $late . "</h1>";
                echo "<h4>" . "Days Late" . "</h4>";
                ?>
            </div>

            <div class="count_display">
                <?php
                echo "<h1>" . $excused . "</h1>";
                echo "<h4>" . "Days Absent\n(Excused)" . "</h4>";
                ?>
            </div>

            <div class="count_display">
                <?php
                echo "<h1>" . $absent . "</h1>";
                echo "<h4>" . "Days Absent\n(Unexcused)" . "</h4>";
                ?>
            </div>
        </div>
    </div>

    <!-- added this bit from here-->
    <div class="tab" style="float:right; width:45%;height:fit-content;margin-left:0;margin-bottom:20px; padding:34px 0">
        <h1 style="color:#000000;font-size:22px;"> Total Attendance Days</h1>
        <h1 style="color:black;font-size:45px;font-weight:600;"><?php echo $total ?></h1>
        <!-- to here -->
    </div>

    <div class="tab" style="float:right; width:45%;height:fit-content;margin-left:0;margin-bottom:50px">
        <h1 style="color:#000000;font-size:22px;">Overall Attendance Percentage</h1>
        <div class="piechart" style="margin-left:16%">
            <div class="legend">
                <div class="entry">
                    <div id="present-color" class="entry-color"></div>
                    <div class="entry-text">PRESENT(<?php echo round((($present / $total) * 100), 2); ?>%)</div>
                </div>

                <div class="entry">
                    <div id="late-color" class="entry-color"></div>
                    <div class="entry-text">LATE(<?php echo round((($late / $total) * 100), 2); ?>%)</div>
                </div>

                <div class="entry">
                    <div id="excused-color" class="entry-color"></div>
                    <div class="entry-text">EXCUSED(<?php echo round((($excused / $total) * 100), 2); ?>%)</div>
                </div>

                <div class="entry">
                    <div id="absent-color" class="entry-color"></div>
                    <div class="entry-text">ABSENT(<?php echo round((($absent / $total) * 100), 2); ?>%)</div>
                </div>
            </div>
        </div>
    </div>

    <div id="indiv" class="overlay">
        <div class="popup" style="width:83%;">
            <a class="close" href="#">&times;</a>

            <div class="content" style="padding-top:50px">
                <h2 style="text-align:center;font-size: 28px;color:#000000;">Attendance Report of Student</h2>
                <?php
                echo "<table style=margin-left:auto;margin-right:auto;text-align:center>";
                $show_col = $db->query("SELECT ID,Surname,Name,Date,Status,Time FROM `$cg` 
										where Surname='$name[0]' and Name='$name[1]' order by Surname");

                if ($show_col->num_rows > 0) {

                    // fetches this data if database is not empty
                    while ($row = $show_col->fetch_assoc()) {
                        $array[] = $row;
                        echo "<tr>";
                        foreach ($row as $r) {
                            echo "<td>";
                            echo $r;
                            echo "</td>";
                        }
                        echo "</tr>";
                    }
	                $conn = new mysqli("localhost", "root", "", "temp");
	                // Check connection
	                if ($conn->connect_error) {
		                die("Connection failed: " . $conn->connect_error);
	                }
                    $array_str = serialize($array);
	                $sql = "INSERT INTO temptb (varname, val) VALUES ('array_student', '$array_str')";
	                if (mysqli_query($conn, $sql)) {
                        mysqli_close($conn);
	                }
                    $_SESSION['array_student'] = $array;
                }

                echo "</table>";
                echo "<br/><h5><center>You can send a copy of the attendance report via email or you can download it in CSV or PDF format.</center></h5>";

                function dl($array, $teacher_name, $cg)
                {
                    // filename = download path/filename
                    $tempname = strtoupper($teacher_name) . "_" . $cg . ".csv";
                    $file = fopen($tempname, "w");
                    fputcsv($file, array("ID#", "Lastname", "Name", "Date", "Status", "Time-in"));

                    if (count($array) > 0) {
                        foreach ($array as $row) {
                            fputcsv($file, $row);
                        }
                    }
                    fclose($file);
                }

                //NOTE FOR EMAIL PART
                //https://www.geeksforgeeks.org/how-to-configure-xampp-to-send-mail-from-localhost-using-php/
                //https://myaccount.google.com/lesssecureapps
                ?>

                <div style="display:flex">
                    <form enctype="multipart/form-data" method="POST" action=""
                          style="margin-top:20px; margin-left:25%; display:flex; text-align:center">
                        <div class="form-group">
                            <input class="form-control" type="email" name="email" placeholder="Email Address"
                                   value="<?php echo $teacherEmail?>" required
                                   style="margin-top:20px; padding:15px 80px;text-align:center"/>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-info" type="submit" name="send_email" value="Send"
                                   style="margin:15px 20px; padding:10px 17px; border-radius:18px;"/>
                        </div>
                    </form>

                    <form method="GET" action="#dl_options">
                        <div class="form-group">
                            <input type="hidden" name="name" value="<?php echo $fullname ?>"/>
                            <input type="submit" name="download" value="Download" class="btn btn-info"
                                   style="border-radius:18px; margin-top:35px; padding:10px 17px;"/>
                        </div>
                    </form>
                </div>

                <?php
                // if Download button was clicked
                if (array_key_exists('download', $_GET)) {
	                // Create connection directly to specific database
	                $conn = new mysqli('localhost', 'root', '', 'temp');
                    $array_str = serialize($array);
	                $sql = "INSERT INTO temptb (varname, val) VALUES ('array_copy', '$array_str')";
	                if (mysqli_query($conn, $sql)) {
		                $sql = "INSERT INTO temptb (varname, val) VALUES ('sd_copy', 'Not Applicable')";
		                if (mysqli_query($conn, $sql)) {
			                $sql = "INSERT INTO temptb (varname, val) VALUES ('ed_copy', 'Not Applicable')";
			                if (mysqli_query($conn, $sql)) {
				                mysqli_close($conn);
			                }
		                }
	                }
                    dl($array, $teacher_name, $cg);
                }

                if (isset($_POST['send_email'])) {

                    // filename = download path/filename
                    // NOTE: CHANGE FILEPATH ON THE SERVER PC
                    $filename = "C:/Users/Kath/Downloads/" . strtoupper($teacher_name) . "_" . $cg . ".csv";
                    $file = fopen($filename, "w");
                    fputcsv($file, array("ID#", "Lastname", "Name", "Date", "Status", "Time-in"));

                    if (count($array) > 0) {
                        foreach ($array as $row) {
                            fputcsv($file, $row);
                        }
                    }

                    fclose($file);

                    // the necessary email addresses
                    $from = '19102579@usc.edu.ph';
                    $to = $_POST["email"];

                    //read from the uploaded file & base64_encode content
                    $handle = fopen($filename, "r");
                    $content = fread($handle, filesize($filename));
                    $type = filetype($filename);
                    fclose($handle);

                    $encoded_content = chunk_split(base64_encode($content));
                    $boundary = md5("random"); // define boundary with a md5 hashed value

                    //header
                    $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
                    $headers .= "From:" . $from . "\r\n"; // Sender Email
                    $headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
                    $headers .= "boundary = $boundary\r\n"; //Defining the Boundary

                    //plain text
                    $body = "--$boundary\r\n";
                    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
                    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
                    $body .= chunk_split(base64_encode("Requested student attendance log from $cg schedule"));

                    //attachment
                    $body .= "--$boundary\r\n";
                    $body .= "Content-Type: $type; name=" . basename($filename) . "\r\n";
                    $body .= "Content-Disposition: attachment; filename=" . basename($filename) . "\r\n";
                    $body .= "Content-Transfer-Encoding: base64\r\n";
                    $body .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
                    $body .= $encoded_content; // Attaching the encoded file with email

                    $sentMailResult = mail($to, "Exported Attendance Log", $body, $headers);

                    if ($sentMailResult) {
                        echo "<h3 style='text-align:center; color:#0b8f47'>Attendance report sent successfully!</h3>";
                        unlink($filename); // delete the file after attachment sent.
                    } else {
                        die("Sorry but the email could not be sent.
							Please try again!");
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div id="dl_options" class="overlay">
    <div class="popup" style="width:40%; margin:10% 30%; padding: 15px; text-align: center;">
        <h2 style="font-size: 28px;color:#000000;">Download Options</h2>
        <h5>Select a file format to download below.</h5>
        <a class="close" href="#">&times;</a>
        <form method="POST" action="/4-monitoring-download.php">
            <div class="form-group">
                <input type="hidden" name="name" value="<?php echo $fullname ?>"/>

                <input type="submit" name="download_pdf" value="PDF" class="btn btn-info"
                       style="border-radius:18px; margin-top:35px; padding:10px 17px;"/>
                <input type="submit" name="download_csv" value="CSV" class="btn btn-info"
                       style="border-radius:18px; margin-top:35px; padding:10px 17px;"/>
            </div>
        </form>
    </div>
</div>

<div style="float:right">
    <a class="btn btn-info" href="#edit" title="Edit"
       style="border-radius: 50%; padding: 16px;position: fixed;bottom: 50px;right: 40px;">
        <i class="fa fa-pencil"></i>
    </a>
</div>

<div id="edit" class="overlay">
    <div class="popup">
        <h2>EDIT ATTENDANCE</h2>
        <a class="close" href="#">&times;</a>

        <div class="content">
            <form action="" method="post">
                <div class="form-group">
                    <label for=""> Date </label>
                    <select name="send_date" class="form-control" required>
                        <option disabled value="" selected>Select Date</option>
                        <?php
                        foreach ($append_date as $op) {
                            echo "<option>";
                            echo date("F j, Y . D", strtotime($op));
                            echo "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for=""> Status </label>
                    <select name="status" class="form-control" required>
                        <option disabled value="" selected>Select Status</option>
                        <option value="PRESENT">PRESENT</option>
                        <option value="LATE">LATE</option>
                        <option value="EXCUSED">EXCUSED</option>
                        <option value="ABSENT">ABSENT</option>
                    </select>
                </div>

                <div style="text-align: center;padding-top:30px">
                    <button type="submit" name="update" class="btn btn-info"
                            style="font-size: 13px;"> Update
                    </button>
                </div>

        </div>
    </div>
</div>
</body>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&display=swap');

    html * {
        font-size: 16px;
        line-height: 1.625;
        font-family: Lato, sans-serif;
    }

    td, th {
        border: 1px solid black;
        padding: 10px;
        margin: 5px;
        text-align: center;
    }

    .btn {
        background-color: #039fe2;
        color: white;
        text-align: center;
        display: inline-block;
        font-weight: 400;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
    }

    .topnav {
        background-color: #173513;
        overflow: hidden;
    }

    /* Style the links inside the navigation bar */
    .topnav a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    /* Change the color of links on hover */
    .topnav a:hover {
        text-decoration: none;
        background-color: #00652c;
        color: #f2f2f2;
    }

    /* Add a color to the active/current link */
    .topnav a.active {
        background-color: #e1af30;
        color: white;
    }

    .tab {
        background-color: white;
        border-radius: 7px;
        margin: 15px 80px;
        padding: 20px;
    }

    /* Style the date divisions */
    .count_display {
        margin: 30px 30px;
        padding: 20px 0;
        text-align: center;
        border-width: 1px;
        border-style: solid;
        border-color: #4f6d7a;
        border-radius: 7px;
        display: inline-block;
        width: 30%;
    }

    .count_display h1 {
        color: black;
        font-size: 50px;
    }

    .count_display h4 {
        font-size: 17px
    }

    .piechart {
        margin: 30px 20px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background-image: conic-gradient(
                #05a750 <?php echo $present_pie . "deg"; ?>,
                #f1be36 0 <?php echo $late_pie . "deg"; ?>,
                #039fe2 0 <?php echo $excused_pie . "deg"; ?>,
                #f22f22 0);
    }

    .legend {
        padding-top: 30px;
        padding-left: 130%;
        margin-right: 0;
    }

    .entry {
        display: inline-flex;
        padding-bottom: 10px;
    }

    .entry-color {
        display: inline-flex;
        height: 18px;
        width: 18px;
        border-radius: 10px;
    }

    .entry-text {
        margin-left: 10px;
    }

    #present-color {
        background-color: #05a750;
    }

    #late-color {
        background-color: #f1be36;
    }

    #excused-color {
        background-color: #039fe2;
    }

    #absent-color {
        background-color: #f22f22;
    }

    .accordion {
        background-color: white;
        color: #444;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: center;
        outline: none;
        font-size: 18px;
        font-weight: 500;
        transition: 0.4s;
        display: block;
        text-transform: uppercase;
    }

    .active, .accordion:hover {
        background-color: #ccc;
    }

    .accordion:after {
        content: '\002B';
        color: #777;
        font-weight: bold;
        float: right;
        margin-left: 5px;
    }

    .active:after {
        content: "\2212";
    }

    .panel {
        background-color: white;
        width: inherit;
        overflow: hidden;
        transition: max-height 0.2s ease-out;
        text-align: left;
        display: inline;
    }

    .panel a {
        background-color: #eaeaea;
        border-radius: 8px;
        margin: 10px 15px;
        padding: 18px;
        display: inline-block;
        min-width: 13%;
        text-align: center;
    }

    .hide {
        display: none;
    }

    .show {
        display: inline-table;
    }

    .overlay {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        transition: opacity 0.5s;
        visibility: hidden;
        opacity: 0;
    }

    .overlay:target {
        visibility: visible;
        opacity: 1;
    }

    .popup {
        text-align: left;
        margin: 30px auto;
        padding: 40px 20px;
        background: #fff;
        border-radius: 5px;
        width: 30%;
        max-height: 150%;
        position: relative;
    }

    .popup h2 {
        margin: 20px;
        padding: 10px;
        color: #4f6d7a;
        font-size: 18px;
    }

    .popup .close {
        position: absolute;
        top: 20px;
        right: 30px;
        transition: all 200ms;
        font-size: 30px;
        font-weight: bold;
        text-decoration: none;
        color: #333;
    }

    .popup .close:hover {
        color: #f22f22;
    }

    .popup .content {
        text-align: left;
        margin: 10px;
        padding: 10px;
        overflow: auto;
        max-height: 80%;
    }
</style>
</html>


<?php
$db->close();
?>
