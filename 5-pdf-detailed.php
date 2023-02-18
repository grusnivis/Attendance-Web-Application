<?php
require('tcpdf/tcpdf.php');
//TAGS: CHANGE FILE ADDRESS, SERVER PC
//teacher name_course group.csv
//teacher name is found at 2-create-table.php. $_session["table"] is found at 3-display-selection.php
$filename = "C:/Users/Quin/Downloads/". $_SESSION['teacherName'] . "_" . $_SESSION["table"] . ".csv";

if ( !file_exists( $filename ) && !is_dir( $filename ) ) {
    //Creates .csv file if .csv doesn't exist
    $array = $_SESSION['array_copy'];
    $filename = "C:/Users/Quin/Downloads/". strtoupper($teacher_name) . "_" . $cg . ".csv";
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
else{
    //Clones a temporary .csv file if .csv exists
    $tempFileName = "C:/Users/Quin/Downloads/". strtoupper($teacher_name) . "_TEMP_" . $cg . ".csv";
    copy($filename, $tempFileName);
    $filename = $tempFileName;
}

$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8');
$pdf->AddPage();
$pdf->setAutoPageBreak(1, 23);
//$pdf->SetMargins(14,14,14);
$pdf->SetFont('Times', 'B', 11);
$pdf->Ln();

//teacher name and course group is found at 2-create-table.php
$pdf->Write(10, utf8_encode(strtoupper($_SESSION["teacherName"])) . "\n" . utf8_encode(strtoupper($_SESSION['table'])), '', false, 'L', true);
$pdf->Ln();

//$row=file('ANTONIETTE M CAñETE_G2-CPE 3101L(T - 0900 AM - 1200 PM).csv');
$row = file($filename);

// for the table headers
for ($c = 0; $c < 1; $c++) {
    $html = '<table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #e1e1e1">' . '<tr>';
    $data = explode(',', trim($row[$c], '\'"',));

    for ($i = 0; $i < count($data); $i++) {
        $html .= '<td>' . trim($data[$i], '\'"') . '</td>';
    }

    $html .= '</tr>' . '</table>';
    $pdf->writeHTML(trim($html), false, false, false, false, '');
}

// for the table rows
$pdf->SetFont('Times', '', 10);

for ($c = 1; $c < count($row); $c++) {
    $html = '<table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #e1e1e1">' . '<tr>';
    $data = explode(',', trim($row[$c], '\'"',));

    for ($i = 0; $i < count($data); $i++) {
        $html .= '<td>' . trim($data[$i], '\'"') . '</td>';
    }
    $html .= '</tr>' . '</table>';
    $pdf->writeHTML(trim($html), false, false, false, false, '');
}
unlink($filename);
ob_end_clean();
$pdf->Output(utf8_encode(strtoupper($teacher_name)) . "_" . $cg . ".pdf", 'D', TRUE);
?>