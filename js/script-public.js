$(function() {
$gallery1 = $('#gallery1');
$cUs = $('tbody.tbAcount td.user');
$tbLog = $('tbody.tbLog');
$tbUs = $('tbody.tbAcount');
$flog = $('form');
$flog_in = $('input',$flog);
if($cUs.text()){
$tbLog.css('display','none');
$tbUs.css('display','block');
} else {
$tbLog.css('display','block');
$tbUs.css('display','none');
}
$flog_in.eq(2).bind('click', function(e){
e.preventDefault();
if(!$.trim($flog_in.eq(0).val()) || !$.trim($flog_in.eq(1).val())){
alert("Introduzca su nombre de ususario y contraseña");$flog_in.eq(0).focus();return;
}
$.ajax({
type:'post',
url:'./admin/ajax/ajax_agent_usua.php',
data: "mode=lg&u="+$flog_in.eq(0).val() + "&p="+$flog_in.eq(1).val(),
cache: false,
success: function(SERVER_RESPONSE) {
if(!SERVER_RESPONSE) {
alert("La respuesta del servidor esta vacia");
return false;
}
var DATA = SERVER_RESPONSE.split('|');
if( DATA.length != 3 ){
alert("La respuesta del servidor tienen un formato de respuesta incorrecta");
alert(": " + SERVER_RESPONSE);
return false;
}
SERVER_CODE = DATA[0];
SERVER_MESAGE = DATA[1];
SERVER_TEST = DATA[2];
if(SERVER_TEST) {alert(SERVER_TEST); }
switch (SERVER_CODE) {
case "OKUS":
window.location = SERVER_MESAGE;
break;
case "INACUS":
alert(SERVER_MESAGE);
break;
case "USER_INV":
alert(SERVER_MESAGE);
$flog_in.eq(0).val('');
$flog_in.eq(1).val('');
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
});
// inicializa galeria
$('#slider').nivoSlider();
});
