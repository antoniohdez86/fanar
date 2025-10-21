<?php 
// - - - - - - - - - - - - - - - - - - - - - - - - - - CONTROL DE SESION
// include_once('./php_libs/script_session.php');
// - - - - - - - - - - - - - - - - - - - - - - - - - - LIBRERIAS NECESARIAS
include_once('../engine/MyObject.php');
include_once('../engine/MySql.php');
include_once('../engine/DataTable.php');
include_once('../engine/Empleado.php');
// - - - - - - - - - - - - - - - - - - - - - - - - - -
$mode = $_POST['mode'];
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
$SERVER_MESSAGE = 'MESSAGE_DEFAULT';//MENSAJE QUE SE ENVIARA AL CLIENTE (navegador)
$SERVER_CODE_RESPONSE = 'CODE_DEFAULT';//CODIGO QUE INTERPRETARA EL CLIENTE (navegador)
$MODE_CAPTURED = false;
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
if(!$mode) {
$MODE_CAPTURED = true;
$SERVER_MESSAGE = 'especifique el modo de llamada a esta pagina!';
$SERVER_CODE_RESPONSE = 'ERROR_NO_MODE';
}
//sleep(4);
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// agrega nuevo empleado
if($mode == 'ins') {
$MODE_CAPTURED = true;
$empl = new Empleado();
$EMPL_ID = $_POST['id'];
$EMPL_NAME = $_POST['nombre'];
$EMPL_APELL1 = $_POST['ape1'];
$EMPL_APELL2 = $_POST['ape2'];
$EMPL_DIR = $_POST['dir'];
$EMPL_PROF = $_POST['prof'];
$EMPL_TEL = $_POST['tel'];
$EMPL_ACT = $_POST['act'];
$empl->insertEmpleado($EMPL_ID,$EMPL_NAME,$EMPL_APELL1,$EMPL_APELL2,$EMPL_DIR,$EMPL_PROF,$EMPL_TEL, $EMPL_ACT);
if($empl->error) {
if($empl->errorCode=='1062') {
$SERVER_MESSAGE = "Ya existe un empleado con el ID '".$EMPL_ID."'";
$SERVER_CODE_RESPONSE = 'ERROR_EXIST_EMPL_ID';
} else {
$SERVER_MESSAGE = $empl->messageErrorTecnico;
$SERVER_CODE_RESPONSE = 'ERROR_EMPLEADO_OBJECT';
}
} else {
$SERVER_CODE_RESPONSE = 'EMPL_SAVED';
if($EMPL_ACT=='true'){ $class = 'up'; $title = 'activo'; } else { $class = 'down'; $title = 'inactivo'; }
$SERVER_MESSAGE = 
'<tr>
<td align="center">'.$EMPL_ID.'</td>
<td>'.$EMPL_NAME.'</td>
<td>'.$EMPL_APELL1.'</td>
<td>'.$EMPL_APELL2.'</td>
<td>'.$EMPL_DIR.'</td>
<td>'.$EMPL_PROF.'</td>
<td>'.$EMPL_TEL.'</td>
<td align="center"><span class='.$class.' title='.$title.'>   <span></td>
<td align="center">0</td>
<td align="center"><a href="#"><input type="radio" name="idEmpl" value="'.$EMPL_ID.'"></a></td>
</tr>';
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
// actualiza datos de empleado X
if($mode == 'upd') {
$MODE_CAPTURED = true;
$empl = new Empleado();
$EMPL_ID = $_POST['id'];// id de empleado
$EMPL_NAME = $_POST['nombre'];// nombre
$EMPL_APELL1 = $_POST['ape1'];// apellido paterno
$EMPL_APELL2 = $_POST['ape2'];// apellido materno
$EMPL_DIR = $_POST['dir'];// direccion
$EMPL_PROF = $_POST['prof'];// profesion
$EMPL_TEL = $_POST['tel'];// telefono
$EMPL_ACT = $_POST['act'];// activo?
$EMPL_NUM_NUCLEOS = $_POST['na'];// numero de nucleos asignados
// actualiza datos de empleado
$empl->updateEmpleado($EMPL_ID,$EMPL_NAME,$EMPL_APELL1,$EMPL_APELL2,$EMPL_DIR,$EMPL_PROF,$EMPL_TEL,$EMPL_ACT);
if($empl->error) { 
$SERVER_MESSAGE = $empl->messageErrorTecnico;
$SERVER_CODE_RESPONSE = 'ERROR_EMPLEADO_OBJECT';
} else {
if($EMPL_ACT=='true') { $class = 'up'; $title = 'activo'; } else { $class = 'down'; $title = 'inactivo'; }
$SERVER_MESSAGE = 
'<tr>
<td align="center">'.$EMPL_ID.'</td>
<td>'.$EMPL_NAME.'</td>
<td>'.$EMPL_APELL1.'</td>
<td>'.$EMPL_APELL2.'</td>
<td>'.$EMPL_DIR.'</td>
<td>'.$EMPL_PROF.'</td>
<td>'.$EMPL_TEL.'</td>
<td align="center"><span class='.$class.' title='.$title.'>   <span></td>
<td align="center">'.$EMPL_NUM_NUCLEOS.'</td>
<td align="center"><a href="#"><input type="radio" name="idEmpl" value="'.$EMPL_ID.'"></a></td>
</tr>';
$SERVER_CODE_RESPONSE = 'EMPL_UPDATED';
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
// elimina registro de empleado X
if($mode == 'del') {
$MODE_CAPTURED = true;
$EMPL_ID = $_POST['id'];
$empl = new Empleado();
$empl->deleteEmpleado($EMPL_ID);
if($empl->error) {
if($empl->errorCode=='1451'){
$SERVER_MESSAGE = "No puede eliminarse el registro del empleado debido a las siguientes 'posibles causas':\n- tiene una cuenta de usuario\n- tiene nucleos asignados";
$SERVER_CODE_RESPONSE = 'ERR_EMPL_REF';
} else {
$SERVER_MESSAGE = "codigo: ".$empl->errorCode;
$SERVER_MESSAGE .= "\nDetalle: ".$empl->messageErrorTecnico;
//$SERVER_MESSAGE = "Detalle: ".$empl->messageError;
$SERVER_CODE_RESPONSE = 'ERROR_EMPLEADO_OBJECT';
}
} else {
$SERVER_MESSAGE = 'registro de empleado eliminado';
$SERVER_CODE_RESPONSE = 'EMPL_DELETED';
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
// lista empleados
if($mode == 'list') {
$MODE_CAPTURED = true;
$empl = new Empleado();
$TRs = $empl->getRowsEmpleados();  // FILAS DE DATOS DE EMPLEADOS
if($empl->error) { 
$SERVER_MESSAGE = $empl->messageError;
$SERVER_CODE_RESPONSE = 'ERROR_EMPLEADO_OBJECT';
} else {
$SERVER_MESSAGE = $TRs;
$SERVER_CODE_RESPONSE = 'OK_LIST';
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
// obtiene combo de empleados que aun NO TIENEN CUENTA
if($mode == 'cbo') {
$MODE_CAPTURED = true;
$empl = new Empleado();
$CBO = $empl->getComboEmpleados();
if($empl->error) { 
//$SERVER_MESSAGE = "Detalle: ".$empl->messageErrorTecnico;
$SERVER_MESSAGE = "Detalle: ".$empl->messageErrorTecnico;
$SERVER_CODE_RESPONSE = 'ERROR_EMPLEADO_OBJECT';
} else {
$SERVER_MESSAGE = $CBO;
$SERVER_CODE_RESPONSE = 'OK_CBO';
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
// obtiene combo de empleados que no tienen la carga maxima en sus nucleos
if($mode == 'cboNc') {
$MODE_CAPTURED = true;
$empl = new Empleado();
$CBO = $empl->getComboEmpleados_mode2();
if($empl->error) { 
//$SERVER_MESSAGE = "Detalle: ".$empl->messageErrorTecnico;
$SERVER_MESSAGE = "Detalle: ".$empl->messageErrorTecnico;
$SERVER_CODE_RESPONSE = 'ERROR_EMPLEADO_OBJECT';
} else {
$SERVER_MESSAGE = $CBO;
$SERVER_CODE_RESPONSE = 'OK_CBO';
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
// 
if($mode == 'cboAll') {
$MODE_CAPTURED = true;
$empl = new Empleado();
$CBO = $empl->getComboTodosEmpleados();
if($empl->error) { 
//$SERVER_MESSAGE = "Detalle: ".$empl->messageErrorTecnico;
$SERVER_MESSAGE = "Detalle: ".$empl->messageErrorTecnico;
$SERVER_CODE_RESPONSE = 'ERROR_EMPLEADO_OBJECT';
} else {
$SERVER_MESSAGE = $CBO;
$SERVER_CODE_RESPONSE = 'OK_CBO';
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
