<?php
require('tcpdf/tcpdf.php');

//TAGS: CHANGE FILE ADDRESS, SERVER PC
//teacher name_course group.csv
//teacher name is found at 2-create-table.php. $_session["table"] is found at 3-display-selection.php
$filename = "C:/Users/Kath/Downloads/". $_SESSION['teacherName'] . "_" . $_SESSION["table"] . ".csv";

if ( !file_exists( $filename ) && !is_dir( $filename ) ) {
    //Creates .csv file if .csv doesn't exist
    $sd = $_SESSION['sd_copy'];
    $ed = $_SESSION['ed_copy'];
    $array_s = $_SESSION['array_s_copy'];
    $filename = "C:/Users/Kath/Downloads/". strtoupper($teacher_name) . "_" . $cg . ".csv";
	$file = fopen($filename,"w");
	fputcsv($file, array("Start date: ", $sd, "End date: ", $ed));
	fputcsv($file, array("Name","Present","Late","Excused","Absent","Attendance Days","% Presence"));
															
		if (count($array_s) > 0) {
			foreach ($array_s as $row) {
				fputcsv($file, $row);
			}
		}

	fclose($file);       
}
else{
    //Clones a temporary .csv file if .csv exists
    $tempFileName = "C:/Users/Kath/Downloads/". strtoupper($teacher_name) . "_TEMP_" . $cg . ".csv";
    copy($filename, $tempFileName);
    $filename = $tempFileName;
}

$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8');
$pdf->AddPage();
$pdf->setAutoPageBreak(1, 23);
//$pdf->SetMargins(14,14,14);
$pdf->SetFont('Times', 'B', 11);
$pdf->Ln();
$pdf->Write(10, utf8_encode(strtoupper($_SESSION["teacherName"])) . "\n" . utf8_encode(strtoupper($_SESSION['table'])), '', false, 'L', true);
$pdf->Ln();

//$row=file('ANTONIETTE M CAñETE_G2-CPE 3101L(T - 0900 AM - 1200 PM).csv');
$row = file($filename);

// for the table headers
for ($c = 0; $c < 2; $c++) {
    if ($c==1){
        $pdf->SetFont('Times', '', 10);
    }
    $html = '<table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #e1e1e1">' . '<tr>';
    $data = explode(',', trim($row[$c], '\'"',));

    for ($i = 0; $i < count($data); $i++) {
        $html .= '<td>' . trim($data[$i], '\'"') . '</td>';
    }

    $html .= '</tr>' . '</table>';
    $pdf->writeHTML(trim($html), false, false, false, false, '');
}

$pdf->SetFont('Times', '', 10);
//skip immediately to the first student row
for ($c = 2; $c < count($row); $c++) {
    $html = '<table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #e1e1e1">' . '<tr>';
    $data = explode(',', trim($row[$c], '\'"',));

    $html .= '<td width="77.5">';
    $html .= trim($data[0], '\'"') . "," . trim($data[1], '\'"');
    $html .= '</td>';

    for ($i = 2; $i < count($data); $i++) {
        $html .= '<td>' . trim($data[$i], '\'"') . '</td>';
    }
    $html .= '</tr>' . '</table>';
    $pdf->writeHTML(trim($html), false, false, false, false, '');
}
unlink($filename);
ob_end_clean();
$pdf->Output(utf8_encode(strtoupper($teacher_name)) . "_" . $cg . "_Summary" . ".pdf", 'D', TRUE);
?>
