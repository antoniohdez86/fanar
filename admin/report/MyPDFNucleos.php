<?php 

/*	Autor: Antonio de Jesus Hernandez Hernandez
 *	Nick: ajhh
 *	Mensaje:  dejando huella en el ITH
 *	email: solitario917@hotmail.com
 */
class PDF extends FPDF {
	
	//Cabecera de página
	function Header() {
		
		// datos de logo 1
		//$logo1 = 'imagenes/director.jpg';
		$logo1 = '../../img/ProcAgr2.jpg';
		$logo1_w = 33;
		$logo1_x = $this->lMargin;
		$logo1_y = $this->tMargin;

		// datos de logo 1
		//$logo2 = 'imagenes/dgest1.jpg';
		$logo2 = '../../img/SRA.jpg';
		$logo2_w = 33;
		$logo2_x = $this->w - ($this->rMargin + $logo2_w);
		$logo2_y = $this->tMargin;
		
		// colocacion de logos
		$this->Image($logo1, $logo1_x, $logo1_y, $logo1_w);	//Logo izquierdo
		$this->Image($logo2,$logo2_x, $logo2_y, $logo2_w);		//Logo derecho
		
		$this->SetFont('Arial','B',13);			//Arial bold 15
		$this->SetTextColor(0,0,0);
		
		$titulo = 'REPORTE DE PROBLEMATICA OPERATIVA DEL PROGRAMA FANAR';
		$titulo_w = $this->GetStringWidth($titulo) + 6;
		$titulo_x = ($this->w/2) - ($titulo_w/2);
		$titulo_y = $this->tMargin + 10;
				
		$this->SetXY($titulo_x,$titulo_y);
		
		$this->Cell($titulo_w,10,$titulo,0,0,'C',false);		//Título		
		$this->Ln(20);									//Salto de línea
	}
	//Pie de página
	function Footer() {
		$this->SetY(-20);
		$this->SetFont('Helvetica','I',8);
		$this->SetTextColor(0,150,0);
		$this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
		$this->Image('./ii.jpg',160,270,'l');
	}
	function mostrarMargen() {
		
		$x1 = $this->lMargin;
		$y1 = $this->tMargin;
		
		$w = $this->w - ($this->lMargin + $this->rMargin);
		$h = $this->h - ($this->tMargin + $this->bMargin);
		
		$this->Rect($x1,$y1,$w,$h,'D');
	}
	private function setStyleForTitle(){
		// - - - - - - titulo de tabla
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0,125,63);
		$this->SetFont('','B',12);
	}
	private function setStyleForHeaders(){
		//Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,125,63);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,153,0);
		$this->SetLineWidth(0.1);
		$this->SetFont('Arial','B',8);
	}
	private function setStyleForDataRows(){
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0);
		$this->SetFont('','',7);
	}

	private function addColumnToHeaders($headers, $text){
		$nCols = count($headers);
		$newHeaders = array();
		$newHeaders[0] = $text;
		for($c=0; $c<$nCols; $c++){
			$newHeaders[$c+1] = $headers[$c];
		}
		return $newHeaders;
	}
	private function addNumericToColumn($data) {
		$nRows = count($data);
		$nCols = count($data[0]);
		
		$newData = array();	// tabla nueva
		
		for($i=0; $i<$nRows; $i++){
			$newData[$i] = array();		// crea una nueva fila
			$newData[$i][0] = ($i+1).".-";		// asigna valor numerico incremental
			for($j=0; $j<$nCols; $j++){
				$newData[$i][$j+1] = $data[$i][$j];
			}
		}
		return $newData;
	}
	function printTablaDinamica($data, $headers, $title) {
		$w = array();		// ANCHO DE HEADERS DE CADA COLUMNA
		$wf = array();		// ANCHO DE CADA CELDA DE DATO DE CADA FILA
		$wo = array();		// ANCHO DE CAMPOS OPTIMIZADOS
		
		$table_w1 = 0;		// ancho de titulo
		$table_w2 = 0;		// suma de anchos de headers
		$table_w3 = 0;		// suma de anchos de celdas maximas
		$table_w4 = 0;		// suma de anchos de headers y celdas maximas(optimizados)
		
		$headers = $this->addColumnToHeaders($headers, "No.");	// agrega columna "NO."
		$data = $this->addNumericToColumn($data);						// agrega columna y asigna Valor incremental
		
		$nCols = count($headers);		// numero de columnas
		$nRows = count($data);			// numero de filas

		$this->setStyleForTitle();
		$table_w1 = $wT = $this->GetStringWidth($title) + 4;	// ancho del titulo
		
		$this->setStyleForHeaders();
		for($c=0; $c<$nCols; $c++){	// calculo del ancho de cada header
			$w[$c] = $this->GetStringWidth($headers[$c]) + 4;
		}
		$table_w2 = array_sum($w);
		
		$this->setStyleForDataRows();
		for($i=0; $i<$nCols; $i++){ $wf[j] = 0; } // inicializa con valores a 0
		for($i=0; $i<$nRows; $i++){	// calculo del celda de dato mas ancho
			for($j=0; $j<$nCols; $j++){
				$sw = $this->GetStringWidth($data[$i][$j]) + 4;
				$wf[$j] = $sw > $wf[$j] ? $sw : $wf[$j];
			}
		}
		$table_w3 = array_sum($wf);

		for($c=0; $c<$nCols; $c++){	// comparacion de anchos de headers y celdas datos
			$wo[$c] = $w[$c] > $wf[$c] ? $w[$c] : $wf[$c];
		}
		$table_w4 = array_sum($wo);
		
		$table_w = max($table_w1,$table_w2,$table_w3,$table_w4);
		if($table_w==$table_w1 && $table_w1 > $table_w4){
			$wr = $table_w1 - $table_w4;
			$i = count($wo);
			$wo[$i-1] += $wr;
		}
		// calculando posicion de tabla
		$tabla_x = $this->getTablePosX($table_w);
		$this->setX($tabla_x);		// posicion x donde se colocara la tabla
		
		$this->setStyleForTitle();	// ESTILO DE TITULO (YA PARA IMPRIMIR)
		$this->Cell($table_w, 8, $title, 0, 0, 'C', true);	// celda del titulo de tabla	<<<------
		$this->ln();												// salto de linea
		$this->setX($tabla_x);									// posicion x donde se colocara la tabla

		$this->setStyleForHeaders();	// ESTILO HEADERS (YA PARA IMPRIMIR)
		for($i=0; $i<$nCols; $i++) 
			$this->Cell($wo[$i], 7, $headers[$i], 1, 0, 'C', 1);
		$this->ln();
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla

		//return;
		// ESTILO FILAS (YA PARA IMPRIMIR)
		$this->setStyleForDataRows();
		for($i=0; $i<$nRows; $i++) {
			for($j=0; $j<$nCols; $j++) {
				$this->Cell($wo[$j], 7, $data[$i][$j], 1, 0, 'C', 1);
			}
			$this->ln();
			$this->setX($tabla_x);	// posicion x donde se colocara la tabla
		}
	}
	private function getTablePosX($table_width) {
		$pos_x = ($this->w/2) - ($table_width/2);
		return $pos_x;
	}
}
?>