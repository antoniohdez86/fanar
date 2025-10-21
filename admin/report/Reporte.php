<?php
include_once('../engine/MyObject.php');
include_once('../engine/MySql.php');
include_once('../engine/DataTable.php');
include_once('../engine/Reporte.php');
include('fpdf.php');// libreria FPDF
include('MyPDFNucleos.php');// libreria MyPDFNucleos
$ERROR = '';
$fields = array();
$fields[0] = $_POST['fM'];
$fields[1] = $_POST['fN'];
$fields[2] = $_POST['fF'];
$fields[3] = $_POST['fO'];
$fields[4] = $_POST['fS'];
$MODE = $_POST['c'];
$ID_MUNICIPIO = $_POST['m'];
$ID_NUCLEO = $_POST['n'];
$ID_VISITANTE = $_POST['v'];
$EMax = $_POST['fEM'];// solo actividad de etapa maxima
$text = $_POST['text'];
//$MODE = 't';
$report = new Reporte();// objeto reporte
$DATA = null;
$TITLE = "";
switch($MODE){
case 'n':// obtener por nucleo
if(empty($ID_NUCLEO)){// error : id de nucleo no especificado
$ERROR = 'No se ha especificado el id del nucleo del que se obtendran los seguimientos hechos';
} else {// obtener seguimientos de nucleo $id_nucleo
if($EMax=='true'){
$DATA = $report->getReportePorNucleo_data($ID_NUCLEO, $fields);
} else {
$DATA = $report->getReportePorNucleo_dataAll($ID_NUCLEO, $fields);
}
if($report->error){
$ERROR = $report->messageError;
} else {
$TITLE = "Seguimientos del nucleo ".$text;
}
}
break;
case 'm':// obtener seguimientos de nucleos pertenecientes a un municipio X
if(empty($ID_MUNICIPIO)){// error : id de municipio no especificado
$ERROR = 'No se ha especificado ningun id de municipio para obtener los seguimientos de sus nucleos pertenecientes';
} else {// obtener seguimientos de nucleos pertenecientes al municipio $id_municipio
if($EMax=='true'){
$DATA = $report->getReportePorMunicipio_data($ID_MUNICIPIO, $fields);
} else {
$DATA = $report->getReportePorMunicipio_dataAll($ID_MUNICIPIO, $fields);
}
if($report->error){
$ERROR = $report->messageError;
} else {
$TITLE = "Seguimientos de nucleos pertenecientes al municipio ".$text;
}
}
break;
case 'e':// obtener seguimientos de nucleos asignados a un Visitante X
if(empty($ID_VISITANTE)){// error : id de visitante no especificado
$ERROR = 'No se ha especificado ningun id del visitante del cual se obtendran los seguimientos hechos de los nucleos bajo su cargo';
} else {// obtener seguimientos de nucleos a cargo del visitante $id_visitante
if($EMax=='true'){
$DATA = $report->getReportePorVisitante_data($ID_VISITANTE, $fields);
} else {
$DATA = $report->getReportePorVisitante_dataAll($ID_VISITANTE, $fields);
}
if($report->error){
$ERROR = $report->messageError;
} else {
$TITLE = "Seguimientos de nucleos a cargo del visitante ".$text;
}
}
break;
case 't':// testing
$ERROR = 'TESTEO: <br>';
$ERROR .= $fields[0]."<br>";
$ERROR .= $fields[1]."<br>";
$ERROR .= $fields[2]."<br>";
$ERROR .= $fields[3]."<br>";
$ERROR .= $fields[4]."<br>";
$ERROR .= $MODE."<br>";
$ERROR .= $ID_MUNICIPIO."<br>";
$ERROR .= $ID_NUCLEO."<br>";
$ERROR .= $ID_VISITANTE."<br>";
$ERROR .= $EMax;
break;
case '':// obtener seguimientos de nucleos asignados a un Visitante X
$ERROR = 'Modo no especificado';
break;
default: 
$ERROR = 'Modo no soportado: "'.$MODE.'"';
break;
}
if(!empty($ERROR)){
echo "<div style='padding:10px; background-color:yellow; font-size:20px;font-weight:bold; '>".$ERROR."</div>";
} else {
$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);
$pdf->SetFillColor(250,250,250);
$pdf->SetDrawColor(217,217,217);
$pdf->mostrarMargen();
$pdf->printTablaDinamica($DATA->Rows, $DATA->headers, $TITLE);
$pdf->SetDisplayMode('real');
$pdf->Output();
}
?>
