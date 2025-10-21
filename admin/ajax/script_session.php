<?php 

/****

	header('Location: ./login.php');

*****/


	session_start();

	/*
	 *	COMPRUEBA SI NO SE HA EXISTE LA VARIABLE SESION "USUARIO"... SINO EXISTE ENTONCES LA CREA E INICIALIZA A "FALSE"
	 *
	 **/
	 
	if( !isset($_SESSION['usuario']) ) {
	
		$_SESSION['usuario'] = false;
	
	}

	/*
	 *	SI LA SESION "USUARIO" ES FALSO ENTONCES "ES UNA SESION INVALIDA"
	 *
	 **/
	if($_SESSION['usuario'] == false) {
	
		echo "acceso no autorizado!!";
		exit(0);
		//header("Location: ../");
	
	}

?>