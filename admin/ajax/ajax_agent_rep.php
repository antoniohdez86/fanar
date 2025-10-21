<?php 
// - - - - - - - - - - - - - - - - - - - - - - - - - - CONTROL DE SESION
//include_once('./php_libs/script_session.php');
// - - - - - - - - - - - - - - - - - - - - - - - - - - LIBRERIAS NECESARIAS
include_once('../engine/MyObject.php');
include_once('../engine/MySql.php');
include_once('../engine/DataTable.php');
include_once('../engine/Reporte.php');
// - - - - - - - - - - - - - - - - - - - - - - - - - -
$mode = $_POST['mode'];
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
$SERVER_MESSAGE = 'MESSAGE_DEFAULT';//MENSAJE QUE SE ENVIARA AL CLIENTE (navegador)
$SERVER_CODE_RESPONSE = 'CODE_DEFAULT';//CODIGO QUE INTERPRETARA EL CLIENTE (navegador)
$MODE_CAPTURED = false;
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
//$mode = 'test';
if(!$mode) {
$MODE_CAPTURED = true;
$SERVER_MESSAGE = 'especifique el modo de llamada a esta pagina!';
$SERVER_CODE_RESPONSE = 'ERROR_NO_MODE';
}
//sleep(5);
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -ELIMINACION DE CATALOGO (ENVIAR A PAPELERA)
if($mode == 'test') {// get report
$MODE_CAPTURED = true;
$fM = $_POST['fM'];// municipio
$fN = $_POST['fN'];// nucleo
$fFS = $_POST['fFS'];// fecha seguimiento
$fO = $_POST['fO'];// observaciones
$fS = $_POST['fS'];// situacion
$EMax = $_POST['emax'];
$test = "";
$test .= "fM: ".$fM."\n";
$test .= "fN: ".$fN."\n";
$test .= "fFS: ".$fFS."\n";
$test .= "fO: ".$fO."\n";
$test .= "fS: ".$fS."\n";
$test .= "emax: ".$EMax."\n";
$test .= "emax: ".($EMax=='true' ? 'verdad' : 'falso')."\n";
$SERVER_MESSAGE = $test;
$SERVER_CODE_RESPONSE = 'OK_DATA';
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'gr') {// get report
$fields = array();
$fields[0] = $_POST['fM'];// municipio
$fields[1] = $_POST['fN'];// nucleo
$fields[2] = $_POST['fFS'];// fecha seguimiento
$fields[3] = $_POST['fO'];// observaciones
$fields[4] = $_POST['fS'];// situacion
$MODE_CAPTURED = true;
$MODO = $_POST['c'];// modo de generacion de reporte
$report = new Reporte();
switch($MODO){
case 'm':// reporte x municipio (nucleos pertenecientes a un municipio)
$ID_MUNIC = $_POST['m'];
$EMax = $_POST['emax'];
if($EMax=='true'){
$TRs = $report->getReportePorMunicipio_EtaMax($ID_MUNIC,$fields);
} else {
$TRs = $report->getReportePorMunicipio_All($ID_MUNIC,$fields);
}
if($report->error) {
if($report->errorCode=='NOSEG') {
$SERVER_MESSAGE = 'No hay ningun seguimiento de ningun nucleo para el municipio';
$SERVER_CODE_RESPONSE = 'NOSEG';
} else {
$SERVER_MESSAGE = $report->messageErrorTecnico;
$SERVER_CODE_RESPONSE = 'ERROR_REPORT_OBJECT';
}
} else {
$SERVER_MESSAGE = $TRs;
$SERVER_CODE_RESPONSE = 'OK_REPORT';
}
break;
case 'n':// reporte x nucleo (reporte de un nucleo especifico)
$ID_NUCLEO = $_POST['n'];
$EMax = $_POST['emax'];
if($EMax=='true'){
$TRs = $report->getReportePorNucleo_EtaMax($ID_NUCLEO);
} else {
$TRs = $report->getReportePorNucleo_All($ID_NUCLEO);
}
if($report->error) {
if($report->errorCode=='NOSEG') {
$SERVER_MESSAGE = 'No hay ningun seguimiento para el nucleo';
$SERVER_CODE_RESPONSE = 'NOSEG';
} else {
$SERVER_MESSAGE = $report->messageError;
$SERVER_CODE_RESPONSE = 'ERROR_REPORT_OBJECT';
}
} else {
$SERVER_MESSAGE = $TRs;
$SERVER_CODE_RESPONSE = 'OK_REPORT';
}
break;
case 'e':// reporte x empleado (nucleos a cargo de un empleado)
$ID_VISIT = $_POST['e'];
$EMax = $_POST['emax'];
if($EMax=='true'){
$TRs = $report->getReportePorVisitante_EtaMax($ID_VISIT);
} else {
$TRs = $report->getReportePorVisitante_All($ID_VISIT);
}
if($report->error) {
if($report->errorCode=='NOSEG') {
$SERVER_MESSAGE = 'No hay ningun seguimientos realizados por el visitante';
$SERVER_CODE_RESPONSE = 'NOSEG';
} else {
$SERVER_MESSAGE = $report->messageError;
$SERVER_CODE_RESPONSE = 'ERROR_REPORT_OBJECT';
}
} else {
$SERVER_MESSAGE = $TRs;
$SERVER_CODE_RESPONSE = 'OK_REPORT';
}
break;
case 't':// testing
$nPost = count($_POST);
$out = '';
$out .= '$_POST[mode] = '.$_POST['mode']."\n";
$out .= '$_POST[c] = '.$_POST['c']."\n";
$out .= '$_POST[m] = '.$_POST['m']."\n";
$out .= '$_POST[n] = '.$_POST['n']."\n";
$out .= '$_POST[e] = '.$_POST['e']."\n";
$SERVER_MESSAGE = $out;
$SERVER_CODE_RESPONSE = 'OK_TEST';
break;
default:
// modo de reporte invalido
$SERVER_MESSAGE = 'modo de soporte invalido';
$SERVER_CODE_RESPONSE = 'ERR_REP_INV';
break;
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode!="" && !$MODE_CAPTURED) {
$SERVER_MESSAGE = "mode [".$mode."] invalido";
$SERVER_CODE_RESPONSE = 'ERROR_MODE';
$TEST = "";
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
echo $SERVER_RESPONSE_FINAL;
?>
