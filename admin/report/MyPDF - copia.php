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
		$this->Image($logo2,$logo2_x, $logo2_y, $logo2_w);	//Logo derecho
		
		$this->SetFont('Times','BI',18);			//Arial bold 15
		$this->SetTextColor(0,0,255);
		
		$titulo = 'Instituto Tecnologico de Huejutla';
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
		$this->Image('imagenes/ii.jpg',160,270,'l');
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
	function printTablaDinamica_BAKUP($data, $headers, $anio) {

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
	function printTablaDinamica($data, $headers, $anio) {

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
}






?>