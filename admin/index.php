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
<script src="../js/script-admin.js"></script>
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
<h1 class="title1">Bienvenido!!</h1>
<p class="parrafo1">
Bienvenido al Sistema FANAR.<br><br>
               El Sistema Fanar es una aplicacion el cual tiene como objetivo el automatizar el registro de las actividades 
               o seguimientos realizados por personas asignadas como responsables(visitantes) de llevar a cabo dichos seguimientos.
               El Sistema Fanar esta compuesto de los siguientes modulos:<br><br>
               <strong>- Modulo de Registro de Usuarios</strong><br>
               <strong>- Modulo de Registro de Municipios</strong><br>
               <strong>- Modulo de Registro de Nucleos Agrarios</strong><br>
               <strong>- Modulo de Registro de Seguimientos/Actividades</strong><br>
               <strong>- Modulo de Reporte de Problematica Operativa</strong>
        </p>
</div>
<div style="clear:both;"></div>
</div>
<div class="footer"></div>
</div>
</body>
</html>
