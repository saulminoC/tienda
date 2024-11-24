<?php
require('librerias/fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, '¡FPDF está configurado correctamente!');
$pdf->Output();
?>
