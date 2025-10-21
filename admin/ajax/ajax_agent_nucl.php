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
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
if(!$mode) {
	$MODE_CAPTURED = true;
	$SERVER_MESSAGE = 'especifique el modo de llamada a esta pagina!';
	$SERVER_CODE_RESPONSE = 'ERROR_NO_MODE';
}

sleep(1);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	ELIMINACION DE CATALOGO (ENVIAR A PAPELERA)

// agrega seguimiento a nucleo X
if($mode == 'delseg') {
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID = $_POST['in'];	// id del nucleo
	$ETAPA_ID = $_POST['ie'];	// id de la etapa
	$COMPLET = $_POST['c'];		// etapas completadas?
	
	$nucleo = new Nucleo();
	$nucleo->eliminarEtapa($NUCL_ID, $ETAPA_ID);

	if($nucleo->error) {
		$SERVER_MESSAGE = $nucleo->messageError;
		$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
	} else {
		$SERVER_MESSAGE = '';
		$SERVER_CODE_RESPONSE = 'SEG_DELETED';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// agrega seguimiento a nucleo X
if($mode == 'loadE') {
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID = $_POST['in'];	// id nucleo
	$ETAPA_ID = $_POST['ie'];	// id empleado
	$FECHA = $_POST['fe'];
	$OBSERV = $_POST['o'];
	$COMPLET = $_POST['c'];
	
	$nucleo = new Nucleo();
	$TR = $nucleo->getRowsEtapasDeId($ETAPA_ID);

	if($nucleo->error) {
		if($nucleo->errorCode=='NOETAP') {
			$SERVER_MESSAGE = 'NOETAP';
			$SERVER_CODE_RESPONSE = 'no se obtuvo la etapa';
		} else {
			$SERVER_MESSAGE = $nucleo->messageError;
			$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
		}
	} else {
		$SERVER_MESSAGE = $TR;
		$SERVER_CODE_RESPONSE = 'SEG_LOADED';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// agrega seguimiento a nucleo X
if($mode == 'useg') {
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID = $_POST['in'];	// id nucleo
	$ETAPA_ID = $_POST['ie'];	// id etapa
	$ETAPA_ANT_ID = $_POST['iea'];	// id etapa anterior
	$FECHA = $_POST['fe'];
	$OBSERV = $_POST['o'];
	$nucleo = new Nucleo();
	$nucleo->actualizarSeguimiento($NUCL_ID, $ETAPA_ID, $ETAPA_ANT_ID, $FECHA, $OBSERV);
	if($nucleo->error) {
		$SERVER_MESSAGE = $nucleo->messageError;
		$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
	} else {
		$SERVER_MESSAGE = 'seguimiento actualizado';
		$SERVER_CODE_RESPONSE = 'SEG_UPDATED';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// agrega seguimiento a nucleo X
if($mode == 'aseg') {
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID = $_POST['in'];	// id nucleo
	$ETAPA_ID = $_POST['ie'];	// id empleado
	$FECHA = $_POST['fe'];
	$OBSERV = $_POST['o'];
	$COMPLET = $_POST['c'];
	
	$nucleo = new Nucleo();
	$nucleo->agregarEtapa($NUCL_ID, $ETAPA_ID, $FECHA, $OBSERV, $COMPLET);

	if($nucleo->error) {
		$SERVER_MESSAGE = $nucleo->messageError;
		$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
	} else {
		//$tr = "<tr>";
		//$tr .= "<td align='center'>".$nucleo['ACT']."</td>";
		//$tr .= "<td align='center'>".$FECHA."</td>";
		//$tr .= "<td align='center'>".$nucleo['VISITADOR']."</td>";
		//$tr .= "<td align='left'><input type='hidden' name='ie' value='".$nucleo['ie']."'>".$nucleo['OBSERVACIONES']."</td>";
		//$tr .= "</tr>";
		
		$SERVER_MESSAGE = '';
		$SERVER_CODE_RESPONSE = 'SEG_SAVED';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// lista de etapas faltantes de un nucleo X
if($mode == 'lstet') {
	
	$MODE_CAPTURED = true;
	
	$ID_NUCLEO = $_POST['in'];
	
	$nucleo = new Nucleo();
	$TRs = $nucleo->getRowsEtapasRest($ID_NUCLEO);  // FILAS DE DATOS DE EMPLEADOS

	if($nucleo->error) { 
		if($nucleo->errorCode=='NOETAPS'){
			$SERVER_MESSAGE = 'sin etapas';
			$SERVER_CODE_RESPONSE = 'NOETAPS';
		} else {
			$SERVER_MESSAGE = $nucleo->messageErrorTecnico;
			$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
		}
	} else {
		$SERVER_MESSAGE = $TRs;
		$SERVER_CODE_RESPONSE = 'OK_LIST';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// datos de un nucleo X (seguimiento)
if($mode == 'segdat') {
	
	$MODE_CAPTURED = true;
	
	$ID_NUCLEO = $_POST['in'];
	
	$nucleo = new Nucleo();
	$TRs = $nucleo->getRowsSeguimientos_Data($ID_NUCLEO);  // FILAS DE DATOS DE EMPLEADOS

	if($nucleo->error) { 
		$SERVER_MESSAGE = $nucleo->messageErrorTecnico;
		$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
	} else {
		$SERVER_MESSAGE = $TRs;
		$SERVER_CODE_RESPONSE = 'OK_DAT';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// lista seguimientos registrados de un nucleo X
if($mode == 'lstseg') {
	
	$MODE_CAPTURED = true;
	
	$ID_NUCLEO = $_POST['in'];
	
	$nucleo = new Nucleo();
	$TRs = $nucleo->getRowsSeguimientos($ID_NUCLEO);  // FILAS DE DATOS DE EMPLEADOS

	if($nucleo->error) { 
		if($nucleo->errorCode=='NOHASSEG'){
			$SERVER_MESSAGE = 'Nucleo sin seguimientos';
			$SERVER_CODE_RESPONSE = 'NOHASSEG';
		} else {
			$SERVER_MESSAGE = $nucleo->messageErrorTecnico;
			$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
		}
	} else {
		$SERVER_MESSAGE = $TRs;
		$SERVER_CODE_RESPONSE = 'OK_LIST';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// asigna visitante
if($mode == 'av_BAK') {		// ASIGNAR VISITADOR
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID = $_POST['in'];
	$EMPL_ID = $_POST['ie'];

	$nucleo = new Nucleo();
	
	if($EMPL_ID==0){
		$nucleo->quitarVisitante($NUCL_ID);
		if($nucleo->error) {
			$SERVER_MESSAGE = 'Error al intentar desasignar al visitante al nucleo';
			$SERVER_CODE_RESPONSE = 'VIS_QUIT_ERR';
			
		} else {
			$SERVER_MESSAGE = 'visitante desasignado';
			$SERVER_CODE_RESPONSE = 'VIS_QUIT_OK';
		}
	} else {
		$nucleo->asignarVisitante($NUCL_ID, $EMPL_ID);
		if($nucleo->error) {
			if($nucleo->errorCode=='1062') {
				$SERVER_MESSAGE = "El nucleo ya ha sido asignado a otro empleado";
				$SERVER_CODE_RESPONSE = 'ERROR_YA_ASIGN';
			} else {
				//$SERVER_MESSAGE = $nucleo->messageError;
				$SERVER_MESSAGE = 'Error al asignar el visitante al nucleo seleccionado';
				$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
			}
		} else {
			$SERVER_MESSAGE = 'visitante asignado';
			$SERVER_CODE_RESPONSE = 'NUCL_ASING_OK';
		}
	}
	
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}


// asignar visitante
if($mode == 'av') {		// ASIGNAR VISITADOR
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID = $_POST['in'];
	$EMPL_ID = $_POST['ie'];

	$nucleo = new Nucleo();
	$nucleo->asignarVisitante($NUCL_ID, $EMPL_ID);
	if($nucleo->error) {
		if($nucleo->errorCode=='1062') {
			$SERVER_MESSAGE = "El nucleo ya ha sido asignado a otro empleado";
			$SERVER_CODE_RESPONSE = 'ERROR_YA_ASIGN';
		} else {
			$SERVER_MESSAGE = 'Error al asignar visitante';
			$SERVER_CODE_RESPONSE = 'VIS_ASIGN_ERR';
		}
	} else {
		$SERVER_MESSAGE = 'visitante asignado';
		$SERVER_CODE_RESPONSE = 'VIS_ASIGN_OK';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// cambiar visitante
if($mode == 'cv') {		// ASIGNAR VISITADOR
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID = $_POST['in'];
	$EMPL_ID = $_POST['ie'];
	$nucleo = new Nucleo();
	$nucleo->cambiarVisitante($NUCL_ID, $EMPL_ID);
	if($nucleo->error) {
		$SERVER_MESSAGE = 'Error al cambiar de visitante';
		$SERVER_CODE_RESPONSE = 'VIS_CHANGED_ERR';
	} else {
		$SERVER_MESSAGE = 'visitante cambiado';
		$SERVER_CODE_RESPONSE = 'VIS_CHANGED_OK';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// quitar visitante
if($mode == 'rv') {		// ASIGNAR VISITADOR
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID = $_POST['in'];
	$EMPL_ID = $_POST['ie'];
	$nucleo = new Nucleo();
	$nucleo->quitarVisitante($NUCL_ID);
	if($nucleo->error) {
		$SERVER_MESSAGE = 'Error al quitar visitante';
		$SERVER_CODE_RESPONSE = 'VIS_QUIT_ERR';
	} else {
		$SERVER_MESSAGE = 'visitante cambiado';
		$SERVER_CODE_RESPONSE = 'VIS_QUIT_OK';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// guarda un nuevo nucleo
if($mode == 'ins') {
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID = $_POST['id'];						// id del nuevo nucleo
	$NUCL_NAME = $_POST['nucleo'];				// nombre para el nuevo nucleo
	$NUCL_TYPE = $_POST['tipo'];					// tipo de nucleo
	$NUCL_SUPERF = $_POST['superficie'];		// superficie del nucleo
	$NUCL_ID_MUNIC = $_POST['idMunicipio'];	// id del municipio al que pertenece el nucleo
	
	$nucleo = new Nucleo();
	$nucleo->insertNucleo($NUCL_ID, $NUCL_NAME, $NUCL_TYPE, $NUCL_SUPERF, $NUCL_ID_MUNIC);

	if($nucleo->error) {
		if($nucleo->errorCode=='1062') {
			$SERVER_MESSAGE = "Ya existe un nucleo Agrario con el ID '".$MUNIC_ID."'";
			$SERVER_CODE_RESPONSE = 'ERROR_EXIST_NUCL_ID';
		} else {
			$SERVER_MESSAGE = $nucleo->messageErrorTecnico;
			$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
		}
	} else {
		
		// obtiene el nombre del municipio correspondiente al un id especifico
		$munic = new Municipio();
		$NUCL_MUNIC = $munic->getNombreMunicipio($NUCL_ID_MUNIC);
		$SERVER_MESSAGE = 
			'<tr>
				<td align="center">'.$NUCL_ID.'</td>
				<td>'.$NUCL_NAME.'</td>
				<td align="center">'.$NUCL_TYPE.'</td>
				<td align="center">'.$NUCL_SUPERF.'</td>
				<td><input type="hidden" name="idMunic" value="'.$NUCL_ID_MUNIC.'">'.$NUCL_MUNIC.'</td>
				<td align="center"><span class=none>   <span></td>
				<td><input type="hidden" name="iv" value="0">sin asignar</td>
				<td align="center"><a href="#">Edit</a></td>
				<td align="center"><a href="#">Elim</a></td>
				<td align="center"><a href="#"><input type="radio" name="idNucl" value="'.$NUCL_ID.'"></a></td>
			</tr>';
		$SERVER_CODE_RESPONSE = 'NUCL_SAVED';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// actualiza un nucleo X
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
			
		$SERVER_MESSAGE = 
			'<tr>
				<td align="center">'.$NUCL_ID.'</td>
				<td>'.$NUCL_NAME.'</td>
				<td align="center">'.$NUCL_TYPE.'</td>
				<td align="center">'.$NUCL_SUPERF.'</td>
				<td>'.$NUCL_MUNIC.'</td>
				<td align="center"><span class=none>   <span></td>
				<td>sin asignar</td>
				<td align="center"><a href="#">Edit</a></td>
				<td align="center"><a href="#">Elim</a></td>
				<td align="center"><a href="#"><input type="radio" name="idNucl" value="'.$NUCL_ID.'"></a></td>
			</tr>';
		$SERVER_CODE_RESPONSE = 'NUCL_UPDATED';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// guarda un nuevo nucleo
if($mode == 'del') {
	
	$MODE_CAPTURED = true;
		
	$NUCL_ID = $_POST['id'];
	
	$nucleo = new Nucleo();
	$nucleo->deleteNucleo($NUCL_ID);	

	if($nucleo->error) {
		
		if($nucleo->errorCode=='HASSEG') {
			$SERVER_MESSAGE = "No es puede eliminar el nucleo mientras tenga seguimientos.";
			$SERVER_CODE_RESPONSE = 'NUCL_HASSEG';
		} else {
			//$SERVER_MESSAGE = "Error: el nucleo no pudo ser eliminado. EC:".$nucleo->errorCode;
			$SERVER_MESSAGE = "Error: el nucleo no pudo ser eliminado.";
			$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
		}
	} else {
		$SERVER_MESSAGE = 'nucleo eliminado';
		$SERVER_CODE_RESPONSE = 'NUCL_DELETED';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// obtiene lista de nucleos
if($mode == 'list') {
	
	$MODE_CAPTURED = true;
	
	$NUCL_ID_MUNIC = $_POST['idMunicipio'];
	
	$nucleo = new Nucleo();
	$TRs = $nucleo->getRowsNucleos($NUCL_ID_MUNIC);  // FILAS DE DATOS DE EMPLEADOS

	if($nucleo->error) { 
		if($nucleo->error == 'NO_HAS_NUCL') {
			$SERVER_MESSAGE = 'No hay nucleos registrados';
			$SERVER_CODE_RESPONSE = 'NO_HAS_NUCL';
		} else {
			$SERVER_MESSAGE = $nucleo->messageErrorTecnico;
			$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
		}
	} else {
		$SERVER_MESSAGE = $TRs;
		$SERVER_CODE_RESPONSE = 'OK_LIST';
	}
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// obtiene combo de nucleos
if($mode == 'cbo') {
	
	$MODE_CAPTURED = true;
	
	$ID_MUNIC = $_POST['m'];
	$nucleo = new Nucleo();

	$cbo = $nucleo->getComboNucleos($ID_MUNIC);

	if($nucleo->error) { 
		$SERVER_MESSAGE = $nucleo->messageErrorTecnico;
		$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
	} else {
		$SERVER_MESSAGE = '';
		$SERVER_MESSAGE = $cbo;
		$SERVER_CODE_RESPONSE = 'OK_CBO';
	}
	
	$SERVER_RESPONSE_FINAL = $SERVER_CODE_RESPONSE."|".$SERVER_MESSAGE."|".$TEST;			//	CONCATENACION DE RESPUESTAS DE SERVIDOR CON TEST
}

// obtiene lista sencilla de nucleos pertenecientes a un visitante X
if($mode == 'ls2') {
	
	$MODE_CAPTURED = true;
	
	$ID_EMPLEADO = $_POST['ie'];
	
	$nucleo = new Nucleo();
	$TRs = $nucleo->getRowsNucleosDelEmpleado($ID_EMPLEADO);  // FILAS DE DATOS DE EMPLEADOS

	if($nucleo->error) { 
		
		if($nucleo->errorCode=='NOHASNUCL'){ 
			$SERVER_MESSAGE = 'aun no tiene nucleos asignados';
			$SERVER_CODE_RESPONSE = 'NOHASNUCL';
		} else {
			$SERVER_MESSAGE = $nucleo->messageError;
			$SERVER_CODE_RESPONSE = 'ERROR_NUCL_OBJECT';
		}
	
	} else {
		$SERVER_MESSAGE = $TRs;
		$SERVER_CODE_RESPONSE = 'OK_LIST';
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