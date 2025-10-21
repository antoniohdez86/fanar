<?php 

/*	Autor: Antonio de Jesus Hernandez Hernandez
 *	Nick: ajhh
 *	Mensaje:  dejando huella en el ITH
 *	email: solitario917@hotmail.com
 */

class MyDataBaseITH {
	
	var $usr;
	var $pwd;
	var $host;
	var $database;
	var $link;
	
	var $dtQuejas;			// quejas
	var $dtEgresados;		// egresados
	
	var $error;
	var $messageError;
	
	/* constructor */
	function MyDataBaseITH($host, $usr, $pwd, $database) {
		$this->usr = $usr;
		$this->pwd = $pwd;
		$this->host = $host;
		$this->database = $database;
		$this->_link = false;
		$this->dtQuejas = false;
		
		$this->error = false;
		$this->messageError = "";
	}
	
	/* muestra informacion usada */
	function showInfo() {
		echo "DBInfo: ".$this->host.":".$this->usr.":".$this->pwd.":".$this->database;
	}
	
	/* conecta a la base */
	function conectar() {
		
		$this->error = false;
		$this->messageError = "";
		
		$link = mysql_connect($this->host, $this->usr, $this->pwd);
		
		if(!$link) {
			$this->error = true;
			$this->messageError = "Error de conexion: ".mysql_errno().", ".mysql_error();
		} else {
			if(!mysql_select_db($this->database, $link)) {
				$this->error = true;
				$this->messageError = "Error al seleccionar la base de datos: ".mysql_errno($link).", ".mysql_error($link);
			}
		}
		return $this->link = $link;
	}
	function obtenerQuejas($fecha_ini, $fecha_fin) {
		
		//echo $fecha_ini." - ".$fecha_fin."<br>";
		
		$this->error = false;
		$this->messageError = "";
		
		if(!$this->conectar()) {
			return false;
		}
		
		$query = "SELECT tblalumnos.nombre,
					  tblalumnos.apellidopat, 
					  tblalumnos.apellidomat,
					  tblquejas.ncontrol,
					  tblcarrera.carrera,
					  tblalumnos.email,
					  tblquejas.suqueja,
					  tblquejas.fecha
				FROM
					  tblalumnos, tblcarrera, tblquejas
				WHERE
					  tblcarrera.cvcarrera = tblalumnos.carrera AND
					  tblalumnos.ncontrol = tblquejas.ncontrol AND 
					  tblquejas.fecha>='".$fecha_ini."' AND tblquejas.fecha<='".$fecha_fin."'";
		
		$dtResult = mysql_query($query, $this->link);
		
		if(!$dtResult) {
			$this->error = true;
			$this->messageError = "Error: ".mysql_errno($link).", ".mysql_error($link);
			return false;
		}
		
		
		$this->dtQuejas = $dtResult;
		
		$data = array();	// almacena cada fila de datos en un arreglo
		
		if( mysql_num_rows($dtResult) > 0 ) {
			for($c=0; $c<mysql_num_rows($dtResult); $c++){
				$data[$c] = mysql_fetch_row($dtResult);
			}
			return $data;
		}
		return false;
	}
	function obtenerEgresados($anio_egreso) {
		
		//echo $fecha_ini." - ".$fecha_fin."<br>";
		
		$this->error = false;
		$this->messageError = "";
		
		if(!$this->conectar()) {
			return false;
		}
		
		if($anio_egreso=="0000") {
			$query = 
			"SELECT 
				tblegresados.anioegreso AS 'Año', 
				tblcarrera.carrera AS Carrera, 
				tblespecialidad.especialidad AS Especialidad, 
				tblegresados.ncontroleg AS 'No.Control', 
				tblalumnos.nombre AS Nombre, 
				tblalumnos.apellidopat AS 'Apell.Pat', 
				tblalumnos.apellidomat AS 'Apell.Mat'
			 FROM   
				tblalumnos, 
				tblegresados, 
				tblespecialidad, 
				tblcarrera
			WHERE 
				tblalumnos.ncontrol = tblegresados.ncontroleg AND 
				tblespecialidad.cvespecialidad = tblegresados.especialidadeg AND 
				tblcarrera.cvcarrera = tblalumnos.carrera 
			ORDER BY tblegresados.anioegreso, tblcarrera.carrera, tblespecialidad.especialidad";
	
		} else {		// sino.... entonces se mostraran solos los registros de los alumnos egresados en el año #### (ej. 2010)
	
			$query = 
			"SELECT 
				tblegresados.anioegreso AS 'Año', 
				tblcarrera.carrera AS 'Carrera', 
				tblespecialidad.especialidad AS 'Especialidad', 
				tblegresados.ncontroleg AS 'No.Control', 
				tblalumnos.nombre AS 'Nombre', 
				tblalumnos.apellidopat AS 'Apell.Pat', 
				tblalumnos.apellidomat AS 'Apell.Mat'
			 FROM   
				tblalumnos, 
				tblegresados, 
				tblespecialidad, 
				tblcarrera
			WHERE 
				tblalumnos.ncontrol = tblegresados.ncontroleg AND 
				tblespecialidad.cvespecialidad = tblegresados.especialidadeg AND 
				tblcarrera.cvcarrera = tblalumnos.carrera  AND 
				tblegresados.anioegreso = '".$anio_egreso."' 
			ORDER BY tblcarrera.carrera, tblespecialidad.especialidad, tblegresados.anioegreso DESC";
		}
		
		$dtResult = mysql_query($query, $this->link);
		
		if(!$dtResult) {
			$this->error = true;
			$this->messageError = "Error: ".mysql_errno($link).", ".mysql_error($link);
			return false;
		}
		
		
		$this->dtEgresados = $dtResult;

		//----------------------------------------------------------------------------------------------------------
		$nColumns = mysql_num_fields($dtResult);	 // obtiene numero de columnas (campos de tabla)
		$nfilas = mysql_num_rows($dtResult);	 // obtiene numero de registros

		$headers = array();
		$data = array();

		if ($nfilas>0)	{
			for($i=0; $i<$nColumns; $i++) {
				$field = mysql_fetch_field($dtResult,$i);
				$headers[$i] = $field->name;
			}
			for($j=0; $j<$nfilas; $j++) {
				$data[$j] = mysql_fetch_array($dtResult);
			}
		}
		//----------------------------------------------------------------------------------------------------------
		
		$datos = array();	// almacena cada fila de datos en un arreglo
		
		$datos[0] = $headers;
		$datos[1] = $data;
		
		return $datos;
	}
	
	
}

?>