<?php 
if(!$LIBRARY_DATA_TABLE) include_once('./DataTable.php');
if(!$LIBRARY_MYSQL_CONNECTION) include_once('./MySql.php');
if(!$LIBRARY_MY_OBJECT) include_once('./MyObject.php');
class Reporte extends MyObject {
	var $cadenaConexion;
	var $error;
	var $errorCode;
	var $messageError;
	var $messageErrorTecnico;
	var $methodError;
	var $EXIT_ON_ERROR_LOG;
	function Reporte() {
		//$this->cadenaConexion = "mysql.nixiweb.com:u669753084_fanar:u669753084_fanar:xfanarx";
		$this->cadenaConexion = "localhost:bd_procedeweb:root:";
		$this->clearErrorLogs();
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	CATALOGOS
	function getReportePorNucleo($ID_NUCLEO) {
		if(!$ID_NUCLEO){
			$this->error = true;
			$this->messageError = "ID_NUCLEO vacio";
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		$sql = "
		SELECT 
			aj_municipios.municipio AS MUNICIPIO, 
			aj_nucleosagrarios.nucleoAgrario AS NUCLEO, 
			aj_etapas.descripcion AS ETA_MAX, 
			aj_seguimientos.problematica AS PROBLEMATICA, 
			aj_seguimientos.compromisos_situacion_cumplimiento AS COMPROMISOS 
		FROM aj_municipios, aj_nucleosagrarios, aj_etapas, aj_seguimientos 
		WHERE 
			aj_municipios.codigo = '".$ID_MUNICIPIO."' AND 
			aj_nucleosagrarios.codigoMunicipio = aj_municipios.codigo AND 
			aj_seguimientos.idNucleo = aj_nucleosagrarios.codigo AND 
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND 
			aj_etapas.idEtapa = (SELECT MAX(aj_seguimientos.idEtapa) FROM aj_seguimientos WHERE aj_seguimientos.idNucleo=aj_nucleosagrarios.codigo)";
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorMunicipio.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		$mysql->close();
		$tr = "";
		if($dt->NumRows == 0) 
			$tr .= "<tr><td colspan='6' class='no-data'>Solo puede generarse reportes de municipios que tengan nucleos con seguimientos en proceso o completados </td></tr>";
		else {
			$num = 1;
			while($report = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td>".$num.".-</td>";
				$tr .= "<td>".$report['MUNICIPIO']."</td>";
				$tr .= "<td>".$report['NUCLEO']."</td>";
				$tr .= "<td>".$report['ETA_MAX']."</td>";
				$tr .= "<td>".$report['PROBLEMATICA']."</td>";
				$tr .= "<td>".$report['COMPROMISOS']."</td>";
				$tr .= "</tr>";
				$num++;
			}
		}
		return $tr;
	}
	function getReportePorNucleo_dataAll($ID_NUCLEO, $FIELDS) {
		if(!$ID_NUCLEO){
			$this->error = true;
			$this->messageError = "ID_MUNICIPIO vacio";
			$this->methodError = "getReportePorNucleo_data.".$mysql->methodError;
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorNucleo_data.".$mysql->methodError;
			return false;
		}
		if(!is_array($FIELDS) || count($FIELDS)==0){
			$this->error = true;
			$this->messageError = "FIELDS no es un array o no tiene ningun elemento";
			return false;
		}
		$cols = array();
		$cols[0] = 'aj_municipios.municipio AS MUNICIPIO';
		$cols[1] = 'aj_nucleosagrarios.nucleoAgrario AS NUCLEO';
		$cols[2] = 'aj_seguimientos.fecha AS FECHA';
		$cols[3] = 'aj_seguimientos.observaciones AS OBSERVACIONES';
		$cols[4] = 'aj_nucleosagrarios.situacion AS SITUACION';
		$sql = "SELECT ";
		$nF = count($FIELDS);
		$nFv = 0;
		$ile = -1;
		for($c=0; $c<$nF; $c++) {
			if($FIELDS[$c]!='false') {
				$sql .= $cols[$c];
				if($c<$nF-1){ $sql .= ", "; }
				$ile = $c;
				$nFv++;
			}
		}
		if($nFv>1 && $ile==$nF-1){	$sql .= ", "; } 
		$sql .= "aj_etapas.descripcion AS 'ETAPA MAXIMA' ";
		$sql .= "
		FROM aj_municipios, aj_nucleosagrarios, aj_etapas, aj_seguimientos, aj_empleado_nucleos 
		WHERE 
			aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio AND 
			aj_nucleosagrarios.codigo = aj_seguimientos.idNucleo AND 
			aj_seguimientos.idNucleo = aj_empleado_nucleos.idNucleo AND
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND 
			aj_empleado_nucleos.idNucleo = ".$ID_NUCLEO." ORDER BY aj_nucleosagrarios.nucleoAgrario";
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorNucleo_data.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);	// establece fuente de datos del DataTable
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		return $dt;
	}
	function getReportePorVisitante_dataAll($ID_VISITANTE, $FIELDS) {
		if(!$ID_VISITANTE){
			$this->error = true;
			$this->messageError = "ID_VISITANTE vacio";
			$this->methodError = "getReportePorVisitante_data.".$mysql->methodError;
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante_data.".$mysql->methodError;
			return false;
		}
		if(!is_array($FIELDS) || count($FIELDS)==0){
			$this->error = true;
			$this->messageError = "FIELDS no es un array o no tiene ningun elemento";
			return false;
		}
		$cols = array();
		$cols[0] = 'aj_municipios.municipio AS MUNICIPIO';
		$cols[1] = 'aj_nucleosagrarios.nucleoAgrario AS NUCLEO';
		$cols[2] = 'aj_seguimientos.fecha AS FECHA';
		$cols[3] = 'aj_seguimientos.observaciones AS OBSERVACIONES';
		$cols[4] = 'aj_nucleosagrarios.situacion AS SITUACION';
		$sql = "SELECT ";
		$nF = count($FIELDS);
		$nFv = 0;
		$ile = -1;
		for($c=0; $c<$nF; $c++) {
			if($FIELDS[$c]!='false') {
				$sql .= $cols[$c];
				if($c<$nF-1){ $sql .= ", "; }
				$ile = $c;
				$nFv++;
			}
		}
		if($nFv>1 && $ile==$nF-1){	$sql .= ", "; } 
		$sql .= "aj_etapas.descripcion AS 'ETAPA MAXIMA' ";
		$sql .= "
		FROM aj_municipios, aj_nucleosagrarios, aj_etapas, aj_seguimientos, aj_empleado_nucleos 
		WHERE 
			aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio AND 
			aj_nucleosagrarios.codigo = aj_seguimientos.idNucleo AND 
			aj_seguimientos.idNucleo = aj_empleado_nucleos.idNucleo AND
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND 
			aj_empleado_nucleos.idEmpleado = ".$ID_VISITANTE." ORDER BY aj_nucleosagrarios.nucleoAgrario";
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante_data.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);	// establece fuente de datos del DataTable
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		return $dt;
	}
	function getReportePorMunicipio_dataAll($ID_MUNICIPIO, $FIELDS) {
		if(!$ID_MUNICIPIO){
			$this->error = true;
			$this->messageError = "ID_MUNICIPIO vacio";
			$this->methodError = "getReportePorMunicipio.".$mysql->methodError;
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorMunicipio.".$mysql->methodError;
			return false;
		}
		if(!is_array($FIELDS) || count($FIELDS)==0){
			$this->error = true;
			$this->messageError = "FIELDS no es un array o no tiene ningun elemento";
			return false;
		}
		$cols = array();
		$cols[0] = 'aj_municipios.municipio AS MUNICIPIO';
		$cols[1] = 'aj_nucleosagrarios.nucleoAgrario AS NUCLEO';
		$cols[2] = 'aj_seguimientos.fecha AS FECHA';
		$cols[3] = 'aj_seguimientos.observaciones AS OBSERVACIONES';
		$cols[4] = 'aj_nucleosagrarios.situacion AS SITUACION';
		$sql = "SELECT ";
		$nF = count($FIELDS);
		$nFv = 0;
		$ile = -1;
		for($c=0; $c<$nF; $c++) {
			if($FIELDS[$c]!='false') {
				$sql .= $cols[$c];
				if($c<$nF-1){ $sql .= ", "; }
				$ile = $c;
				$nFv++;
			}
		}
		if($nFv>1 && $ile==$nF-1){	$sql .= ", "; } 
		$sql .= "aj_etapas.descripcion AS 'ETAPA MAXIMA' ";
		$sql .= "
		FROM aj_municipios, aj_nucleosagrarios, aj_etapas, aj_seguimientos 
		WHERE 
			aj_municipios.codigo = '".$ID_MUNICIPIO."' AND 
			aj_nucleosagrarios.codigoMunicipio = aj_municipios.codigo AND 
			aj_seguimientos.idNucleo = aj_nucleosagrarios.codigo AND 
			aj_seguimientos.idEtapa = aj_etapas.idEtapa 
		ORDER BY aj_nucleosagrarios.codigo";
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorMunicipio.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);	// establece fuente de datos del DataTable
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		return $dt;
	}
	function getReportePorNucleo_data($ID_NUCLEO, $FIELDS) {
		if(!$ID_NUCLEO){
			$this->error = true;
			$this->messageError = "ID_MUNICIPIO vacio";
			$this->methodError = "getReportePorNucleo_data.".$mysql->methodError;
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorNucleo_data.".$mysql->methodError;
			return false;
		}
		if(!is_array($FIELDS) || count($FIELDS)==0){
			$this->error = true;
			$this->messageError = "FIELDS no es un array o no tiene ningun elemento";
			return false;
		}
		$cols = array();
		$cols[0] = 'aj_municipios.municipio AS MUNICIPIO';
		$cols[1] = 'aj_nucleosagrarios.nucleoAgrario AS NUCLEO';
		$cols[2] = 'aj_seguimientos.fecha AS FECHA';
		$cols[3] = 'aj_seguimientos.observaciones AS OBSERVACIONES';
		$cols[4] = 'aj_nucleosagrarios.situacion AS SITUACION';
		$sql = "SELECT ";
		$nF = count($FIELDS);
		$nFv = 0;
		$ile = -1;
		for($c=0; $c<$nF; $c++) {
			if($FIELDS[$c]!='false') {
				$sql .= $cols[$c];
				if($c<$nF-1){ $sql .= ", "; }
				$ile = $c;
				$nFv++;
			}
		}
		if($nFv>1 && $ile==$nF-1){	$sql .= ", "; } 
		$sql .= "aj_etapas.descripcion AS 'ETAPA MAXIMA' ";
		$sql .= "
		FROM aj_municipios, aj_nucleosagrarios, aj_etapas, aj_seguimientos, aj_empleado_nucleos 
		WHERE 
			aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio AND 
			aj_nucleosagrarios.codigo = aj_seguimientos.idNucleo AND 
			aj_seguimientos.idNucleo = aj_empleado_nucleos.idNucleo AND
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND
			aj_etapas.idEtapa = (
				SELECT MAX(aj_seguimientos.idEtapa) 
				FROM aj_seguimientos 
				WHERE aj_seguimientos.idNucleo = aj_nucleosagrarios.codigo
			) AND 
			aj_empleado_nucleos.idNucleo = ".$ID_NUCLEO;
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorNucleo_data.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);	// establece fuente de datos del DataTable
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		return $dt;
	}
	function getReportePorVisitante_data($ID_VISITANTE, $FIELDS) {
		if(!$ID_VISITANTE){
			$this->error = true;
			$this->messageError = "ID_VISITANTE vacio";
			$this->methodError = "getReportePorVisitante_data.".$mysql->methodError;
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante_data.".$mysql->methodError;
			return false;
		}
		if(!is_array($FIELDS) || count($FIELDS)==0){
			$this->error = true;
			$this->messageError = "FIELDS no es un array o no tiene ningun elemento";
			return false;
		}
		$cols = array();
		$cols[0] = 'aj_municipios.municipio AS MUNICIPIO';
		$cols[1] = 'aj_nucleosagrarios.nucleoAgrario AS NUCLEO';
		$cols[2] = 'aj_seguimientos.fecha AS FECHA';
		$cols[3] = 'aj_seguimientos.observaciones AS OBSERVACIONES';
		$cols[4] = 'aj_nucleosagrarios.situacion AS SITUACION';
		$sql = "SELECT ";
		$nF = count($FIELDS);
		$nFv = 0;
		$ile = -1;
		for($c=0; $c<$nF; $c++) {
			if($FIELDS[$c]!='false') {
				$sql .= $cols[$c];
				if($c<$nF-1){ $sql .= ", "; }
				$ile = $c;
				$nFv++;
			}
		}
		if($nFv>1 && $ile==$nF-1){	$sql .= ", "; } 
		$sql .= "aj_etapas.descripcion AS 'ETAPA MAXIMA' ";
		$sql .= "
		FROM aj_municipios, aj_nucleosagrarios, aj_etapas, aj_seguimientos, aj_empleado_nucleos 
		WHERE 
			aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio AND 
			aj_nucleosagrarios.codigo = aj_seguimientos.idNucleo AND 
			aj_seguimientos.idNucleo = aj_empleado_nucleos.idNucleo AND
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND
			aj_etapas.idEtapa = (
				SELECT MAX(aj_seguimientos.idEtapa) 
				FROM aj_seguimientos 
				WHERE aj_seguimientos.idNucleo = aj_nucleosagrarios.codigo
			) AND 
			aj_empleado_nucleos.idEmpleado = ".$ID_VISITANTE;
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante_data.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);	// establece fuente de datos del DataTable
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		return $dt;
	}
	function getReportePorMunicipio_data($ID_MUNICIPIO, $FIELDS) {
		if(!$ID_MUNICIPIO){
			$this->error = true;
			$this->messageError = "ID_MUNICIPIO vacio";
			$this->methodError = "getReportePorMunicipio.".$mysql->methodError;
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorMunicipio.".$mysql->methodError;
			return false;
		}
		if(!is_array($FIELDS) || count($FIELDS)==0){
			$this->error = true;
			$this->messageError = "FIELDS no es un array o no tiene ningun elemento";
			return false;
		}
		$cols = array();
		$cols[0] = 'aj_municipios.municipio AS MUNICIPIO';
		$cols[1] = 'aj_nucleosagrarios.nucleoAgrario AS NUCLEO';
		$cols[2] = 'aj_seguimientos.fecha AS FECHA';
		$cols[3] = 'aj_seguimientos.observaciones AS OBSERVACIONES';
		$cols[4] = 'aj_nucleosagrarios.situacion AS SITUACION';
		$sql = "SELECT ";
		$nF = count($FIELDS);
		$nFv = 0;
		$ile = -1;
		for($c=0; $c<$nF; $c++) {
			if($FIELDS[$c]!='false') {
				$sql .= $cols[$c];
				if($c<$nF-1){ $sql .= ", "; }
				$ile = $c;
				$nFv++;
			}
		}
		if($nFv>1 && $ile==$nF-1){	$sql .= ", "; } 
		$sql .= "aj_etapas.descripcion AS 'ETAPA MAXIMA' ";
		$sql .= "
		FROM aj_municipios, aj_nucleosagrarios, aj_etapas, aj_seguimientos 
		WHERE 
			aj_municipios.codigo = '".$ID_MUNICIPIO."' AND 
			aj_nucleosagrarios.codigoMunicipio = aj_municipios.codigo AND 
			aj_seguimientos.idNucleo = aj_nucleosagrarios.codigo AND 
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND 
			aj_etapas.idEtapa = (SELECT MAX(aj_seguimientos.idEtapa) FROM aj_seguimientos WHERE aj_seguimientos.idNucleo=aj_nucleosagrarios.codigo)";
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorMunicipio.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);	// establece fuente de datos del DataTable
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		return $dt;
	}
	function getReportePorMunicipio_All($ID_MUNICIPIO) {
		if(!$ID_MUNICIPIO){
			$this->error = true;
			$this->messageError = "ID_MUNICIPIO vacio";
			$this->methodError = "getReportePorMunicipio_All.".$mysql->methodError;
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorMunicipio_All.".$mysql->methodError;
			return false;
		}
		$sql = "
		SELECT 
			aj_municipios.municipio AS MUNICIPIO, 
			aj_nucleosagrarios.nucleoAgrario AS NUCLEO, 
			aj_etapas.descripcion AS ETA_MAX, 
			aj_seguimientos.fecha AS FECHA, 
			aj_seguimientos.observaciones AS OBSERVACIONES, 
			aj_nucleosagrarios.situacion AS SITUACION 
		FROM aj_municipios, aj_nucleosagrarios, aj_etapas, aj_seguimientos 
		WHERE 
			aj_municipios.codigo = '".$ID_MUNICIPIO."' AND 
			aj_nucleosagrarios.codigoMunicipio = aj_municipios.codigo AND 
			aj_seguimientos.idNucleo = aj_nucleosagrarios.codigo AND 
			aj_seguimientos.idEtapa = aj_etapas.idEtapa ORDER BY aj_nucleosagrarios.nucleoAgrario";
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorMunicipio_All.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		if($AS_DATA){ return $mysql->source; }
		$tr = "";
		if($dt->NumRows == 0) 
			$tr .= "<tr><td colspan='6' class='no-data'>Solo puede generarse reportes de municipios que tengan nucleos con seguimientos en proceso o completados </td></tr>";
		else {
			$num = 1;
			while($report = $dt->Next()) {
				if($report['SITUACION']==0){ $report['SITUACION'] = 'inactivo'; } 
				else if($report['SITUACION']==1){ $report['SITUACION'] = 'en proceso'; } 
				else if($report['SITUACION']==2){ $report['SITUACION'] = 'completo'; } 
				else { $report['SITUACION'] = 'desconocido'; }
				$tr .= "<tr>";
				$tr .= "<td>".$num.".-</td>";
				$tr .= "<td>".$report['MUNICIPIO']."</td>";
				$tr .= "<td>".$report['NUCLEO']."</td>";
				$tr .= "<td>".$report['ETA_MAX']."</td>";
				$tr .= "<td>".$report['FECHA']."</td>";
				$tr .= "<td>".$report['OBSERVACIONES']."</td>";
				$tr .= "<td>".$report['SITUACION']."</td>";
				$tr .= "</tr>";
				$num++;
			}
		}
		return $tr;
	}
	function getReportePorVisitante_All($ID_VISITANTE) {
		if(!$ID_VISITANTE){
			$this->error = true;
			$this->messageError = "ID_VISITANTE vacio";
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante.".$mysql->methodError;
			return false;
		}
		$sql = "
		SELECT 
			aj_municipios.municipio AS MUNICIPIO, 
			aj_nucleosagrarios.nucleoAgrario AS NUCLEO, 
			aj_etapas.descripcion AS 'ETAPA MAXIMA', 
			aj_seguimientos.fecha AS FECHA, 
			aj_seguimientos.observaciones AS OBSERVACIONES, 
			aj_nucleosagrarios.situacion AS SITUACION 
		FROM aj_municipios, aj_nucleosagrarios, aj_seguimientos, aj_empleado_nucleos, aj_etapas 
		WHERE 
			aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio AND 
			aj_nucleosagrarios.codigo = aj_seguimientos.idNucleo AND 
			aj_seguimientos.idNucleo = aj_empleado_nucleos.idNucleo AND
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND 
			aj_empleado_nucleos.idEmpleado = ".$ID_VISITANTE;
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		if($AS_DATA){ return $mysql->source; }
		$tr = "";
		if($dt->NumRows == 0) 
			$tr .= "<tr><td colspan='6' class='no-data'>Solo puede generarse reportes de municipios que tengan nucleos con seguimientos en proceso o completados </td></tr>";
		else {
			$num = 1;
			while($report = $dt->Next()) {
				if($report['SITUACION']==0){ $report['SITUACION'] = 'inactivo'; } 
				else if($report['SITUACION']==1){ $report['SITUACION'] = 'en proceso'; } 
				else if($report['SITUACION']==2){ $report['SITUACION'] = 'completo'; } 
				else { $report['SITUACION'] = 'desconocido'; }
				$tr .= "<tr>";
				$tr .= "<td>".$num.".-</td>";
				$tr .= "<td>".$report['MUNICIPIO']."</td>";
				$tr .= "<td>".$report['NUCLEO']."</td>";
				$tr .= "<td>".$report['ETAPA MAXIMA']."</td>";
				$tr .= "<td>".$report['FECHA']."</td>";
				$tr .= "<td>".$report['OBSERVACIONES']."</td>";
				$tr .= "<td>".$report['SITUACION']."</td>";
				$tr .= "</tr>";
				$num++;
			}
		}
		return $tr;
	}
	function getReportePorNucleo_All($ID_NUCLEO) {
		if(!$ID_NUCLEO){
			$this->error = true;
			$this->messageError = "ID_VISITANTE vacio";
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante.".$mysql->methodError;
			return false;
		}
		$sql = "
		SELECT 
			aj_municipios.municipio AS MUNICIPIO, 
			aj_nucleosagrarios.nucleoAgrario AS NUCLEO, 
			aj_etapas.descripcion AS 'ETAPA MAXIMA', 
			aj_seguimientos.fecha AS FECHA, 
			aj_seguimientos.observaciones AS OBSERVACIONES, 
			aj_nucleosagrarios.situacion AS SITUACION 
		FROM aj_municipios, aj_nucleosagrarios, aj_seguimientos, aj_empleado_nucleos, aj_etapas 
		WHERE 
			aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio AND 
			aj_nucleosagrarios.codigo = aj_seguimientos.idNucleo AND 
			aj_seguimientos.idNucleo = aj_empleado_nucleos.idNucleo AND
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND 
			aj_empleado_nucleos.idNucleo = ".$ID_NUCLEO;
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		if($AS_DATA){ return $mysql->source; }
		$tr = "";
		if($dt->NumRows == 0) 
			$tr .= "<tr><td colspan='6' class='no-data'>Solo puede generarse reportes de municipios que tengan nucleos con seguimientos en proceso o completados </td></tr>";
		else {
			$num = 1;
			while($report = $dt->Next()) {
				if($report['SITUACION']==0){ $report['SITUACION'] = 'inactivo'; } 
				else if($report['SITUACION']==1){ $report['SITUACION'] = 'en proceso'; } 
				else if($report['SITUACION']==2){ $report['SITUACION'] = 'completo'; } 
				else { $report['SITUACION'] = 'desconocido'; }
				$tr .= "<tr>";
				$tr .= "<td>".$num.".-</td>";
				$tr .= "<td>".$report['MUNICIPIO']."</td>";
				$tr .= "<td>".$report['NUCLEO']."</td>";
				$tr .= "<td>".$report['ETAPA MAXIMA']."</td>";
				$tr .= "<td>".$report['FECHA']."</td>";
				$tr .= "<td>".$report['OBSERVACIONES']."</td>";
				$tr .= "<td>".$report['SITUACION']."</td>";
				$tr .= "</tr>";
				$num++;
			}
		}
		return $tr;
	}
	function getReportePorMunicipio_EtaMax($ID_MUNICIPIO) {
		if(!$ID_MUNICIPIO){
			$this->error = true;
			$this->messageError = "ID_MUNICIPIO vacio";
			$this->methodError = "getReportePorMunicipio.".$mysql->methodError;
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorMunicipio.".$mysql->methodError;
			return false;
		}
		$sql = "
		SELECT 
			aj_municipios.municipio AS MUNICIPIO, 
			aj_nucleosagrarios.nucleoAgrario AS NUCLEO, 
			aj_etapas.descripcion AS ETA_MAX, 
			aj_seguimientos.fecha AS FECHA, 
			aj_seguimientos.observaciones AS OBSERVACIONES, 
			aj_nucleosagrarios.situacion AS SITUACION 
		FROM aj_municipios, aj_nucleosagrarios, aj_etapas, aj_seguimientos 
		WHERE 
			aj_municipios.codigo = '".$ID_MUNICIPIO."' AND 
			aj_nucleosagrarios.codigoMunicipio = aj_municipios.codigo AND 
			aj_seguimientos.idNucleo = aj_nucleosagrarios.codigo AND 
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND 
			aj_etapas.idEtapa = (SELECT MAX(aj_seguimientos.idEtapa) FROM aj_seguimientos WHERE aj_seguimientos.idNucleo=aj_nucleosagrarios.codigo)";
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorMunicipio.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		if($AS_DATA){ return $mysql->source; }
		$tr = "";
		if($dt->NumRows == 0) 
			$tr .= "<tr><td colspan='6' class='no-data'>Solo puede generarse reportes de municipios que tengan nucleos con seguimientos en proceso o completados </td></tr>";
		else {
			$num = 1;
			while($report = $dt->Next()) {
				if($report['SITUACION']==0){ $report['SITUACION'] = 'inactivo'; } 
				else if($report['SITUACION']==1){ $report['SITUACION'] = 'en proceso'; } 
				else if($report['SITUACION']==2){ $report['SITUACION'] = 'completo'; } 
				else { $report['SITUACION'] = 'desconocido'; }
				$tr .= "<tr>";
				$tr .= "<td>".$num.".-</td>";
				$tr .= "<td>".$report['MUNICIPIO']."</td>";
				$tr .= "<td>".$report['NUCLEO']."</td>";
				$tr .= "<td>".$report['ETA_MAX']."</td>";
				$tr .= "<td>".$report['FECHA']."</td>";
				$tr .= "<td>".$report['OBSERVACIONES']."</td>";
				$tr .= "<td>".$report['SITUACION']."</td>";
				$tr .= "</tr>";
				$num++;
			}
		}
		return $tr;
	}
	function getReportePorVisitante_EtaMax($ID_VISITANTE) {
		if(!$ID_VISITANTE){
			$this->error = true;
			$this->messageError = "ID_VISITANTE vacio";
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante.".$mysql->methodError;
			return false;
		}
		$sql = "
		SELECT 
			aj_municipios.municipio AS MUNICIPIO, 
			aj_nucleosagrarios.nucleoAgrario AS NUCLEO, 
			aj_etapas.descripcion AS 'ETAPA MAXIMA', 
			aj_seguimientos.fecha AS FECHA, 
			aj_seguimientos.observaciones AS OBSERVACIONES, 
			aj_nucleosagrarios.situacion AS SITUACION 
		FROM aj_municipios, aj_nucleosagrarios, aj_seguimientos, aj_empleado_nucleos, aj_etapas 
		WHERE 
			aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio AND 
			aj_nucleosagrarios.codigo = aj_seguimientos.idNucleo AND 
			aj_seguimientos.idNucleo = aj_empleado_nucleos.idNucleo AND
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND
			aj_etapas.idEtapa = (
				SELECT MAX(aj_seguimientos.idEtapa) 
				FROM aj_seguimientos 
				WHERE aj_seguimientos.idNucleo = aj_nucleosagrarios.codigo
			) AND 
			aj_empleado_nucleos.idEmpleado = ".$ID_VISITANTE;
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		if($AS_DATA){ return $mysql->source; }
		$tr = "";
		if($dt->NumRows == 0) 
			$tr .= "<tr><td colspan='6' class='no-data'>Solo puede generarse reportes de municipios que tengan nucleos con seguimientos en proceso o completados </td></tr>";
		else {
			$num = 1;
			while($report = $dt->Next()) {
				if($report['SITUACION']==0){ $report['SITUACION'] = 'inactivo'; } 
				else if($report['SITUACION']==1){ $report['SITUACION'] = 'en proceso'; } 
				else if($report['SITUACION']==2){ $report['SITUACION'] = 'completo'; } 
				else { $report['SITUACION'] = 'desconocido'; }
				$tr .= "<tr>";
				$tr .= "<td>".$num.".-</td>";
				$tr .= "<td>".$report['MUNICIPIO']."</td>";
				$tr .= "<td>".$report['NUCLEO']."</td>";
				$tr .= "<td>".$report['ETAPA MAXIMA']."</td>";
				$tr .= "<td>".$report['FECHA']."</td>";
				$tr .= "<td>".$report['OBSERVACIONES']."</td>";
				$tr .= "<td>".$report['SITUACION']."</td>";
				$tr .= "</tr>";
				$num++;
			}
		}
		return $tr;
	}
	function getReportePorNucleo_EtaMax($ID_NUCLEO) {
		if(!$ID_NUCLEO){
			$this->error = true;
			$this->messageError = "ID_VISITANTE vacio";
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante.".$mysql->methodError;
			return false;
		}
		$sql = "
		SELECT 
			aj_municipios.municipio AS MUNICIPIO, 
			aj_nucleosagrarios.nucleoAgrario AS NUCLEO, 
			aj_etapas.descripcion AS 'ETAPA MAXIMA', 
			aj_seguimientos.fecha AS FECHA, 
			aj_seguimientos.observaciones AS OBSERVACIONES, 
			aj_nucleosagrarios.situacion AS SITUACION 
		FROM aj_municipios, aj_nucleosagrarios, aj_seguimientos, aj_empleado_nucleos, aj_etapas 
		WHERE 
			aj_municipios.codigo = aj_nucleosagrarios.codigoMunicipio AND 
			aj_nucleosagrarios.codigo = aj_seguimientos.idNucleo AND 
			aj_seguimientos.idNucleo = aj_empleado_nucleos.idNucleo AND
			aj_seguimientos.idEtapa = aj_etapas.idEtapa AND
			aj_etapas.idEtapa = (
				SELECT MAX(aj_seguimientos.idEtapa) 
				FROM aj_seguimientos 
				WHERE aj_seguimientos.idNucleo = aj_nucleosagrarios.codigo
			) AND 
			aj_empleado_nucleos.idNucleo = ".$ID_NUCLEO;
		$mysql->executeSelect($sql);
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getReportePorVisitante.".$mysql->methodError;
			return false;
		}
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		$mysql->close();
		if(!$dt->NumRows){
			$this->error = true;
			$this->errorCode = 'NOSEG';
			$this->messageError = "No hay seguimientos para generar reporte";
			return false;
		}
		if($AS_DATA){ return $mysql->source; }
		$tr = "";
		if($dt->NumRows == 0) 
			$tr .= "<tr><td colspan='6' class='no-data'>Solo puede generarse reportes de municipios que tengan nucleos con seguimientos en proceso o completados </td></tr>";
		else {
			$num = 1;
			while($report = $dt->Next()) {
				if($report['SITUACION']==0){ $report['SITUACION'] = 'inactivo'; } 
				else if($report['SITUACION']==1){ $report['SITUACION'] = 'en proceso'; } 
				else if($report['SITUACION']==2){ $report['SITUACION'] = 'completo'; } 
				else { $report['SITUACION'] = 'desconocido'; }
				$tr .= "<tr>";
				$tr .= "<td>".$num.".-</td>";
				$tr .= "<td>".$report['MUNICIPIO']."</td>";
				$tr .= "<td>".$report['NUCLEO']."</td>";
				$tr .= "<td>".$report['ETAPA MAXIMA']."</td>";
				$tr .= "<td>".$report['FECHA']."</td>";
				$tr .= "<td>".$report['OBSERVACIONES']."</td>";
				$tr .= "<td>".$report['SITUACION']."</td>";
				$tr .= "</tr>";
				$num++;
			}
		}
		return $tr;
	}
	private function clearErrorLogs() {
		$this->error = false;
		$this->errorCode = '';
		$this->messageError = "";
		$this->messageErrorTecnico = "";
		$this->methodError = "";
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
?>
