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
class Municipio extends MyObject {

	var $cadenaConexion;
	
	var $error;
	var $errorCode;
	var $messageError;
	var $messageErrorTecnico;
	var $methodError;
	
	var $EXIT_ON_ERROR_LOG;

	function Municipio() {
		//$this->cadenaConexion = "mysql.nixiweb.com:u669753084_fanar:u669753084_fanar:xfanarx";
		$this->cadenaConexion = "localhost:bd_procedeweb:root:";
		$this->clearErrorLogs();
	}
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	CATALOGOS
	function getRowsMunicipios($ID='', $NAME='', $CLASS='', $TITLE='') {
		
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
			$this->methodError = "getRowsMunicipios.".$mysql->methodError;
			return false;
		}

		$sql = "SELECT aj_municipios.codigo as Clave, 
		               aj_municipios.municipio AS Municipio 
				    FROM aj_municipios";
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsMunicipios.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		$tr = "";
		
		if($dt->NumRows == 0) 
			$tr .= "<tr class='no-data'><td colspan='5'>No hay municipios registrados</td></tr>";
		else 
			while($municipio = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td>".$municipio['Clave']."</td>";
				$tr .= "<td>".$municipio['Municipio']."</td>";
				$tr .= "<td align='center'><input type='radio' name='idMunic' value='".$municipio['Clave']."'></td>";
				$tr .= "</tr>";
			}
		return $tr;
	}
	function getComboMunicipios($ID='', $NAME='', $CLASS='', $TITLE='') {
		
		$ID = $ID ? 'id="'.$ID.'"' : '';
		$NAME = $NAME ? 'name="'.$NAME.'"' : '';
		$CLASS = $CLASS ? 'class="'.$CLASS.'"' : '';
		$TITLE = $TITLE ? 'title="'.$TITLE.'"':'sin titulo';
				 
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getComboEmpleados.".$mysql->methodError;
			return false;
		}

		$sql = "SELECT aj_municipios.codigo AS Clave, aj_municipios.municipio AS Municipio 
					 FROM aj_municipios";
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "Error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getComboMunicipios.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();

		$select = "<select name=".$NAME." ".$TITLE." ".$ID." ".$CLASS." >";
		$select .= "<option style='color:#999; '>-- lista de municipios --</option>";
		
		if($dt->NumRows == 0) 
			$select .= "<option value='0'>no hay municipios registrados</option>";
		else 
			while($municipio = $dt->Next()) 
				$select .= "<option value='".$municipio['Clave']."'>".$municipio['Municipio']."</option>";
		$select .= "</select>";

		return $select;
	}
	function getNombreMunicipio($ID_MUNICIPIO) {		
		
		if(!$ID_MUNICIPIO){
			$this->error = true;
			$this->messageError = "ID_MUNICIPIO esta vacio";
			return false;
		}
				 
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de dato";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getNombreMunicipio.".$mysql->methodError;
			return false;
		}
		
		$sql = "SELECT aj_municipios.municipio AS Municipio 
				    FROM aj_municipios 
					WHERE aj_municipios.codigo = '".$ID_MUNICIPIO."'";
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "Error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getNombreMunicipio.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		if (!$dt->NumRows){
			$this->error = true;
			$this->messageError = "no existe ningun municipio de id ['".$ID_MUNICIPIO."']";
			return false;
		}
	
		$mysql->close();
		$row = $dt->Next();
		return $row['Municipio'];
	}

	function deleteMunicipio($ID_MUNICIPIO) {

		$this->clearErrorLogs();

		$isEmpty = !$ID_MUNICIPIO ? 'ID_EMPLEADO no tiene valor' : '';

		if($isEmpty != ''){
			$this->error = true;
			$this->messageError = $isEmpty;
			$this->methodError = 'deleteMunicipio';
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
		
		$sql = "DELETE FROM aj_municipios WHERE codigo = '".$ID_MUNICIPIO."'";
		
		$mysql->executeDelete($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		return $mysql->num_affected_rows;
	}
	function insertMunicipio($ID_MUNICIPIO, $MUNICIPIO) {
		
		$this->clearErrorLogs();
		
		$error = !$ID_MUNICIPIO? "ID_MUNICIPIO  vacio\n" : "";
		$error .= !$MUNICIPIO ? "NOMBRE DE MUNICIPIO vacio\n" : "";
		
		if($error){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'insertMunicipio';
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
		
		$sql = "INSERT INTO aj_municipios VALUES('".$ID_MUNICIPIO."', '".$MUNICIPIO."') ";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		
		$mysql->executeInsert($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		return $mysql->num_affected_rows;
	}
	function updateMunicipio($ID_MUNICIPIO, $MUNICIPIO) {
		
		$this->clearErrorLogs();
		
		$error = !$ID_MUNICIPIO? "ID MUNICIPIO vacio\n" : "";
		$error .= !$MUNICIPIO ? "NOMBRE DE MUNICIPIO vacio\n" : "";

		if($error != ''){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'updateMunicipio';
			return false;
		}
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = 'updateMunicipio.'.$mysql->methodError;
			return false;
		}

		$sql = "UPDATE aj_municipios SET municipio='".$MUNICIPIO."' WHERE codigo='".$ID_MUNICIPIO."'";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 

		$mysql->executeUpdate($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = 'updateMunicipio.'.$mysql->methodError;
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