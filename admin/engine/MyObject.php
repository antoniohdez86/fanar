<?php 
//DEFINE UNA VARIABLE QUE PUEDEN UTILIZAR LOS ARCHIVOS EXTERNOS PARA COMPROBAR SI YA HAN INCLUIDO ESTAS LIBRERIAS Y SINO PUES SE INCLUYEN
$LIBRARY_MY_OBJECT = true;
class MyObject {
//ESTA FUNCION DEVUELVE UNA CADENA QUE INCLUYE EL NUMERO DE SALTOS Y NUMERO DE TABULACIONES (EN ESPACIOS).
function util($num_saltos, $num_spaces) {
$spaces = "";
$saltos = "";
for($c=0; $c<$num_saltos; $c++) { $saltos .= "\n"; }
for($c=0; $c<$num_spaces; $c++) { $spaces .= " ";  }
return $saltos.$spaces;
}
//ESTA FUNCION DEVUELVE UNA CADENA QUE INCLUYE EL NUMERO DE SALTOS Y NUMERO DE TABULACIONES (EN TABULACIONES).
function util_v2($num_saltos, $num_spaces) {
$spaces = "";
$saltos = "";
for($c=0; $c<$num_saltos; $c++) { $saltos .= "\n"; }
for($c=0; $c<$num_spaces; $c++) { $spaces .= "\t"; }
return $saltos.$spaces;
}
}
?>
