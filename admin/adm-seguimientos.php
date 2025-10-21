<?php 
	include_once('./engine/InfoUser.php');
	include_once('./engine/script_session.php');

	$NUCLEO = $_POST['in'];
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<link rel="stylesheet" href="../css/style-admin.css" type="text/css" media="screen" />
<script src="../js/jquery-1.7.1.min.js"></script>
<script>
<?php 
	if($NUCLEO){ echo '$IN = '.$NUCLEO.';'; } else { echo '$IN = 0;'; } 
	if($_SESSION['usuario']){ echo '$IE = '.$_SESSION['usuario']->id.";"; } else { echo '$IE = 0;'; } 
?>
</script>
<script src="../js/calendar/popcalendar.js"></script>
<script src="../js/script-admin-seg.js"></script>
<style>

/**************************/
.div-seg {
	width:100%;
	margin:auto;
	/*background-color:#090;*/
	/*background-color:#B0D8FF;*/
	padding:0px;
}
.div-seg form {
	display:block;
	width:100%;
}
.div-seg form fieldset {
	float:left;
	padding:5px;
	margin:10px 0px 0px 10px;
   border:1px solid #060;
}
.div-seg form fieldset table {
	
}
.div-seg form fieldset table td.lbl {
	text-align:right;
}
.div-seg form fieldset.fieldset1 {

}
.div-seg form fieldset.fieldset2 {

}
.div-seg .title {

}
.div-seg .nucleo {

}

/***************************************************/



.div-seg form fieldset legend {
	font-weight:bold;
	color:#FFF;
	background-color:#00B900;
	padding:3px 7px;
	margin-bottom:5px;
}
</style>
</head>
<body>
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
				<h2 class="title2">REGISTRO DE ACTIVIDADES</h2>
				<p class="parrafo1">
					Registre actividades/seguimientos de los nucleos bajo su cargo.
				</p>
				<div class="div-seg">
            	<span class="sp-empleado"><?php echo $_SESSION['usuario']->empleado; ?></span><br><br>
               <table class="tbl-nucl-asig" border="0" cellspacing="0">
               	<tbody class="tbHead">
	                  <tr><td colspan="2" class="tdTitle">Nucleos asignados al visitante <span><?php echo strtoupper($_SESSION['usuario']->empleado); ?></span></td></tr>
                  	<tr><th width="70">ID</th><th width="300">Nucleo</th></tr>
                  </tbody>
               	<tbody class="tbBody">
                  	<tr class="no-data"><td colspan="2">. . .</td></tr>
                  </tbody>
               	<tbody class="tbHide">
                  	<tr><td colspan="2">sin datos</td></tr>
                  </tbody>
               </table>
					<form class="formSeg" name="formSeg">
						<fieldset class="fieldset1" id="frmData">
							<legend>Datos</legend>
							<table class="tbl-datos" border="0">
								<tbody>
									<tr>
										<td class="lbl"><label>Responsable: </label></td>
										<td colspan="3"><input type="text" value="" class="emp" style="width:70%;" readonly><input type="hidden" name="ie" value=""></td>
									</tr>
									<tr>
										<td class="lbl"><label>Municipio: </label></td>
										<td><input type="text" value="" class="mun" readonly></td>
										<td class="lbl"><label>Nucleo Agrario: </label></td>
										<td><input type="text" value="" class="nuc" readonly></td>
										<td class="lbl"><label>Superficie: </label></td>
										<td><input type="text" value="" size="10" class="sup" readonly></td>
										<td class="lbl"><label>Tipo: </label></td>
										<td><input type="text" value="" size="10" class="tip" readonly></td>
									</tr>
								</tbody>
							</table>
						</fieldset><div style="clear:both;"></div>
						<fieldset class="fieldset2" style="min-height:380px;">
							<legend>Etapas</legend>
							<table class="tbl-etapas" border="0" cellspacing="0">
								<tbody class="tbHead">
                        	<tr>
										<th width="31">ETA</th>
										<th width="38">ACT</th>
										<th width="351">DESCRIPCION</th>
                           </tr>
								</tbody>
								<tbody class="tbBody">
									<tr class="no-data"><td colspan="3">. . .</td></tr>
								</tbody>
                        <tbody class="tbHide"></tbody>
							</table>
						</fieldset>
						<fieldset class="fieldset3" id="frmAdd" style="min-height:380px;">
							<legend>Datos de la actividad</legend>
                     <input type="hidden" name="modo" value="nuevo">
                     <table class="tbl-act" width="98%" cellspacing="0" border="0">
                     	<tbody class="tbBody">
                        	<tr>
	                           <td><label>ACTIVIDAD</label></td>
   	                        <td><label>DESCRIPCION</label></td>
      	                     <td><label>FECHA</label></td>
                           </tr>
                        	<tr>
	                           <td><input type="text" size="8" readonly><input type="hidden" value="0" name="iact"></td>
   	                        <td><input type="text" size="42" readonly></td>
      	                     <td><input type="text" size="10" readonly  id="dateSeg" onClick="popUpCalendar(this, formSeg.dateSeg, 'yyyy-mm-dd');"></td>
                           </tr>
                        	<tr>
	                           <td colspan="3" valign="middle">
                              	<label>OBSERVACIONES</label>
                                 <textarea rows="3" readonly></textarea>
                              </td>
                           </tr>
                        	<tr>
	                           <td colspan="3" align="center">
                              	<input type="button" class="button" disabled value="Agregar">
                                 <input type="button" class="button" disabled value="Guardar Cambios">
                                 <input type="button" class="button" disabled value="Cancelar">
                              </td>
                           </tr>
                        </tbody>
                     </table><br>
           				<ul class="mnu_seg_btns" id="seg_btns">
	                     <li><a href="#" title="">Eliminar</a></li>
								<li><a href="#" title="">Editar</a></li>
                        <li><a href="#" title="" style="display:none;">Test</a></li>
							</ul>
							<div style="clear:both;"></div>
                     <table class="tbl-seguim" width="98%" cellspacing="0" border="0">
                     	<tbody class="tbHead">
									<tr>
										<th colspan="4" style="padding:8px; background-color:#090;">ACTIVIDADES REGISTRADAS</th>
									</tr>
									<tr>
										<th>ACTIVIDAD</th>
										<th>FECHA</th>
										<th>VISITADOR</th>
										<th>OBSERVACIONES</th>
									</tr>
								</tbody>
								<tbody class="tbBody">
                        	<tr class="no-data"><td colspan="4">. . .</td></tr>
                        </tbody>
                        <tbody class="tbHide"></tbody>
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