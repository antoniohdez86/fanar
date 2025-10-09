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
		
		//$this->cadenaConexion = "mysql.nixiweb.com:u775268735_procede:u775268735_proce:procedex";
		$this->cadenaConexion = "localhost:bd_procedeweb:root:";
		$this->clearErrorLogs();
	}
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	CATALOGOS
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

		$sql = "SELECT aj_nucleosagrarios.codigo as Clave, 
		               aj_nucleosagrarios.nucleoAgrario AS Nucleo, 
							aj_nucleosagrarios.tipoNucleo AS Tipo, 
							aj_nucleosagrarios.superficie AS Superficie, 
							aj_nucleosagrarios.codigoMunicipio AS codigoMunicipio, 
							aj_municipios.municipio AS Municipio 
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
		
		if($dt->NumRows == 0) 
			$tr .= "<tr class='no-data'><td colspan='8'>no hay Nucleos Agrarios registrados</td></tr>";
		else 
			while($nucleo = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td>".$nucleo['Clave']."</td>";
				$tr .= "<td>".$nucleo['Nucleo']."</td>";
				$tr .= "<td>".$nucleo['Tipo']."</td>";
				$tr .= "<td>".$nucleo['Superficie']."</td>";
				$tr .= "<td><input type='hidden' name='idMunic' value='".$nucleo['codigoMunicipio']."'>".$nucleo['Municipio']."</td>";
				$tr .= "<td><a href='#'>Edit<a></td>";
				$tr .= "<td><a href='#'>Elim<a></td>";
				$tr .= "<td><a href='#'><input type='radio' name='idNucl' value='".$nucleo['Clave']."'></a></td>";
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
		
		$isEmpty  = '';
		$isEmpty .= !$ID_NUCLEO ? 'ID_NUCLEO no tiene valor' : '';

		if($isEmpty != ''){
			$this->error = true;
			$this->messageError = $isEmpty;
			$this->methodError = 'deleteNucleo';
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
		
		$sql = "DELETE FROM aj_nucleosagrarios WHERE codigo = '".$ID_NUCLEO."'";
		
		$mysql->executeDelete($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		return $mysql->num_affected_rows;
	}
	function insertNucleo($ID_NUCLEO, $NUCLEO, $TIPO, $SUPERFICIE, $ID_MUNICIPIO) {
		
		$this->clearErrorLogs();
		
		$error = "";
		$error .= !$ID_NUCLEO? "ID_MUNICIPIO  vacio\n" : "";
		$error .= !$NUCLEO ? "NUCLEO vacio\n" : "";
		$error .= !$TIPO ? "TIPO vacio\n" : "";
		$error .= !$SUPERFICIE ? "SUPERFICIE vacio\n" : "";
		$error .= !$ID_MUNICIPIO ? "ID_MUNICIPIO vacio\n" : "";
		
		
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
		
		$sql = "INSERT INTO aj_nucleosagrarios VALUES('".$ID_NUCLEO."', '".$NUCLEO."', '".$TIPO."', '".$SUPERFICIE."', '".$ID_MUNICIPIO."') ";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		
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
	
	
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

}
















?>