$(function(){
var $bloquer1 = $('.layer-bloquer'),// bloquea la pagina
    $dialog1 = $('#dialog1'),// dialogo 1
 $dialog2 = $('#dialog2'),// dialogo 2
    $dialog1_Acept = $('input:[type=button]', $dialog1).eq(0),// DIALOG 1
 $dialog1_Cancel = $('input:[type=button]', $dialog1).eq(1),// DIALOG 1
    $dialog1_Acept2 = $('input:[type=button]', $dialog2).eq(0),// DIALOG 2
 $dialog1_Cancel2 = $('input:[type=button]', $dialog2).eq(1),// DIALOG 2
 $form1 = $('form', $dialog1),
 $form2 = $('form', $dialog2),
 $tblNucleos = $('.tbl-nucleos'),
 $text = null,
 $row = null, 
 $option = null, 
 $nSeg=null;
 $FORM_SEG = $('.formX');
 $FORM_SEG_in = $('input', $FORM_SEG);
 $NUCL_table = $('.tbl-nucleos');
 $NUCL_tbBody = $('.tbBody', $NUCL_table);
 $NUCL_tbHide = $('.tbHide', $NUCL_table);
 $NUCL_noData = $('.no-data', $NUCL_tbBody);
 $NUCL_rows = null;
 $NUCL_row = null;
 $NUCL_row_sp = null;// indicador de situacion
 $NUCL_row_hdM = null;// idMunicipio
 $NUCL_row_hdV = null;// idVisitante
 $NUCL_row_rdo = null;// idNucleo
 $NUCL_cells = null;
 $NUCL_form = $('form:[name=frmNucleo]');
 $NUCL_form_in = $('input', $NUCL_form);
 $NUCL_form_sel = $('select', $NUCL_form);
 $NUCL_form_sel_opts = null;
 $NUCL_form_sel_opt = null;
 $NUCL_form_rdo = null;
 $VISIT_form = $('form:[name=frmVisit]');
 $VISIT_form_in = $('input', $VISIT_form);
 $VISIT_form_sel = $('select', $VISIT_form);
 $VISIT_form_sel_opts = null;
 $VISIT_form_sel_opt = null;
 $MENU_HORIZ = $('#mnu-nucl');
 $MENU_HORIZ_BTNS = $('a', $MENU_HORIZ);
// ACEPTAR (FORM NUCLEO)
$NUCL_form_in.eq(6).bind('click', function() {
$NUCL_form_rdo = $('input:radio[checked]', $NUCL_form);
$NUCL_form_sel_opt = $('option:selected', $NUCL_form_sel);
// ID, NUCLEO, SUPERFICIE
if(!$NUCL_form_in.eq(1).val() || !$NUCL_form_in.eq(2).val() || !$NUCL_form_in.eq(5).val()){ alert("todos los campos son requeridos!"); return; }
if($NUCL_form_rdo.val()==undefined){ alert("Elija un tipo de nucleo!"); return; }
if($NUCL_form_sel_opt.index()==0) { alert("Elija un municipio!"); return; }
var params = "";
if($NUCL_form_in.eq(0).val()=='nuevo') {
params = "mode=ins";
} else if($NUCL_form_in.eq(0).val()=='edit') { 
params = "mode=upd";
} 
params += "&id=" + $NUCL_form_in.eq(1).val();
params += "&nucleo=" + $NUCL_form_in.eq(2).val();
params += "&tipo=" + $NUCL_form_rdo.val();
params += "&superficie=" + $NUCL_form_in.eq(5).val();
params += "&idMunicipio=" + $NUCL_form_sel_opt.val();
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
data: params,
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
case "NUCL_SAVED":
if($NUCL_noData.parent().parent().hasClass('tbBody')) {
$NUCL_noData.parent().remove().prependTo($NUCL_tbHide);
}
$NUCL_tbBody.prepend(SERVER_MESAGE);
$NUCL_rows = $('tr', $NUCL_tbBody);
$NUCL_rows.unbind('click');
$NUCL_rows.bind('click', function() {
$NUCL_row = $(this);// establece la fila seleccionada
$NUCL_row_sp = $('span', $NUCL_row);// indicador situacion
$NUCL_row_hdM = $('input:hidden', $NUCL_row).eq(0);// idMunicipio
$NUCL_row_hdV = $('input:hidden', $NUCL_row).eq(1);// idVisitante
$NUCL_row_rdo = $('input:radio', $NUCL_row).attr('checked',true); // establece el radio de la fila seleccionada (y lo marca)
$NUCL_cells = $('td', $NUCL_row);// establece las celdas de la fila selecionada 
$NUCL_rows.removeClass('tr-selected');// deselecciona cualquier otra fila que lo este
$NUCL_row.addClass('tr-selected');// lo indica como seleccionado
});
break;
case "ERROR_EXIST_NUCL_ID":
alert(SERVER_MESAGE);
return;
break;
case "NUCL_UPDATED":
$NUCL_form_rdo = $('input:radio[checked]', $NUCL_form);// tipo de nucleo seleccionado
$NUCL_form_sel_opt = $('option:selected', $NUCL_form);
$NUCL_cells.eq(1).text($NUCL_form_in.eq(2).val());// cambia valor a celda "Nucleo"
$NUCL_cells.eq(2).text($NUCL_form_rdo.val());// cambia valor a celda "Tipo nucleo"
$NUCL_cells.eq(3).text($NUCL_form_in.eq(5).val());// cambia valor a celda "Superficie"
var $tmp_hidden = $('input', $NUCL_cells.eq(4));
$NUCL_cells.eq(4).text($NUCL_form_sel_opt.text());// cambia valor a celda "Municipio"
$NUCL_cells.eq(4).prepend($tmp_hidden);
$tmp_hidden.val($NUCL_form_sel_opt.val());
//$('input', $NUCL_row).eq(0).val($NUCL_form_sel_opt.val());// cambia valor del hidden "id Municipio"
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE); return;
break;
}
$dialog1.css('display','none');
$bloquer1.css('display','none');
},
error: function(err) { alert("error: " + err); }
});
});
// CANCELAR (FORM NUCLEO)
$NUCL_form_in.eq(7).bind('click', function() {
$bloquer1.css('display','none');
$NUCL_form.parent().css('display','none');
});
// BOTONES DEL FORMULARIO --- ASIGNAR VISITANTE ---
$VISIT_form_in.bind('click', function() {
$VISIT_BUTTON = $(this);
switch($VISIT_BUTTON.val()) {
case "Guardar":
// obtiene coleccion de opcions
$VISIT_form_sel_opts = $('option', $VISIT_form_sel.eq(0));// options de select 1
// comprueba si la coleccion esta vacia
if(!$VISIT_form_sel_opts.length){
alert('El control no contiene ninigun empleado');
return;
}
// obtiene coleccion de items seleccionados
$VISIT_form_sel_opt = $('option:selected', $VISIT_form_sel.eq(0));// options que se han seleccionado
// comprueba que haya elementos seleccionados
if(!$VISIT_form_sel_opt.length){
alert('Seleccione un empleado');
return;
}
// comprueba que solo un elemento este seleccionado
if($VISIT_form_sel_opt.length>1){
alert('Solo puede seleccionar un empleado');
return;
}
params = '';
// A) Asignar visitante 
if($VISIT_form_in.eq(0).val()==0){// se esta asignando a un nucleo que aun no tiene visitante
if($VISIT_form_sel_opt.val()==0){// se ha seleccionado la opcion --sin asignar-- 
$dialog2.css('display','none');
$bloquer1.css('display','none');
return;
} else {// se ha seleccionado un --empleado-- valido
params += 'mode=av';// asignar nuevo visitante al nucleo
}
} else {// se esta cambiando de visitante o eliminando visitante
// B) QUITAR VISITANTE
// se ha elegido --quitar-- el visitante al nucleo
if($VISIT_form_sel_opt.val()==0) {
// el nucleo al que se le quiere quitar el visitante -- TIENE SEGUIMIENTOS --
if(parseInt($.trim($NUCL_row_sp.text()))>0){
alert('No se puede quitar el visitante a un nucleo mientras tenga seguimientos');
return;
} else { // el nucleo al que se le quiere quitar el visitante -- NO TIENE SEGUIMIENTOS --
params += 'mode=rv';// remover visitante
}
} else if($VISIT_form_sel_opt.val() == $VISIT_form_in.eq(0).val()) {
// se ha seleccionado el mismo empleado como visitante
$dialog2.css('display','none');
$bloquer1.css('display','none');
return;
} else {
// B) CAMBIAR VISITANTE
// se ha elegido cambiar de visitante
params += 'mode=cv';// cambiar visitante
}
}
params += '&in=' + $NUCL_row_rdo.val() + "&ie=" + $VISIT_form_sel_opt.val();
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
data: params,
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
case "VIS_ASIGN_OK":
$NUCL_row_hdV = $('input', $NUCL_cells.eq(6));
$NUCL_cells.eq(6).text($VISIT_form_sel_opt.text());// muestra el nombre del nuevo visitante en la celda del nucleo
$NUCL_cells.eq(6).prepend($NUCL_row_hdV);
$NUCL_row_hdV.val($VISIT_form_sel_opt.val());// cambia el id del visitante por el del nuevo
$NUCL_row_sp.attr('class','process').html('&nbsp;0&nbsp;');
break;
case "VIS_CHANGED_OK":
$NUCL_row_hdV = $('input', $NUCL_cells.eq(6));
$NUCL_cells.eq(6).text($VISIT_form_sel_opt.text());// muestra el nombre del nuevo visitante en la celda del nucleo
$NUCL_cells.eq(6).prepend($NUCL_row_hdV);
$NUCL_row_hdV.val($VISIT_form_sel_opt.val());
break;
case "VIS_QUIT_OK":
$NUCL_row_hdV = $('input', $NUCL_cells.eq(6));
$NUCL_cells.eq(6).text('sin asignar');
$NUCL_cells.eq(6).prepend($NUCL_row_hdV);
$NUCL_row_hdV.val($VISIT_form_sel_opt.val());
$NUCL_row_sp.attr('class','none').html('&nbsp;0&nbsp;').html("&nbsp;&nbsp;&nbsp;");
break;
case "ERROR_YA_ASIGN":
alert(SERVER_MESAGE);
return;
break;
case "VIS_CHANGED_ERR":
alert(SERVER_MESAGE);
return;
break;
case "VIS_ASIGN_ERR":
alert(SERVER_MESAGE);
return;
break;
case "VIS_QUIT_ERR":
alert(SERVER_MESAGE);
return;
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
return;
break;
}
$dialog2.css('display','none');
$bloquer1.css('display','none');
},
error: function(err) { alert("error: " + err); }
});
return;
break;
case "Cancelar":
$bloquer1.css('display','none');
$dialog2.css('display','none');
break;
}
});
// inicializa div bloque
$bloquer1.css('opacity',0.3);
// evento al redimensionar navegador
$win = $(window);
$win.bind('resize',function(e) {
if($dialog1.css('display')=='block'){
$dialog1_w = $dialog1.width();
$dialog1_h = $dialog1.height();
$win_w = $win.width();
$win_h = $win.height();
$dialog1_x = ($win_w/2) - ($dialog1_w/2);
$dialog1_y = ($win_h/2) - ($dialog1_h/2);
$dialog1.css({'left':$dialog1_x,'top':$dialog1_y});
}
});
// MENU VERTICAL ( ELIMINAR, EDITAR, NUEVO, VER SEGUIMIENTOS )
$MENU_HORIZ_BTNS.bind('click',function(e) {
e.preventDefault();
$MENU_BUTTON = $(this);// link (boton) pulsado
// realiza una accion acorde al texto del boton
switch($MENU_BUTTON.text()) {
// TEST
case "Test":
alert($NUCL_row.html())
break;
// NUEVO
case "Nuevo":
$NUCL_form_in.eq(0).val('nuevo');// hiddem
$NUCL_form_in.eq(1).val('').attr('disabled',false).focus();// id nucleo
$NUCL_form_in.eq(2).val('');// nucleo
$NUCL_form_in.eq(3).attr('checked', false);// radio comunidad
$NUCL_form_in.eq(4).attr('checked', false);// radio ejido
$NUCL_form_in.eq(5).val('');// superficie
$NUCL_form_sel_opts.eq(0).attr('selected', true);// municipio
$dialog1_w = $dialog1.width();
$dialog1_h = $dialog1.height();
$win_w = $win.width();
$win_h = $win.height();
$dialog1_x = ($win_w/2) - ($dialog1_w/2);
$dialog1_y = ($win_h/2) - ($dialog1_h/2);
$dialog1.css({'left':$dialog1_x,'top':$dialog1_y});
$('th',$dialog1).text('Nuevo Nucleo Agrario');
$bloquer1.css('display','block');
$dialog1.css('display','block');
break;
// EDITAR
case "Editar":
if(!$NUCL_rows.length) { alert('Sin registros de nucleos Agrarios!!'); return; }
if($NUCL_rows.length==1 && $('td',$NUCL_rows.eq(0)).hasClass('no-data')) { alert('Sin registros de nucleos Agrarios!!'); return; }
if($NUCL_row == null) { alert('Seleccione un registro de un nucleo Agrario'); return; }
$im = $('input', $NUCL_row).eq(0).val();// TEXT : id de municipio 
$ie = $('input', $NUCL_row).eq(1).val();// TEXT : id de visitante
iOptN = $('option:[value=' + $im + ']', $NUCL_form_sel).index();// obtiene el OPTION que tenga como value un valor X
$NUCL_form_in.eq(0).val('edit');// hiddem
$NUCL_form_in.eq(1).val( $NUCL_cells.eq(0).text() ).attr('disabled', true);// id nucleo
$NUCL_form_in.eq(2).val( $NUCL_cells.eq(1).text() ).focus();// nucleo
// selecciona el radio correspondiente al tipo de nucleo que haya escrito en la celda
if( $NUCL_cells.eq(2).text() == 'Comunidad' ){
$NUCL_form_in.eq(3).attr('checked', true);// RADIO : comunidad
} else {
$NUCL_form_in.eq(4).attr('checked', true);// RADIO : ejido
}
$NUCL_form_in.eq(5).val( $NUCL_cells.eq(3).text() );// TEXT : superficie
$NUCL_form_sel_opts.eq(iOptN).attr('selected', true);// SELECT : municipio
// calcula posicion y muestra el dialogo
$dialog1_w = $dialog1.width();
$dialog1_h = $dialog1.height();
$win_w = $win.width();
$win_h = $win.height();
$dialog1_x = ($win_w/2) - ($dialog1_w/2);
$dialog1_y = ($win_h/2) - ($dialog1_h/2);
$dialog1.css({ 'left':$dialog1_x, 'top':$dialog1_y });
$('th',$dialog1).text('Editar Nucleo Agrario');
$bloquer1.css('display','block');
$dialog1.css('display','block');
break;
// ELIMINAR
case "Eliminar":
if(!$NUCL_rows.length) { alert('Sin registros de nucleos Agrarios!!'); return; }
if($NUCL_rows.length==1 && $('td',$NUCL_rows.eq(0)).hasClass('no-data')) { alert('Sin registros de nucleos Agrarios!!'); return; }
if($NUCL_row == null) { alert('Seleccione un registro de un nucleo Agrario'); return; }
if(!confirm("Eliminar Nucleo Agrario [" + $NUCL_cells.eq(0).text() + ", " + $NUCL_cells.eq(1).text() + "] ?"))
return;
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
data: "mode=del&id=" + $NUCL_cells.eq(0).text(),
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
case "NUCL_DELETED":
$NUCL_row.remove();
$NUCL_rows = $('tr', $NUCL_tbBody);
// borra cualquier referencia
$NUCL_row = null;
$NUCL_row_sp = null;
$NUCL_row_hdM = null;
$NUCL_row_hdV = null;
$NUCL_row_rdo = null;
$NUCL_cells = null;
if(!$NUCL_rows.length) {
$NUCL_noData.parent().remove().prependTo($NUCL_tbBody);
$NUCL_noData.text('No hay nucleos registrados');
}
break;
case "NUCL_HASSEG":
alert(SERVER_MESAGE);
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
break;
// ASIGNAR VISITANTE
case "Asignar visitante":
if(!$NUCL_rows.length) { alert('Sin registros de nucleos Agrarios!!'); return; }
if($NUCL_rows.length==1 && $('td',$NUCL_rows.eq(0)).hasClass('no-data')) { alert('Sin registros de nucleos Agrarios!!'); return; }
if($NUCL_row == null) { alert('Seleccione un registro de un nucleo Agrario'); return; }
// comprueba que que el nucleo tenga un visitador asignado
if ($NUCL_cells.eq(6).text()!='sin asignar'){
if(parseInt($.trim($NUCL_row_sp.text()))>0){
// muestra la situacion de seguimientos y pregunta antes de mostrar el dialogo
if(!confirm("El nucleo seleccionado ya tiene seguimientos hechos por el visitador actualmente asignado.\nDesea cambiar el visitador de todas maneras?\nNota: Los seguimientos hechos al nucleo se mantendran")){
return;
}
}
}
$VISIT_form_in.eq(0).val($NUCL_row_hdV.val());// guarda el id del visitante en el formulario "Asignar Visitante"
$dialog2_w = $dialog2.width();
$dialog2_h = $dialog2.height();
$win_w = $win.width();
$win_h = $win.height();
$dialog2_x = ($win_w/2) - ($dialog2_w/2);
$dialog2_y = ($win_h/2) - ($dialog2_h/2);
$dialog2.css({'left':$dialog2_x,'top':$dialog2_y});
$bloquer1.css('display','block');
$dialog2.css('display','block');
$('select', $dialog2).focus();
break;
// VER SEGUIMIENTOS
case "Ver seguimientos":
if(!$NUCL_rows.length) { alert('Sin registros de nucleos Agrarios!!'); return; }
if($NUCL_rows.length==1 && $('td',$NUCL_rows.eq(0)).hasClass('no-data')) { alert('Sin registros de nucleos Agrarios!!'); return; }
if($NUCL_row == null) { alert('Seleccione un registro de un nucleo Agrario'); return; }
if($NUCL_row_sp.hasClass('none')){
alert('No se ha asignado ningun visitante a este nucleo');
return;
}
$FORM_SEG_in.eq(0).val($NUCL_row_rdo.val());
$FORM_SEG.submit();
break;
}
});
function getListNucleos() {
$NUCL_noData.text('Cargando...');
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
data: "mode=list",
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
case 'NO_HAS_NUCL':
$NUCL_noData.text(SERVER_MESAGE);
$NUCL_rows = $('tr', $NUCL_tbBody);
break;
case "OK_LIST":
$NUCL_noData.parent().remove().prependTo($NUCL_tbHide);
// ahora si pega el resultado de la consulta y obtiene la coleccion de filas
$NUCL_tbBody.html(SERVER_MESAGE);
$NUCL_rows = $('tr', $NUCL_tbBody);
// si no se recibio ninguna fila (nisiquiera la de mensaje que deveria enviar el servidor) entonces se recupera la fila (no-data) y se muestra
if(!$NUCL_rows.length) {
$NUCL_noData.parent().remove().prependTo($NUCL_tbBody);
$NUCL_noData.text('sin respuesta del servidor...')
return; 
} 
$NUCL_rows.bind('click', function(){
$NUCL_row = $(this);// establece la fila seleccionada
$NUCL_row_sp = $('span', $NUCL_row);// indicador situacion
$NUCL_row_hdM = $('input:hidden', $NUCL_row).eq(0);// idMunicipio
$NUCL_row_hdV = $('input:hidden', $NUCL_row).eq(1);// idVisitante
$NUCL_row_rdo = $('input:radio', $NUCL_row).attr('checked',true); // establece el radio de la fila seleccionada (y lo marca)
$NUCL_cells = $('td', $NUCL_row);// establece las celdas de la fila selecionada 
$NUCL_rows.removeClass('tr-selected');// deselecciona cualquier otra fila que lo este
$NUCL_row.addClass('tr-selected');// lo indica como seleccionado
});
break;
default:
alert('a4')
$('.no-data',$NUCL_tbBody).text(SERVER_CODE + "<br>" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
}
function getCboMunicipios() {
$.ajax({
type:'post',
url:'./ajax/ajax_agent_munic.php',
data: "mode=cbo",
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
case "OK_CBO":
var $VIRTUAL_select = $(SERVER_MESAGE);// obtine objeto select enviado por el server
$VIRTUAL_select_opt = $('option', SERVER_MESAGE);// obtiene los objetos option del select recibido
$NUCL_form_sel.append($VIRTUAL_select_opt);// agrega los options al select del DOM
$NUCL_form_sel_opts = $('option', $NUCL_form_sel);// opbiene los option del select del DOM
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
}
function getCboEmpl() {
$.ajax({
type:'post',
url:'./ajax/ajax_agent_empl.php',
data: "mode=cboNc",
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
case "OK_CBO":
$VISIT_form_sel.append($('option',$(SERVER_MESAGE)));
return;
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
}
getListNucleos();
getCboMunicipios();
getCboEmpl();
});
