<?php
require('tcpdf/tcpdf.php');

//TAGS: CHANGE FILE ADDRESS, SERVER PC
//teacher name_course group.csv
//teacher name is found at 2-create-table.php. $_session["table"] is found at 3-display-selection.php
$filename = "C:/Users/Kath/Downloads/" . $_SESSION['teacherName'] . "_" . $_SESSION["table"] . ".csv";

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

for ($c = 0; $c < 1; $c++) {
    $html = '<table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #e1e1e1">' . '<tr>';
    $data = explode(',', trim($row[$c], '\'"',));

    for ($i = 0; $i < count($data); $i++) {
        $html .= '<td width="77">' . trim($data[$i], '\'"') . '</td>';
    }
    $html .= '</tr>' . '</table>';
    $pdf->writeHTML(trim($html), false, false, false, false, '');
}

$pdf->SetFont('Times', '', 10);
for ($c = 1; $c < count($row); $c++) {
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
ob_end_clean();
$pdf->Output(utf8_encode(strtoupper($teacher_name)) . "_" . $cg . ".pdf", 'D', TRUE);
?>
