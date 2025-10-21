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
	function printTabla_ReportSeguimientosDeNucleoPorMunicipio() {
		
		// anchos de cada celda de la tabla
		$w = array(17,100,20,140);

		// - - - - - - titulo de tabla
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0,125,63);
		$this->SetFont('','B',12);
		$this->Cell(array_sum($w),8, utf8_decode('Reporte de Seguimientos de Nucleos  XXX - NombreNucleo').$anio,0,0,'C',true);

		$this->Ln();

	    //Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,125,63);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,153,0);
		$this->SetLineWidth(0.1);
		$this->SetFont('Arial','B',8);
		
		// Encabezados de columnas
		$this->Cell($w[0], 7, "ACT. ", 1, 0, 'C', 1);
		$this->Cell($w[1], 7, "DESCRIPCION", 1, 0, 'C', 1);
		$this->Cell($w[2], 7, "FECHA", 1, 0, 'C', 1);
		$this->Cell($w[3], 7, "COMENTARIOS", 1, 0, 'C', 1);
		
		$this->Ln();
	    
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0);
		$this->SetFont('','',7);

		$h = 10; // cell height

		$fill = false;
		$this->Cell($w[0], $h, "1", 'LRB', 0, 'C', $fill);
		$this->Cell($w[1], $h, "ACTIVIDAD UNO", 'LRB', 0, 'L', $fill);
		$this->Cell($w[2], $h, "2012/02/15", 'LRB', 0, 'L', $fill);
		$this->Cell($w[3], $h, "AQUI EL COMENTARIO SOBRE EL SEGUIMIENTO EN CUESTION", 'LRB', 0, 'L', $fill);
		$this->Ln();

		$fill = false;
		$this->Cell($w[0], $h, "1", 'LRB', 0, 'C', $fill);
		$this->Cell($w[1], $h, "ACTIVIDAD UNO", 'LRB', 0, 'L', $fill);
		$this->Cell($w[2], $h, "2012/02/15", 'LRB', 0, 'L', $fill);
		$this->Cell($w[3], $h, "AQUI EL COMENTARIO SOBRE EL SEGUIMIENTO EN CUESTION", 'LRB', 0, 'L', $fill);
		$this->Ln();
	}
	function printTabla_ReportSeguimientosDeNucleo() {
		
		// anchos de cada celda de la tabla
		$w = array(17,100,20,140);
		
		$this->setStyleForTitle();	// estilo para titulo de tabla
		$this->Cell(array_sum($w),8, utf8_decode('Reporte de Seguimientos del Nucleo XXX - NombreNucleo').$anio,0,0,'C',true);

		$this->Ln();
	   $this->setStyleForHeaders();	// estilo para headers
		
		// imprime HEADERS
		$this->Cell($w[0], 7, "ACT. ", 1, 0, 'C', 1);
		$this->Cell($w[1], 7, "DESCRIPCION", 1, 0, 'C', 1);
		$this->Cell($w[2], 7, "FECHA", 1, 0, 'C', 1);
		$this->Cell($w[3], 7, "COMENTARIOS", 1, 0, 'C', 1);
		
		$this->Ln();
		$this->setStyleForDataRows();	// estilo para filas de datos

		$h = 10; // cell height

		$fill = false;
		$this->Cell($w[0], $h, "1", 'LRB', 0, 'C', $fill);
		$this->Cell($w[1], $h, "ACTIVIDAD UNO", 'LRB', 0, 'L', $fill);
		$this->Cell($w[2], $h, "2012/02/15", 'LRB', 0, 'L', $fill);
		$this->Cell($w[3], $h, "AQUI EL COMENTARIO SOBRE EL SEGUIMIENTO EN CUESTION", 'LRB', 0, 'L', $fill);
		$this->Ln();

		$fill = false;
		$this->Cell($w[0], $h, "1", 'LRB', 0, 'C', $fill);
		$this->Cell($w[1], $h, "ACTIVIDAD UNO", 'LRB', 0, 'L', $fill);
		$this->Cell($w[2], $h, "2012/02/15", 'LRB', 0, 'L', $fill);
		$this->Cell($w[3], $h, "AQUI EL COMENTARIO SOBRE EL SEGUIMIENTO EN CUESTION", 'LRB', 0, 'L', $fill);
		$this->Ln();
	}

	function mostrarCuadricula($orientacion) {
		
		if($orientacion=='V') {
			$mm_width = round($this->w);		// obtiene ancho de la hoja actual en mm
			$mm_height = round($this->h);		// obtiene alto de la hoja actual en mm
		
			$mm_height_or = $mm_height;
		
			$style = '';
			$fill = true;

			for($y=0; $y<$mm_height; $y++) {

				for($x=0; $x<$mm_width; $x++) {
		
					if($fill){
						$this->SetFillColor(220,220,220);
					} else {
						$this->SetFillColor(255,255,255);
					}
					$fill = !$fill;
			
					$this->Rect($x,$y,1,1,'F');
				}
				$fill = !$fill;
			}	
		}
		if($orientacion=='H') {
			$mm_width = round($this->w);		// obtiene ancho de la hoja actual en mm
			$mm_height = round($this->h);		// obtiene alto de la hoja actual en mm
		
			$mm_height_or = $mm_height;
		
			$style = '';
			$fill = true;

			for($y=0; $y<$mm_height; $y++) {

				for($x=0; $x<$mm_width; $x++) {
		
					if($fill){
						$this->SetFillColor(220,220,220);
					} else {
						$this->SetFillColor(255,255,255);
					}
					$fill = !$fill;
			
					$this->Rect($x,$y,1,1,'F');
				}
			}	
		}
	}
	function mostrarMargen() {
		
		$x1 = $this->lMargin;
		$y1 = $this->tMargin;
		
		$w = $this->w - ($this->lMargin + $this->rMargin);
		$h = $this->h - ($this->tMargin + $this->bMargin);
		
		$this->Rect($x1,$y1,$w,$h,'D');
	}
	function imprimirPortada($fecha) {
		
		$this->SetXY(45,80);
		
		$this->SetTextColor(79,185,255);
		$this->SetFont('Arial','BI',18);
		$this->Cell(120,30, "Informe de Alumnos Egresados",0,2,'C', true);
		
		$this->SetTextColor(79,185,255);
		$this->SetFont('Arial','BI',14);		
		$this->Cell(120,30, "Fecha de egreso: ".$fecha,0,0,'C', false);
	}
	function printTablaEstatica() {
		
		// anchos de cada celda de la tabla
		$w = array(15,35,40,60,52,75);

		// - - - - - - titulo de tabla
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0,125,63);
		$this->SetFont('','B',12);
		$this->Cell(array_sum($w),8, utf8_decode('Alumnos Egresados en el año ').$anio,0,0,'C',true);

		$this->Ln();

	    //Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,125,63);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,153,0);
		$this->SetLineWidth(0.1);
		$this->SetFont('Arial','B',8);
		
		// Encabezados de columnas
		$this->Cell($w[0], 7, "No. ", 1, 0, 'C', 1);
		$this->Cell($w[1], 7, "MUNICIPIO", 1, 0, 'C', 1);
		$this->Cell($w[2], 7, "NUCLEO AGRARIO", 1, 0, 'C', 1);
		$this->Cell($w[3], 7, "ETAPA MAXIMA", 1, 0, 'C', 1);
		$this->Cell($w[4], 7, "PROBLEMATICA", 1, 0, 'C', 1);
		$this->Cell($w[5], 7, "COMPROMISOS Y SITUACION DE CUMPLIMIENTO", 1, 0, 'C', 1);
		
		$this->Ln();
	    
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0);
		$this->SetFont('','',7);

		$h = 7; // cell height

		$fill = false;
		$this->Cell($w[0], $h, "1.", 'LR', 0, 'C', $fill);
		$this->Cell($w[1], $h, "HUEJUTLA DE REYES ", 'LR', 0, 'L', $fill);
		$this->Cell($w[2], $h, "ATLALCO Y TEPEOLOL II", 'LR', 0, 'L', $fill);
		$this->Cell($w[3], $h, "INCORPORADO AL FANAR EL 11/NOV/2012", 'LR', 0, 'L', $fill);
		$this->Cell($w[4], $h, "SIN PROBLEMATICA", 'LR', 0, 'L', $fill);
		$this->Cell($w[5], $h, "CERTIFICACION POR PARTE DEL RAN", 'LR', 0, 'L', $fill);
		$this->Ln();

		$fill = true;
		$this->Cell($w[0], $h, "1.", 'LR', 0, 'C', $fill);
		$this->Cell($w[1], $h, "HUEJUTLA DE REYES", 'LR', 0, 'L', $fill);
		$this->Cell($w[2], $h, "ATLALCO Y TEPEOLOL II", 'LR', 0, 'L', $fill);
		$this->Cell($w[3], $h, "INCORPORADO AL FANAR EL 11/NOV/2012", 'LR', 0, 'L', $fill);
		$this->Cell($w[4], $h, "SIN PROBLEMATICA", 'LR', 0, 'L', $fill);
		$this->Cell($w[5], $h, "CERTIFICACION POR PARTE DEL RAN", 'LR', 0, 'L', $fill);
		$this->Ln();

	}
	function printTablaEstatica2() {
		
		$w = array(23,24,40,30,80,80);

		// - - - - - - titulo de tabla
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0,125,63);
		$this->SetFont('','B',12);
		$this->Cell(array_sum($w),8,'Alumnos Egresados en el año '.$anio,0,0,'C',true);

		$this->Ln();

	    //Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,125,63);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,153,0);
		$this->SetLineWidth(0.1);
		$this->SetFont('Arial','B',9);
		
		// Encabezados de columnas
		$this->Cell($w[0], 7, "No. Control", 1, 0, 'C', 1);
		$this->Cell($w[1], 7, "Especialidad", 1, 0, 'C', 1);
		$this->Cell($w[2], 7, "Apellidos", 1, 0, 'C', 1);
		$this->Cell($w[3], 7, "Nombre", 1, 0, 'C', 1);
		$this->Cell($w[4], 7, "Domicilio", 1, 0, 'C', 1);
		$this->Cell($w[5], 7, "Empresa", 1, 0, 'C', 1);
		
		$this->Ln();
	    
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0);
		$this->SetFont('');

//		$num_registros = count($dtEgresados);
		
		$fill = false;
//		for($c=0; $c<$num_registros; $c++) {
//			// Datos
//			$this->Cell($w[0], 5, $dtEgresados[$c]['nControl'], 'LR', 0, 'C', $fill);
//			$this->Cell($w[1], 5, utf8_decode($dtEgresados[$c]['espec']), 'LR', 0, 'C', $fill);
//			$this->Cell($w[2], 5, utf8_decode($dtEgresados[$c]['apePat']." ".$dtEgresados[$c]['apeMat']), 'LR', 0, 'L', $fill);
//			$this->Cell($w[3], 5, utf8_decode($dtEgresados[$c]['nombre']), 'LR', 0, 'L', $fill);
//			$this->Cell($w[4], 5, utf8_decode($dtEgresados[$c]['domic']), 'LR', 0, 'L', $fill);
//			$this->Cell($w[5], 5, utf8_decode($dtEgresados[$c]['empr']), 'LR', 0, 'C', $fill);
//			$this->Ln();
//			$fill = !$fill;
//		}
//		$this->Cell(array_sum($w),0,'','T');
	}
	function printTabla($dtEgresados,$anio) {
		
		$w = array(23,24,40,30,80,80);

		// - - - - - - titulo de tabla
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0,125,63);
		$this->SetFont('','B',12);
		$this->Cell(array_sum($w),8,'Alumnos Egresados en el año '.$anio,0,0,'C',true);

		$this->Ln();

	    //Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,125,63);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,153,0);
		$this->SetLineWidth(0.1);
		$this->SetFont('Arial','B',9);
		
		// Encabezados de columnas
		$this->Cell($w[0], 7, "No. Control", 1, 0, 'C', 1);
		$this->Cell($w[1], 7, "Especialidad", 1, 0, 'C', 1);
		$this->Cell($w[2], 7, "Apellidos", 1, 0, 'C', 1);
		$this->Cell($w[3], 7, "Nombre", 1, 0, 'C', 1);
		$this->Cell($w[4], 7, "Domicilio", 1, 0, 'C', 1);
		$this->Cell($w[5], 7, "Empresa", 1, 0, 'C', 1);
		
		$this->Ln();
	    
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0);
		$this->SetFont('');

		$num_registros = count($dtEgresados);
		
		$fill = false;
		for($c=0; $c<$num_registros; $c++) {
			// Datos
			$this->Cell($w[0], 5, $dtEgresados[$c]['nControl'], 'LR', 0, 'C', $fill);
			$this->Cell($w[1], 5, utf8_decode($dtEgresados[$c]['espec']), 'LR', 0, 'C', $fill);
			$this->Cell($w[2], 5, utf8_decode($dtEgresados[$c]['apePat']." ".$dtEgresados[$c]['apeMat']), 'LR', 0, 'L', $fill);
			$this->Cell($w[3], 5, utf8_decode($dtEgresados[$c]['nombre']), 'LR', 0, 'L', $fill);
			$this->Cell($w[4], 5, utf8_decode($dtEgresados[$c]['domic']), 'LR', 0, 'L', $fill);
			$this->Cell($w[5], 5, utf8_decode($dtEgresados[$c]['empr']), 'LR', 0, 'C', $fill);
			$this->Ln();
			$fill = !$fill;
		}
		$this->Cell(array_sum($w),0,'','T');
	}
	function printTablaDinamica_BACKUP($data, $headers, $anio) {

		$w = array(20,40,35,20,30,30,30,70,50,50,50,50,50);		// el array podria tener mas elementos de los necesarios.
		$w_acum = 0;
		
		$align = array('L','L','L','L','C','C','C','L','L','L','L','L','L');		// el array podria tener mas elementos de los necesarios.

		// - - - - - - titulo de tabla
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0,125,63);
		$this->SetFont('','B',12);

		for($i=0; $i<count($headers); $i++) {
			$w_acum += $w[$i];
		}
		
		
		$tabla_x = ($this->w/2) - ($w_acum/2);
		
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla
		
		$this->Cell($w_acum,8,"Alumnos Egresados en el ".utf8_decode("año")." ".$anio,0,0,'C',true);		// celda del titulo de tabla

		$this->ln();
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla

	    //Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,125,63);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,153,0);
		$this->SetLineWidth(0.1);
		$this->SetFont('Arial','B',9);
		
		for($i=0; $i<count($headers); $i++) {
			$this->Cell($w[$i], 7, utf8_decode(utf8_encode($headers[$i])), 1, 0, 'C', 1);
		}
		
		$this->ln();
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla
	    
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0);
		$this->SetFont('');

		$num_registros = count($data);
		
		//---------------------------------------------------------------
		
		/*
		$tmp_x = $this->getX();
		$tmp_y = $this->getY();
		
		$this->setXY(60,30);
		
		$this->Cell(30,6,$num_registros." registros",1,0,'C',true);
		
		for($c=0; $c<$num_registros; $c++) {
			$this->Cell(20, 6, utf8_decode($data[$c][4]), 0, 0, 'C', true);
		}
		
		
		$this->setXY($tmp_x,$tmp_y);
		*/
		
		//---------------------------------------------------------------
		
		$fill = false;
		
		for($c=0; $c<$num_registros; $c++) {
			// Datos
			for($i=0; $i<count($headers); $i++) {
				$this->Cell($w[$i], 5, "".utf8_decode(utf8_encode($data[$c][$i])), 'LR', 0, $align[$i], $fill);
			}
			$this->ln();
			$this->setX($tabla_x);	// posicion x donde se colocara la tabla
			$fill = !$fill;
		}
		$this->Cell($w_acum,0,'','T');
	}
	function printTablaDinamica_BACKUP2($data, $headers, $anio) {

		$w = array(15,45,35,20,30,30,30,70,50,50,50,50,50);		// el array podria tener mas elementos de los necesarios.
		$w_acum = 0;
		
		$align = array('C','C','C','L','C','C','C','L','L','L','L','L','L');		// el array podria tener mas elementos de los necesarios.

		// - - - - - - titulo de tabla
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0,125,63);
		$this->SetFont('','B',12);

		for($i=0; $i<count($headers); $i++) {
			$w_acum += $w[$i];
		}
		
		
		$tabla_x = ($this->w/2) - ($w_acum/2);
		
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla
		
		$this->Cell($w_acum,8,"Alumnos Egresados en el ".utf8_decode("año")." ".$anio,0,0,'C',true);		// celda del titulo de tabla

		$this->ln();
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla

	    //Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,125,63);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,153,0);
		$this->SetLineWidth(0.1);
		$this->SetFont('Arial','B',9);
		
		for($i=0; $i<count($headers); $i++) {
			$this->Cell($w[$i], 7, utf8_decode(utf8_encode($headers[$i])), 1, 0, 'C', 1);
		}
		
		$this->ln();
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla
	    
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0);
		$this->SetFont('');

		$num_registros = count($data);
		
		//---------------------------------------------------------------
		
		/*
		$tmp_x = $this->getX();
		$tmp_y = $this->getY();
		
		$this->setXY(60,30);
		
		$this->Cell(30,6,$num_registros." registros",1,0,'C',true);
		
		for($c=0; $c<$num_registros; $c++) {
			$this->Cell(20, 6, utf8_decode($data[$c][4]), 0, 0, 'C', true);
		}
		
		
		$this->setXY($tmp_x,$tmp_y);
		*/
		
		//---------------------------------------------------------------
		
		$fill = false;
		
		for($c=0; $c<$num_registros; $c++) {
			// Datos
//			for($i=0; $i<count($headers); $i++) {
//				$this->Cell($w[$i], 5, "".utf8_decode(utf8_encode($data[$c][$i])), 'LR', 0, $align[$i], $fill);
//			}
			$this->Cell($w[0], 5, "".$data[$c][0], 'LR', 0, $align[0], $fill);
			$this->Cell($w[1], 5, "".utf8_decode(utf8_encode($data[$c][1])), 'LR', 0, $align[1], $fill);
			$this->Cell($w[2], 5, "".utf8_decode(utf8_encode($data[$c][2])), 'LR', 0, $align[2], $fill);
			$this->Cell($w[3], 5, "".$data[$c][3], 'LR', 0, $align[3], $fill);
			$this->Cell($w[4], 5, "".utf8_decode($data[$c][4]), 'LR', 0, $align[4], $fill);
			$this->Cell($w[5], 5, "".utf8_decode($data[$c][5]), 'LR', 0, $align[5], $fill);
			$this->Cell($w[6], 5, "".utf8_decode($data[$c][6]), 'LR', 0, $align[6], $fill);
						
			$this->ln();
			$this->setX($tabla_x);	// posicion x donde se colocara la tabla
			$fill = !$fill;
		}
		$this->Cell($w_acum,0,'','T');
	}
	function printTablaDinamica_small($data, $headers, $anio) {

		$w = array(15,45,35,25,70,30,30,70,50,50,50,50,50);		// el array podria tener mas elementos de los necesarios.
		$w_acum = 0;
		
		$align = array('C','C','C','C','L','C','C','L','L','L','L','L','L');		// el array podria tener mas elementos de los necesarios.

		$columns_names = array("Año", "Carrera", "Especialidad", "No.Control", "Nombre");


		// - - - - - - titulo de tabla
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0,125,63);
		$this->SetFont('','B',12);

		for($i=0; $i<count($columns_names); $i++) {
			$w_acum += $w[$i];
		}
		
		$tabla_x = ($this->w/2) - ($w_acum/2);
		
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla
		
		
		$titulo_tabla = "Alumnos Egresados".(($anio=="0000") ? "" : " en el ".utf8_decode("año")." ".$anio);
		
		$this->Cell($w_acum,8,$titulo_tabla,0,0,'C',true);		// celda del titulo de tabla

		$this->ln();
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla

	    //Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,125,63);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,153,0);
		$this->SetLineWidth(0.1);
		$this->SetFont('Arial','B',9);
		
		for($i=0; $i<count($columns_names); $i++) {
			$this->Cell($w[$i], 7, utf8_decode($columns_names[$i]), 1, 0, 'C', 1);
		}
		
		$this->ln();
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla
	    
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0);
		$this->SetFont('');

		$num_registros = count($data);

		$fill = false;
		
		for($c=0; $c<$num_registros; $c++) {
			$this->Cell($w[0], 5, "".$data[$c][0], 'LR', 0, $align[0], $fill);
			$this->Cell($w[1], 5, "".utf8_decode(utf8_encode($data[$c][1])), 'LR', 0, $align[1], $fill);
			$this->Cell($w[2], 5, "".utf8_decode(utf8_encode($data[$c][2])), 'LR', 0, $align[2], $fill);
			$this->Cell($w[3], 5, "".$data[$c][3], 'LR', 0, $align[3], $fill);
			
			$nombre = utf8_decode($data[$c][4]." ".$data[$c][5]." ".$data[$c][6]);
			
			$this->Cell($w[4], 5, $nombre, 'LR', 0, $align[4], $fill);
						
			$this->ln();
			$this->setX($tabla_x);	// posicion x donde se colocara la tabla
			$fill = !$fill;
		}
		$this->Cell($w_acum,0,'','T');
	}
	
	function printTablaDinamica_($data, $headers, $title) {
		
		$nCols = count($headers);
		$nRows = count($data);

		$wT = $this->GetStringWidth($title);	// ancho del titulo
		$w = array();	$w_acum = 0;
		$wf = array();	$wf_acum = 0;

		// ancho de cada columna de la tabla basandose en el texto de encabezado de columnas
		for($c=0; $c<$nCols; $c++){
			$w[$c] = $this->GetStringWidth($headers) + 6;
			$w_acum += $w[$c];
		}
		
		// comparacion del contenido mas largo de cada columna de dato
		for($i=0; $i<$nRows; $i++){
			for($j=0; $j<$nCols; $j++){
				$sw = $this->GetStringWidth($data[$i][$j]);
				if($sw>$wf[j]){
					$wf[$j] = $sw;
				}
			}
		}
		// ancho que tendria la tabla basandose en el ancho de contenido de cada columna de datos
		for($i=0; $i<$nCols; $i++) {
			$wf_acum += $wf[$i];
		}
		
		/*
		for($c=0; $c<$nCols; $c++){
			if($w[$c]<$wf[$c]){
				$w[$c] = $wf[$c];
			}
		}
		*/

		//$align = array('C','C','C','L','C','C','C','L','L','L','L','L','L');		// el array podria tener mas elementos de los necesarios.

		// - - - - - - estilo para celda donde que tendra el titulo de tabla
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0,125,63);
		$this->SetFont('','B',12);

		//for($i=0; $i<count($headers); $i++) {
		//	$w_acum += $w[$i];
		//}
		
		$tabla_x = ($this->w/2) - ($w_acum/2);	// posicion x de la tabla
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla
		$this->Cell($wT, 8, $title, 0, 0, 'C', true);		// celda del titulo de tabla
		$this->ln();
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla

	    //Colores, ancho de línea y fuente en negrita	(NOMBRES DE CADA COLUMNA)
		$this->SetFillColor(0,125,63);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,153,0);
		$this->SetLineWidth(0.1);
		$this->SetFont('Arial','B',9);
		
		for($i=0; $i<$nCols; $i++) {
			$this->Cell($w[$i], 7, $headers[$i], 1, 0, 'C', 1);
		}
		
		$this->ln();
		$this->setX($tabla_x);	// posicion x donde se colocara la tabla
	   
		return; 
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0);
		$this->SetFont('');

		$num_registros = count($data);
		
		//---------------------------------------------------------------
		
		/*
		$tmp_x = $this->getX();
		$tmp_y = $this->getY();
		
		$this->setXY(60,30);
		
		$this->Cell(30,6,$num_registros." registros",1,0,'C',true);
		
		for($c=0; $c<$num_registros; $c++) {
			$this->Cell(20, 6, utf8_decode($data[$c][4]), 0, 0, 'C', true);
		}
		
		
		$this->setXY($tmp_x,$tmp_y);
		*/
		
		//---------------------------------------------------------------
		
		$fill = false;
		
		for($c=0; $c<$num_registros; $c++) {
			// Datos
//			for($i=0; $i<count($headers); $i++) {
//				$this->Cell($w[$i], 5, "".utf8_decode(utf8_encode($data[$c][$i])), 'LR', 0, $align[$i], $fill);
//			}
			$this->Cell($w[0], 5, "".$data[$c][0], 'LR', 0, $align[0], $fill);
			$this->Cell($w[1], 5, "".utf8_decode(utf8_encode($data[$c][1])), 'LR', 0, $align[1], $fill);
			$this->Cell($w[2], 5, "".utf8_decode(utf8_encode($data[$c][2])), 'LR', 0, $align[2], $fill);
			$this->Cell($w[3], 5, "".$data[$c][3], 'LR', 0, $align[3], $fill);
			$this->Cell($w[4], 5, "".utf8_decode($data[$c][4]), 'LR', 0, $align[4], $fill);
			$this->Cell($w[5], 5, "".utf8_decode($data[$c][5]), 'LR', 0, $align[5], $fill);
			$this->Cell($w[6], 5, "".utf8_decode($data[$c][6]), 'LR', 0, $align[6], $fill);
						
			$this->ln();
			$this->setX($tabla_x);	// posicion x donde se colocara la tabla
			$fill = !$fill;
		}
		$this->Cell($w_acum,0,'','T');
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
			$newData[$i][0] = $c+1;		// asigna valor numerico incremental
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
	function printTesting() {
		
		$title = "hola mundo";
		
		
		$this->setX(100);
		
		// - - - - - - estilo para celda donde que tendra el titulo de tabla
		$this->SetFillColor(225,245,205);
		$this->SetTextColor(0,125,63);
		$this->SetFont('','B',12);
		
		$title_w = $this->GetStringWidth($title) + 10;	// ancho del titulo
		
		$this->Cell($title_w, 8, $title, 0, 0, 'C', true);		// celda del titulo de tabla
	}	
	function printTesting2($data) {
		$nRows = count($data);
		$nCols = count($data[0]);

		$this->SetFillColor(0,125,63);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,153,255);
		$this->SetFont('Arial','',9);	

		$cols_w = array();
		for($j=0; $j<$nCols; $j++){
			$cols_w[$j] = 0;
		}
		for($i=0; $i<$nRows; $i++){
			for($j=0; $j<$nCols; $j++){
				$cols_w[$j] = ($cols_w[$j]<$this->GetStringWidth($data[$i][$j])) ? $this->GetStringWidth($data[$i][$j]) : $cols_w[$j];	// ancho del titulo
			}
		}
		for($j=0; $j<$nCols; $j++){
			$cols_w[$j] += 6;
		}
		$table_w = array_sum($cols_w);
		$table_x = $this->getTablePosX($table_w);
		$this->setX($table_x);
		
		$row1 = array();
		$row1 = $data[0];
		for($j=0; $j<$nCols; $j++) {
			$this->Cell($cols_w[$j], 8, $row1[$j], 0, 'R', 'C', true);		// celda del titulo de tabla
		}
	}
	private function getTablePosX($table_width) {
		$pos_x = ($this->w/2) - ($table_width/2);
		return $pos_x;
	}
}
?>