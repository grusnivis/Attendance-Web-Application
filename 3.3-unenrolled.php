<?php
    //session_start();

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
 
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO temptb (varname, val) VALUES ('table', '$tempvar1')";
	
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
	}
    //$_SESSION['table'] = $cg;
    //$class = $_SESSION['Class Selected'];
?>

<html>
<head>
	<meta http-equiv="Content-Type"
		content="text/html; charset=UTF-8">

	<title>View Attendance</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="css/unenrolled-monitoring-style.css"/>

	<!--<link rel="stylesheet" type="text/css"
		href="css/monitoring-style.css"> -->
</head>


<body style="background-color: #eaeaea">

	<?php
		if(isset($_POST['update'])){
			$rfid_tag = $_POST['rfid'];
			$id_number = strtoupper($_POST['id_num']);
			$name = strtoupper($_POST['name']);
			$surname = strtoupper($_POST['surname']);

			$query = "UPDATE `$cg` SET ID='$id_number', Name='$name', Surname='$surname' WHERE RFID='$rfid_tag'";
			$query_run = mysqli_query($db, $query);

			if($query_run){
				//echo '<script> alert("Data Updated"); </script>';
				header("Location: 3.3-unenrolled.php#");
				exit;
			}
			else{
				echo '<script> alert("Data Not Updated"); </script>';
			}
		}
	?>

	<nav class="topnav">
		<a href="2-create-table.php" style="color: #f2f2f2;"><i class="fa fa-home" style="font-size: 27px;text-align:center"></i></a>
		<a href="3-display-selection.php" style="color: #f2f2f2">Overall Attendance</a>
		<a href="3.1-class-list.php" style="color: #f2f2f2">Class List</a>
		<a href="3.2-date-filter.php" style="color: #f2f2f2">Date Filter</a>
        <a class="active" href="3.3-unenrolled.php" style="color: #f2f2f2">Unenrolled RFIDs</a>
		<a href="logout.php" style="color: #f2f2f2; float:right">Log Out</a>
	</nav>

	<div class="container pt-5" style="text-align:center">
        <!-- class course code display -->
		<div style="text-align:center">
			<h1 style="color:#dd6e42;font-size: 28px;"> <?php echo $cg; ?> Attendance</h1>
        </div>

		<div class=tab style=margin-top:50px>
			<h6 style="padding-top:8px; color: #4f6d7a">Select date to view details</h6>

			<?php
			// query to get dates from database (no duplicates)
			$append_date = array();
			$d = "select Date from `$cg` where ID='' and Surname='' group by Date";
			$get_date = mysqli_query($db,$d);

			while ($date_array = $get_date->fetch_assoc()){
				foreach($date_array as $date_for_tab){
					$append_date[] = $date_for_tab;	
			?>	
					<!-- div for dates (within range) in vertical navigation bar -->
					<button id="defaultOpen" class="tablinks" onclick="openTab(event, '<?php echo $date_for_tab;?>')"><?php echo $date_for_tab ?></button>
			<?php
				}
			}
			?>
		</div>

		<?php
			foreach($append_date as $tab_date){
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
			<a class="btn btn-danger" href="#edit" title="Edit RFID"
                style="border-radius: 50%; padding: 16px;position: fixed;bottom: 70px;right: 80px;">
				<i class="fa fa-pencil"></i>
			</a>
		</div>
	</div>

		<div id="edit" class="overlay">
			<div class="popup">
				<h2>EDIT ATTENDANCE <br/> (for Unenrolled RFIDs)</h2>
				<a class="close" href="#">&times;</a>
                <h3 style="font-size:medium;color:#dd6e42;text-align:left;padding-left:30px">
                    <?php
                    if(isset($_POST['find'])){
                        $enrolled = $_POST['rfid'];
                        // concatenates the ID, Surname and Name if the RFID is found in the database and ID is not blank
                        // $match = $db->query("SELECT Concat(ID,',',Surname,',',Name) as found FROM `$cg` WHERE RFID=$enrolled AND NOT ID=''");


                        $match = $db->query("SELECT Concat(ID,',',Surname,',',Name) as found FROM `$cg` 
											WHERE RFID=$enrolled AND NOT ID=''");

                        while($info = $match->fetch_assoc()){
                            $disp_info = explode(',',$info['found']);
                            echo "<div>" . "MATCH FOUND IN DATABASE" . "</br></div>";
                            echo "RFID: " . $enrolled . "</br>";
                            echo "ID: " . $disp_info[0] . "</br>";
                            echo "Surname: " . $disp_info[1] . "</br>";
                            echo "Name: " . $disp_info[2] . "</br>";
                        }
                    }

                    ?>
                </h3>

				<div class="content">
					<form action="" method="post">
                    <div class="form-group">
                        <label for=""> RFID </label>
                            <div style="display:flex;">
                            <select name="rfid" class="form-control" required> 
                            <option disabled value="" selected>Select RFID</option>
							<?php
								$rfid = $db->query("select RFID from `$cg` WHERE Surname='' group by RFID order by Date");
								while($tag = $rfid->fetch_assoc()){
									foreach($tag as $op){
										echo "<option>";
										echo $op;
										echo "</option>";
									}
								}
							?>
							</select>
                                <button type="submit" name="find" class="btn btn-danger" style="font-size: 13px;padding-left:10px"> Find </button>
                            </div>

					<div class="form-group">
                        <label for=""> ID Number</label>
                        <input type="text" name="id_num" class="form-control" 
								style="text-transform: capitalize;" placeholder="Enter ID Number">
					</div>

					<div class="form-group">
                        <label for=""> Surname </label>
                        <input type="text" name="surname" class="form-control" 
								style="text-transform: capitalize;" placeholder="Enter Surname">
					</div>

					<div class="form-group">
                        <label for=""> Name </label>
                        <input type="text" name="name" class="form-control" 
								style="text-transform: capitalize;" placeholder="Enter Name">
					</div>
				</div>
				
				<div style="text-align: center">
					<button type="submit" name="update" class="btn btn-danger" 
						style="font-size: 13px;"> UPDATE </button>		
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
function show_unenrolled($db, $cg, $tab_date){		
	// this query displays only the empty ID and name columns (sort by status)
	$info = $db->query("select RFID,Date,Status,Time from `$cg` WHERE Surname='' AND Date='$tab_date'");

	echo "<table style=margin-left:auto;margin-right:auto;text-align:center>";
	echo "<th> RFID </th>" .  "<th> Date </th>" . "<th> Status </th>" . "<th> Time-in </th>";	

	while ($details = $info->fetch_assoc()) { 
		echo "<tr>";
		foreach($details as $d){
			echo "<td>" . $d . "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}
?>