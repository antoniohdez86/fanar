<?php 

if(!$LIBRARY_DATA_TABLE) include_once('./DataTable.php');
if(!$LIBRARY_MYSQL_CONNECTION) include_once('./MySql.php');
if(!$LIBRARY_MY_OBJECT) include_once('./MyObject.php');

/*	DEPENDENCIAS...
 *
 *	MySqlConnection :	'MySql.php'
 *	DataTable		 :	'DataTable.php'
 *	MyObject		 :	'MyObject.php'
 */
class Nucleo extends MyObject {

	var $cadenaConexion;
	
	var $error;
	var $errorCode;
	var $messageError;
	var $messageErrorTecnico;
	var $methodError;
	
	var $EXIT_ON_ERROR_LOG;

	function Nucleo() {
		//$this->cadenaConexion = "mysql.nixiweb.com:u669753084_fanar:u669753084_fanar:xfanarx";
		$this->cadenaConexion = "localhost:bd_procedeweb:root:";
		$this->clearErrorLogs();
	}
	
	function eliminarEtapa($ID_NUCLEO, $ID_ETAPA, $COMPLETE=0) {
		
		$this->clearErrorLogs();

		$error = !$ID_NUCLEO? "ID_NUCLEO  vacio\n" : "";
		$error .= !$ID_ETAPA? "ID_ETAPA vacio\n" : "";
		
		if($error){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'agregarEtapa';
			return false;
		}

		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$sql = "DELETE FROM aj_seguimientos WHERE aj_seguimientos.idNucleo='".$ID_NUCLEO."' AND aj_seguimientos.idEtapa=".$ID_ETAPA;
		
		$mysql->executeInsert($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		$sql = "UPDATE aj_nucleosagrarios SET situacion=1 WHERE codigo='".$ID_NUCLEO."'";
		$mysql->executeInsert($sql);
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		return $mysql->num_affected_rows;
	}	
	function agregarEtapa($ID_NUCLEO, $ID_ETAPA, $FECHA, $OBSERV, $COMPLETE=0) {
		
		$this->clearErrorLogs();
		
		$error = "";
		$error .= !$ID_NUCLEO? "ID_NUCLEO  vacio\n" : "";
		$error .= !$ID_ETAPA? "ID_ETAPA vacio\n" : "";
		$error .= !$FECHA? "FECHA vacio\n" : "";
		$error .= !$OBSERV? "OBSERV vacio\n" : "";
		
		if($error){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'agregarEtapa';
			return false;
		}

		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$sql = "INSERT INTO aj_seguimientos VALUES('".$ID_NUCLEO."', '".$FECHA."', '".$ID_ETAPA."', '".$OBSERV."', 'ninguno', 'ninguno', 'ninguno', 0) ";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		
		$mysql->executeInsert($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		if($COMPLETE){
			$sql = "UPDATE aj_nucleosagrarios SET situacion=2 WHERE codigo='".$ID_NUCLEO."'";
			$mysql->executeInsert($sql);
			if(!$mysql->execute_success) {
				$this->error = true;
				$this->errorCode = $mysql->errorCode;
				$this->messageErrorTecnico = $mysql->messageErrorTecnico;
				return false;
			}
		}
		return $mysql->num_affected_rows;
	}
	function asignarVisitante($ID_NUCLEO, $ID_EMPLEADO) {
		
		$this->clearErrorLogs();
		
		$error = "";
		$error .= !$ID_NUCLEO? "ID_NUCLEO  vacio\n" : "";
		$error .= !$ID_EMPLEADO? "ID_EMPLEADO vacio\n" : "";
		
		if($error){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'asignarVisitante';
			return false;
		}

		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$sql = "INSERT INTO aj_empleado_nucleos VALUES(".$ID_EMPLEADO.", '".$ID_NUCLEO."') ";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		
		$mysql->executeInsert($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		$sql = "UPDATE aj_nucleosagrarios SET situacion=1 WHERE codigo='".$ID_NUCLEO."'";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		
		$mysql->executeInsert($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		return $mysql->num_affected_rows;
	}	
	function cambiarVisitante($ID_NUCLEO, $ID_EMPLEADO) {
		
		$this->clearErrorLogs();
		
		$error = !$ID_NUCLEO? "ID_NUCLEO  vacio\n" : "";
		$error .= !$ID_EMPLEADO? "ID_EMPLEADO vacio\n" : "";
		
		if($error){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'cambiarVisitante';
			return false;
		}

		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$sql = "UPDATE aj_empleado_nucleos 
					  SET aj_empleado_nucleos.idEmpleado=".$ID_EMPLEADO." 
					WHERE aj_empleado_nucleos.idNucleo='".$ID_NUCLEO."'";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		
		$mysql->executeInsert($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		return $mysql->num_affected_rows;
	}	
	function quitarVisitante($ID_NUCLEO) {

		$this->clearErrorLogs();
		
		$isEmpty  = '';
		$isEmpty .= !$ID_NUCLEO ? 'ID_NUCLEO no tiene valor' : '';

		if($isEmpty != ''){
			$this->error = true;
			$this->messageError = $isEmpty;
			$this->methodError = 'quitarVisitante';
			return false;
		}
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$sql = "DELETE FROM aj_empleado_nucleos WHERE aj_empleado_nucleos.idNucleo = '".$ID_NUCLEO."'";
		$mysql->executeDelete($sql);
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$sql = "UPDATE aj_nucleosagrarios SET aj_nucleosagrarios.situacion=0 WHERE aj_nucleosagrarios.codigo = '".$ID_NUCLEO."'";
		$mysql->executeDelete($sql);
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}		
		
		return $mysql->num_affected_rows;
	}	
	function actualizarSeguimiento($ID_NUCLEO, $ID_ETAPA, $ID_ETAPA_ANTERIOR, $FECHA, $OBSERV) {
		
		$this->clearErrorLogs();
		
		$error  = !$ID_NUCLEO? "ID_NUCLEO  vacio\n" : "";
		$error .= !$ID_ETAPA? "ID_ETAPA vacio\n" : "";
		$error .= !$FECHA? "FECHA vacio\n" : "";
		$error .= !$OBSERV? "OBSERV vacio\n" : "";
		
		if($error){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'cambiarVisitante';
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		$sql = "UPDATE aj_seguimientos 
					  SET aj_seguimientos.fecha='".$FECHA."', 
					  		aj_seguimientos.idEtapa=".$ID_ETAPA.", 
							aj_seguimientos.observaciones='".$OBSERV."' 
					WHERE aj_seguimientos.idNucleo='".$ID_NUCLEO."' AND aj_seguimientos.idEtapa='".$ID_ETAPA_ANTERIOR."'";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		
		$mysql->executeInsert($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		return $mysql->num_affected_rows;
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	CATALOGOS
	function getRowsEtapasRest($ID_NUCLEO='', $ID='', $NAME='', $CLASS='', $TITLE='') {
		
		$ID = $ID ? 'id="'.$ID.'"' : '';
		$NAME = $NAME ? 'name="'.$NAME.'"' : '';
		$CLASS = $CLASS ? 'class="'.$CLASS.'"' : '';
		$TITLE = $TITLE ? 'title="'.$TITLE.'"':'sin titulo';
		
		if(!$ID_NUCLEO){
			$this->error = true;
			$this->messageError = "ID_NUCLEO esta vacio";
			$this->methodError = "getRowsSeguimientos";
			return false;
		}
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsSeguimientos_Data.".$mysql->methodError;
			return false;
		}
		$sql = "SELECT aj_etapas.idEtapa, 
							aj_etapas.eta, 
							aj_etapas.act, 
							aj_etapas.descripcion, 
							aj_etapas.activo 
					 FROM aj_etapas  
					WHERE aj_etapas.idEtapa NOT IN (SELECT aj_seguimientos.idEtapa FROM aj_seguimientos WHERE aj_seguimientos.idNucleo='".$ID_NUCLEO."')";
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsSeguimientos_Data.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		$tr = "";
		
		if($dt->NumRows == 0) {
			$this->error = true;
			$this->errorCode = 'NOETAPS';
			$this->messageError = "sin etapas";
			return false;
		} else {
			while($nucleo = $dt->Next()) {
				$class = ($nucleo['activo']) ? "" : "class='disabled'";
				$tr .= "<tr ".$class.">\n";
				$tr .= "	<td align='center'><input type='hidden' value='".$nucleo['idEtapa']."'>".$nucleo['eta']."</td>\n";
				$tr .= "	<td align='center'>".$nucleo['act']."</td>\n";
				$tr .= "	<td align='center'>".$nucleo['descripcion']."</td>\n";
				$tr .= "</tr>\n";
			}
		}
		return $tr;
	}
	function getRowsEtapasDeId($ID_ETAPA='') {
		
		if(!$ID_ETAPA){
			$this->error = true;
			$this->messageError = "ID_ETAPA esta vacio";
			$this->methodError = "getRowsEtapasDeId";
			return false;
		}
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsEtapasDeId.".$mysql->methodError;
			return false;
		}
		$sql = "SELECT aj_etapas.idEtapa, 
							aj_etapas.eta, 
							aj_etapas.act, 
							aj_etapas.descripcion, 
							aj_etapas.activo 
					 FROM aj_etapas 
					WHERE aj_etapas.idEtapa = ".$ID_ETAPA;
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsEtapasDeId.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		if($dt->NumRows == 0) {
			$this->error = true;
			$this->errorCode = 'NOETAP';
			return false;
		} else {
			$nucleo = $dt->Next();
			$class = ($nucleo['activo']) ? "" : "class='disabled'";
			$tr  = "<tr ".$class.">\n";
			$tr .= "	<td align='center'><input type='hidden' value='".$nucleo['idEtapa']."'>".$nucleo['eta']."</td>\n";
			$tr .= "	<td align='center'>".$nucleo['act']."</td>\n";
			$tr .= "	<td align='center'>".$nucleo['descripcion']."</td>\n";
			$tr .= "</tr>\n";
		}
		return $tr;
	}
	function getRowsSeguimientos_Data($ID_NUCLEO='', $ID='', $NAME='', $CLASS='', $TITLE='') {
		
		$ID = $ID ? 'id="'.$ID.'"' : '';
		$NAME = $NAME ? 'name="'.$NAME.'"' : '';
		$CLASS = $CLASS ? 'class="'.$CLASS.'"' : '';
		$TITLE = $TITLE ? 'title="'.$TITLE.'"':'sin titulo';
		
		if(!$ID_NUCLEO){
			$this->error = true;
			$this->messageError = "ID_NUCLEO esta vacio";
			$this->methodError = "getRowsSeguimientos";
			return false;
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsSeguimientos_Data.".$mysql->methodError;
			return false;
		}

					 
		$sql = "SELECT CONCAT(aj_empleados.nombre,' ',aj_empleados.apePat,' ',aj_empleados.apeMat) AS Nombre,
							aj_empleados.idEmpleado AS idEmpleado, 
							aj_municipios.municipio AS Municipio,
							aj_nucleosagrarios.nucleoAgrario AS Nucleo,
							aj_nucleosagrarios.superficie AS Superficie,
							aj_nucleosagrarios.tipoNucleo AS Tipo 
					 FROM aj_empleados, aj_municipios, aj_nucleosagrarios, aj_empleado_nucleos  
					WHERE aj_empleados.idEmpleado = aj_empleado_nucleos.idEmpleado AND
					      aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio AND
							aj_nucleosagrarios.codigo = aj_empleado_nucleos.idNucleo AND
							aj_empleado_nucleos.idNucleo = '".$ID_NUCLEO."'";
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsSeguimientos_Data.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		$tr = "";
		
		if($dt->NumRows == 0) 
			$tr .= "<tr><td class='no-data'>sin resultados</td></tr>";
		else 
			while($nucleo = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td align='center'><input type='hidden' value='".$nucleo['idEmpleado']."'>".$nucleo['Nombre']."</td>";
				$tr .= "<td align='center'>".$nucleo['Municipio']."</td>";
				$tr .= "<td align='center'>".$nucleo['Nucleo']."</td>";
				$tr .= "<td align='center'>".$nucleo['Superficie']."</td>";
				$tr .= "<td align='center'>".$nucleo['Tipo']."</td>";
				$tr .= "</tr>";
			}
		return $tr;
	}
	function getRowsSeguimientos($ID_NUCLEO='', $ID='', $NAME='', $CLASS='', $TITLE='') {
		
		$ID = $ID ? 'id="'.$ID.'"' : '';
		$NAME = $NAME ? 'name="'.$NAME.'"' : '';
		$CLASS = $CLASS ? 'class="'.$CLASS.'"' : '';
		$TITLE = $TITLE ? 'title="'.$TITLE.'"':'sin titulo';
		
		if(!$ID_NUCLEO){
			$this->error = true;
			$this->messageError = "ID_NUCLEO esta vacio";
			$this->methodError = "getRowsSeguimientos";
			return false;
		}
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsSeguimientos.".$mysql->methodError;
			return false;
		}
		$sql = "SELECT aj_etapas.idEtapa AS ie, 
							aj_etapas.act AS ACT, 
		 				   aj_seguimientos.fecha AS FECHA, 
							aj_empleado_nucleos.idEmpleado AS VISITADOR, 
							aj_seguimientos.observaciones AS OBSERVACIONES 
					 FROM aj_seguimientos, aj_etapas, aj_empleado_nucleos 
					WHERE aj_etapas.idEtapa = aj_seguimientos.idEtapa AND 
					      aj_empleado_nucleos.idNucleo = aj_seguimientos.idNucleo AND aj_seguimientos.idNucleo='".$ID_NUCLEO."' ORDER BY aj_etapas.idEtapa";
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsSeguimientos.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		$tr = "";
		
		if($dt->NumRows == 0) {
			$this->error = true;
			$this->errorCode = 'NOHASSEG';	// NO HAY SEGUIMIENTOS
			return false;
		} else {
			while($nucleo = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td align='center'><input type='hidden' name='ie' value='".$nucleo['ie']."'>".$nucleo['ACT']."</td>";
				$tr .= "<td align='center'>".$nucleo['FECHA']."</td>";
				$tr .= "<td align='center'>".$nucleo['VISITADOR']."</td>";
				$tr .= "<td align='left'>".$nucleo['OBSERVACIONES']."</td>";
				$tr .= "</tr>";
			}
		}
		return $tr;
	}
	function getNumSeguimientosDeNucleo($ID_NUCLEO) {
		if(!$ID_NUCLEO){
			$this->error = true;
			$this->messageError = "ID_NUCLEO esta vacio";
			return false;
		}		
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			return false;
		}
	
		$sql = "SELECT COUNT(aj_seguimientos.idNucleo) AS NumSeg FROM aj_seguimientos WHERE aj_seguimientos.idNucleo='".$ID_NUCLEO."'";
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		if(!$dt->NumRows){
			$this->error = true;
			$this->messageError = "No se pudo obtener el numero de seguimientos del nucleo indicado";
			return;
		}
		
		$mysql->close();
		
		$cell = $dt->Next();
		return $cell['NumSeg'];
	}
	
	// obtiene array de registros de nucleos pertenecientes a un municipio X
	function getNucleosDeMunicipio($ID_MUNICIPIO='') {
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		$sql = 
		"SELECT aj_nucleosagrarios.codigo as Clave, 
			   aj_nucleosagrarios.nucleoAgrario AS Nucleo, 
			   aj_nucleosagrarios.tipoNucleo AS Tipo, 
			   aj_nucleosagrarios.superficie AS Superficie, 
			   aj_nucleosagrarios.codigoMunicipio AS codigoMunicipio, 
			   aj_municipios.municipio AS Municipio, 
			   aj_nucleosagrarios.situacion AS situacion 
		   FROM aj_nucleosagrarios 
		  WHERE aj_nucleosagrarios.codigoMunicipio='".$ID_MUNICIPIO."' ORDER BY Clave ASC ";
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		$mysql->close();
		if($dt->NumRows == 0) {
			$this->error = true;
			$this->errorCode = 'NO_HAS_NUCL';
			$this->messageError = "No hay nucleos registrados";
			return false;
		} 
		else 
			while($nucleo = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td align='center'>".$nucleo['Clave']."</td>";
				$tr .= "<td>".$nucleo['Nucleo']."</td>";
				$tr .= "<td align='center'>".$nucleo['Tipo']."</td>";
				$tr .= "<td align='center'>".$nucleo['Superficie']."</td>";
				$tr .= "<td><input type='hidden' name='idMunic' value='".$nucleo['codigoMunicipio']."'>".$nucleo['Municipio']."</td>";
				
				$class = "";
				
				if($nucleo['situacion']==0){	// sin actividades
					$class = "none";
					$title = "sin actividad";
					$text = "   ";
				} else if($nucleo['situacion']==1){	// en proceso
					$title = "en proceso: ".$nucleo['NumSeg']." seguimientos";
					$class = "process";
					$text = " ".$nucleo['NumSeg']." ";
				} else if($nucleo['situacion']==2){	// seguimientos completados
					$title = "completo: ".$nucleo['NumSeg']." seguimientos";
					$class = "complet";
					$text = " ".$nucleo['NumSeg']." ";
				}
				
				$nucleo['visitador'] = ($nucleo['visitador']) ? $nucleo['visitador'] : 'sin asignar';
				$nucleo['idVisitador'] = ($nucleo['idVisitador']) ? $nucleo['idVisitador'] : 0;
				
				$tr .= "<td align='center'><span class='".$class."' title='".$title."'>".$text."</span></td>";
				$tr .= "<td><input type='hidden' name='iv' value='".$nucleo['idVisitador']."'>".$nucleo['visitador']."</td>";
				$tr .= "<td align='center'><a href='#'>Edit</a></td>";
				$tr .= "<td align='center'><a href='#'>Elim</a></td>";
				$tr .= "<td align='center'><a href='#'><input type='radio' name='idNucl' value='".$nucleo['Clave']."'></a></td>";
				$tr .= "</tr>";
			}
		return $tr;
	}
	
	function getRowsNucleosDelEmpleado($ID_EMPLEADO) {
		
		if(!$ID_EMPLEADO) {
			$this->error = true;
			$this->messageError = "ID_EMPLEADO vacio";
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsNucleos.".$mysql->methodError;
			return false;
		}

		$sql = "SELECT aj_nucleosagrarios.codigo as Clave, 
		               aj_nucleosagrarios.nucleoAgrario AS Nucleo, 
							aj_nucleosagrarios.situacion AS situacion 
				    FROM aj_nucleosagrarios, aj_empleado_nucleos 
					 WHERE aj_nucleosagrarios.codigo=aj_empleado_nucleos.idNucleo AND aj_empleado_nucleos.idEmpleado = ".$ID_EMPLEADO;
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsNucleos.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		$tr = "";
		
		if($dt->NumRows == 0) {
			$tr .= "<tr><td class='no-data' colspan='2'>sin nucleos</td></tr>";			
			$this->error = true;
			$this->errorCode = 'NOHASNUCL';
			$this->messageError = "aun no tiene nucleos asignados";
			return false;
		}
		else {
			while($nucleo = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td align='center'>".$nucleo['Clave']."</td>";
				$tr .= "<td>".$nucleo['Nucleo']."</td>";
				$tr .= "</tr>";
			}
		}
		return $tr;
	}
	function getRowsNucleos($ID_MUNICIPIO='', $ID='', $NAME='', $CLASS='', $TITLE='') {
		
		$ID = $ID ? 'id="'.$ID.'"' : '';
		$NAME = $NAME ? 'name="'.$NAME.'"' : '';
		$CLASS = $CLASS ? 'class="'.$CLASS.'"' : '';
		$TITLE = $TITLE ? 'title="'.$TITLE.'"':'sin titulo';
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsNucleos.".$mysql->methodError;
			return false;
		}

		$opcional = "";
		
		if($ID_MUNICIPIO) {
			$opcional = " aj_nucleosagrarios.codigo = '".$ID_MUNICIPIO."' AND ";
		}
/*
		$sql = "SELECT aj_nucleosagrarios.codigo as Clave, 
		               aj_nucleosagrarios.nucleoAgrario AS Nucleo, 
							aj_nucleosagrarios.tipoNucleo AS Tipo, 
							aj_nucleosagrarios.superficie AS Superficie, 
							aj_nucleosagrarios.codigoMunicipio AS codigoMunicipio, 
							aj_municipios.municipio AS Municipio, 
							aj_nucleosagrarios.situacion AS situacion, 
							(SELECT CONCAT(aj_empleados.nombre,' ',aj_empleados.apePat,' ',aj_empleados.apePat) 
							  FROM aj_empleados 
							  WHERE aj_empleados.idEmpleado = 
									(SELECT aj_empleado_nucleos.idEmpleado 
									   FROM aj_empleado_nucleos 
									  WHERE aj_empleado_nucleos.idNucleo = aj_nucleosagrarios.codigo)
							) AS visitador 
				    FROM aj_nucleosagrarios, aj_municipios 
					 WHERE ".$opcional." aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio  ORDER BY Clave ASC ";
*/
		$sql = "SELECT aj_nucleosagrarios.codigo as Clave, 
		               aj_nucleosagrarios.nucleoAgrario AS Nucleo, 
							aj_nucleosagrarios.tipoNucleo AS Tipo, 
							aj_nucleosagrarios.superficie AS Superficie, 
							aj_nucleosagrarios.codigoMunicipio AS codigoMunicipio, 
							aj_municipios.municipio AS Municipio, 
							aj_nucleosagrarios.situacion AS situacion, 
							( SELECT COUNT(aj_seguimientos.idNucleo) 
							  FROM aj_seguimientos 
							  WHERE aj_seguimientos.idNucleo=aj_nucleosagrarios.codigo
							) AS NumSeg, 
							( SELECT CONCAT(aj_empleados.nombre,' ',aj_empleados.apePat,' ',aj_empleados.apePat) 
							  FROM aj_empleados 
							  WHERE aj_empleados.idEmpleado = 
									(SELECT aj_empleado_nucleos.idEmpleado 
									   FROM aj_empleado_nucleos 
									  WHERE aj_empleado_nucleos.idNucleo = aj_nucleosagrarios.codigo)
							) AS visitador, 
							( SELECT aj_empleados.idEmpleado 
							  FROM aj_empleados 
							  WHERE aj_empleados.idEmpleado = 
									(SELECT aj_empleado_nucleos.idEmpleado 
									   FROM aj_empleado_nucleos 
									  WHERE aj_empleado_nucleos.idNucleo = aj_nucleosagrarios.codigo)
							) AS idVisitador 
				    FROM aj_nucleosagrarios, aj_municipios 
					 WHERE ".$opcional." aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio  ORDER BY Clave ASC ";
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsNucleos.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		$tr = "";
		
		if($dt->NumRows == 0) {
			$this->error = true;
			$this->errorCode = 'NO_HAS_NUCL';
			$this->messageError = "No hay nucleos registrados";
			return false;
		} 
		else 
			while($nucleo = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td align='center'>".$nucleo['Clave']."</td>";
				$tr .= "<td>".$nucleo['Nucleo']."</td>";
				$tr .= "<td align='center'>".$nucleo['Tipo']."</td>";
				$tr .= "<td align='center'>".$nucleo['Superficie']."</td>";
				$tr .= "<td><input type='hidden' name='idMunic' value='".$nucleo['codigoMunicipio']."'>".$nucleo['Municipio']."</td>";
				
				$class = "";
				
				if($nucleo['situacion']==0){	// sin actividades
					$class = "none";
					$title = "sin actividad";
					$text = "   ";
				} else if($nucleo['situacion']==1){	// en proceso
					$title = "en proceso: ".$nucleo['NumSeg']." seguimientos";
					$class = "process";
					$text = " ".$nucleo['NumSeg']." ";
				} else if($nucleo['situacion']==2){	// seguimientos completados
					$title = "completo: ".$nucleo['NumSeg']." seguimientos";
					$class = "complet";
					$text = " ".$nucleo['NumSeg']." ";
				}
				
				$nucleo['visitador'] = ($nucleo['visitador']) ? $nucleo['visitador'] : 'sin asignar';
				$nucleo['idVisitador'] = ($nucleo['idVisitador']) ? $nucleo['idVisitador'] : 0;
				
				$tr .= "<td align='center'><span class='".$class."' title='".$title."'>".$text."</span></td>";
				$tr .= "<td><input type='hidden' name='iv' value='".$nucleo['idVisitador']."'>".$nucleo['visitador']."</td>";
				$tr .= "<td align='center'><a href='#'>Edit</a></td>";
				$tr .= "<td align='center'><a href='#'>Elim</a></td>";
				$tr .= "<td align='center'><a href='#'><input type='radio' name='idNucl' value='".$nucleo['Clave']."'></a></td>";
				$tr .= "</tr>";
			}
		return $tr;
	}
	function getComboNucleos($ID_MUNICIPIO='', $ID='', $NAME='', $CLASS='', $TITLE='') {
		
		$ID = $ID ? 'id="'.$ID.'"' : '';
		$NAME = $NAME ? 'name="'.$NAME.'"' : '';
		$CLASS = $CLASS ? 'class="'.$CLASS.'"' : '';
		$TITLE = $TITLE ? 'title="'.$TITLE.'"':'sin titulo';
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				 
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getComboNucleos.".$mysql->methodError;
			return false;
		}

		$opcional = "";
		if($ID_MUNICIPIO){
			$opcional = "WHERE aj_nucleosagrarios.codigoMunicipio = '".$ID_MUNICIPIO."'";
		}

		$sql = "SELECT aj_nucleosagrarios.codigo AS Clave, 
		               aj_nucleosagrarios.nucleoAgrario AS Nucleo 
				    FROM aj_nucleosagrarios ".$opcional;
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "Error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getComboNucleos.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$select = "<select name=".$NAME." ".$TITLE." ".$ID." ".$CLASS." >";
		$select .= "<option style='color:#999; '>-- lista de nucleos Agrarios --</option>";
		
		if($dt->NumRows == 0) 
			$select .= "<option value='0'>no hay nucleos Agrarios registrados</option>";
		else 
			while($nucleo = $dt->Next()) 
				$select .= "<option value='".$nucleo['Clave']."'>".$nucleo['Clave']." - ".$nucleo['Nucleo']."</option>";
		
		$select .= "</select>";
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		return $select;
	}

	function deleteNucleo($ID_NUCLEO) {

		$this->clearErrorLogs();
		
		$isEmpty = !$ID_NUCLEO ? 'ID_NUCLEO no tiene valor' : '';
		if($isEmpty != ''){
			$this->error = true;
			$this->messageError = $isEmpty;
			return false;
		}
		
		$NoSeg = $this->getNumSeguimientosDeNucleo($ID_NUCLEO);
		
		if($this->error){
			return;
		}
		
		if($NoSeg>0){
			$this->error = true;
			$this->errorCode = "HASSEG";
			return false;
		}
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$sql = "DELETE FROM aj_empleado_nucleos WHERE idNucleo = '".$ID_NUCLEO."'";
		
		$mysql->executeDelete($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}

		$sql = "DELETE FROM aj_nucleosagrarios WHERE codigo = '".$ID_NUCLEO."'";
		
		$mysql->executeDelete($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		return $mysql->num_affected_rows;
	}
	function insertNucleo($ID_NUCLEO, $NUCLEO, $TIPO, $SUPERFICIE, $ID_MUNICIPIO) {
		
		$this->clearErrorLogs();

		$error = !$ID_NUCLEO? "ID_MUNICIPIO  vacio\n" : "";
		$error .= !$NUCLEO ? "NUCLEO vacio\n" : "";
		$error .= !$TIPO ? "TIPO vacio\n" : "";
		$error .= !$ID_MUNICIPIO ? "ID_MUNICIPIO vacio\n" : "";
		
		$SUPERFICIE = (!$SUPERFICIE) ? '0' : $SUPERFICIE;
		
		if($error){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'insertNucleo';
			return false;
		}

		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$sql = "INSERT INTO aj_nucleosagrarios VALUES('".$ID_NUCLEO."', '".$NUCLEO."', '".$TIPO."', '".$SUPERFICIE."', '".$ID_MUNICIPIO."', 0) ";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		
		$mysql->executeInsert($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		return $mysql->num_affected_rows;
	}
	function updateNucleo($ID_NUCLEO, $NUCLEO, $TIPO, $SUPERFICIE, $ID_MUNICIPIO) {
		
		$this->clearErrorLogs();
		
		$error = "";
		$error .= !$ID_NUCLEO? "ID_NUCLEO vacio\n" : "";
		$error .= !$NUCLEO ? "NUCLEO vacio\n" : "";
		$error .= !$TIPO ? "TIPO vacio\n" : "";
		$error .= !$SUPERFICIE ? "SUPERFICIE vacio\n" : "";
		$error .= !$ID_MUNICIPIO ? "ID_MUNICIPIO vacio\n" : "";

		if($error != ''){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'updateNucleo';
			return false;
		}
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = 'updateNucleo.'.$mysql->methodError;
			return false;
		}
		

		$sql = "UPDATE aj_nucleosagrarios SET nucleoAgrario='".$NUCLEO."', tipoNucleo='".$TIPO."', superficie='".$SUPERFICIE."', codigoMunicipio='".$ID_MUNICIPIO."' WHERE codigo='".$ID_NUCLEO."'";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 

		$mysql->executeUpdate($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = 'updateNucleo.'.$mysql->methodError;
			return false;
		}
		
		return $mysql->num_affected_rows;
	}

	private function clearErrorLogs() {
		$this->error = false;
		$this->errorCode = '';
		$this->messageError = "";
		$this->messageErrorTecnico = "";
		$this->methodError = "";
	}
	
}
?>