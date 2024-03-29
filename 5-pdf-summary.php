<?php
    $conn = new mysqli("localhost", "root", "", "temp");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT val FROM temptb WHERE varname = 'teacherName' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $tempvar1 = $row["val"];
    }
    $teacher = $tempvar1;
    
    $sql = "SELECT val FROM temptb WHERE varname = 'table' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $tempvar2 = $row["val"];
    }
    $table = $tempvar2;
    
    $sql = "SELECT val FROM temptb WHERE varname = 'sd_copy' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $tempvar3 = $row["val"];
        $ds = $tempvar3;
    }
    else
        $ds = '';
    
    $sql = "SELECT val FROM temptb WHERE varname = 'ed_copy' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $tempvar4 = $row["val"];
        $de = $tempvar4;
    }
    else
        $de = '';
    
    $sql = "SELECT val FROM temptb WHERE varname = 'array_s_copy' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $tempvar5 = $row["val"];
        mysqli_close($conn);
        $scopy = unserialize($tempvar5);
    }
    else
        $scopy = '';
    
require('tcpdf/tcpdf.php');

//TAGS: CHANGE FILE ADDRESS, SERVER PC
//teacher name_course group.csv
//teacher name is found at 2-create-table.php. $_session["table"] is found at 3-display-selection.php
$filename = "C:/Users/Kath/Downloads/". $teacher . "_" . $table . ".csv";

if ( !file_exists( $filename ) && !is_dir( $filename ) ) {
    //Creates .csv file if .csv doesn't exist
    $sd = $ds;
    $ed = $de;
    $array_s = $scopy;
    $filename = "C:/Users/Kath/Downloads/". strtoupper($teacher) . "_" . $table . ".csv";
	$file = fopen($filename,"w");
	fputcsv($file, array("Start date: ", $sd, "End date: ", $ed));
	fputcsv($file, array("ID#", "Name","Present","Late","Excused","Absent","Attendance Days","% Presence"));
															
		if (count($array_s) > 0) {
			foreach ($array_s as $row) {
				fputcsv($file, $row);
			}
		}

	fclose($file);       
}
else{
    //Clones a temporary .csv file if .csv exists
    $tempFileName = "C:/Users/Kath/Downloads/". strtoupper($teacher) . "_TEMP_" . $table . ".csv";
    copy($filename, $tempFileName);
    $filename = $tempFileName;
}

$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8');
$pdf->AddPage();
$pdf->setAutoPageBreak(1, 23);
//$pdf->SetMargins(14,14,14);
$pdf->SetFont('Times', 'B', 11);
$pdf->Ln();
$pdf->Write(10, utf8_encode(strtoupper($teacher)) . "\n" . utf8_encode(strtoupper($table)), '', false, 'L', true);
$pdf->Ln();

//$row=file('ANTONIETTE M CAñETE_G2-CPE 3101L(T - 0900 AM - 1200 PM).csv');
$row = file($filename);

// for the table headers
for ($c = 0; $c < 2; $c++) {
    if ($c==1){
        $pdf->SetFont('Times', '', 10);
    }
    $html = '<table border="0" cellspacing="0" cellpadding="8" style="border:1px solid #e1e1e1;width:100%">' . '<tr>';
    $data = explode(',', trim($row[$c], '\'"'));

    for ($i = 0; $i < count($data); $i++) {
        $html .= '<td>' . trim($data[$i], '\'"') . '</td>';
    }

    $html .= '</tr>' . '</table>';
    $pdf->writeHTML(trim($html), false, false, false, false, '');
}

$pdf->SetFont('Times', '', 10);
//skip immediately to the first student row
for ($c = 2; $c < count($row); $c++) {
    $html = '<table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #e1e1e1;width:100%">' . '<tr>';
    $data = explode(',', trim($row[$c], '\'"'));

    $html .= '<td width="77.5">';
    $html .= trim($data[0], '\'"');
    $html .= '</td>';

    $html .= '<td>' . trim($data[1], '\'"') . "," . trim($data[2], '\'"') . '</td>';

    for ($i = 3; $i < count($data); $i++) {
        $html .= '<td>' . trim($data[$i], '\'"') . '</td>';
    }
    $html .= '</tr>' . '</table>';
    $pdf->writeHTML(trim($html), false, false, false, false, '');
}
unlink($filename);
ob_end_clean();
$pdf->Output(utf8_encode(strtoupper($teacher)) . "_" . $table . "_Summary" . ".pdf", 'D', TRUE);
?>
