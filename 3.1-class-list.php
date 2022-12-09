<?php
// LOOK FOR WAY TO DELETE ALL DOWNLOADED FILES FROM THE HTDOCS
// CHANGE LOCATION OF DOWNLOAD
    session_start();

	include '0-connect.php';
	$teacher_name = strtoupper($_SESSION["Teacher name"]);
	$cg = $_SESSION["table"];
    $_SESSION['table'] = $cg;
	$array = array();
	$array_s = array();

?>

<?php
	//jump
	//SUMMARY DOWNLOAD AND MAIL
	if(isset($_GET['download_s_pdf'])){
		include '5-pdf-summary.php';
	}

	if(isset($_GET['download_pdf'])){
		include '5-pdf-detailed.php';
	}
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

	/* Style the tab */
	.tab {
		float: left;
		background-color: white;
		width: 20%;
		border-radius: 7px;
		margin-left: 100px;
		margin-top: 50px;
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
		height: fit-content;
		background-color: white;
		border-radius: 7px;
		padding:40px 50px; 
		margin:50px 24%;
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
		text-align: center;
		margin: 40px auto;
		padding: 15px;
		background: #fff;
		border-radius: 5px;
		width: 83%;
		max-height: 110%;
		position: relative;
	}

	.popup h2 {
		margin: 20px;
		padding: 10px;
		color: #4f6d7a;
		font-size: 28px;
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
		text-align: center;
		margin: 10px;
		padding: 10px;
		overflow: auto;
		max-height: 60%;
	}
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
	<nav class="topnav">
		<a href="http://localhost/attendance%20monitoring/2-create-table.php" style="color: #f2f2f2;"><i class="fa fa-home" style="font-size: 27px;text-align:center"></i></a>
		<a href="http://localhost/attendance%20monitoring/3-display-selection.php" style="color: #f2f2f2">Overall Attendance</a>
		<a class="active" href="http://localhost/attendance%20monitoring/3.1-class-list.php" style="color: #f2f2f2">Class List</a>
		<a href="http://localhost/attendance%20monitoring/3.2-date-filter.php" style="color: #f2f2f2">Date Filter</a>
		<a href="http://localhost/attendance%20monitoring/3.3-unenrolled.php" style="color: #f2f2f2">Unenrolled RFIDs</a>
		<a href="#sign-out" style="color: #f2f2f2; float:right">Sign Out</a>
	</nav>

	<div class="container pt-5" style="text-align:center">
        <!-- class course code display -->
		<div style="text-align:center">
			<h1 style="color:#dd6e42;font-size: 28px;"> <?php echo $cg; ?> Attendance</h1>
        </div>

		<div style="margin-right:10px; margin-top:10px; text-align:center">
			<a class="btn btn-danger" href="#summary" title="Export Summarized Report of Logs"
				style="border-radius: 7px; padding: 6px 10px; margin-top:10px;margin-right:10px; width:150px;">
				Export (Summary)
			</a>

			<a class="btn" href="#classlist" title="Export Detailed Report of Logs"
				style="border-radius: 7px; padding: 6px 10px; margin-top:10px;margin-left:10px; width:150px;
					   background-color:white;color:#dc3545;border-color:#dc3545;border-width:2px">
				Export (Detailed)
			</a>
    	</div>
	</div>

	<div class="tabcontent" style="margin-top:20px">
		<?php
			echo "<table style=margin-left:auto;margin-right:auto;text-align:center>";
			//echo "<th> ID Number </th>" . "<th> Names </th>";
			echo "<th> Name </th>";
		
			// this query doesn't include the empty ID and name columns
			$rfid = $db->query("select Concat(RFID, ', ', ID)
								as rf_id from `$cg` WHERE NOT name=''
								group by Name order by Surname");

			$concat= $db->query("select Concat(Surname, ', ', Name)
								as name from `$cg` WHERE NOT name=''
								group by Name order by Surname");	
		
			while ($row1 = $rfid->fetch_assoc() AND $row = $concat->fetch_assoc()) {
				echo "<tr>";
				
				// to pass the rfid,id,name,surname to the next page (4-monitoring)
				$rf_id = $row1['rf_id'];
				$fullname = $row['name'];
				$name=urlencode($fullname . ", " . $rf_id);
				echo "<td style=text-align:left>" . "<a href=4-monitoring.php/?name=$name> $fullname </a>" . "</td>";

				echo "</tr>";
			}
			
			echo "</table>";
		?>
	</div>

	<div id="classlist" class="overlay">
        <div class="popup">
            <h2>CLASSLIST ATTENDANCE REPORT (DETAILED)</h2>
			<a class="close" href="#">&times;</a>                    
					
            <div class="content">
            <?php       
                //$array = array();
                echo "<table style=margin-left:auto;margin-right:auto;text-align:center>";
				echo "<th> ID# </th>" . "<th> Lastname </th>" . "<th> Name </th>" .
						 "<th> Date </th>" . "<th> Status </th>" .
						 "<th> Time-in </th>";
                $show_col = $db->query("SELECT ID,Surname,Name,Date,Status,Time FROM `$cg` order by Surname");
                        
                if ($show_col->num_rows > 0) {

                    // fetches this data if database is not empty
                    while($row = $show_col->fetch_assoc()) {
                        $array[] = $row;
						echo "<tr>";
						foreach($row as $r){
							echo "<td>";
							echo $r;
							echo "</td>";
						}
						echo "</tr>";
                    }
                }

                echo "</table>";

				function dl($array, $teacher_name, $cg){
					// filename = download path/filename            
					$tempname = strtoupper($teacher_name) . "_" . $cg . ".csv";
					$file = fopen($tempname,"w");
					fputcsv($file, array("ID#","Lastname","Name","Date","Status","Time-in"));
												
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
						<input class="form-control" type="email" name="email" placeholder="Email Address" required
								style = "margin-top:20px; padding:15px 80px;text-align:center"/>
					</div>

					<div class="form-group">
						<input class="btn btn-danger" type="submit" name="send_email" value="Send" 
								style = "margin:15px 20px; padding:10px 17px; border-radius:18px;"/>
					</div>	
				</form>		

				<form method="GET" action="#dl_options">
					<input class="btn btn-danger" type="submit" name="Dl" value="Download"
						style = "margin:35px 20px; padding:10px 17px; border-radius:18px;"/>
				</form>

				
			</div>

			<?php
			//jump
			if(array_key_exists('Dl',$_GET)){
				dl($array, $teacher_name, $cg);
			}
			 
			// DETAILED DOWNLOAD AND SEND MAIL
			if(isset($_GET['download_csv'])){
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
				$from = '19101182@usc.edu.ph';
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

	<div id="dl_options_s" class="overlay">
        <div class="popup" style="width:40%; margin:10% 30%">
			<h2>Download Options:</h2>
			<a class="close" href="http://localhost/attendance%20monitoring/3.1-class-list.php#">&times;</a>
			<form method="GET">
				<div class="form-group">
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
			<a class="close" href="http://localhost/attendance%20monitoring/3.1-class-list.php#">&times;</a>
			<form method="GET">
				<div class="form-group">
					<input type="submit" name="download_pdf" value="PDF" class="btn btn-danger" 
						style="border-radius:18px; margin-top:35px; padding:10px 17px;"/>
					<input type="submit" name="download_csv" value="CSV" class="btn btn-danger" 
						style="border-radius:18px; margin-top:35px; padding:10px 17px;"/>
				</div>
			</form>
		</div>
	</div>

	<div id="summary" class="overlay">
        <div class="popup">
            <h2>CLASSLIST ATTENDANCE REPORT (SUMMARY)</h2>
			<a class="close" href="#">&times;</a>                    
					
            <div class="content">
            <?php       
				$d = array();
				$date = array();
				//$array_s = array();

                $show_col = $db->query("SELECT Name,Surname,Date FROM `$cg` order by Surname");
                        
                if ($show_col->num_rows > 0) {
					echo "<table id='test' style=margin-left:auto;margin-right:auto;text-align:center>";
					echo "<th> Name </th>" . "<th> Present </th>" . "<th> Late </th>" .
						 "<th> Excused </th>" . "<th> Absent </th>" .
						 "<th> Attendance Days </th>" . "<th> % Presence </th>";

                    // fetches this data if database is not empty
                    while($row = $show_col->fetch_assoc()) {
                        $d[] = $row['Date'];
						$n[] = $row['Surname'] . ", " . $row['Name'];
					}

					$date = array_unique($d);
					$total = count($date);
					$name = array_unique($n);

					echo "<tr>";

					$count = count($name);
					$keys = array_keys($name);

					for($i = 0; $i < $count; $i++) {
						$present = 0;
						$late = 0;
						$excused = 0;
						$absent = 0;

						$split = explode(", ", $name[$keys[$i]]);
						$stat = $db->query("SELECT Status FROM `$cg` WHERE Name='$split[1]' AND Surname='$split[0]'");

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

						if(($present + $late + $excused + $absent) !== $total){
							$absent++;
						}

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
				}

				function dl_s($array_s, $teacher_name, $cg){
					// filename = download path/filename            
					$tempname = strtoupper($teacher_name) . "_" . $cg . ".csv";
					$_SESSION['file'] = $tempname;
					$file = fopen($tempname,"w");
					fputcsv($file, array("Name","Present","Late","Excused","Absent","Attendance Days","% Presence"));
															
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
						<input class="btn btn-danger" type="submit" name="send_email_s" value="Send" 
								style = "margin:15px 20px; padding:10px 17px; border-radius:18px;"/>
					</div>	
				</form>		

				<form method="GET" action="#dl_options_s">
					<input class="btn btn-danger" type="submit" name="Dl_s" value="Download"
						style = "margin:35px 20px; padding:10px 17px; border-radius:18px;"/>
				</form>
			</div>

				<!--<a href="#dl_options" title="Select Download Options" class="btn"  onclick="location.href='#dl_options_s';"
					style="background-color:#dc3545; color:white;border-radius:18px; margin-top: 35px; margin-bottom:45px; padding:10px 17px;">
					Download
				</a> -->

				<?php
					//jump
					// if Download button was clicked
					if(array_key_exists('Dl_s',$_GET)){
						dl_s($array_s, $teacher_name, $cg);
					 }
					//SUMMARY DOWNLOAD AND MAIL
					if(isset($_GET['download_s_csv'])){
						// filename = download path/filename            
						$filename = "C:/Users/Amber/Downloads/". strtoupper($teacher_name) . "_" . $cg . ".csv";
						$file = fopen($filename,"w");
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
						$filename = "C:/Users/Amber/Downloads/". strtoupper($teacher_name) . "_" . $cg . ".csv";
						$file = fopen($filename,"w");
						fputcsv($file, array("Name","Present","Late","Excused","Absent","Attendance Days","% Presence"));
														
						if (count($array_s) > 0) {
							foreach ($array_s as $row) {
								fputcsv($file, $row);
							}
						}

						fclose($file);

						// the necessary email addresses
						$from = '19101182@usc.edu.ph';
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
							//unlink($tempname); // delete the file after attachment sent.
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
</body>
</html>