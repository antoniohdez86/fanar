<?php
	define("MY_NAME_SERVER","localhost");
	define("MY_DB_USER","root");
	define("MY_DB_PASSWORD","");	//	valor original "ith250484"
	define("MY_DB_NAME","bdithuejutlaweb");	
	function conecta_bd()
	{
		$conexion = mysql_connect(MY_NAME_SERVER, MY_DB_USER, MY_DB_PASSWORD) or die("No se puede establecer conexion con el servidor de la Base de Datos");	
		mysql_select_db(MY_DB_NAME,$conexion) or die("Error al seleccionar la Base de Datos");
		return $conexion;
	}	
	function ejecuta_query($cad)
	{
		$consulta= mysql_query($cad,conecta_bd())or die("Error al ejecutar la consulta: ".$cad);			 
		return $consulta;
	}
?>