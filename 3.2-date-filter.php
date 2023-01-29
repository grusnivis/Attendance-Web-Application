<?php
    session_start();
	include '0-connect.php';
	$teacher_name = $_SESSION["teacherName"];
    $cg = $_SESSION["table"];
    $_SESSION['table'] = $cg;

	$array = array();
	$array_s = array();

	if(isset($_GET['download_s_pdf'])){
		include '5-pdf-summary.php';
	}

	if(isset($_GET['download_pdf'])){
		include '5-pdf-detailed.php';
	}

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>View Attendance</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="css/date-filter-style.css"/>

	<!--<link rel="stylesheet" type="text/css"
		href="css/monitoring-style.css"> -->
</head>


<body style="background-color: #eaeaea">
	<nav class="topnav">
		<a href="2-create-table.php" style="color: #f2f2f2;"><i class="fa fa-home" style="font-size: 27px;text-align:center"></i></a>
		<a href="3-display-selection.php" style="color: #f2f2f2">Overall Attendance</a>
		<a href="3.1-class-list.php" style="color: #f2f2f2">Class List</a>
		<a class="active" href="3.2-date-filter.php" style="color: #f2f2f2">Date Filter</a>
		<a href="3.3-unenrolled.php" style="color: #f2f2f2">Unenrolled RFIDs</a>
		<a href="logout.php" style="color: #f2f2f2; float:right">Log Out</a>
	</nav>

	<div class="container pt-5" style="text-align:center">
        <!-- class course code display -->
		<div style="text-align:center">
			<h1 style="color:#dd6e42;font-size: 28px;"> <?php echo $cg; ?> Attendance Log</h1>
			
			<div style="padding-top: 40px; padding-bottom: 20px; text-align:center">
				<!-- Date Filtering (this is for date selection)-->
				<form method="GET" action="">
					<label for="from">FROM: </label>
					<input type="date" name="start_date">

					<label for="to">TO: </label>
					<input type="date" name="end_date">

					<!-- button! -->
					 <btn><input type="submit" style="background-color: #dd6e42; color: white;border:0"
						method="GET" name="btn" value="filter"></btn>
				</form>
            </div>
        </div>

	<!-- Displays data from table based on the dates chosen -->
	<?php
		// if "filter" button is pressed
		if (isset($_GET['btn'])) {
			$append_date = array();
			$headers = array("ID#", "Name", "Date", "Status", "Time");
			$date = $_GET['start_date'];
			$date2 = $_GET['end_date'];
			
			// if start date field is empty and end date field is not empty
			if($date == "" && !($date2 == "")){
				$date = $date2;
				$date == "";
				$date2 = "";
			}

			// query to get content of selected columns that have the selected date in the row
			$q = "select Date,Status,Time from `$cg` where Date='$date'
				 order by Surname";
			$check_date = mysqli_query($db,$q);

			// query to get dates from database (no duplicates)
			$d = "select Date from `$cg` group by Date order by Date";
			$get_date = mysqli_query($db,$d);

	?>
		<div>
			<?php 
				if($date2 == "" && !($date == "")){
			?>
					<div style="display:inline-flex;">
						<h5> <?php echo "Checking attendance on:</br>" . $date;?> </h5>
						<a class="btn btn-danger" href="#date" title="Export"
							style="border-radius: 7px; padding: 13px 15px 10px 15px; margin: 0 20px;">
							Export
						</a>
					</div>
					<div style="text-align:center;background-color:white;border-radius:7px;
							    margin:50px 15%; padding:50px">
			<?php
						$array = array();
						$show_col = $db->query("SELECT * FROM `$cg` 
												where Date='$date'  order by Surname");
								
						if ($show_col->num_rows > 0) {
							// fetches this data if database is not empty
							while($row = $show_col->fetch_assoc()) {
								$array[] = $row;
							}
						}

						display_table($db, $cg, $headers, $check_date, $date);
			?>
						<div id="date" class="overlay">
						<div class="popup" style="width:83%;">
							<a class="close" href="#">&times;</a>                    
											
							<div class="content" style="padding-top:50px">
							<?php       
								//NOTE FOR EMAIL PART
								//https://www.geeksforgeeks.org/how-to-configure-xampp-to-send-mail-from-localhost-using-php/
								//https://myaccount.google.com/lesssecureapps
							?>

							<div style="display:flex">
								<form enctype="multipart/form-data" method="POST" action="" 
										style="margin-top:20px; margin-left:25%; display:flex; text-align:center">
									<div class="form-group">
										<input class="form-control" type="email" name="email" placeholder="Email Address" required
												style = "margin-top:20px; padding:15px 80px;text-align:center"/>
									</div>

									<div class="form-group">
										<input type="hidden" name="start_date" value="<?php echo $_GET['start_date']?>"/>
										<input type="hidden" name="end_date" value="<?php echo $_GET['end_date']?>"/>
										<input type="hidden" name="btn" value="filter"/>

										<input class="btn btn-danger" type="submit" name="send_email" value="Send" 
												style = "margin:15px 20px; padding:10px 17px; border-radius:18px;"/>
									</div>	
								</form>		

								<form method="post">
									<input type="submit" name="download" value="Download" class="btn btn-danger" 
										style="border-radius:18px; margin-top:35px; padding:10px 17px;"/>
								</form>
							</div>
							</div>

							<?php
								if(isset($_GET['download'])){
								// filename = download path/filename            
								$filename = "C:/Users/Amber/Downloads/". strtoupper($teacher_name) . "_" . $cg . ".csv";
								$file = fopen($filename,"w");
								fputcsv($file, array("ID#","Lastname","Name","Date","Status","Time-in"));
																	
								if (count($array) > 0) {
									foreach ($array as $row) {
										fputcsv($file, $row);
									}
								}
								fclose($file);
								}

								if(isset($_POST['send_email'])){
									// filename = download path/filename            
									$filename = "C:/Users/Amber/Downloads/". strtoupper($teacher_name) . "_" . $cg . ".csv";
									$file = fopen($filename,"w");
									fputcsv($file, array("ID#","Lastname","Name","Date","Status","Time-in"));
																	
									if (count($array) > 0) {
										foreach ($array as $row) {
											fputcsv($file, $row);
										}
									}

									fclose($file);

									// the necessary email addresses
                                    // NOTE: CHANGE EMAIL ADDRESS ON SERVER PC IF NECESSARY
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
									$headers .= "From:".$from."\r\n"; // Sender Email
									$headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
									$headers .= "boundary = $boundary\r\n"; //Defining the Boundary
													
									//plain text
									$body = "--$boundary\r\n";
									$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
									$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
									$body .= chunk_split(base64_encode("Requested attendance log from $cg schedule"));
													
									//attachment
									$body .= "--$boundary\r\n";
									$body .="Content-Type: $type; name=".basename($filename)."\r\n";
									$body .="Content-Disposition: attachment; filename=".basename($filename)."\r\n";
									$body .="Content-Transfer-Encoding: base64\r\n";
									$body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
									$body .= $encoded_content; // Attaching the encoded file with email
												
									$sentMailResult = mail($to, "Exported Attendance Log", $body, $headers);

									if($sentMailResult ){
										echo "<h3 style=text-align:center>File Sent Successfully<h3>";
										unlink($filename); // delete the file after attachment sent.
									}
									else{
										die("Sorry but the email could not be sent.
											Please try again!");
									}
								}
								?>
							</div>
						</div>
						</div>
					</div>
			<?php
				}
				elseif (!($date == "") || !($date2 == "")){
			?>
					</div>

					<div>
						<h5> <?php echo "Checking attendance from:</br>" . $date . " to " . $date2;?> </h5>	
					</div>

					<div style="text-align:center">
						<a class="btn btn-danger" href="#summary" title="Export Summarized Report of Logs"
							style="border-radius: 7px; padding: 6px 10px; margin: 10 5px 0 20px; 
									width:fit-content">
							Export(Summary)
						</a>

						<a class="btn" href="#date" title="Export"
							style="border-radius: 7px; padding: 6px 10px; margin: 10 5px 0 20px;
							background-color:white;color:#dc3545;border-color:#dc3545;border-width:2px; width:fit-content">
							Export(Detailed)
						</a>
					</div>
					
					
					<div class=tab style=margin-top:110px>
						<h6 style="padding-top:8px; color: #4f6d7a">Select date to view details</h6>
			<?php
					$array = array();
					while ($date_array = $get_date->fetch_assoc()){

						foreach($date_array as $date_from_user){
							$stat = check_in_range($date, $date2, $date_from_user);
							

							if($stat){
								// stores the dates from range of date filter
								$append_date[] = $date_from_user;					
								
								// for the csv file - export
								$show_col = $db->query("SELECT ID,Surname,Name,Date,Status,Time FROM `$cg` 
															where Date='$date_from_user'  order by Surname");
											
								if ($show_col->num_rows > 0) {
									// fetches this data if database is not empty
									while($row = $show_col->fetch_assoc()) {
										$array[] = $row;
									}
								}
			?>
								<!-- div for dates (within range) in vertical navigation bar -->
								<button id="defaultOpen" class="tablinks" onclick="openTab(event, '<?php echo $date_from_user;?>')"><?php echo $date_from_user ?></button>
			<?php			
							}
						}	
					}
			?>
			
				</div>	

			<?php
				foreach($append_date as $tab_date){

			?>
					<div id=<?php echo $tab_date ?> class="tabcontent">
					<?php
						$display = "select Date,Status,Time from `$cg` 
						where Date='$tab_date' 
						 order by Surname";
						$chosen_dates = mysqli_query($db,$display);
						display_table($db, $cg, $headers, $chosen_dates, $tab_date);
					?>
					</div>
			<?php
				}
			?>
		<?php
			}
		?>
	</div>

	<div id="summary" class="overlay">
        <div class="popup" style="width:83%;">
		<h2 style="font-size:28px;">ATTENDANCE REPORT (SUMMARY)</h2>
			<a class="close" href="#">&times;</a>                    
					
            <div class="content">
            <?php       
				echo "<table id='test' style=margin-left:auto;margin-right:auto;text-align:center>";
				echo "<th> Name </th>" . "<th> Present </th>" . "<th> Late </th>" .
					 "<th> Excused </th>" . "<th> Absent </th>" .
					 "<th> Attendance Days </th>" . "<th> % Presence </th>";
				//$n = array();

				foreach($append_date as $filtered_date){
					$show_col = $db->query("SELECT Name,Surname FROM `$cg` where Date='$filtered_date' order by Surname");

					if ($show_col->num_rows > 0) {
						// fetches this data if database is not empty
						while($row = $show_col->fetch_assoc()) {
							$n[] = $row['Surname'] . ", " . $row['Name'];
						}	
					}
				}

				$total = count($append_date);
				$name = array_unique($n);
				$count = count($name);
				$keys = array_keys($name);

				for($i = 0; $i < $count; $i++) {
					$present = 0;
					$late = 0;
					$excused = 0;
					$absent = 0;


					for($c = 0; $c < $total; $c++){
						$split = explode(", ", $name[$keys[$i]]);
						$stat = $db->query("SELECT Status FROM `$cg` WHERE Name='$split[1]' AND Surname='$split[0]' AND Date='$append_date[$c]' order by Surname");

						while($s = $stat->fetch_assoc()) {
							if($s['Status'] === "PRESENT"){
								$present++;
							}

							if($s['Status'] === "LATE"){
								$late++;
							}

							if($s['Status'] === "EXCUSED"){
								$excused++;
							}

							if($s['Status'] === "ABSENT"){
								$absent++;
							}
						}
				}
					if(($present + $late + $excused + $absent) !== $total){
						$absent++;
					}
				
					echo "<tr>";

						echo "<td>";
						echo $name[$keys[$i]];
						echo "</td>";

						echo "<td>";
						echo $present;
						echo "</td>";

						echo "<td>";
						echo $late;
						echo "</td>";

						echo "<td>";
						echo $excused;
						echo "</td>";

						echo "<td>";
						echo $absent;
						echo "</td>";

						echo "<td>";
						echo $total;
						echo "</td>";

						echo "<td>";
						$percent = round(((($present+$late)/ $total) * 100)) . "%";
						echo $percent;
						echo "</td>";
						
						$array_s[$i] = [$name[$keys[$i]] , $present , $late , $excused , $absent , $total , $percent];
					echo "</tr>";
				}
				echo "</table>";
				
				function dl($array, $teacher_name, $cg){
					// filename = download path/filename            
					$tempname = strtoupper($teacher_name) . "_" . $cg . ".csv";
					$file = fopen($tempname,"w");
					fputcsv($file, array("Start date:",$_GET['start_date']," ","End date:",$_GET['end_date']));
					fputcsv($file, array("ID#","Lastname","Name","Date","Status","Time-in"));
												
					if (count($array) > 0) {
						foreach ($array as $row) {
							fputcsv($file, $row);
						}
					}
					fclose($file);
				}


				function dl_s($array_s, $teacher_name, $cg){
					// filename = download path/filename            
					$tempname = strtoupper($teacher_name) . "_" . $cg . ".csv";
					$_SESSION['file'] = $tempname;
					$file = fopen($tempname,"w");
					fputcsv($file, array("Start date:",$_GET['start_date']," ","End date:",$_GET['end_date']));
					fputcsv($file, array("Name","","Present","Late","Excused","Absent","Attendance Days","% Presence"));
															
					if (count($array_s) > 0) {
						foreach ($array_s as $row) {
							fputcsv($file, $row);
						}
					}
					fclose($file);
				}
	?>
			<div style="display:flex">
				<form enctype="multipart/form-data" method="POST" action="" 
						style="margin-top:20px; margin-left:25%; display:flex; text-align:center">
					<div class="form-group">
						<input class="form-control" type="email" name="email" placeholder="Email Address" required
								style = "margin-top:20px; padding:15px 80px;text-align:center"/>
					</div>

					<div class="form-group">
						<input type="hidden" name="start_date" value="<?php echo $_GET['start_date']?>"/>
						<input type="hidden" name="end_date" value="<?php echo $_GET['end_date']?>"/>
						<input type="hidden" name="btn" value="filter"/>
					
						<input class="btn btn-danger" type="submit" name="send_email_s" value="Send" 
								style = "margin:15px 20px; padding:10px 17px; border-radius:18px;"/>
					</div>	
				</form>		

				<form method="GET" action="#dl_options_s">
					<input type="hidden" name="start_date" value="<?php echo $_GET['start_date']?>"/>
					<input type="hidden" name="end_date" value="<?php echo $_GET['end_date']?>"/>
					<input type="hidden" name="btn" value="filter"/>

					<input class="btn btn-danger" type="submit" name="Dl_s" value="Download" 
						style = "margin:35px 20px; padding:10px 17px; border-radius:18px;"/>
				</form>
			</div>

			<?php
				// if Download button was clicked
				if(array_key_exists('Dl_s',$_GET)){
					dl_s($array_s, $teacher_name, $cg);
				}
				
				//SUMMARY DOWNLOAD AND MAIL
				if(isset($_GET['download_s_csv'])){
					// filename = download path/filename
                    // NOTE: CHANGE FILEPATH ON THE SERVER PC
					$filename = "C:/Users/Kath/Downloads/". strtoupper($teacher_name) . "_" . $cg . ".csv";
					$file = fopen($filename,"w");
					fputcsv($file, array("Start date:",$_GET['start_date']," ","End date:",$_GET['end_date']));
					fputcsv($file, array("Name","Present","Late","Excused","Absent","Attendance Days","% Presence"));
															
					if (count($array_s) > 0) {
						foreach ($array_s as $row) {
							fputcsv($file, $row);
						}
					}

					fclose($file);
				}

				if(isset($_POST['send_email_s'])){

					// filename = download path/filename
                    // NOTE: CHANGE FILEPATH ON THE SERVER PC
					$filename = "C:/Users/Kath/Downloads/". strtoupper($teacher_name) . "_" . $cg . ".csv";
					$file = fopen($filename,"w");
					fputcsv($file, array("Name","Present","Late","Excused","Absent","Attendance Days","% Presence"));
											
					if (count($array_s) > 0) {
						foreach ($array_s as $row) {
							fputcsv($file, $row);
						}
					}
	
					fclose($file);
	
					// the necessary email addresses
                    // NOTE: CHANGE EMAIL ADDRESS ON SERVER PC IF NECESSARY
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
					$headers .= "From:".$from."\r\n"; // Sender Email
					$headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
					$headers .= "boundary = $boundary\r\n"; //Defining the Boundary
							
					//plain text
					$body = "--$boundary\r\n";
					$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
					$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
					$body .= chunk_split(base64_encode("Requested attendance log from $cg schedule"));
							
					//attachment
					$body .= "--$boundary\r\n";
					$body .="Content-Type: $type; name=".basename($filename)."\r\n";
					$body .="Content-Disposition: attachment; filename=".basename($filename)."\r\n";
					$body .="Content-Transfer-Encoding: base64\r\n";
					$body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
					$body .= $encoded_content; // Attaching the encoded file with email
						
					$sentMailResult = mail($to, "Exported Attendance Log", $body, $headers);
	
					if($sentMailResult ){
						echo "<h3 style=text-align:center>File Sent Successfully<h3>";
						unlink($filename); // delete the file after attachment sent.
					}
					else{
						die("Sorry but the email could not be sent.
							Please try again!");
					}
				}

			?>

		</div>
		</div>
	</div>

	
	<!--jump-->
	<div id="dl_options_s" class="overlay">
        <div class="popup" style="width:40%; margin:10% 30%">
			<h2>Download Options:</h2>
			<a class="close" href="#">&times;</a>
			<form method="GET">
				<div class="form-group">
					<input type="hidden" name="start_date" value="<?php echo $_GET['start_date']?>"/>
					<input type="hidden" name="end_date" value="<?php echo $_GET['end_date']?>"/>
					<input type="hidden" name="btn" value="filter"/>

					<input type="submit" name="download_s_pdf" value="PDF" class="btn btn-danger" 
						style="border-radius:18px; margin-top:35px; padding:10px 17px;"/>
					<input type="submit" name="download_s_csv" value="CSV" class="btn btn-danger" 
						style="border-radius:18px; margin-top:35px; padding:10px 17px;"/>
				</div>
			</form>
		</div>
	</div>

	<div id="dl_options" class="overlay">
        <div class="popup" style="width:40%; margin:10% 30%">
			<h2>Download Options:</h2>
			<a class="close" href="#">&times;</a>
			<form method="GET">
				<div class="form-group">
					<input type="hidden" name="start_date" value="<?php echo $_GET['start_date']?>"/>
					<input type="hidden" name="end_date" value="<?php echo $_GET['end_date']?>"/>
					<input type="hidden" name="btn" value="filter"/>

					<input type="submit" name="download_pdf" value="PDF" class="btn btn-danger" 
						style="border-radius:18px; margin-top:35px; padding:10px 17px;"/>
					<input type="submit" name="download_csv" value="CSV" class="btn btn-danger" 
						style="border-radius:18px; margin-top:35px; padding:10px 17px;"/>
				</div>
			</form>
		</div>
	</div>
	
	<!-- JUMP -->
	<div id="date" class="overlay">
		<div class="popup" style="width:83%;">
			<h2 style="margin-top:90px; font-size:28px;">ATTENDANCE REPORT (DETAILED)</h2>     
			<a class="close" href="#">&times;</a>               
											
			<div class="content" style="padding-top:50px">
			<?php       
				//NOTE FOR EMAIL PART
				//https://www.geeksforgeeks.org/how-to-configure-xampp-to-send-mail-from-localhost-using-php/
				//https://myaccount.google.com/lesssecureapps
			?>

			<div style="display:flex">
				<form enctype="multipart/form-data" method="POST" action="" 
						style="margin-left:25%; display:flex; text-align:center">
					<div class="form-group">
						<input class="form-control" type="email" name="email" placeholder="Email Address" required
								style = "padding:15px 80px;text-align:center"/>
					</div>

					<div class="form-group">
						<input type="hidden" name="start_date" value="<?php echo $_GET['start_date']?>"/>
						<input type="hidden" name="end_date" value="<?php echo $_GET['end_date']?>"/>
						<input type="hidden" name="btn" value="filter"/>
						
						<input class="btn btn-danger" type="submit" name="send_email" value="Send" 
								style = "margin:0 20px; padding:10px 17px; border-radius:18px;"/>
					</div>	
				</form>		

				<form method="GET" action="#dl_options">
				<div class="form-group">
					<input type="hidden" name="start_date" value="<?php echo $_GET['start_date']?>"/>
					<input type="hidden" name="end_date" value="<?php echo $_GET['end_date']?>"/>
					<input type="hidden" name="btn" value="filter"/>

					<input class="btn btn-danger" type="submit" name="Dl" value="Download"
						style = "margin:0 20px; padding:10px 17px; border-radius:18px;"/>
					
					<!-- 
						<a class="btn" onclick="location.href='#dl_options';" title="Download"
							style="margin:0 20px; padding:10px 17px; border-radius:18px;
							background-color:white;color:#dc3545;border-color:#dc3545;border-width:2px; width:fit-content">
							Download
						</a>
					-->
				</div>
				</form>
			</div>

			<?php
				if(array_key_exists('Dl',$_GET)){
					dl($array, $teacher_name, $cg);
				}

				if(isset($_GET['download_csv'])){
				// filename = download path/filename
                // NOTE: CHANGE FILEPATH ON THE SERVER PC
				$filename = "C:/Users/Kath/Downloads/". strtoupper($teacher_name) . "_" . $cg . ".csv";
				$file = fopen($filename,"w");
				fputcsv($file, array("Start date:",$_GET['start_date']," ","End date:",$_GET['end_date']));
				fputcsv($file, array("ID#","Lastname","Name","Date","Status","Time-in"));
																	
				if (count($array) > 0) {
					foreach ($array as $row) {
						fputcsv($file, $row);
					}
				}
				fclose($file);
				}

				if(isset($_POST['send_email'])){
					// filename = download path/filename
                    // NOTE: CHANGE FILEPATH ON THE SERVER PC
					$filename = "C:/Users/Kath/Downloads/". strtoupper($teacher_name) . "_" . $cg . ".csv";
					$file = fopen($filename,"w");
					fputcsv($file, array("ID#","Lastname","Name","Date","Status","Time-in"));
																	
					if (count($array) > 0) {
						foreach ($array as $row) {
							fputcsv($file, $row);
						}
					}

					fclose($file);

					// the necessary email addresses
                    // NOTE: CHANGE EMAIL ADDRESS ON SERVER PC IF NECESSARY
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
					$headers .= "From:".$from."\r\n"; // Sender Email
					$headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
					$headers .= "boundary = $boundary\r\n"; //Defining the Boundary
													
					//plain text
					$body = "--$boundary\r\n";
					$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
					$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
					$body .= chunk_split(base64_encode("Requested attendance log from $cg schedule"));
													
					//attachment
					$body .= "--$boundary\r\n";
					$body .="Content-Type: $type; name=".basename($filename)."\r\n";
					$body .="Content-Disposition: attachment; filename=".basename($filename)."\r\n";
					$body .="Content-Transfer-Encoding: base64\r\n";
					$body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
					$body .= $encoded_content; // Attaching the encoded file with email
												
					$sentMailResult = mail($to, "Exported Attendance Log", $body, $headers);

					if($sentMailResult ){
						echo "<h3 style=text-align:center>File Sent Successfully<h3>";
						unlink($filename); // delete the file after attachment sent.
					}
					else{
						die("Sorry but the email could not be sent.
							Please try again!");
					}
				}
				?>
		</div>
	</div>	
	<?php	
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
function display_table($db, $cg, $headers, $check_date, $date_from_user){

	//echo "<div style=padding-top:20px;padding-bottom:20px;text-align:center>";
	echo "<table style=margin-left:auto;margin-right:auto;text-align:center>";

	foreach($headers as $value) {
		echo "<th>" . $value . "</th>";
	}

	$id = $db->query("select ID as id from `$cg` where Date='$date_from_user' order by Surname");
	$concat= $db->query("select Concat(Surname, ', ', Name) as name from `$cg` where Date='$date_from_user' order by Surname");

	while ($id_num = $id->fetch_assoc() AND $row = $concat->fetch_assoc() AND $other_col = $check_date->fetch_assoc()) {
		echo "<tr>";

		$id_number = $id_num['id'];
		$fullname = $row['name'];

		if($fullname !== ", "){
			echo "<td>" . $id_number . "</td>";
			echo "<td>" . $fullname . "</td>";
			
			foreach($other_col as $value) {
				echo "<td>" . $value . "</td>";
			}
		}

		echo "</tr>";
	}

	while ($columns=mysqli_fetch_assoc($check_date)) {				
		echo "<tr>";
		foreach ($columns as $items){
			echo "<td>" . $items . "</td>";
			$array[] = $items;
		}
		echo "</tr>";
	}	
	echo "</table>";
}

function check_in_range($date, $date2, $date_from_user)
    {
        // Convert to timestamp
        $date = strtotime($date);
        $date2 = strtotime($date2);
        $user_ts = strtotime($date_from_user);

        // Check that user date is between start & end
        if (($user_ts >= $date) && ($user_ts <= $date2)) {
            return 1;
        }
        else{
            return 0;
        }
    }
?>