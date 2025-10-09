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
class Empleado extends MyObject {

	var $cadenaConexion;
	
	var $error;
	var $errorCode;
	var $messageError;
	var $messageErrorTecnico;
	var $methodError;
	
	var $EXIT_ON_ERROR_LOG;

	function Empleado() {
		//$this->cadenaConexion = "mysql.nixiweb.com:u775268735_procede:u775268735_proce:procedex";
		$this->cadenaConexion = "localhost:bd_procedeweb:root:";
		$this->clearErrorLogs();
	}
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	CATALOGOS
	function getNombreCompletoEmpleado($ID_EMPLEADO) {
		
		if(empty($ID_EMPLEADO)){
			$this->error = true;
			$this->messageError = "ID de empleado no especificado";
			$this->methodError = "getNombreCompletoEmpleado";
			return false;
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				 
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getNombreCompletoEmpleado.".$mysql->methodError;
			return false;
		}

		$sql = "SELECT aj_empleados.nombre, 
					aj_empleados.apePat, 
					aj_empleados.apeMat 
			    FROM  aj_empleados 
			    WHERE aj_empleados.idEmpleado=".$ID_EMPLEADO;
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "En la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getNombreCompletoEmpleado.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		$tr = "";
		
		if($dt->NumRows == 0) {
			$this->error = true;
			$this->messageError = "No se pudo obtener el nombre completo del empleado de id [".$ID_EMPLEADO."]";
			$this->methodError = "getNombreCompletoEmpleado";
			return false;

		}
		else {
			$empleado = $dt->Next();
			$nombreCompleto = $empleado['nombre']." ".$empleado['apePat']." ".$empleado['apeMat'];
		}
		return $nombreCompleto;
	}
	function getRowsEmpleados($ID='', $NAME='', $CLASS='', $TITLE='') {
		
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
			$this->methodError = "getRowsEmpleados.".$mysql->methodError;
			return false;
		}

		$sql = "SELECT aj_empleados.idEmpleado as ID, 
		               aj_empleados.nombre AS Nombre, 
							aj_empleados.apePat AS 'Apell. Pat.', 
							aj_empleados.apeMat AS 'Apell. Mat.', 
							aj_empleados.direccion AS Direccion, 
							aj_empleados.profesion AS Profesion, 
							aj_empleados.telefono AS Telefono,
							aj_empleados.activo AS Activo 
				    FROM aj_empleados";
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "En la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getRowsEmpleados.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		$tr = "";
		
		if($dt->NumRows == 0) 
			$tr .= "<tr class='no-data'><td colspan='14'>no hay empleados registrados</td></tr>";
		else 
			while($empleado = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td>".$empleado['ID']."</td>";
				$tr .= "<td>".$empleado['Nombre']."</td>";
				$tr .= "<td>".$empleado['Apell. Pat.']."</td>";
				$tr .= "<td>".$empleado['Apell. Mat.']."</td>";
				$tr .= "<td>".$empleado['Direccion']."</td>";
				$tr .= "<td>".$empleado['Profesion']."</td>";
				$tr .= "<td>".$empleado['Telefono']."</td>";
				//$tr .= "<td>".$empleado['Usuario']."</td>";
				//$tr .= "<td>".$empleado['Contraseña']."</td>";
				
				$activo = ($empleado['Activo']) ? 'SI' : 'NO';
				
				$tr .= "<td><a href='#'>".$activo."<a></td>";
				$tr .= "<td><a href='#'>Edit<a></td>";
				$tr .= "<td><a href='#'>Elim<a></td>";
				$tr .= "<td><a href='#'><input type='radio' name='idEmpl' value='".$empleado['ID']."'></a></td>";
				$tr .= "</tr>";
			}
		return $tr;
	}
	function getComboEmpleados($ID='', $NAME='', $CLASS='', $TITLE='') {
		
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
			$this->methodError = "getComboEmpleados.".$mysql->methodError;
			return false;
		}

		$sql = "SELECT idEmpleado as ID, 
		               nombre AS Nombre, 
							apePat AS 'Apell. Pat.', 
							apeMat AS 'Apell. Mat.' 
			     FROM aj_empleados 
			    WHERE aj_empleados.tieneCuenta = 0";
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "En la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = "getComboEmpleados.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$select = "<select name=".$NAME." ".$TITLE." ".$ID." ".$CLASS." >";
		$select .= "<option style='color:#999; '>-- lista de empleados sin cuenta de usuario --</option>";
		
		if($dt->NumRows == 0) 
			$select .= "<option value='0'>no hay empleados registrados</option>";
		else 
			while($empleado = $dt->Next()) 
				$select .= "<option value='".$empleado['ID']."'>".$empleado['ID']." - ".$empleado['Nombre']." ".$empleado['Apell. Pat.']." ".$empleado['Apell. Mat.']."</option>";
		
		$select .= "</select>";
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		return $select;
	}

	function deleteEmpleado($ID_EMPLEADO) {

		$this->clearErrorLogs();
		
		$isEmpty  = '';
		$isEmpty .= !$ID_EMPLEADO ? 'ID_EMPLEADO no tiene valor' : '';

		if($isEmpty != ''){
			$this->error = true;
			$this->messageError = $isEmpty;
			$this->methodError = 'deleteEmpleado';
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
		
		$sql = "DELETE FROM aj_empleados WHERE idEmpleado = ".$ID_EMPLEADO;
		
		$mysql->executeDelete($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		return $mysql->num_affected_rows;
	}
	function insertEmpleado($ID_EMPLEADO, $NOMBRE, $APELL1, $APELL2, $DIRECCION, $PROFESION, $TELEFONO, $ACTIVO) {
		
		$this->clearErrorLogs();
		
		$error = "";
		$error .= !$ID_EMPLEADO? "ID EMPLEADO vacio\n" : "";
		$error .= !$NOMBRE ? "NOMBRE vacio\n" : "";
		$error .= !$APELL1? "APELLIDO PATERNO vacio\n" : "";
		$error .= !$APELL2? "APELLIDO MATERNO vacio\n" : "";
		$error .= !$DIRECCION? "DIRECCION vacio\n" : "";
		$error .= !$PROFESION? "PROFESION vacio\n" : "";
		$error .= !$TELEFONO? "TELEFONO vacio\n" : "";
		
		if($error){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'insertEmpleado';
			return false;
		}

		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos de Austins";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$sql = "INSERT INTO aj_empleados VALUES(".$ID_EMPLEADO.", '".$NOMBRE."', '".$APELL1."', '".$APELL2."', '".$DIRECCION."', '".$PROFESION."','".$TELEFONO."',".$ACTIVO.") ";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		
		$mysql->executeInsert($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->errorCode = $mysql->errorCode;
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		return $mysql->num_affected_rows;
	}
	function updateEmpleado($ID_EMPLEADO, $NOMBRE, $APELL1, $APELL2, $DIRECCION, $PROFESION, $TELEFONO, $ACTIVO) {
		
		$this->clearErrorLogs();
		
		$error = "";
		$error .= !$ID_EMPLEADO? "ID EMPLEADO vacio\n" : "";
		$error .= !$NOMBRE ? "NOMBRE vacio\n" : "";
		$error .= !$APELL1? "APELLIDO PATERNO vacio\n" : "";
		$error .= !$APELL2? "APELLIDO MATERNO vacio\n" : "";
		$error .= !$DIRECCION? "DIRECCION vacio\n" : "";
		$error .= !$PROFESION? "PROFESION vacio\n" : "";
		$error .= !$TELEFONO? "TELEFONO vacio\n" : "";

		if($error != ''){
			$this->error = true;
			$this->messageError = $error;
			$this->methodError = 'updateEmpleado';
			return false;
		}
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = 'updateEmpleado.'.$mysql->methodError;
			return false;
		}
		

		$sql = "UPDATE aj_empleados SET nombre='".$NOMBRE."', apePat='".$APELL1."', apeMat='".$APELL2."', direccion='".$DIRECCION."', profesion='".$PROFESION."', telefono='".$TELEFONO."', activo=".$ACTIVO." WHERE idEmpleado=".$ID_EMPLEADO;		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 

		$mysql->executeUpdate($sql);
		
		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia '".$sql."'";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			$this->methodError = 'updateEmpleado.'.$mysql->methodError;
			return false;
		}
		
		return $mysql->num_affected_rows;
	}

	function getEmpleadoNuevoID() {
		
		$this->clearErrorLogs();
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = 'Error de conexion con la base de datos';
			$this->messageErrorTecnico = $mysql->getInfoError();
			$this->methodError = "getEmpleadoNuevoID.".$mysql->methodError;
			return false;
		}
		
		$sql = "SELECT * FROM tbl_categorias";
		
		$mysql->executeSelect($sql);

		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia[".$sql."]";
			$this->messageErrorTecnico = $mysql->getInfoError();
			$this->methodError = "getEmpleadoNuevoID.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		if($dt->NumRows==0) {
			return 100;
		}
		
		if(!$nuevaClave = $mysql->getMaxValueFrom('cCategoria','tbl_categorias')){
			$this->error = true;
			$this->messageError = 'Error al generar la clave para producto!';
			$this->messageErrorTecnico = $mysql->getInfoError();
			$this->methodError = "getEmpleadoNuevoID.".$mysql->methodError;
			return false;
		}
		$mysql->close();
		return $nuevaClave + 1;
	}
	function getProductNewKey() {

		$this->clearErrorLogs();
		
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = 'Error de conexion a la base Austins';
			$this->messageErrorTecnico = $mysql->getInfoError();
			$this->methodError = "getProductNewKey.".$mysql->methodError;
			return false;
		}
		
		$sql = "SELECT * FROM tbl_productos";
		
		$mysql->executeSelect($sql);

		if(!$mysql->execute_success) {
			$this->error = true;
			$this->messageError = "Error al ejecutar la sentencia[".$sql."]";
			$this->messageErrorTecnico = $mysql->getInfoError();
			$this->methodError = "getProductNewKey.".$mysql->methodError;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		if($dt->NumRows==0) {
			return 200;
		}
		
		if(!$nuevaClave = $mysql->getMaxValueFrom('cProducto','tbl_productos')){
			$this->error = true;
			$this->messageError = 'Error al generar la clave para producto!';
			$this->messageErrorTecnico = $mysql->getInfoError();
			$this->methodError = "getProductNewKey.".$mysql->methodError;
			return false;
		}
		
		$mysql->close();
		return $nuevaClave + 1;
	
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