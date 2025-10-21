<?php 

//	DEFINE UNA VARIABLE QUE PUEDEN UTILIZAR LOS ARCHIVOS EXTERNOS PARA COMPROBAR SI YA HAN INCLUIDO ESTAS LIBRERIAS Y SINO PUES SE INCLUYEN
$LIBRARY_MYSQL_CONNECTION = true;

/*
 *		MySqlConnection
 *
 *		- Clase base mediante el cual se pueden realizar operacione sobre bases de datos MySql.
 *		- Algunas clases pueden usar esta clase para realizar operaciones con bases de datos.
 *
 *		DEPENDENCIAS : ninguno
 *
 **/
 

 
 
class MySqlConnection {
	
		var $conexion;				//	STRING : CONTIENE LA CONEXION A UNA BASE DE DATOS ESPECIFICA A LA QUE SE ENCUENTRA CONECTADA.
		var $user;					//	STRING : NOMBRE DE USUARIO CON EL QUE SE CONECTARA Y ABRIRA UNA BASE DE DATOS
		var $password;				//	STRING : CONTRASEÑA CON EL CUAL EL USUARIO SE CONECTARA A LA BD
		var $database;				//	STRING : NOMBRE DE LA BASE DE DATOS QUE SE SELECCIONARA PARA TRABAJAR CON ESTA.
		var $server;				//	STRING : NOMBRE O IP DEL SERVIDOR EN EL CUAL SE ENCUENTRA LA BASE DE DATOS
		var $source;				//	SOURCE : VARIABLE EN EL CUAL SE RECUPERARAN LOS RESULTADOS DE REALIZAR UNA CONSULTA
		var $dataSet;				//	
		var $connected;				//	BOOL : PROPIEDAD DE SOLO LECTURA QUE CONTENDRA EL ESTADO DE LA CONEXION CON LA BASE DE DATOS. true: conectado, false: desconectado
		var $execute_success;		//	BOOL : PROPIEDAD QUE INDICA SI AL REALIZAR UNA SENTENCIA SQL ESTA SE EJECUTO CON EXITO(true) O FALLO(false)
		var $num_affected_rows;		//	INT  : NUMERO DE FILAS AFECTADAS COMO RESULTADO DE LA EJECUSION DE UNA SENTENCIA DEL TIPO: update, insert, delete, etc.
		
		var $error;						//	BOOL : INDICA SI EN ALGUNA FUNCION HA OCURRIDO ALGUN TIPO DE ERROR { true: hay error,  false: NO hay error }
		var $errorCode;				// NUM  : CODIGO DE ERROR
		var $messageError;			//	STRING : SI $error ES "TRUE" ESTA VARIABLE CONTIENE UNA CADENA CON EL MENSAJE DE ERROR
		var $messageErrorTecnico;
		var $methodError;
		
		var $CadenaDeConexion;		//	STRING : CADENA CON DATOS VALIDOS PARA UNA CONEXION A LA BD
									//          - el formato correcto para una cadena de conexion es el siguiente "server:database:user:password" (SIN ESPACIOS)
		
		
		/*	
		 *	CONSTRUCTOR 
		 *
		 *	Inicializa los miembros de la clase a valores predeterminados o vacios
		 */		
		function MySqlConnection() {
			
				$this->user = "";
				$this->password = "";
				$this->database = "";
				$this->server = "localhost";
				$this->execute_success = false;
				$this->num_affected_rows = 0;
				$this->connected =  false;
				
				$this->methodError = 'no implementado';
		}
		
		//	VALIDA QUE LOS DATOS COMO: [ Usuario, Password, Database, y Server ] ESTEN INICIALIZADOS CON VALORES NO VACIOS.
		function validateParams() {
			
			$this->error = false;
			$this->messageError = '';
			
			if( empty($this->user) || empty($this->password) || empty($this->database) || empty($this->server) ){
				$this->error = true;
				$this->messageError  = 'No se han definido valores para los algunos parametros para la conexion a la base de datos: ';
				$this->messageError += 'usuario:"'.$this->user.'", password:"'.$this->password.'", database:"'.$this->database.'", server:"'.$this->server.'".';
				return false;
			}
			return true;
		}
		
		//	CREA Y ABRE UNA CONEXION CON LA BASE DE DATOS
		function connect() {
				
				//	inicia control de errores
				$this->error = false;
				$this->messageError = '';
				
				// conecta a la base de datos
				$this->conexion = @mysqli_connect( $this->server, $this->user, $this->password );
				
				// si ocurrio una falla con la conexion
				if(!$this->conexion){
					$this->error = true;
					$this->messageError = 'Error al intentar extablecer la conexion';
					return false;
				}
				
				$db_selected =  @mysqli_select_db( $this->conexion, $this->database );
				
				// si ocurrio una falla con la seleccion de la base de datos
				if(!$db_selected){
					$this->error = true;
					$this->messageError = 'Error al seleccionar la base de datos: '.$this->database;
					return false;
				}
				
				//return $this->connected = ($this->conexion && $db_selected) ? 1 : 0;
				return $this->connected = true;
		}
		
		//	NO IMPLEMENTADO
		function isOpen() {	}
		
		//	CIERRA LA CONEXION
		function close() {
				mysqli_close($this->conexion);
		}
		
		//	EJECUTA UNA SENTENCIA Y DEVUELVE EL RESULTADO EN LA PROPIEDAD "$this->source" DEL OBJETO MySqlConnection
		function executeQuery($query) {

			$this->execute_success = ($this->source = mysqli_query( $this->conexion, $query ) ) ? 1 : 0;
			return $this->source;
		}
		
		//	EJECUTA EL UNA SENTENCIA DEL TIPO [ UPDATE ] Y DEVUELVE EL NUMERO DE FILAS AFECTADAS
		function executeUpdate($query) {
						
			$this->clearErrorLogs();
			$this->execute_success = (mysqli_query( $this->conexion, $query ) ) ? 1 : 0;
			
			if(!$this->execute_success) {
				$this->error = true;
				$this->messageError = 'No se pudo ejecutar la sentencia sql "'.strtoupper($query).'"';
				$this->messageErrorTecnico = $this->getInfoError();
				$this->methodError = 'executeUpdate';
				return false;
			}
			return $this->num_affected_rows = mysqli_affected_rows($this->conexion);
		}
		
		//	EJECUTA EL UNA SENTENCIA DEL TIPO [ INSERT ] Y DEVUELVE EL NUMERO DE FILAS AFECTADAS
		function executeInsert($query) {
			
			$this->clearErrorLogs();
			$this->execute_success = (mysqli_query( $this->conexion, $query ) ) ? 1 : 0;
			
			if(!$this->execute_success) {
				$this->error = true;
				$this->errorCode = mysqli_errno($this->conexion);
				$this->messageError = 'No se pudo ejecutar la sentencia sql "'.strtoupper($query).'"';
				$this->messageErrorTecnico = $this->getInfoError();
				$this->methodError = 'executeInsert';
				return false;
			}
			
			return $this->num_affected_rows = mysqli_affected_rows($this->conexion);
		}
		
		//	EJECUTA EL UNA SENTENCIA DEL TIPO [ DELETE ] Y DEVUELVE EL NUMERO DE FILAS AFECTADAS
		function executeDelete($query) {
				$this->clearErrorLogs();
				$this->execute_success = (mysqli_query( $this->conexion, $query ) ) ? 1 : 0;
				if(!$this->execute_success) {
					$this->error = true;
					$this->messageError = 'No se pudo ejecutar la sentencia sql "'.strtoupper($query).'"';
					$this->messageErrorTecnico = $this->getInfoError();
					$this->methodError = 'executeDelete';
					return false;
				}
				return $this->num_affected_rows = mysqli_affected_rows($this->conexion);
		}
		
		//	EJECUTA EL UNA SENTENCIA DEL TIPO [ SELECT ] Y UN IDENTIFICADOR DE TIPO "RECURSO" EL CUAL CONTIENE TODAS LAS FILAS DEVUELTAS POR LA CONSULTA
		function executeSelect($query) {
			
				$this->clearErrorLogs();
				
				$this->execute_success = ($this->source = mysqli_query( $this->conexion, $query ) ) ? 1 : 0;
				
				if(!$this->execute_success){
					
					$this->error = true;
					$this->messageError = 'No se pudo ejecutar la sentencia sql "'.strtoupper($query).'"';
					$this->messageErrorTecnico = $this->getInfoError();
					$this->methodError = 'executeSelect';
					return false;
				}
				return $this->source;
		}
		
		//	DEVUELVE EL REGISTRO DE LA TABLA ($tbl_name) QUE TENGA EL VALOR MAS ALTO EN SU COLUMNA ($column_name) Y LE SUMA 1 PARA GENERAR LA NUEVA CLAVE
		function getMaxValueFrom($column_name, $table_name) {
			
				$query = "SELECT MAX(".$column_name.") AS NuevaClave FROM ".$table_name;
				
				$this->execute_success = ($this->source = mysqli_query( $this->conexion, $query ) ) ? 1 : 0;
				
				if(mysqli_num_rows($this->source) > 0) {
					$row = mysqli_fetch_array($this->source);
					return $row[0];
				}
				return 100;
		}
		
		//	DEVUELVE INFORMACION BASICA ACERCA DE LOS DATOS USADOS PARA LA CONEXION
		function getInfoBasic() {
			return	"INFO BASIC: <br>".
					"user: ".$this->user.", <br>".
					"password: ".$this->password.", <br>".
					"database: ".$this->database.", <br>".
					"server: ".$this->server."<br>";
		}
		function getInfoConnection() {
			return "INFO CONNECTION: <br>".
					"isServerConnected: ".$this->connected.", <br>".
					//"isDBSelected: ".$this->password.", <br>".
					"database: ".$this->database.", <br>".
					 "server: ".$this->server."<br>";
		}
		
		//	DEVUELVE UNA CADENA CON EL CODIGO Y MENSAJE DE ERROR
		function getInfoError() {
				return ($this->conexion) ? "code: ".mysqli_errno($this->conexion).", msg: ".mysqli_error($this->conexion)."<br>" : "code: ".mysqli_errno($this->conexion).", msg: ".mysqli_error($this->conexion)."<br>";
		}
		
		//----------------------------------    NUEVOS METODOS AGREGADOS   ----------------------------------------//
		
		function conectar() {
			
			$this->clearErrorLogs();

			if( empty($this->CadenaDeConexion) ){
				$this->error = true;
				$this->messageError = 'No ha establecido la cadena de conexion!!: ['.$this->CadenaDeConexion.']';
				$this->methodError = 'conectar';
				return false;
			}
			
			$datos = explode(':', $this->CadenaDeConexion);
			
			if(count($datos) != 4){
				$this->error = true;
				$this->messageError = 'La cadena de conexion no es valida: ['.$this->CadenaDeConexion.']';
				$this->methodError = 'conectar';
				return false;
			}
			
			$SERVER = $this->server = $datos[0];
			$DATABASE = $this->database = $datos[1];
			$USER = $this->user = $datos[2];
			$PASSWORD = $this->password = $datos[3];
	
	
			// conecta a la base de datos
			$this->conexion = @mysqli_connect( $SERVER, $USER, $PASSWORD );
			
			// si ocurrio una falla con la conexion
			if(!$this->conexion){
				$this->error = true;
				$this->messageError = 'Error al intentar extablecer la conexion';
				$this->messageErrorTecnico = $this->getInfoError();
				$this->methodError = 'conectar';
				return false;
			}
				
			$db_selected =  @mysqli_select_db( $this->conexion, $DATABASE );
				
			// si ocurrio una falla con la seleccion de la base de datos
			if(!$db_selected){
				$this->error = true;
				$this->messageError = 'Error al seleccionar la base de datos: '.$this->database;
				$this->messageErrorTecnico = $this->getInfoError();
				$this->methodError = 'conectar';
				return false;
			}
				
			//return $this->connected = ($this->conexion && $db_selected) ? 1 : 0;
			return $this->connected = true;
		}
		
		private function clearErrorLogs() {
			$this->error = false;
			$this->errorCode = '';
			$this->messageError = '';
			$this->messageErrorTecnico = '';
			$this->methodError = '';
		}
		
		
}
?>