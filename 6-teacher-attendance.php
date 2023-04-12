<html>

<?php
session_start();

if (isset($_GET['return-to-admin-menu']) && $_GET['return-to-admin-menu'] == 'Return to Administrator Menu') {
    header("location: admin-main.php");
}

$checkTeacherAttendanceDB = mysqli_connect("localhost", "root", "");
$dbName = "teacher attendance";

$query = "SHOW DATABASES LIKE '$dbName'";
$sqlStatement = $checkTeacherAttendanceDB->query($query);

if (!($sqlStatement->num_rows == 1)){ //if there are no databases with "teacher attendance" in the name
	// Create connection directly to specific database
	$conn = new mysqli('localhost', 'root', '', 'temp');
	$sql = "INSERT INTO temptb (varname, val) VALUES ('checkTeacherAttendanceDB', 'There are no attendance logs in the local server.')";
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
//    $_SESSION["checkTeacherAttendanceDB"] = "There are no attendance logs in the local server.";
    mysqli_close($checkTeacherAttendanceDB);
    header("location: admin-main.php");
}
else{
    //proceed with the next processes. close the current database connection
    mysqli_close($checkTeacherAttendanceDB);
}
?>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&display=swap');

	html * {
	font-size: 16px;
	line-height: 1.625;
	font-family: Lato, sans-serif;
	}

    td,th {
        border: 1px solid black;
        padding: 10px;
        margin: 5px;
        text-align: center;
    }

    .tab {
        display: inline-block;
		background-color: white; 
		border-radius: 7px; 
		padding: 20px;
        width:25%
	}

    	/* Style the buttons inside the tab */
	.tab button {
		display: block;
		background-color: inherit;
		color: black;
		padding: 10px 16px;
		width: 100%;
		border: none;
		outline: none;
		text-align: left;
		cursor: pointer;
		transition: 0.3s;
		font-size: 17px;
		border-radius: 7px;
	}

	/* Change background color of buttons on hover */
	.tab button:hover {
		background-color: darkgray;
	}

	/* Create an active/current "tab button" class */
	.tab button.active {
		background-color: #e1af30;
	}

	/* Style the tab content */
	.tabcontent {
		float: right;
		width:60%;
		height: fit-content;
		background-color: white;
		border-radius: 7px;
		padding:40px 50px; 
		margin: 0 50px 90px 50px;
	}
</style>

<head>
	<meta http-equiv="Content-Type"
		content="text/html; charset=UTF-8">

	<title>View Teacher Attendance</title>

	<link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link type = "text/css" rel="stylesheet" href = "css/register-teacher-style.css">

</head>

<body style="background-color: #eaeaea">
<?php
    // THIS PART CONNECTS TO THE DATABASE
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "teacher attendance";

    $db = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    else{
    ?>
        <div class="container pt-5" style="text-align:center">
        <div class="tab">
            <h6 style="padding-top:8px; color: #4f6d7a">Select course to view teacher attendance:</h6>
    <?php
            $LOC = array();
            $array = array();
            $courses = $db->query("SELECT Course FROM `teacher_attendance` group by Course");
        
            while ($list = $courses->fetch_assoc()){

                foreach($list as $list_of_courses){
                    $LOC[] = $list_of_courses;
                ?>
				<!-- div for dates (within range) in vertical navigation bar -->
				<button id="defaultOpen" class="tablinks" onclick="openTab(event, '<?php echo $list_of_courses;?>')"><?php echo $list_of_courses; ?></button>
    <?php
                }
            }
    ?>

    </div>

    <?php
			foreach($LOC as $tab_course){
	?>
				<div id='<?php echo $tab_course; ?>' class="tabcontent">
				<?php
                    $display = "select ID,Surname,Name,Date,Status,Time from `teacher_attendance` 
                    where Course='$tab_course' order by Surname";
                    
                    $chosen_course = mysqli_query($db,$display);
                    echo "<h1>Teacher Attendance</h1><br/>";
                    display_table($chosen_course);
				?>
				</div>
	<?php
			}
    }
?>
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
function display_table($chosen_course){

	//echo "<div style=padding-top:20px;padding-bottom:20px;text-align:center>";
	echo "<table style=margin-left:auto;margin-right:auto;text-align:center>";

	echo "<th> ID# </th>" . "<th> Lastname </th>" . "<th> Name </th>" .
		"<th> Date </th>" . "<th> Status </th>" . "<th> Time-in </th>";

    while ($row = $chosen_course->fetch_assoc()) { 
        $array[] = $row;
        echo "<tr>";
        foreach($row as $r){
            echo "<td>";
            echo $r;
            echo "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
    echo "<br/>";
    echo "<form method = 'GET' enctype='multipart/form-data'>";
    echo "<input type='submit' name='return-to-admin-menu' class='btn btn-info'
               style='color: white; background-color: #0b8f47;' value='Return to Administrator Menu' formnovalidate/>";
    echo "</form>";
}