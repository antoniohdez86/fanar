<?php
include_once('../engine/MyObject.php');
include_once('../engine/MySql.php');
include_once('../engine/DataTable.php');
include_once('../engine/Empleado.php');
include_once('../engine/Municipio.php');
include_once('../engine/Nucleo.php');
include('fpdf.php');							// libreria FPDF
include('MyPDFNucleos.php');				// libreria MyPDFNucleos

$MODE = 'gxn';
$ERROR = '';
switch($MODE){
	case 'gxn';	// obtener por nucleo
		$id_nucleo = '';
		if(empty($id_nucleo)){
			// error : id de nucleo no especificado
			$ERROR = 'No se ha especificado el id del nucleo del que se obtendran los seguimientos hechos';
		} else {
			// obtener seguimientos de nucleo $id_nucleo
			$nucleo = new Nucleo();
			$nucleo->getRowsNucleos();
		}
		break;
	case 'gxm';	// obtener seguimientos de nucleos pertenecientes a un municipio X
		$id_municipio = '';
		if(empty($id_municipio)){
			// error : id de municipio no especificado
			$ERROR = 'No se ha especificado ningun id de municipio para obtener los seguimientos de sus nucleos pertenecientes';
		} else {
			// obtener seguimientos de nucleos pertenecientes al municipio $id_municipio
		}
		break;
	case 'gxv';	// obtener seguimientos de nucleos asignados a un Visitante X
		$id_visitante = '';
		if(empty($id_visitante)){
			// error : id de visitante no especificado
			$ERROR = 'No se ha especificado ningun id del visitante del cual se obtendran los seguimientos hechos de los nucleos bajo su cargo';
		} else {
			// obtener seguimientos de nucleos a cargo del visitante $id_visitante
		}
		break;
	case '';	// obtener seguimientos de nucleos asignados a un Visitante X
		$ERROR = 'Modo no especificado';
		break;
	default: 
		$ERROR = 'Modo no soportado';
		break;
}

if(!empty($ERROR)){
	echo "<div style='padding:10px; background-color:yellow; font-size:28px;font-weight:bold; display:inline; margin:auto;'>".$ERROR."</div>";
} else {
	$pdf = new PDF('L','mm','A4');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(250,250,250);
	$pdf->SetDrawColor(217,217,217);

	$pdf->mostrarMargen();

	$pdf->printTabla_ReportSeguimientosDeNucleo();

	$pdf->SetDisplayMode('real');
	$pdf->Output();	
}
?>