<?php 

// - - - - - - - - - - - - - - - - - - - - - - - - - - 	CONTROL DE SESION
	//include_once('./php_libs/script_session.php');
	include_once('../engine/MyObject.php');
	include_once('../engine/MySql.php');
	include_once('../engine/DataTable.php');
	include_once('../engine/Municipio.php');

	$mode = $_POST['mode'];
	$SERVER_MESSAGE = 'MESSAGE_DEFAULT';							//	MENSAJE QUE SE ENVIARA AL CLIENTE (navegador)
	$SERVER_CODE_RESPONSE = 'CODE_DEFAULT';						//	CODIGO QUE INTERPRETARA EL CLIENTE (navegador)
	$MODE_CAPTURED = false;
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
if(!$mode) {
	$MODE_CAPTURED = true;
	$SERVER_MESSAGE = 'especifique el modo de llamada a esta pagina!';
	$SERVER_CODE_RESPONSE = 'ERROR_NO_MODE';
}

if($mode == 'ins') {
	
	$MODE_CAPTURED = true;
	
	$munic = new Municipio();
	
	$MUNIC_ID = $_POST['id'];
	$MUNIC_NAME = $_POST['municipio'];

	$munic->insertMunicipio($MUNIC_ID,$MUNIC_NAME);

	if($munic->error) {
		if($munic->errorCode=='1062') {
			//$SERVER_MESSAGE = "Ya existe un municipio con el ID '".$MUNIC_ID."'";
			$SERVER_MESSAGE = "Ya existe un municipio con el mismo ID o con el mismo nombre";
			$SERVER_CODE_RESPONSE = 'ERROR_EXIST_MUNIC_ID';
		} else {
			$SERVER_MESSAGE = $munic->messageError;
			$SERVER_CODE_RESPONSE = 'ERROR_MUNIC_OBJECT';
		}
	} else {
		$SERVER_MESSAGE = '';
		$SERVER_CODE_RESPONSE = 'MUNIC_SAVED';
		$SERVER_MESSAGE = 
			'<tr>
				<td>'.$MUNIC_ID.'</td>
				<td>'.$MUNIC_NAME.'</td>
				<td align="center"><input type="radio" name="idMunic" value="'.$MUNIC_ID.'"></td>
			</tr>';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'upd') {
	
	$MODE_CAPTURED = true;

	$munic = new Municipio();
	
	$MUNIC_ID = $_POST['id'];
	$MUNIC_NAME = $_POST['municipio'];

	$munic->updateMunicipio($MUNIC_ID,$MUNIC_NAME);	
	
	if($munic->error) { 
		$SERVER_MESSAGE = $munic->messageErrorTecnico;
		$SERVER_CODE_RESPONSE = 'ERROR_MUNICIPIO_OBJECT';
	} else {
		$SERVER_MESSAGE = 
			'<tr>
				<td>'.$MUNIC_ID.'</td>
				<td>'.$MUNIC_NAME.'</td>
				<td align="center"><input type="radio" name="idMunic" value="'.$MUNIC_ID.'"></td>
			</tr>';
		$SERVER_CODE_RESPONSE = 'MUNIC_UPDATED';
	}
	
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'del') {
	
	$MODE_CAPTURED = true;

	$munic = new Municipio();
	
	$MUNIC_ID = $_POST['id'];
		
	$munic->deleteMunicipio($MUNIC_ID);

	if($munic->error) { 
		if($munic->errorCode=='1451'){
			$SERVER_MESSAGE = "El municipio seleccionado esta siendo usado.\nElimine los nucleos que hagan referencia a este municipio e intentelo de nuevo";
			$SERVER_CODE_RESPONSE = 'ISUSED';
		} else {
			$SERVER_MESSAGE = "Error al eliminar el municipio";
			$SERVER_CODE_RESPONSE = 'ERROR_MUNICIPIO_OBJECT';
		}
	
	} else {
		$SERVER_MESSAGE = '';
		$SERVER_CODE_RESPONSE = 'MUNIC_DELETED';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'list') {
	sleep(1);
	$MODE_CAPTURED = true;
	$munic = new Municipio();
	$TRs = $munic->getRowsMunicipios();  // FILAS DE DATOS DE EMPLEADOS
	if($munic->error) { 
		$SERVER_MESSAGE = $munic->messageError;
		$SERVER_CODE_RESPONSE = $munic->errorCode;
	} else {
		$SERVER_MESSAGE = $TRs;
		$SERVER_CODE_RESPONSE = 'OK_LIST';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'cbo') {
	
	$MODE_CAPTURED = true;

	$munic = new Municipio();

	$cbo = $munic->getComboMunicipios();

	if($munic->error) { 
		$SERVER_MESSAGE = $munic->messageErrorTecnico;
		$SERVER_CODE_RESPONSE = 'ERROR_MUNICIPIO_OBJECT';
	} else {
		$SERVER_MESSAGE = '';
		$SERVER_MESSAGE = $cbo;
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