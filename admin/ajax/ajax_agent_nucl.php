<?php 

// - - - - - - - - - - - - - - - - - - - - - - - - - - 	CONTROL DE SESION
	//include_once('./php_libs/script_session.php');
// - - - - - - - - - - - - - - - - - - - - - - - - - - 	LIBRERIAS NECESARIAS
	include_once('../engine/MyObject.php');
	include_once('../engine/MySql.php');
	include_once('../engine/DataTable.php');
	include_once('../engine/Municipio.php');
	include_once('../engine/Nucleo.php');
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
	
	$NUCL_ID = $_POST['id'];
	$NUCL_NAME = $_POST['nucleo'];
	$NUCL_TYPE = $_POST['tipo'];
	$NUCL_SUPERF = $_POST['superficie'];
	$NUCL_ID_MUNIC = $_POST['idMunicipio'];
	
	$nucleo = new Nucleo();
	$nucleo->Nucleo();
	$nucleo->insertNucleo($NUCL_ID, $NUCL_NAME, $NUCL_TYPE, $NUCL_SUPERF, $NUCL_ID_MUNIC);

	if($munic->error) {
		if($munic->errorCode=='1062') {
			$SERVER_MESSAGE = "Ya existe un nucleo Agrario con el ID '".$MUNIC_ID."'";
			$SERVER_CODE_RESPONSE = 'ERROR_EXIST_NUCL_ID';
		} else {
			$SERVER_MESSAGE = $munic->messageError;
			$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
		}
	} else {
		
		// obtiene el nombre del municipio correspondiente al un id especifico
		$munic = new Municipio();
		$NUCL_MUNIC = $munic->getNombreMunicipio($NUCL_ID_MUNIC);		
		
		$SERVER_MESSAGE = 
			'<tr>
				<td>'.$NUCL_ID.'</td>
				<td>'.$NUCL_NAME.'</td>
				<td>'.$NUCL_TYPE.'</td>
				<td>'.$NUCL_SUPERF.'</td>
				<td>'.$NUCL_MUNIC.'</td>
				<td><a href="#">Edit</a></td>
				<td><a href="#">Elim</a></td>
				<td><a href="#"><input type="radio" name="idNucl" value="'.$NUCL_ID.'"></a></td>
			</tr>';
			
		$SERVER_CODE_RESPONSE = 'NUCL_SAVED';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'upd') {
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID = $_POST['id'];
	$NUCL_NAME = $_POST['nucleo'];
	$NUCL_TYPE = $_POST['tipo'];
	$NUCL_SUPERF = $_POST['superficie'];
	$NUCL_ID_MUNIC = $_POST['idMunicipio'];
	
	$nucleo = new Nucleo();
	$nucleo->updateNucleo($NUCL_ID, $NUCL_NAME, $NUCL_TYPE, $NUCL_SUPERF, $NUCL_ID_MUNIC);		

	if($nucleo->error) { 
		$SERVER_MESSAGE = $nucleo->messageErrorTecnico;
		$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
	} else {
		
		// obtiene el nombre del municipio correspondiente al un id especifico
		$munic = new Municipio();
		$NUCL_MUNIC = $munic->getNombreMunicipio($NUCL_ID_MUNIC);
		
		$SERVER_MESSAGE = 
			'<tr>
				<td>'.$NUCL_ID.'</td>
				<td>'.$NUCL_NAME.'</td>
				<td>'.$NUCL_TYPE.'</td>
				<td>'.$NUCL_SUPERF.'</td>
				<td>'.$NUCL_MUNIC.'</td>
				<td><a href="#">Edit</a></td>
				<td><a href="#">Elim</a></td>
				<td><a href="#"><input type="radio" name="idNucl" value="'.$NUCL_ID.'"></a></td>
			</tr>';
		$SERVER_CODE_RESPONSE = 'NUCL_UPDATED';
	}
	
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'del') {
	
	$MODE_CAPTURED = true;
		
	$NUCL_ID = $_POST['id'];
	
	$nucleo = new Nucleo();
	$nucleo->deleteNucleo($NUCL_ID);	

	if($nucleo->error) { 
		//$SERVER_MESSAGE = "Detalle: ".$empl->messageErrorTecnico;
		$SERVER_MESSAGE = "Detalle: ".$nucleo->messageError;
		$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
	} else {
		$SERVER_MESSAGE = '';
		$SERVER_CODE_RESPONSE = 'NUCL_DELETED';
	}
	
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}
if($mode == 'list') {
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID_MUNIC = $_POST['idMunicipio'] ?? 0;
	
	$nucleo = new Nucleo();
	$nucleo->Nucleo();
	$TRs = $nucleo->getRowsNucleos($NUCL_ID_MUNIC);  // FILAS DE DATOS DE EMPLEADOS

	if($nucleo->error) { 
		$SERVER_MESSAGE = $nucleo->messageErrorTecnico;
		$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
	} else {
		$SERVER_MESSAGE = '';
		$SERVER_CODE_RESPONSE = 'OK_LIST';
	}

	$SERVER_MESSAGE = $TRs;
	
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