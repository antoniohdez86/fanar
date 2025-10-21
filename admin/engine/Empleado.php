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
	var $nombre;
	var $username;
	var $isAdmin;
	var $isActive;
	var $noNucleosAsign;
	
	var $cadenaConexion;
	
	var $error;
	var $errorCode;
	var $messageError;
	var $messageErrorTecnico;
	var $methodError;
	
	var $EXIT_ON_ERROR_LOG;

	function Empleado() {
		//$this->cadenaConexion = "mysql.nixiweb.com:u669753084_fanar:u669753084_fanar:xfanarx";
		$this->cadenaConexion = "localhost:bd_procedeweb:root:";
		$this->clearErrorLogs();
	}
	
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
							aj_empleados.activo AS Activo, 
							(SELECT COUNT(*) FROM aj_empleado_nucleos WHERE aj_empleado_nucleos.idEmpleado = aj_empleados.idEmpleado) AS 'Nucleos Asignados' 
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
		
		if($dt->NumRows == 0) {
			$this->error = true;
			$this->errorCode = 'NOHAS';
			$this->messageError = "No hay empleados registrados";
			return false;
		}
		else 
			while($empleado = $dt->Next()) {
				$tr .= "<tr>";
				$tr .= "<td align='center'>".$empleado['ID']."</td>";
				$tr .= "<td>".$empleado['Nombre']."</td>";
				$tr .= "<td>".$empleado['Apell. Pat.']."</td>";
				$tr .= "<td>".$empleado['Apell. Mat.']."</td>";
				$tr .= "<td>".$empleado['Direccion']."</td>";
				$tr .= "<td>".$empleado['Profesion']."</td>";
				$tr .= "<td>".$empleado['Telefono']."</td>";
				if($empleado['Activo']){
					$class = 'up';
					$title = 'activo';
				} else {
					$class = 'down';
					$title = 'inactivo';
				}
				$tr .= "<td align='center'><span class='".$class."' title='".$title."'>   <span></td>";
				$tr .= "<td align='center'>".$empleado['Nucleos Asignados']."</td>";
				$tr .= "<td align='center'><a href='#'><input type='radio' name='idEmpl' value='".$empleado['ID']."'></a></td>";
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
	function getComboTodosEmpleados($ID='', $NAME='', $CLASS='', $TITLE='') {
		
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

		/*
		$sql = "SELECT idEmpleado as ID, 
		               nombre AS Nombre, 
							apePat AS 'Apell. Pat.', 
							apeMat AS 'Apell. Mat.' 
			     FROM aj_empleados";
		*/
		$sql = "SELECT 
					DISTINCT aj_empleados.idEmpleado,
				   CONCAT(aj_empleados.nombre , ' ' , aj_empleados.apePat, ' ' ,aj_empleados.apeMat) AS nombre,
				   (SELECT aj_usuarios.isAdmin FROM aj_usuarios WHERE aj_usuarios.idEmpleado=aj_empleados.idEmpleado) AS isAdmin
					FROM aj_empleados, aj_empleado_nucleos 
					WHERE aj_empleado_nucleos.idEmpleado = aj_empleados.idEmpleado";
		
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
				$select .= "<option value='".$empleado['idEmpleado']."'>".$empleado['idEmpleado']." - ".$empleado['nombre']."</option>";
		
		$select .= "</select>";
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		return $select;
	}
	function getComboEmpleados_mode2($ID='', $NAME='', $CLASS='', $TITLE='') {
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
		$sql = "SELECT aj_empleados.idEmpleado as ID, 
		               CONCAT(aj_empleados.nombre,' ',aj_empleados.apePat,' ',aj_empleados.apeMat) AS nombre, 
							(SELECT COUNT(*) FROM aj_empleado_nucleos WHERE aj_empleado_nucleos.idEmpleado=aj_empleados.idEmpleado) AS nucleos 
			       FROM aj_empleados 
				   WHERE aj_empleados.idEmpleado <> (SELECT aj_usuarios.idEmpleado FROM aj_usuarios WHERE aj_usuarios.isAdmin=1)";
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
		$select = "<select name=".$NAME." ".$TITLE." ".$ID." ".$CLASS." multiple='multiple' size='20' >";
		if($dt->NumRows == 0) 
			$select .= "<option value='0'>no hay empleados registrados</option>";
		else {
			$select .= "<option value='0' style='color:#666'>-- sin asignar --</option>";
			while($empleado = $dt->Next()) 
				if($empleado['nucleos']<3) 
					$select .= "<option value='".$empleado['ID']."'>".$empleado['nombre']."</option>";
		}
		$select .= "</select>";
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
			$this->errorCode = $mysql->errorCode;
			$this->messageError = "No se pudo conectar a la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$sql = "DELETE FROM aj_empleados WHERE idEmpleado = ".$ID_EMPLEADO;
		
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
		
		$sql = "INSERT INTO aj_empleados VALUES(".$ID_EMPLEADO.", '".$NOMBRE."', '".$APELL1."', '".$APELL2."', '".$DIRECCION."', '".$PROFESION."','".$TELEFONO."',".$ACTIVO.", 0) ";		// CADA CATALOGO QUE SE INSERTA ES ACTIVADO 
		
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
	
	function getInfo($ID_EMPLEADO) {
		if(!$ID_EMPLEADO){
			$this->error = true;
			$this->errorCode = 'EMPTYIDEMPL';
			$this->messageError = "ID de empleado no especificado";
			return false;
		}
		$mysql = new MySqlConnection();
		$mysql->CadenaDeConexion = $this->cadenaConexion;
		if(!$mysql->conectar()){
			$this->error = true;
			$this->messageError = "Error al intentar conectar con la base de datos";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		$sql = "SELECT CONCAT(aj_empleados.nombre,' ',aj_empleados.apePat,' ',aj_empleados.apeMat) AS nombre,
							(SELECT aj_usuarios.usuario FROM aj_usuarios WHERE aj_usuarios.idEmpleado=aj_empleados.idEmpleado) AS usuario, 
							(SELECT aj_usuarios.isAdmin FROM aj_usuarios WHERE aj_usuarios.idEmpleado=aj_empleados.idEmpleado) AS isAdmin,
							aj_empleados.activo, 
							(SELECT COUNT(*) FROM aj_empleado_nucleos WHERE aj_empleado_nucleos.idEmpleado = aj_empleados.idEmpleado) AS 'NoNucleos' 
				    FROM aj_empleados 
					 WHERE aj_empleados.idEmpleado=".$ID_EMPLEADO;
		
		$mysql->executeSelect($sql);
		
		if(!$mysql->execute_success){
			$this->error = true;
			$this->messageError = "En la ejecusion de sentencia : [".$sql."]";
			$this->messageErrorTecnico = $mysql->messageErrorTecnico;
			return false;
		}
		
		$dt = new DataTable();
		$dt->setSourcce($mysql->source);
		
		$mysql->close();
		
		$tr = "";
		
		if($dt->NumRows == 0) {
			$this->error = true;
			$this->errorCode = 'NODATA';
			$this->messageError = "No existe ningun empleado de id[".$ID_EMPLEADO."]";
			return false;
		} else {
			$empleado = $dt->Next();
			$this->nombre = $empleado['nombre']." ".$empleado['']." ".$empleado[''];
			$this->username = $empleado['usuario'];
			$this->isAdmin = $empleado['isAdmin'];
			$this->isActive = $empleado['activo'];
			$this->noNucleosAsign = $empleado['NoNucleos'];
		}
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