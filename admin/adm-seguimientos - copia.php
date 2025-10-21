<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1"/>
<link rel="icon" type="image/png" sizes="16x16"  href="../img/favicon2.png">
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
	width:960px;
	margin:auto;
	background-color:#090;
	padding:10px;
}
.div-seg form {
	display:block;
	width:100%;
}
.div-seg form fieldset {
	float:left;
	padding:15px;

}
.div-seg form fieldset.fieldset1 {

}
.div-seg form fieldset.fieldset2 {

}
.div-seg form fieldset.fieldset3 {
	width:50%;
	
}
.div-seg form fieldset legend {
	
}
.div-seg form fieldset input {


}
.div-seg form fieldset label {
	margin:10px;
}
</style>
</head>
<body>
	<div class="layer-bloquer"></div>
	<div class="dialog1" id="dialog1">
		<form name="frmMunicipio">
			<input type="hidden" name="mode" value="nuevo">
			<table>
				<tbody>
					<tr><th>Nuevo Nucleo Agrario</th></tr>
					<tr><td>Id Nucleo Agrario</td></tr>
					<tr><td><input type="text" name="id" /></td></tr>
					<tr><td>Nombre para el Nucleo Agrario</td></tr>
					<tr><td><input type="text" name="id" /></td></tr>
					<tr><td>Empleado</td></tr>
					<tr><td>
						<select>
						<option>Fulanito Hernandez</option>
						<option>Perenganito Hernandez</option>
						<option>Sutanito Hernandez</option>
						<option>Alguien Hernandez</option>
						</select>
					</td></tr>
					<tr><td>Municipio</td></tr>
					<tr><td>
						<select>
						<option>Huejutla</option>
						<option>Huautla</option>
						<option>Atlapexco</option>
						</select>
					</td></tr>
					<tr><td>Propiedad</td></tr>
					<tr><td><input type="text" name="id" /></td></tr>
					<tr><td>Superficies</td></tr>
					<tr><td><input type="text" name="id" /></td></tr>
					<tr><td>Representantes</td></tr>
					<tr><td><input type="text" name="id" /></td></tr>
					<tr><td>Problematica</td></tr>
					<tr><td><input type="text" name="id" /></td></tr>
					<tr><td>Instituciones</td></tr>
					<tr><td><input type="text" name="id" /></td></tr>
					<tr><td>Resultado</td></tr>
					<tr><td><input type="text" name="id" /></td></tr>
					<tr><td>Activos</td></tr>
					<tr><td><input type="text" name="id" /></td></tr>
					<tr><td>Estrategia</td></tr>
					<tr><td><input type="text" name="id" /></td></tr>
					<tr><td><input type="button" name="guardar" value="Guardar" /><input type="button" name="cancelar" value="Cancelar" /></td></tr>
				</tbody>
			</table>
		</form>
	</div>
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
				<ul class="menu2" id="mnu-nucl">
					<li><a href="#" title="">Nuevo</a></li>
					<li><a href="#" title="">Editar</a></li>
					<li><a href="#" title="">Eliminar</a></li>
				</ul>
				<div style="clear:both;"></div>
				<div class="div-seg">
					<form class="formSeg">
						<fieldset class="fieldset1">
							<legend>Datos</legend>
							<label>Responsable: <input type="text" value="Hidalgo"></label>
							<label>Delegacion: <input type="text" value="Hidalgo"></label>
							<label>Residencia: <input type="text" value="Hidalgo"></label>
							<input type="image" src="../img/calendar.png" align="absmiddle">
							<input type="image" src="../img/BlocNotas.png" align="absmiddle"><br>
							<label>Municipio: <input type="text" value="Hidalgo"></label>
							<label>Nucleo Agrario: <input type="text" value="Hidalgo"></label>
							<label>Superficie: <input type="text" value="Hidalgo" size="10"></label>
							<label>Tipo: <input type="text" value="Hidalgo" size="10"></label>
						</fieldset>
						<fieldset class="fieldset2">
							<legend>Etapas</legend>
							<table>
								<tbody>
									<th>ETA</th>
									<th>ACT</th>
									<th>DESCRIPCION</th>
									<th>FECHA PROG</th>
								</tbody>
								<tbody>
									<tr>                           	
										<td>3</td>
										<td>30</td>
										<td>Inicio de los trabajos de medicion</td>
										<td>�</td>
									</tr>
									<tr>                           	
										<td>5</td>
										<td>51</td>
										<td>Convocatoria a Asamblea de aprobacion 1era. (PA)</td>
										<td>�</td>
									</tr>
									<tr>                           	
										<td>3</td>
										<td>30</td>
										<td>Inicio de los trabajos de medicion</td>
										<td>�</td>
									</tr>
									<tr>                           	
										<td>3</td>
										<td>30</td>
										<td>Inicio de los trabajos de medicion</td>
										<td>�</td>
									</tr>
									<tr>
										<td>3</td>
										<td>30</td>
										<td>Inicio de los trabajos de medicion</td>
										<td>�</td>
									</tr>
										<tr>
										<td>3</td>
										<td>30</td>
										<td>Inicio de los trabajos de medicion</td>
										<td>�</td>
									</tr>
								</tbody>
							</table>
						</fieldset>
						<fieldset class="fieldset3">
							<legend>Datos de la actividad</legend>
							<label>Actividad </label><input type="text" align="top">
							<label>Descripcion <input type="text"></label>
							<label>Fecha <input type="text"></label>
							<label>Observaciones <input type="text"></label>
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