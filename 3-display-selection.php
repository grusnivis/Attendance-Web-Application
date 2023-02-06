<?php
    //session_start();

	include '0-connect.php';
	if(isset($_GET['table'])){
		$cg = strtoupper($_GET['table']);
		$conn = new mysqli("localhost", "root", "", "temp");
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "INSERT INTO temptb (varname, val) VALUES ('table', '$cg')";
		
		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
		}
		//$_SESSION['table'] = $cg;
	}
	else{
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
			mysqli_close($conn);
		}
		$cg = $tempvar1;
	}
	
	
	// query to get dates from database (no duplicates)
	$append_date = array();
	$presentArray = array();
	$lateArray = array();
	$excusedArray = array();
	$absentArray = array();

	$total = 0;
	$presentTotal = 0;
	$absentTotal = 0;

	$d = "select Date from `$cg` group by Date order by Date";
	$get_date = mysqli_query($db,$d);
	
	while ($date_array = $get_date->fetch_assoc()){
		$append_date[] = $date_array['Date'];
	}

	foreach($append_date as $date){

		$present = 0;
		$late = 0;
		$absent = 0;
		$excused = 0;

		$s = "select Status from `$cg` where Date='$date'";
		$get_status = mysqli_query($db,$s);

		while ($status = $get_status->fetch_assoc()){
			if($status['Status'] === "PRESENT"){
				$total++;
				$presentTotal++;
				$present++;
			}

			if($status['Status'] === "LATE"){
				$total++;			
				$presentTotal++;
				$late++;
			}

			if($status['Status'] === "EXCUSED"){
				$total++;
				$absentTotal++;
				$excused++;
			}

			if($status['Status'] === "ABSENT"){
				$total++;
				$absentTotal++;
				$absent++;
			}
		}

		$presentArray[] = $present;
		$lateArray[] = $late;
		$excusedArray[] = $excused;
		$absentArray[] = $absent;
	}

	$sem_p = round((($presentTotal / $total) * 100));
	$sem_a = round((($absentTotal / $total) * 100));
?>

<html>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&display=swap');

	html * {
	font-size: 16px;
	line-height: 1.625;
	font-family: Lato, sans-serif;
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
		text-transform: capitalize;
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

	.tab {
		background-color: white; 
		border-radius: 7px; 
		margin: 50px 20px;
		padding: 20px;
	}

	.tab chartBox {
        width: fit-content;
        padding: 20px;
        border-radius: 20px;
        background: white;
      }

      .container {
        width: inherit;
        max-width: inherit;
      }

      .containerBody {
        height: 350px;
		padding-right: 30px;
	  }
</style>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>View Attendance</title>

	<link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!--<link rel="stylesheet" type="text/css"
		href="css/monitoring-style.css">-->
</head>


<body style="background-color: #eaeaea">
	<nav class="topnav">
		<a href="http://localhost/attendance%20monitoring/2-create-table.php" style="color: #f2f2f2;"><i class="fa fa-home" style="font-size: 27px;text-align:center"></i></a>
		<a class="active" href="http://localhost/attendance%20monitoring/3-display-selection.php" style="color: #f2f2f2">Overall Attendance</a>
		<a href="http://localhost/attendance%20monitoring/3.1-class-list.php" style="color: #f2f2f2">Class List</a>
		<a href="http://localhost/attendance%20monitoring/3.2-date-filter.php" style="color: #f2f2f2">Date Filter</a>
		<a href="http://localhost/attendance%20monitoring/3.3-unenrolled.php" style="color: #f2f2f2">Unenrolled RFIDs</a>
		<a href="#sign-out" style="color: #f2f2f2; float:right;">Sign Out</a>
	</nav>

	<div class="container pt-5" style="text-align:center">
        <!-- class course code display -->
		<div style="text-align:center">
			<h1 style="color:#dd6e42;font-size: 28px;"> <?php echo $cg; ?> Attendance</h1>
        </div>

		<div style="float:right; width:35%; color:#444">
			<div class="tab" style="margin-top: 27%">
				<h5>Total Semestral Presence</h5>
				<h2 style="font-weight:700"> <?php echo $sem_p; ?>% </h2>
			</div>

			<div class="tab">
				<h5>Total Semestral Absence</h5>
				<h2 style="font-weight:700"> <?php echo $sem_a; ?>% </h2>
			</div>
		</div>

		<div class="tab" style="float:left; width:55%; overflow-x:scroll;">
			<div class="chartBox">
				<div class="container">
					<div class="containerBody">
						<canvas id="myChart"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>

		<script>
			// https://www.chartjs3.com/docs/chart/getting-started/
			// converts php array to java array
			var dateArray = <?php echo json_encode($append_date); ?>;

			var presentArray = <?php echo json_encode($presentArray); ?>;
			var lateArray = <?php echo json_encode($lateArray); ?>;
			var excusedArray = <?php echo json_encode($excusedArray); ?>;
			var absentArray = <?php echo json_encode($absentArray); ?>;

				// insert array for each status, each corresponding to date

				data = {
					//x-axis labels
					labels: dateArray,

					// y-axis data
					datasets: [{
						data: presentArray,
						fill: false,
						borderColor: "rgba(79, 109, 122, 1)",
						backgroundColor: "rgba(79, 109, 122, .2)",
						borderWidth: 1,
						label:'PRESENT'
					}, { 
						data: lateArray,
						fill: false,
						borderColor: "rgba(221, 110, 66, 1)",
						backgroundColor: "rgba(221, 110, 66, .2)",
						borderWidth: 1,
						label:'LATE'
					}, { 
						data: excusedArray,
						fill: false,
						borderColor: "rgba(232, 218, 178, 1)",
						backgroundColor: "rgba(232, 218, 178, .2)",
						borderWidth: 1,
						label:'EXCUSED'
					}, {
						data: absentArray,
						fill: false,
						borderColor: "rgba(220, 53, 69, 1)",
						backgroundColor: "rgba(220, 53, 69, .2)",
						borderWidth: 1,
						label:'ABSENT'
					}]
				};

		// config 
		config = {
			type: 'bar',
			data,
			options: {
				maintainAspectRatio: false,
				indexAxis: 'x',
				plugins: {
					legend: {
						position:'bottom',
						align:'start',
					},

					
				},
				
				scales: {
					y: {
						title: {
							display: true,
							text: '# of Students',
							font: {
								size: 15
							}
						}
					},
					x: {
						title: {
							display: true,
							text: 'Dates',
							font: {
								size: 15
							}
						}
					}
				}
			}
		};

		// render init block
		myChart = new Chart(
		document.getElementById('myChart'),
		config
		);

		const containerBody = document.querySelector('.containerBody');
		const totalLabels = myChart.data.labels.length;
		if(totalLabels > 7) {
			const newWidth = 700 + ((totalLabels - 7) * 50);
			containerBody.style.width = `${newWidth}px`;
		}
		</script>
</body>
</html>
