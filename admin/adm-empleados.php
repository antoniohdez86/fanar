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
<script src="../js/script-admin-empl.js"></script>
</head>
<body>
	<div class="layer-bloquer"></div>
	<div class="dialog1" id="dialog1">
		<form name="frmEmpl" method="post">
			<input type="hidden" name="mode" value="nuevo">
			<table>
				<tbody>
					<tr><th class="title">Nuevo Usuario</th></tr>
               <tr><th class="message">panel de mensajes</th></tr>
					<tr><td>Id</td></tr>
					<tr><td><input type="text" name="id" maxlength="4" onKeyPress="if(event.keyCode<48 || event.keyCode>57) event.returnValue=false;"/></td></tr>
					<tr><td>Nombre</td></tr>
					<tr><td><input type="text" name="nombre" maxlength="20" onKeyPress="if(!((event.keyCode>96 && event.keyCode<123) || (event.keyCode>64 && event.keyCode<91) || (event.keyCode==241 || event.keyCode==209 || event.keyCode==32))) event.returnValue=false;" /></td></tr>
					<tr><td>Apellido Paterno</td></tr>
					<tr><td><input type="text" name="apeP" maxlength="20" onKeyPress="if(!((event.keyCode>96 && event.keyCode<123) || (event.keyCode>64 && event.keyCode<91) || (event.keyCode==241 || event.keyCode==209 || event.keyCode==32))) event.returnValue=false;"  /></td></tr>
					<tr><td>Apellido Materno</td></tr>
					<tr><td><input type="text" name="apeM" maxlength="20" onKeyPress="if(!((event.keyCode>96 && event.keyCode<123) || (event.keyCode>64 && event.keyCode<91) || (event.keyCode==241 || event.keyCode==209 || event.keyCode==32 || event.keyCode==45))) event.returnValue=false;"  /></td></tr>
					<tr><td>Direccion</td></tr>
					<tr><td><input type="text" name="Direcc" maxlength="70" onKeyPress="if(!((event.keyCode>96 && event.keyCode<123) || (event.keyCode>64 && event.keyCode<91) || (event.keyCode==241 || event.keyCode==209 || event.keyCode==32 || event.keyCode==46) || (event.keyCode>47 && event.keyCode<58))) event.returnValue=false;" /></td></tr>
					<tr><td>Profesion</td></tr>
					<tr><td><input type="text" name="Profes" maxlength="40" onKeyPress="if(!((event.keyCode>96 && event.keyCode<123) || (event.keyCode>64 && event.keyCode<91) || (event.keyCode==241 || event.keyCode==209 || event.keyCode==32 || event.keyCode==46))) event.returnValue=false;"  /></td></tr>
					<tr><td>Telefono</td></tr>
					<tr><td><input type="text" name="Tel" maxlength="15" onKeyPress="if(!(event.keyCode>47 && event.keyCode<58 || event.keyCode==45)) event.returnValue=false;"/></td></tr>
					<tr><td>Activo <input type="checkbox" name="Act" /></td></tr>
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
                  <li><a href="adm-reportes.php" title="">REPORTES</a></li>
                  <li><a href="../" title="">IR AL SITIO PUBLICO</a></li>
                  <li><a href="engine/cerrar_sesion.php" title="">CERRAR SESION</a></li>
					</ul>
				</div>
			</div>
			<div class="colDer">
				<h2 class="title2">Administracion de Empleados</h2>
				<p class="parrafo1">
					Lista de empleados registrados, aqui es donde se registran nuevos usuarios, se eliminan y actualizan.
               Estos empleados pueden ser asignados como visitantes a un nucleo Agrario espefico.
				</p>
				<ul class="menu2" id="mnu-empl">
					<li><a href="#" title="">Nuevo</a></li>
					<li><a href="#" title="">Editar</a></li>
					<li><a href="#" title="">Eliminar</a></li>
				</ul>
				<div style="clear:both;"></div>
				<div>
            	<table class="tbl-empl-log"><tbody><tr><th class="message" style="">panel de mensajes</th></tr></tbody></table>
					<table class="tbl-empleados" id="tbl-empleados" border="0" cellspacing="0">
						<tbody class="tbHead">
							<tr>
								<th>ID</th>
								<th>NOMBRE</th>
								<th>APELL. PAT.</th>
								<th>APELL. MAT.</th>
								<th>DIRECCION</th>
								<th>PROFESION</th>
								<th>TELEFONO</th>
								<th>ACTIVO</th>
                        <th>NUCLEOS<br>ASIGNADOS</th>
								<th>SELECCIONAR</th>
							</tr>
						</tbody>
						<tbody class="tbBody">
							<tr>
								<td colspan="12" class="loading no-data">cargando...</td>
							</tr>
						</tbody>
                  <tbody class="tbHide"></tbody>
					</table>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div class="footer"></div>
	</div>
   <div class="infoUsr"><?php echo $_SESSION['usuario']->empleado ?> : <?php echo $_SESSION['usuario']->isAdmin ? 'Administrador' : 'Visitante'; ?></div>
</body>
</html>