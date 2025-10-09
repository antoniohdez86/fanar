<?php

//	DEFINE UNA VARIABLE QUE PUEDEN UTILIZAR LOS ARCHIVOS EXTERNOS PARA COMPROBAR SI YA HAN INCLUIDO ESTAS LIBRERIAS Y SINO PUES SE INCLUYEN
$LIBRARY_DATA_TABLE = true;

/*
 *	DataTable	:	Clase que crea una tabla logica
 *
 *	-- metodos --
 *
 *	DataTable()		: Constructor
 *	setSource()		: Recibe como recurso o fuente de datos el resultado de la consulta a una BD, y la coleccion de filas de datos a partir de estos.
 *	getNumRows()	: devuelve el numero de filas o registro de la tabla (numero de elementos de la coleccion de filas)
 *	Next()			: por cada llamada a este metodo devuelve el siguiente elemento (fila de datos) de la ultimo elemento devuelto (la primera llamada devuelve el primer elemento, la segunda llamada devuelve el segundo y asi sucesivamente).
 *	Reset()			: reinicia el indice interno para el recorrido de los elementos filas de datos.
 *	getRow()		: devuelve el elemento o fila de datos de indice especifico
 *
 **/

class DataTable {
	
		var $source;
		var $Rows;
		var $NumRows;
		var $NumFields;
		var $currentIndex;
		var $Row;
		
		var $html;
		
		function DataTable() {
				$this->Rows = 0;
				$this->NumRows = 0;
				$this->NumFields = 0;
				$this->currentIndex = 0;
				$this->Rows = array();
		}
		
		function setSourcce_v2($source) {
				$this->source = $source;
				$this->NumRows = mysql_num_rows($source);
				$this->NumFields = mysql_num_fields($source);
				
				//for( $i=0; $i<$this->NumRows; $i++ ) {
				//		$this->Rows[$i] = mysql_fetch_array( $this->source, MYSQL_BOTH );
				//}
				//--------------------------------------------------------------------------
				
				$nColumns = $this->NumFields;
				$nRows = $this->NumRows;
			
				$html = '';
				$html_row = '';
				$html_table = '';

				for($c = 0; $c < $nRows; $c++){
					
					$row = mysql_fetch_array( $this->source, MYSQL_BOTH );
					$html_table .= $row[0].'.';
					$html_table .= $row[1].'.';
					$html_table .= $row[2].'<br>';
				}

				/*
				for($c = 0; $c < $nRows; $c++){
					$html_row = '';
					for($i = 0; $i < $nRows; $i++){
						$row = mysql_fetch_array( $this->source, MYSQL_BOTH );
						$html_row .= $row[$i].'.';
					}
					$html_table .= $html_row.'<br>';
				}*/
				$this->html = $html_table;
		}
		function setSourcce($source) {
				$this->source = $source;
				$this->NumRows = mysql_num_rows($source);
				$this->NumFields = mysql_num_fields($source);
				for( $i=0; $i<$this->NumRows; $i++ ) {
						$this->Rows[$i] = mysql_fetch_array( $this->source, MYSQL_BOTH );
				}
		}
		function getNumRows() { return count($this->Rows); }
		function Next() {
				if(!$this->NumRows) return 0;
				if( $this->currentIndex < $this->NumRows ) {
						$i = $this->currentIndex;
						$this->currentIndex++;
						return $this->Row = $this->Rows[$i];
				}
				return 0;
		}
		function Reset() {
				$this->currentIndex = 0;
		}
		function getRow($index) {
				return $this->Rows[$index];
		}
		function getHtmlTable() {
			
			$nColumns = $this->NumFields;
			$nRows = $this->NumRows;
			
			$html = '';
			$html_campo = '';
			$html_row = '';
			
			for($c = 0; $c < $nRows; $c++){
				for($i = 0; $i < $nRows; $i++){
					$html_campo = '';
					$html_campo .= $c;
				}
			}
			
		}
}
class Persona{
	var $nombre;
	var $apellido;
	var $edad;
	
	function registrar(){}
	function mostrarDatos(){}
	function eliminar(){}
	function establecerNombre($nombre){
		$this->nombre = $nombre;
	}
	function obtenerEdad(){
		return $this->edad;
	}
	
}
?>