<?php

include('fpdf.php');							// libreria FPDF
include('MyPDF.php');							// libreria FPDF

//--------------------------------------------------------------------------------------------------------------------------------

$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);
$pdf->SetFillColor(250,250,250);
$pdf->SetDrawColor(217,217,217);

//--------------------------------------------------------------------------------------------------------------------------------

//$pdf->mostrarCuadricula('H');
$pdf->mostrarMargen();
$pdf->printTablaEstatica();
//
//$pdf->SetY(45);
//
//$headers = $dtColumns;
//$data = $dtAlumnosEgresados;
//
//$pdf->printTablaDinamica_small($data, $headers, $anio_egreso);
////$pdf->printTablaDinamica($data, $headers, $anio);

//--------------------------------------------------------------------------------------------------------------------------------

//$pdf->imprimirPortada("2008");
$pdf->SetDisplayMode('real');
//$pdf->SetDisplayMode('fullwidth');
//$pdf->SetDisplayMode('fullpage');
$pdf->Output();
?> 	