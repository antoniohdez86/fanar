<?php 

// - - - - - - - - - - - - - - - - - - - - - - - - - - 	CONTROL DE SESION
// include_once('./php_libs/script_session.php');
// - - - - - - - - - - - - - - - - - - - - - - - - - - 	LIBRERIAS NECESARIAS
	include_once('../engine/MyObject.php');
	include_once('../engine/MySql.php');
	include_once('../engine/DataTable.php');
	include_once('../engine/Empleado.php');
// - - - - - - - - - - - - - - - - - - - - - - - - - -
	$mode = $_POST['mode'];
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	$SERVER_MESSAGE = 'MESSAGE_DEFAULT';							//	MENSAJE QUE SE ENVIARA AL CLIENTE (navegador)
	$SERVER_CODE_RESPONSE = 'CODE_DEFAULT';						//	CODIGO QUE INTERPRETARA EL CLIENTE (navegador)
	$MODE_CAPTURED = false;
	$TEST = "";
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
if(!$mode) {
	$MODE_CAPTURED = true;
	$SERVER_MESSAGE = 'especifique el modo de llamada a esta pagina!';
	$SERVER_CODE_RESPONSE = 'ERROR_NO_MODE';
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	ELIMINACION DE CATALOGO (ENVIAR A PAPELERA)
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
	//$EMPL_USER = $_POST['user'];
	//$EMPL_PASS = $_POST['pass'];
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
		$SERVER_MESSAGE = '';
		$SERVER_CODE_RESPONSE = 'EMPL_SAVED';
		
		$EMPL_ACT = ($EMPL_ACT=='true') ? 'SI' : 'NO';
		
		$SERVER_MESSAGE = 
			'<tr>
				<td>'.$EMPL_ID.'</td>
				<td>'.$EMPL_NAME.'</td>
				<td>'.$EMPL_APELL1.'</td>
				<td>'.$EMPL_APELL2.'</td>
				<td>'.$EMPL_DIR.'</td>
				<td>'.$EMPL_PROF.'</td>
				<td>'.$EMPL_TEL.'</td>
				<td><a href="#">'.$EMPL_ACT.'</a></td>
				<td><a href="#">Edit</a></td>
				<td><a href="#">Elim</a></td>
				<td><a href="#"><input type="radio" name="idEmpl" value="'.$EMPL_ID.'"></a></td>
			</tr>';
	}

	/*
	$TEST = "DATOS RECIBIDOS\n";
	$TEST .= "ID: ".$EMPL_ID."\n";
	$TEST .= "NOMBRE: ".$EMPL_NAME."\n";
	$TEST .= "APELL1: ".$EMPL_APELL1."\n";
	$TEST .= "APELL1: ".$EMPL_APELL2."\n";
	$TEST .= "DIR: ".$EMPL_ID."\n";
	$TEST .= "PROF: ".$EMPL_PROF."\n";
	$TEST .= "TEL: ".$EMPL_TEL."\n";
	$TEST .= "ACT: ".$EMPL_ACT."\n";
	*/
	
	
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'upd') {
	
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
		
	$empl->updateEmpleado($EMPL_ID,$EMPL_NAME,$EMPL_APELL1,$EMPL_APELL2,$EMPL_DIR,$EMPL_PROF,$EMPL_TEL,$EMPL_ACT);

	if($empl->error) { 
		$SERVER_MESSAGE = $empl->messageErrorTecnico;
		$SERVER_CODE_RESPONSE = 'ERROR_EMPLEADO_OBJECT';
	} else {
		$EMPL_ACT = ($EMPL_ACT=='true') ? 'SI' : 'NO';
		$SERVER_MESSAGE = 
			'<tr>
				<td>'.$EMPL_ID.'</td>
				<td>'.$EMPL_NAME.'</td>
				<td>'.$EMPL_APELL1.'</td>
				<td>'.$EMPL_APELL2.'</td>
				<td>'.$EMPL_DIR.'</td>
				<td>'.$EMPL_PROF.'</td>
				<td>'.$EMPL_TEL.'</td>
				<td><a href="#">'.$EMPL_ACT.'</a></td>
				<td><a href="#">Edit</a></td>
				<td><a href="#">Elim</a></td>
				<td><a href="#"><input type="radio" name="idEmpl" value="'.$EMPL_ID.'"></a></td>
			</tr>';
		$SERVER_CODE_RESPONSE = 'EMPL_UPDATED';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'del') {
	
	$MODE_CAPTURED = true;

	$empl = new Empleado();
	
	$EMPL_ID = $_POST['id'];
		
	$empl->deleteEmpleado($EMPL_ID);

	if($empl->error) { 
		//$SERVER_MESSAGE = "Detalle: ".$empl->messageErrorTecnico;
		$SERVER_MESSAGE = "Detalle: ".$empl->messageError;
		$SERVER_CODE_RESPONSE = 'ERROR_EMPLEADO_OBJECT';
	} else {
		$SERVER_MESSAGE = '';
		$SERVER_CODE_RESPONSE = 'EMPL_DELETED';
	}
	
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'list') {
	
	$MODE_CAPTURED = true;

	$empl = new Empleado();
	$empl->Empleado();
		
	$TRs = $empl->getRowsEmpleados();  // FILAS DE DATOS DE EMPLEADOS

	if($empl->error) { 
		$SERVER_MESSAGE = $empl->messageError;
		$SERVER_CODE_RESPONSE = 'ERROR_EMPLEADO_OBJECT';
	} else {
		$SERVER_MESSAGE = $TRs;
		$SERVER_CODE_RESPONSE = 'OK_LIST';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
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
	
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

/*	MODO : DESCONOCIDO
 * 
 *	OCURRE CUANDO MODE NO ES UN CAMPO VACIO, PERO SU VALOR NO ESTA COMTEMPLADO EN ESTE SCRIPT
 *
 **/
if($mode!="" && !$MODE_CAPTURED) {

	$SERVER_MESSAGE = "mode [".$mode."] invalido";
	$SERVER_CODE_RESPONSE = 'ERROR_MODE';

	$TEST = "";
	
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

echo $SERVER_RESPONSE_FINAL;

?>