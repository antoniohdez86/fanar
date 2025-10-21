<?php
//include_once('./script_session.php');
include_once('../engine/MyObject.php');
include_once('../engine/MySql.php');
include_once('../engine/DataTable.php');
include_once('../engine/Usuario.php');
include_once('../engine/Empleado.php');
include_once('../engine/InfoUser.php');
$mode = $_POST['mode'];
$SERVER_MESSAGE = 'MESSAGE_DEFAULT';//MENSAJE QUE SE ENVIARA AL CLIENTE (navegador)
$SERVER_CODE_RESPONSE = 'CODE_DEFAULT';//CODIGO QUE INTERPRETARA EL CLIENTE (navegador)
$MODE_CAPTURED = false;
//$mode = 'test_session';
if(!$mode) {
$MODE_CAPTURED = true;
$SERVER_MESSAGE = 'especifique el modo de llamada a esta pagina!';
$SERVER_CODE_RESPONSE = 'ERROR_NO_MODE';
}
if($mode == 'ins') {
$MODE_CAPTURED = true;
$EMPL_ID = $_POST['ie'];// ID del empleado al que se le creara la cuenta
$EMPL_USER = $_POST['u'];// nombre de usuario
$EMPL_PASS = $_POST['p'];// password
$EMPL_ISADMIN = $_POST['ia'];// password
$us = new Usuario();
$us->insertUsuario($EMPL_USER, $EMPL_PASS, $EMPL_ID, 0);// crea nuevo Usuario para el empleado de id especifico
//$us->insertUsuario($EMPL_USER, $EMPL_PASS, $EMPL_ID, $EMPL_ISADMIN);// (POSIBLE AGUJERO DE SEGURIDAD)
if($us->error) {
if($us->errorCode=='1062') {
$SERVER_MESSAGE = "Ya existe un usuario con el ID '".$EMPL_ID."'";
$SERVER_CODE_RESPONSE = 'ERROR_EXIST_USER';
} else {
$SERVER_MESSAGE = $us->messageErrorTecnico;
$SERVER_CODE_RESPONSE = 'ERROR_USER_OBJECT';
}
} else {
$empl = new Empleado();
$nombre = $empl->getNombreCompletoEmpleado($EMPL_ID);
$SERVER_CODE_RESPONSE = 'USER_SAVED';
$SERVER_MESSAGE = 
'<tr>
<td>'.$EMPL_USER.'</td>
<td>'.$EMPL_PASS.'</td>
<td>'.$EMPL_ID." - ".$nombre.'</td>
<td align="center"><input type="radio" name="idUsr" value="'.$EMPL_ID.'"></td>
</tr>';
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'upd') {
$MODE_CAPTURED = true;
$EMPL_ID = $_POST['ie'];// ID del empleado al que se le creara la cuenta
$EMPL_ID_NAME = $_POST['ine'];// ID del empleado al que se le creara la cuenta
$EMPL_USER = $_POST['u'];// nombre de usuario
$EMPL_PASS = $_POST['p'];// password
$EMPL_ISADMIN = $_POST['ia'];// password
$us = new Usuario();
$us->updateUsuario($EMPL_USER, $EMPL_PASS);
if($us->error) { 
$SERVER_MESSAGE = $us->messageError;
$SERVER_CODE_RESPONSE = 'ERROR_USER_OBJECT';
} else {
$SERVER_MESSAGE = 
'<tr>
<td>'.$EMPL_USER.'</td>
<td>'.$EMPL_PASS.'</td>
<td>'.$EMPL_ID_NAME.'</td>
<td align="center"><input type="radio" name="idUsr" value="'.$EMPL_ID.'"></td>
</tr>';
$SERVER_CODE_RESPONSE = 'USER_UPDATED';
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'del') {
$MODE_CAPTURED = true;
$EMPL_ID = $_POST['ie'];// ID del empleado
$EMPL_USER = $_POST['u'];// nombre de usuario
$us = new Usuario();
$ui = $us->getInfoUserOf($EMPL_USER);
if($us->error){
$SERVER_MESSAGE = $us->messageError;
$SERVER_CODE_RESPONSE = 'ERROR';
} else {
if($ui->isAdmin) {
$SERVER_MESSAGE = "No es posible eliminar la cuenta de administrador";
$SERVER_CODE_RESPONSE = 'ERROR_IA';
} else {
$us->deleteUsuario($EMPL_USER, $EMPL_ID);
if($us->error) { 
$SERVER_MESSAGE = "Error: La cuenta de usuario no pudo ser eliminada";
$SERVER_CODE_RESPONSE = 'ERROR_USER_OBJECT';
} else {
$SERVER_MESSAGE = 'Cuenta eliminada';
$SERVER_CODE_RESPONSE = 'USER_DELETED';
}
}
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'list') {
$MODE_CAPTURED = true;
$us = new Usuario();
$TRs = $us->getRowsUsuarios();  // FILAS DE DATOS DE EMPLEADOS
if($us->error) { 
$SERVER_MESSAGE = $us->messageError;
$SERVER_CODE_RESPONSE = $us->errorCode;
} else {
$SERVER_MESSAGE = $TRs;
$SERVER_CODE_RESPONSE = 'OK_LIST';
}
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'test') {
$MODE_CAPTURED = true;
$EMPL_ID = $_POST['ie'];
$EMPL_ID_NAME = $_POST['ine'];// ID del empleado al que se le creara la cuenta
$EMPL_USER = $_POST['u'];// nombre de usuario
$EMPL_PASS = $_POST['p'];// password
$EMPL_ISADMIN = $_POST['ia'];// password
$SERVER_MESSAGE = "EMPL_ID: ".$EMPL_ID."\n";
$SERVER_MESSAGE .= "EMPL_ID_NAME: ".$EMPL_ID_NAME."\n";
$SERVER_MESSAGE .= "EMPL_USER: ".$EMPL_USER."\n";
$SERVER_MESSAGE .= "EMPL_PASS: ".$EMPL_PASS."\n";
$SERVER_MESSAGE .= "EMPL_ISADMIN: ".$EMPL_ISADMIN;
$SERVER_CODE_RESPONSE = 'OKTEST';
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'test_session') {
session_start();
$MODE_CAPTURED = true;
$SERVER_MESSAGE = "EMPL_ID: ".$_SESSION['usuario']->id."\n";
$SERVER_MESSAGE .= "EMPL_NAME: ".$_SESSION['usuario']->empleado."\n";
$SERVER_MESSAGE .= "EMPL_USER: ".$_SESSION['usuario']->user."\n";
$SERVER_MESSAGE .= "EMPL_ISADMIN: ".$_SESSION['usuario']->isAdmin."\n";
$SERVER_MESSAGE .= "EMPL_ISACTIVE: ".$_SESSION['usuario']->isActivo."\n";
$SERVER_CODE_RESPONSE = 'OKTEST';
$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;//CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'lg') {
$MODE_CAPTURED = true;
$EMPL_USER = $_POST['u'];// nombre de usuario
$EMPL_PASS = $_POST['p'];// password
$us = new Usuario();
$iu = $us->login($EMPL_USER, $EMPL_PASS);// devuelve objeto InfoUser en caso de exito
if($us->error) { 
if($us->errorCode='NVU100') {
$SERVER_MESSAGE = "Usuario invalido!";
$SERVER_CODE_RESPONSE = 'USER_INV';
} else {
$SERVER_MESSAGE = "Error al intentar validar usuario";
$SERVER_CODE_RESPONSE = 'ERROR_USER_OBJECT';
}
} else {
session_start();
if(!$iu->isActivo){
$SERVER_MESSAGE = 'Usuario inactivo';
$SERVER_CODE_RESPONSE = 'INACUS';
} else {
$_SESSION['usuario'] = $iu;
$SERVER_MESSAGE = './admin/index.php';
$SERVER_CODE_RESPONSE = 'OKUS';
}
//$TEST = $_SESSION['usuario']->user.":".$_SESSION['usuario']->id.":".$_SESSION['usuario']->empleado.":".$_SESSION['usuario']->isAdmin.":".$_SESSION['usuario']->isActivo;
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
