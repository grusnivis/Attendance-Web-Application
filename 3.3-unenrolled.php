<?php
    session_start();

	include '0-connect.php';
	$cg = $_SESSION["table"];
    $_SESSION['table'] = $cg;
    //$class = $_SESSION['Class Selected'];
?>

<html>

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

	.btn{
		background-color: #dc3545;
		color: white;
		text-align: center;
		display:inline-block;
		font-weight:400;
		vertical-align:middle;
		cursor:pointer;
		border:1px solid transparent;
		padding:.375rem .75rem;
		font-size:1rem;
		line-height:1.5;
		border-radius:.25rem;
	}

	.topnav {
		background-color: #4f6d7a;
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
		background-color:darkgray;
		color: black;
	}
  
  /* Add a color to the active/current link */
	.topnav a.active {
		background-color: #dd6e42;
		color: white;
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
		margin: 50px auto;
		padding: 15px;
		background: #fff;
		border-radius: 5px;
		width: 30%;
		max-height: 90%;
		position: relative;
		transition: all 0.5s ease-in-out;
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
		color: #dd6e42;
	}
	
	.popup .content {
		margin: 20px;
		padding: 10px;
		overflow: auto;
		max-height: 50%;
	}

	/* Style the tab */
	.tab {
		float: left;
		background-color: white;
		width: 20%;
		border-radius: 7px;
	}

	/* Style the buttons inside the tab */
	.tab button {
		display: block;
		background-color: inherit;
		color: black;
		padding: 22px 16px;
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
		background-color: #dd6e42;
	}

	/* Style the tab content */
	.tabcontent {
		float: right;
		width:65%;
		height: fit-content;
		background-color: white;
		border-radius: 7px;
		padding:40px 50px; 
		margin:50px 50px;
	}

	/* Style the tab content 
	.tabcontent {
		height: fit-content;
		background-color: white;
		border-radius: 7px;
		padding:40px 50px; 
		margin:50px 24%;
	}*/
</style>

<head>
	<meta http-equiv="Content-Type"
		content="text/html; charset=UTF-8">

	<title>View Attendance</title>

	<link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	
	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
				header("Location: http://localhost/attendance%20monitoring/3.3-unenrolled.php#");
				exit;
			}
			else{
				echo '<script> alert("Data Not Updated"); </script>';
			}
		}
	?>

	<nav class="topnav">
		<a href="http://localhost/attendance%20monitoring/2-create-table.php" style="color: #f2f2f2;"><i class="fa fa-home" style="font-size: 27px;text-align:center"></i></a>
		<a href="http://localhost/attendance%20monitoring/3-display-selection.php" style="color: #f2f2f2">Overall Attendance</a>
		<a href="http://localhost/attendance%20monitoring/3.1-class-list.php" style="color: #f2f2f2">Class List</a>
		<a href="http://localhost/attendance%20monitoring/3.2-date-filter.php" style="color: #f2f2f2">Date Filter</a>
        <a class="active" href="http://localhost/attendance%20monitoring/3.3-unenrolled.php" style="color: #f2f2f2">Unenrolled RFIDs</a>
		<a href="#sign-out" style="color: #f2f2f2; float:right">Sign Out</a>
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
				<div class="content">
					<form action="" method="post">
                    <div class="form-group">
                        <label for=""> RFID </label>
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
					</div>

					<div class="form-group">
                        <label for=""> ID Number</label>
                        <input type="text" name="id_num" class="form-control" 
								style="text-transform: capitalize;" placeholder="Enter ID Number" required>
					</div>

					<div class="form-group">
                        <label for=""> Surname </label>
                        <input type="text" name="surname" class="form-control" 
								style="text-transform: capitalize;" placeholder="Enter Surname" required>
					</div>

					<div class="form-group">
                        <label for=""> Name </label>
                        <input type="text" name="name" class="form-control" 
								style="text-transform: capitalize;" placeholder="Enter Name" required>
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
	$info = $db->query("select RFID,Date,Status,Time from `$cg` 
						WHERE Surname='' AND Date='$tab_date'");

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