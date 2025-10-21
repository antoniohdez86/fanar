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
<script src="../js/script-admin-rep.js"></script>
<style>
.div-design { }
.div-design form { }
.div-design form fieldset{ padding:5px; margin:10px 0px 0px 10px; border:1px solid #060; width:auto; display:inline; }
.div-design form fieldset legend {
font-weight:bold;
color:#FFF;
background-color:#00B900;
padding:3px 7px;
margin-bottom:5px;
}
</style>
</head>
<body>
<div class="errorLog" style="display:none; background-color:#000; color:#0F0; font-family:Consolas; width:90%; height:90%; position:fixed;"></div>
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
                  <li><a href="../" title="">IR AL SITIO PUBLICO</a></li>
                  <li><a href="engine/cerrar_sesion.php" title="">CERRAR SESION</a></li>
</ul>
</div>
</div>
<div class="colDer">
<h2 class="title2">REPORTE DE PROBLEMATICA OPERATIVA DEL PROGRAMA FANAR</h2>
<p class="parrafo1">
Cree reportes personalizados de las actividades/seguimientos de cada nucleo, municipio o visitante. 
</p>
            <div class="div-design">
<form name="formReportDesign" class="form-design">
<fieldset>
<legend>Diseñador de reportes</legend>
<table class="tbl-design" border="0">
<tbody>
<tr style="display:block;">
<td>generar reporte por: </td>
<td>
                              <select>
                                 <option style="color:#666;">-- modo de generacion --</option>
                                 <option>Municipio</option>
                                    <option>Nucleo Agrario</option>
                                    <option>Nucleos encargados de un Empleado</option>
                                 </select>
                              </td>
</tr>
<tr style="display:none;">
                           <td>elegir Municipio: </td>
<td>
                              <select>
                                 <option>-- elejir Municipio --</option>
                                 </select>
                              </td>
</tr>
<tr style="display:none;">
<td>elegir Nucleo Agrario: </td>
<td>
                              <select>
                                 <option>-- elejir nucleo agrario --</option>
                                 </select>
                              </td>
</tr>
<tr style="display:none;">
<td>elegir Empleado: </td>
<td>
                              <select>
                                 <option>-- elejir empleado --</option>
                                 </select>
                              </td>
</tr>
                           <tr>
                           <td colspan="2" align="center"><input type="button" value="Generar"></td>
                           </tr>
<tr>
<td colspan="2">elegir campos requeridos: </td>
</tr>
                           <tr>
<td>
                              <input type="checkbox" name="fN" value="fNuc" checked disabled>nucleo agrario<br>
                                 <input type="checkbox" name="fM" value="fMun" checked>municipio<br>
                                 <input type="checkbox" name="fFS" value="fFSeg" checked>fecha seguimiento<br>
                              </td>
<td>
                                 <input type="checkbox" name="fO" value="fObs" checked>observaciones<br>
                                 <input type="checkbox" name="fS" value="fSit" checked>situacion<br>
                              </td>
</tr>
<tr>
<td colspan="2">Solo etapas maximas: <input type="checkbox" name="fEM" value="fEMax" checked></td>
</tr>
</tbody>
</table>
</fieldset>
</form>
               <div style="clear:both;"></div>
</div>
<div><br>
               <table border="0" align="center" style="margin:auto;">
<tbody class="tbTitle">
<tr>
<th align="center">
                        <form name="frmGetRep" method="post" action="report/Reporte.php" target="_blank">
                           <input type="image" src="../img/pdf_gen.png" title="Generar pdf" id="btnPdf" style="display:none;">
                              <input type="hidden" name="c">
                              <input type="hidden" name="m">
                              <input type="hidden" name="n">
                              <input type="hidden" name="v">
                              <input type="hidden" name="fM">
                              <input type="hidden" name="fN">
                              <input type="hidden" name="fF">
                              <input type="hidden" name="fO">
                              <input type="hidden" name="fS">
                              <input type="hidden" name="fEM">
                              <input type="hidden" name="text">
                           </form>
</th>
</tr>
</tbody>
               </table>
<table class="tbl-reporte" border="0" cellspacing="1">
<tbody class="tbHead">
<tr>
<th>No.</th>
<th>MUNICIPIO</th>
<th>NUCLEO AGRARIO</th>
<th>ETAPA MAXIMA</th>
<th>FECHA</th>
<th>OBSERVACIONES</th>
                        <th>SITUACION</th>
</tr>
</tbody>
<tbody class="tbBody">
<tr><td colspan="7" class="no-data">Visor de reportes</td></tr>
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
