<?php
//DEFINE UNA VARIABLE QUE PUEDEN UTILIZAR LOS ARCHIVOS EXTERNOS PARA COMPROBAR SI YA HAN INCLUIDO ESTAS LIBRERIAS Y SINO PUES SE INCLUYEN
$LIBRARY_PERSONA = true;
class Persona {
var $nombre;
var $apellido;
var $edad;
function registrar(){}
function mostrarDatos(){}
function eliminar(){}
function establecerNombre($nombre){
$this->nombre = $nombre;
}
function obtenerEdad(){
return $this->edad;
}
}
?>
