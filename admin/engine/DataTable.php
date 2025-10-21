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
	var $headers;
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
		$nColumns = $this->NumFields;
		$nRows = $this->NumRows;
		$html = '';
		$html_row = '';
		$html_table = '';
		if($nRows>0){
			for($i=0; $i<$nColumns; $i++) {
				$field = mysql_fetch_field($this->source,$i);
				$this->headers[$i] = $field->name;
			}
			for($c = 0; $c < $nRows; $c++){
				$row = mysql_fetch_array( $this->source, MYSQL_BOTH );
				$html_table .= $row[0].'.';
				$html_table .= $row[1].'.';
				$html_table .= $row[2].'<br>';
			}
		}
		$this->html = $html_table;
	}
	function setSourcce($source) {
		$this->source = $source;
		$this->NumRows = mysql_num_rows($source);
		$this->NumFields = mysql_num_fields($source);
		if($this->NumRows>0){
			
			$iSit = -1;
			
			for($i=0; $i<$this->NumFields; $i++) {
				$field = mysql_fetch_field($this->source,$i);
				$this->headers[$i] = $field->name;
				if($field->name=='SITUACION'){ $iSit=$i; }	// obtenemos el indice del header "SITUACION"
			}
			for( $i=0; $i<$this->NumRows; $i++ ) {
				$this->Rows[$i] = mysql_fetch_array( $this->source, MYSQL_BOTH );
				$text = '';
				switch($this->Rows[$i][$iSit]){
					case 0: $this->Rows[$i][$iSit] = 'inactivo'; break;
					case 1: $this->Rows[$i][$iSit] = 'en proceso'; break;
					case 2: $this->Rows[$i][$iSit] = 'completo'; break;
					default: $this->Rows[$i][$iSit] = 'desconocido'; break;
				}
			}
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
	function Reset() { $this->currentIndex = 0; }
	function getRow($index) { return $this->Rows[$index]; }
	function getHtmlTable() {
		$tr = "";
		if($this->NumRows>0){
			$tr ="<tr>";
			for($i=0; $i<$this->NumFields; $i++) {
				$tr .= "<th>".$this->headers[$i]."</th>";
			}
			$tr .="</tr>";
			for( $i=0; $i<$this->NumRows; $i++ ) {
				$tr .= "<tr>";
				for($j=0; $j<$this->NumFields; $j++) {
					$tr .= "<td>".$this->Rows[$i][$j]."</td>";
				}
				$tr .="</tr>";				
			}
		}
		$this->html = "<table border=1>".$tr."<table>";
		return $this->html;
	}
}
?>