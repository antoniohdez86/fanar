<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1"/>
<link rel="icon" type="image/png" sizes="16x16"  href="../img/favicon2.png">
<link rel="stylesheet" href="../css/style-admin.css" type="text/css" media="screen" />
<script src="../js/jquery-1.7.1.min.js"></script>
<script src="../js/script-admin-nucl.js"></script>
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
.dialog1 table tr td.label { padding-top:8px; }
.dialog1 table tr td.input { padding-bottom:0px }
.dialog1 table tr td.label-rdo { padding-top:8px; }
.dialog1 table tbody tr td.buttons { text-align:right; }
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
</style>
</head>
<body>
	<div class="layer-bloquer"></div>
	<div class="dialog1" id="dialog1">
		<form name="frmNucleo">
			<input type="hidden" name="mode" value="nuevo">
			<table>
				<tbody>
					<tr><th>Nuevo Nucleo Agrario</th></tr>
					<tr><td class="label">Id Nucleo Agrario</td></tr>
					<tr><td class="input"><input type="text" name="id" /></td></tr>
					<tr><td class="label">Nombre para el Nucleo Agrario</td></tr>
					<tr><td class="input"><input type="text" name="id" /></td></tr>
               <tr><td class="label-rdo">Tipo: <input type="radio" name="tipo" value="comunidad" />Comunidad  <input type="radio" name="tipo" value="ejido" />Ejido</td></tr>
					<tr><td class="label">Perteneciente al Municipio de:</td></tr>
					<tr><td class="select">
						<select>
						<option value="200">Huejutla</option>
						<option value="201">Huautla</option>
						<option value="203">Atlapexco</option>
						</select>
					</td></tr>
					<tr><td class="label">Superficie</td></tr>
					<tr><td class="input"><input type="text" name="id" /></td></tr>
					<tr><td class="buttons"><input type="button" name="guardar" value="Guardar" /><input type="button" name="cancelar" value="Cancelar" /></td></tr>
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
				<h2 class="title2">Administracion de Nucleos Agrarios</h2>
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
				<div>
					<table class="tbl-nucleos">
						<tbody class="tbHead">
							<tr>
								<th>Codigo</th>
								<th>Nucleo Agrario</th>
								<th>Tipo</th>
								<th>Superficie</th>
								<th>Municipio</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                        <th>Seleccionar</th>
							</tr>
						</tbody>
						<tbody class="tbBody">
							<tr>
								<td colspan="8" class="loading">Cargando...</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div class="footer"></div>
	</div>
</body>
</html>