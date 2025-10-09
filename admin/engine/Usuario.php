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
class Usuario extends MyObject {

	var $cadenaConexion;
	
	var $error;
	var $errorCode;
	var $messageError;
	var $messageErrorTecnico;
	var $methodError;
	
	var $EXIT_ON_ERROR_LOG;

	function Usuario() {
		//$this->cadenaConexion = "mysql.nixiweb.com:u775268735_procede:u775268735_proce:procedex";
		$this->cadenaConexion = "localhost:bd_procedeweb:root:";
		$this->clearErrorLogs();
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	CATALOGOS
	function getRowsUsuarios($ID='', $NAME='', $CLASS='', $TITLE='') {
		
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
			$this->methodError = "getRowsUsuarios.".$mysql->methodError;
			return false;
		}

		$sql = "SELECT aj_usuarios.usuario AS Usuario, 
		               aj_usuarios.password AS 'Contraseña', 
							aj_usuarios.isAdmin AS 'Admin', 
							aj_usuarios.idEmpleado AS idEmpleado, 
							aj_empleados.nombre AS nombre, 
							aj_empleados.apePat AS ape1, 
							aj_empleados.apeMat AS ape2 
							
				    FROM aj_usuarios, aj_empleados 
					 WHERE aj_empleados.idEmpleado = aj_usuarios.idEmpleado";
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error en la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsUsuarios.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		$tr = "";
		
		if($dt->NumRows == 0) 
			$tr .= "<tr class='no-data'><td colspan='14'>no hay empleados registrados</td></tr>";
		else 
			while($usuario = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td>".$usuario['Usuario']."</td>";
				$tr .= "<td>".$usuario['Contraseña']."</td>";
				$tr .= "<td>".$usuario['idEmpleado'].' - '.$usuario['nombre']." ".$usuario['ape1']." ".$usuario['ape2']."</td>";
				$tr .= "<td><a href='#'>Edit</a></td>";
				$tr .= "<td><a href='#'>Elim</a></td>";
				$tr .= "<td><a href='#'><input type='radio' name='idUsr' value='".$usuario['idEmpleado']."'></a></td>";
				$tr .= "</tr>";
			}
		return $tr;
	}
	function insertUsuario($USUARIO, $PASSWORD, $ID_EMPLEADO, $IS_ADMIN) {
		$this->clearErrorLogs();
		$error = !$USUARIO? "USUARIO vacio\n" : "";
		$error .= !$PASSWORD ? "PASSWORD vacio\n" : "";
		$error .= !$ID_EMPLEADO? "ID_EMPLEADO vacio\n" : "";
		//$error .= empty($IS_ADMIN)? "IS_ADMIN vacio\n" : "";
		if($error) {
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'insertUsuario';
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = $mysql->methodError.".insertUsuario";
			return false;
		}
		$sql = "INSERT INTO aj_usuarios VALUES('".$USUARIO."', '".$PASSWORD."', ".$IS_ADMIN.", ".$ID_EMPLEADO.") ";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO
		$mysql->executeInsert($sql);
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = $mysql->methodError.".insertUsuario";
			return false;
		}
		$sql = "UPDATE aj_empleados SET tieneCuenta=1 WHERE idEmpleado=".$ID_EMPLEADO;		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		$mysql->executeInsert($sql);
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = $mysql->methodError.".insertUsuario";
			return false;
		}
		return $mysql->num_affected_rows;
	}
	function updateUsuario($USUARIO, $PASSWORD) {
		
		$this->clearErrorLogs();
		
		$error = "";
		$error .= !$USUARIO ? "USUARIO vacio\n" : "";
		$error .= !$PASSWORD ? "PASSWORD vacio\n" : "";
		//$error .= !$ID_EMPLEADO ? "ID_EMPLEADO vacio\n" : "";

		if($error != ''){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'updateUsuario';
			return false;
		}
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = 'updateUsuario.'.$mysql->methodError;
			return false;
		}
		

		$sql = "UPDATE aj_usuarios SET password='".$PASSWORD."' WHERE usuario='".$USUARIO."'";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 

		$mysql->executeUpdate($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = 'updateUsuario.'.$mysql->methodError;
			return false;
		}
		
		
		return $mysql->num_affected_rows;
	}
	function deleteUsuario($USUARIO, $ID_EMPLEADO) {
		$this->clearErrorLogs();
		$isEmpty  = '';
		$isEmpty .= !$USUARIO ? 'USUARIO no tiene valor' : '';
		$isEmpty .= !$ID_EMPLEADO ? 'ID_EMPLEADO no tiene valor' : '';
		if($isEmpty != '') {
			$this->error = true;
			$this->messageError = $isEmpty;
			$this->methodError = 'deleteUsuario';
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
		$sql = "DELETE FROM aj_usuarios WHERE usuario = '".$USUARIO."'";
		$mysql->executeDelete($sql);
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = 'deleteUsuario';
			return false;
		}
		$sql = "UPDATE aj_empleados SET tieneCuenta=0 WHERE idEmpleado=".$ID_EMPLEADO;		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		$mysql->executeDelete($sql);
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = 'deleteUsuario';
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