<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1"/>
<link rel="stylesheet" href="../css/style-admin.css" type="text/css" media="screen" />
<script src="../js/jquery-1.7.1.min.js"></script>
<script src="../js/script-admin-seg.js"></script>
<style>
.dialog1 {
	behavior:url(../css/PIE/PIE.htc);
	position:absolute;
	display:inline-block;
	
	border:1px #0C0 solid;
   background-color:#030;
   display:none;
   z-index:999;
	
   /* aplica efecto redondeado */
	border-radius:8px;
   -webkit-border-radius:8px;
   -moz-border-radius:8px;
   
	color:#0F0;
	padding:5px;
}
.dialog1 table { }
.dialog1 table tr { }
.dialog1 table tr th { background-color:#060; }
.dialog1 table tr td { }
.dialog1 table tbody tr td:last-child { text-align:right; }
.dialog1 table input[type=text] { width:250px; }
.dialog1 table select { width:250px; }
.dialog1 table input[type=button] { border:1px #090 solid; margin:3px 0px; }

.layer-bloquer {
     position:fixed;
     width:100%;
     height:100%;
     z-index:998;
     opacity:0.5;
     background-color:#030;
     display:none;
}

/**************************/
.div-seg {
	width:100%;
	margin:auto;
	/*background-color:#090;*/
	background-color:#B0D8FF;
	padding:0px;
}
.div-seg form {
	display:block;
	width:100%;
}
.div-seg form fieldset {
	float:left;
	padding:10px;
     border:1px solid #060;

}
.div-seg form fieldset table {
	background-color:#FFF;	
}
.div-seg form fieldset table td.lbl {
	text-align:right;
}
.div-seg form fieldset.fieldset1 {

}
.div-seg form fieldset.fieldset2 {

}
/* tabla de etapas */
.div-seg form fieldset.fieldset2 table tr th {
	background-color:#099;
}
.div-seg form fieldset.fieldset2 table tr:hover td {
	background-color:#096;
}

.div-seg form fieldset.fieldset3 {
	width:50%;
}

.div-seg form fieldset.fieldset3 .tabla1 td {
	text-align:center;
}
.div-seg form fieldset.fieldset3 .tabla1 td input {

}
.div-seg form fieldset.fieldset3 .tabla1 td textarea {
	width:70%;
}

/* tabla de seguimientos registrados */
.div-seg form fieldset.fieldset3 .tabla2 tr th {
	padding:5px;
}
.div-seg form fieldset.fieldset3 .tabla2 tr td{
	
}
.div-seg form fieldset.fieldset3 .tabla2 tr:hover {
	background-color:#096;
}

.div-seg form fieldset legend {
	
}
.div-seg form fieldset input {


}
.div-seg form fieldset label {
	margin:10px 0px 10px 10px;
}
</style>
</head>
<body>
	<div class="layer-bloquer"></div>
	<div class="main-doc">
		<div class="header">
			<img class="logo1" src="../img/ProcAgr2.jpg" />
			<img class="logo2" src="../img/SRA.jpg" />
			<img class="logo3" src="../img/Imagen2.png" />
			<a href="#" class="lnk-logout" >cerrar sesion</a>
		</div>
		<div class="body">
			<div class="colIzq">
				<div class="gadget">
					<ul>
						<li><a href="index.php" title="">INICIO</a></li>
						<li><a href="adm-usuarios.php" title="">USUARIOS</a></li>
						<li><a href="adm-empleados.php" title="">EMPLEADOS</a></li>
						<li><a href="adm-municipios.php" title="">MUNICIPIOS</a></li>
						<li><a href="adm-nucleos.php" title="">NUCLEOS AGRARIOS</a></li>
						<li><a href="adm-seguimientos.php" title="">SEGUIMIENTOS</a></li>
						<li><a href="adm-reportes.php" title="">REPORTE DE PROBLEMATICA OPERATIVA DEL PROGRAMA FANAR 1012</a></li>
					</ul>
				</div>
			</div>
			<div class="colDer">
				<h2 class="title2">REGISTRO DE ACTIVIDADES</h2>
				<p class="parrafo1">
				Aqui un texto!!, aqui mas texto y por aqui otro mas y asi y asi sucesivamente
				hasta tener una cantidad considerable de caracteres para formar palabras 
				entendibles para todos los usuarios que entren al sitio, pero todavia canven 
				mas palabras!!.. ok entonces aqui van, esto es texto, esto es otro texto pero
				tambien voy a poner aqui este otro mas.
				</p>
				<div class="div-seg">
					<form class="formSeg">
						<fieldset class="fieldset1">
							<legend>Datos</legend>
                     <table>
                     <tr>
                     	<td class="lbl"><label>Responsable:</label></td>
                     	<td><input type="text" value=""></td>
                     </tr>
                     <tr>
                     	<td class="lbl"><label>Municipio:</label></td>
                        <td><input type="text" value=""></td>
                        <td class="lbl"><label>Nucleo Agrario: </label></td>
                        <td><input type="text" value=""></td>
                        <td class="lbl"><label>Superficie: </label></td>
                        <td><input type="text" value="" size="10"></td>
                        <td class="lbl"><label>Tipo: </label></td>
                        <td><input type="text" value="" size="10"></td>
                     </tr>
                     </table>
						</fieldset><div style="clear:both;"></div>
						<fieldset class="fieldset2">
							<legend>Etapas</legend>
							<table class="tbl-etapas">
								<tbody>
									<th>ETA</th>
									<th>ACT</th>
									<th>DESCRIPCION</th>
								</tbody>
								<tbody>
									<tr><td>?</td><td>1</td><td>------------------------------------------------</td></tr>
                           <tr><td>?</td><td>3</td><td>------------------------------------------------</td></tr>
                           <tr><td>?</td><td>20</td><td>------------------------------------------------</td></tr>
                           <tr><td>3</td><td>30</td><td>Inicio de los trabajos de medicion (RAN)</td></tr>
                           <tr><td>4</td><td>40</td><td>Termino de los trabajos de medicion (RAN)</td></tr>
                           <tr><td>5</td><td>50</td><td>Planos preliminares</td></tr>
									<tr><td>5</td><td>51</td><td>Convocatoria a Asamblea de aprobacion de planos 1era. (PA)</td></tr>
                           <tr><td>5</td><td>52</td><td>Convocatoria a Asamblea de aprobacion de planos 2era. (PA)</td></tr>
                           <tr><td>6</td><td>60</td><td>Asamblea de aprobacion de planos (PA)</td></tr>
                           <tr><td>7</td><td>70</td><td>Entrega de planos definitivos (RAN)</td></tr>
                           <tr><td>7</td><td>71</td><td>Convocatoria a ADDAT 1era. (PA)</td></tr>
                           <tr><td>7</td><td>72</td><td>Convocatoria a ADDAT 2era. (PA)</td></tr>
                           <tr><td>7</td><td>72</td><td>Convocatoria a ADDAT ulterior. (PA)</td></tr>
                           <tr><td>8</td><td>80</td><td>ADDAT (PA)</td></tr>
                           <tr><td>9</td><td>90</td><td>Ingreso expediente general RAN (PA)</td></tr>
                           <tr><td>9</td><td>91</td><td>Regresado (RAN)</td></tr>
                           <tr><td>9</td><td>92</td><td>Reingreso (PA)</td></tr>
                           <tr><td>10</td><td>100</td><td>Certificacion (RAN)</td></tr>
								</tbody>
							</table>
						</fieldset>
						<fieldset class="fieldset3">
							<legend>Datos de la actividad</legend>
                     <table border="0" width="100%" class="tabla1">
                     	<tbody>
                        	<tr>
	                           <td width="20%"><label>ACTIVIDAD</label></td>
   	                        <td width="60%"><label>DESCRIPCION</label></td>
      	                     <td width="20%"><label>FECHA</label></td>
                           </tr>
                        	<tr>
	                           <td><input type="text" size="8"></td>
   	                        <td><input type="text" size="42"></td>
      	                     <td><input type="text" size="10"></td>
                           </tr>
                        	<tr>
	                           <td colspan="3">OBSERVACIONES<textarea></textarea></td>
                           </tr>
                        	<tr>
	                           <td colspan="3"><input type="button" value="Agregar"></td>
                           </tr>
                        </tbody>
                     </table><br>
                     <table border="1" class="tabla2">
                     	<tbody>
                        	<tr>
										<th>ACTIVIDAD</th>
										<th>FECHA</th>
										<th>VISITADOR</th>
         	                  <th>OBSERVACIONES</th>
                           </tr>
                        </tbody>
                     	<tbody>
                        	<tr>
										<td>1</td>
										<td>18/11/2011</td>
         	                  <td>6703</td>
										<td>con esta fecha se llevo a cabo la reunion de infor</td>
                           </tr>
                        	<tr>
										<td>3</td>
										<td>18/11/2011</td>
         	                  <td>6703</td>
										<td>con esta fecha los organos de representacion y vig.</td>
                           </tr>
                        	<tr>
										<td>20</td>
										<td>18/11/2011</td>
         	                  <td>6703</td>
										<td>con esta fecha el nucleo agrario celebro asamblea</td>
                           </tr>
                        </tbody>
                     </table>
						</fieldset>
					</form>
					<div style="clear:both;"></div>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div class="footer"></div>
	</div>
</body>
</html>