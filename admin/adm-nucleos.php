<?php 
	include_once('engine/InfoUser.php');
	include_once('./engine/script_session.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1"/>
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
.dialog2 {
	behavior:url(../css/PIE/PIE.htc);
	position:absolute;
	display:inline-block;
	border:1px #0C0 solid;
   background-color:#030;
   display:none;
   z-index:999;
	border-radius:8px;
   -webkit-border-radius:8px;
   -moz-border-radius:8px;
	color:#0F0;
	padding:5px;
}
.dialog2 table { }
.dialog2 table tr { }
.dialog2 table tr th { background-color:#060; }
.dialog2 table tr td.label { padding-top:8px; }
.dialog2 table tr td.input { padding-bottom:0px }
.dialog2 table tr td.label-rdo { padding-top:8px; }
.dialog2 table tbody tr td.buttons { text-align:right; }
.dialog2 table input[type=text] { width:250px; }
.dialog2 table select { width:290px; }
.dialog2 table input[type=button] { border:1px #090 solid; margin:3px 0px; }
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
	<form name="formX" class="formX" method="post" action="./adm-seguimientos.php">
   	<input type="hidden" name="in">
	</form>
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
               <tr><td class="label-rdo">Tipo: <input type="radio" name="tipo" value="Comunidad" />Comunidad  <input type="radio" name="tipo" value="Ejido" />Ejido</td></tr>
					<tr><td class="label">Perteneciente al Municipio de:</td></tr>
					<tr><td class="select">
						<select></select>
					</td></tr>
					<tr><td class="label">Superficie</td></tr>
					<tr><td class="input"><input type="text" name="id" /></td></tr>
					<tr><td class="buttons"><input type="button" name="guardar" value="Guardar" /><input type="button" name="cancelar" value="Cancelar" /></td></tr>
				</tbody>
			</table>
		</form>
	</div>
	<div class="dialog2" id="dialog2">
		<form name="frmVisit">
			<input type="hidden" name="mode" value="">
			<table>
				<tbody>
					<tr><th>LISTA EMPLEADOS</th></tr>
					<tr><td class="select">
						<select multiple size="20"></select>
					</td></tr>
					<tr><td class="buttons"><input type="button" name="guardar" value="Guardar" /><input type="button" name="cancelar" value="Cancelar" /></td></tr>
				</tbody>
            <tbody style="display:none;">
            	<tr>
               	<td>
						<select multiple size="20"></select>
                  </td>
               </tr>
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
               	<?php 
							if($_SESSION['usuario']->isAdmin) {
						?>
						<li><a href="adm-usuarios.php" title="">USUARIOS</a></li>
						<li><a href="adm-empleados.php" title="">EMPLEADOS</a></li>
						<li><a href="adm-municipios.php" title="">MUNICIPIOS</a></li>
               	<?php 
							}
						?>						
						<li><a href="adm-nucleos.php" title="">NUCLEOS AGRARIOS</a></li>
						<li><a href="adm-seguimientos.php" title="">SEGUIMIENTOS</a></li>
                  <li><a href="adm-reportes.php" title="">REPORTES</a></li>
                  <li><a href="engine/cerrar_sesion.php" title="">CERRAR SESION</a></li>
					</ul>
				</div>
			</div>
			<div class="colDer">
				<h2 class="title2">Administracion de Nucleos Agrarios</h2>
				<p class="parrafo1">
					En esta seccion puede agregar,eliminar y modificar nucleos Agrarios, como tambien puede asignar 
               un visitante para un nucleo en cuestion.
				</p>
				<ul class="menu2" id="mnu-nucl">
				<?php if($_SESSION['usuario']->isAdmin) {	?>
					<li><a href="#" title="">Nuevo</a></li>
					<li><a href="#" title="">Editar</a></li>
					<li><a href="#" title="">Eliminar</a></li>
				<?php }	?>
               <li><a href="#" title="">Ver seguimientos</a></li>
				<?php if($_SESSION['usuario']->isAdmin) {	?>
					<li><a href="#" title="">Asignar visitante</a></li>
               <li><a href="#" title="" style="display:none;">Test</a></li>
				<?php }	?>
				</ul>
				<div style="clear:both;"></div>
				<div>
					<table class="tbl-nucleos" border="0" cellspacing="0">
						<tbody class="tbHead">
							<tr>
								<th>CODIGO</th>
								<th>NUCLEO AGRARIO</th>
								<th>TIPO NUCLEO</th>
								<th>SUPERFICIE</th>
								<th>MUNICIPIO</th>
                        <th>SITUACION</th>
                        <th>VISITADOR</th>
                        <th>SELECCIONAR</th>
							</tr>
						</tbody>
						<tbody class="tbBody">
							<tr>
								<td colspan="10" class="no-data">. . .</td>
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
</body>
</html>
