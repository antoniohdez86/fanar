<?php

include('fpdf.php');							// libreria FPDF
include('MyPDFNucleos.php');						// libreria FPDF

$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);
$pdf->SetFillColor(250,250,250);
$pdf->SetDrawColor(217,217,217);

$pdf->mostrarMargen();

$pdf->printTabla_ReportSeguimientosDeNucleo();
$pdf->Ln();
$pdf->printTabla_ReportSeguimientosDeNucleo();
$pdf->Ln();
$pdf->printTabla_ReportSeguimientosDeNucleo();

$pdf->SetDisplayMode('real');
$pdf->Output();
?> 	