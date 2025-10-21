<?php

include('fpdf.php');							// libreria FPDF
include('MyPDF.php');							// libreria FPDF
//include("conex.php");				// libreria base de datos
//include('MyDataBaseITH.php');					// libreria FPDF


//----------------------------------------------------- DEPURACION

//$DEBUG = false;
////  si se eligio la opcion "todas las generaciones" entonces se mostrara todos los registros de los alumnos egresados de todos los años
////  desde la primera generacion registrada hasta la ultima
//$anio_egreso = $_POST["anio"];
//
//if(trim($anio_egreso)=="") {
//	if(!$DEBUG) {
//		die("Especifique un año de egreso.");
//		exit(0);
//	}
//	$anio_egreso = "0000";
//}

// ---------------------------------------------------- BASE DE DATOS

//$dbEgresados = new MyDataBaseITH( MY_NAME_SERVER, MY_DB_USER, MY_DB_PASSWORD, MY_DB_NAME );
//$data = $dbEgresados->obtenerEgresados($anio_egreso);
//
//$dtColumns = array();
//$dtAlumnosEgresados = array();
//
//if(!$data) {
//	if($dbEgresados->error){
//		die($dbEgresados->messageError);
//	} else {
//		die("Ha ocurrido un error con la base de datos!!!");
//	}
//	exit(0);
//}
//
//$dtColumns = $data[0];
//$dtAlumnosEgresados = $data[1];

//--------------------------------------------------------------------------------------------------------------------------------
//Creación del objeto de la clase heredada
$pdf = new PDF('P','mm','A4');				// crea objeto PDF

$pdf->AliasNbPages();		// agrega

$pdf->AddPage();

$pdf->SetFont('Arial','',9);
$pdf->SetFillColor(250,250,250);
$pdf->SetDrawColor(217,217,217);

//--------------------------------------------------------------------------------------------------------------------------------

////$pdf->mostrarCuadricula('H');
////$pdf->mostrarMargen();
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