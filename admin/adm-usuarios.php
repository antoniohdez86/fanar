<?php 
	include_once('engine/InfoUser.php');
	include_once('./engine/script_session.php');
	if(!$_SESSION['usuario']->isAdmin){
		header('Location: ./index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1"/>
<link rel="stylesheet" href="../css/style-admin.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/style-admin-dialogs.css" type="text/css" media="screen" />
<script src="../js/jquery-1.7.1.min.js"></script>
<script src="../js/script-admin-usua.js"></script>
</head>
<body>
	<div class="layer-bloquer"></div>
	<div class="dialog1" id="dialog1">
		<form name="frmMunicipio" method="post">
			<input type="hidden" name="mode" value="nuevo">
			<table>
				<tbody>
					<tr><th>Nuevo Usuario</th></tr>
					<tr><td>Nombre de Usuario</td></tr>
					<tr><td><input type="text" name="User" /></td></tr>
					<tr><td>Contraseña</td></tr>
					<tr><td><input type="text" name="Pass" /></td></tr>
					<tr><td>Empleado</td></tr>
					<tr><td><input type="text" name="Empl" /></td></tr>
					<tr>
						<td class="select">
							<select>
								<option>option 1</option>
								<option>option 2</option>
								<option>option 3</option>
							</select>
						</td>
					</tr>
					<tr><td class="td-buttons"><input type="button" name="guardar" value="Guardar" /><input type="button" name="cancelar" value="Cancelar" /></td></tr>
				</tbody>
			</table>
		</form>
	</div>
	<div class="main-doc">
		<div class="header">
			<img class="logo1" src="../img/ProcAgr2.jpg" />
			<img class="logo2" src="../img/SRA.jpg" />
         <img class="logo3" src="../img/Imagen2.png" />
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
                  <li><a href="engine/cerrar_sesion.php" title="">CERRAR SESION</a></li>
					</ul>
				</div>
			</div>
			<div class="colDer">
				<h2 class="title2">Administracion de USUARIOS</h2>
				<p class="parrafo1">
				Aqui un texto!!, aqui mas texto y por aqui otro mas y asi y asi sucesivamente
				hasta tener una cantidad considerable de caracteres para formar palabras 
				entendibles para todos los usuarios que entren al sitio, pero todavia canven 
				mas palabras!!.. ok entonces aqui van, esto es texto, esto es otro texto pero
				tambien voy a poner aqui este otro mas.
				</p>
				<ul class="menu2" id="mnu-empl">
					<li><a href="#" title="">Nuevo</a></li>
					<li><a href="#" title="">Editar</a></li>
					<li><a href="#" title="">Eliminar</a></li>
				</ul>
				<div style="clear:both;"></div>
				<div>
					<table class="tbl-usuarios" id="tbl-usuarios" border="0" cellspacing="0">
						<tbody class="tbHead">
							<tr>
								<th>USUARIO</th>
								<th>CONTRASEÑA</th>
								<th>EMPLEADO</th>
								<th>EDITAR</th>
								<th>ELIMINAR</th>
								<th>SELECCIONAR</th>
							</tr>
						</tbody>
						<tbody class="tbBody">
							<tr>
								<td colspan="6" class="loading">cargando...</td>
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