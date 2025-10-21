<?php 
session_start();
if( !isset($_SESSION['usuario']) ) {
$_SESSION['usuario'] = false;
}
if($_SESSION['usuario'] == false) {
header("Location: ../");
}
?>
