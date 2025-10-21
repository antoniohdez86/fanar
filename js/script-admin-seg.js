$(function(){
var $datos_input = $('.formSeg input');
var $datos_input_text = $('.formSeg input:text, textarea');
$async = true;
// FORMULARIO DATA-NUCLEO
var $DATSEG_form = $('#frmData');
var $DATSEG_form_txt = $('input:text', $DATSEG_form);
var $DATSEG_form_hid = $('input:hidden', $DATSEG_form);
var $DATSEG_form_ie = null;// id empleado
var $DATSEG_form_im = null;// id municipio (uso futuro)
// FORMULARIO AGREGAR/EDITAR SEGUIMIENTO
var $ACT_form = $('#frmAdd');
var $ACT_form_in = $('input', $ACT_form);
var $ACT_form_hid = $('input:hidden', $ACT_form);
var $ACT_form_txt = $('input:text', $ACT_form);
var $ACT_form_com = $('textarea', $ACT_form);
var $ACT_form_btn = $('input:button', $ACT_form);
var $ACT_form_md = $ACT_form_hid.eq(0);// id ETAPA
var $ACT_form_ie = $ACT_form_hid.eq(1);// id ETAPA
var $ACT_form_fech = $('#dateSeg');// id ETAPA
$ACT_form_fech.datepicker({ 
dateFormat: 'yy-mm-dd' 
});
// FORMULARIO SEGUIMIENTOS
var $frmAdd = $('#frmAdd');
var $frmAdd_in = $('input, textarea',$frmAdd);
// tabla ETAPA
$ETAPA_table = $('.tbl-etapas');
$ETAPA_body1 = $('.tbBody',$ETAPA_table);
$ETAPA_body2 = $('.tbHide',$ETAPA_table);
$ETAPA_rows = $('tr',$ETAPA_body1);
$ETAPA_row = null;// fila campos de la etapa seleccionada
$ETAPA_cells = null;// coleccion de campos de la fila de la etapa seleccionada
$ETAPA_ie = null;// id etapa seleccionada
$ETAPA_noData = $('.no-data', $ETAPA_body1);
$ETAPA_noData_td = $('td', $ETAPA_noData);
// tabla SEGUIMIENTO
$SEG_table = $('.tbl-seguim');
$SEG_body1 = $('.tbBody',$SEG_table);
$SEG_tbHide = $('.tbHide',$SEG_table);
$SEG_rows = $('tr',$SEG_body1);
$SEG_row = null;// tr: current row
$SEG_cells = null;// td: cells of current row
$SEG_row_ie = null;// input: id de etapa del seguimiento seleccionado
$SEG_noData = $('.no-data',$SEG_body1);// tr: current row
$SEG_noData_td = $('td',$SEG_noData);// td: ...
$SEG_Eta_upd = null;// current row
$SEG_btns = $('.mnu_seg_btns a');
// tabla NUCLEOS ASIGNADOS AL USUARIO DE LA SESION ACTUAL
$NUC_table = $('.tbl-nucl-asig');
$NUC_body1 = $('tbody',$NUC_table).eq(1);
$NUC_rows = $('tr',$NUC_body1);
$NUC_row = null;// current row
$NUC_cells = null;// cells of current row
$NUC_noData = $('.no-data', $NUC_body1);// current row
$NUC_noData_td = $('td', $NUC_noData);// current row
// estos son variables usados al editar un seguimiento
$BAK_ie = null;// 
$BAK_fs = null;
$BAK_os = null;
// inicializa estilos visuales
$datos_input_text.css({
'background-color':'#B7FFB7',
'border':'1px solid #8CD385'
});
// BOTONES DEL FORMULARIO "AGREGAR/EDITAR SEGUIMIENTO"
$ACT_form_btn.bind('click', function(e) {
e.preventDefault();
var $BUTTON = $(this);
switch($BUTTON.val()) {
// AGREGAR SEGUIMIENTO
case "Agregar": 
// al agregar un seguimiento los datos necesarios que se enviaran al servidor son:
//1) ID de la etapa
//2) fecha de seguimiento
//3) observaciones
$ETA_rows_enable = $('tr:[class!=disabled]', $ETAPA_body1);// obtiene todas las etapas validas (etapas seleccionables)
$ETA_rows_disable = $('tr:[class=disabled]', $ETAPA_body1);// obtiene todas las etapas NO validas (etapas no seleccionables)
$ETA_rows_total = $('tr', $ETAPA_body1);// obtiene todas las etapas (validas e invalidas)
// comprueba que haya etapas seleccionables
if(!$ETA_rows_enable.length){ alert('Etapas completas'); return; }
$compl = ($ETA_rows_enable.length==1) ? 1 : 0;// indicador de si al agregar este seguimiento se completan todos los seguimientos para este nucleo
if($ETAPA_row==null){
alert('Seleccione una etapa'); return;
}
if(!$ACT_form_ie.val() || !$ACT_form_txt.eq(2).val() || !$ACT_form_com.val()) { alert("Todos los campos son requeridos"); return; }
var ajax_params = "mode=aseg&in="+$IN+"&ie=" + $ACT_form_ie.val() + "&fe=" + $ACT_form_txt.eq(2).val() + "&o=" + $ACT_form_com.val() + "&c="+$compl;
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
async:$async,
data: ajax_params,
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
case "SEG_SAVED":
if($SEG_noData.parent().attr('class')=='tbBody') {
$SEG_noData.remove().appendTo($SEG_tbHide);
}
// crea fila con datos de nuevo seguimiento
$tr = $("<tr><td align='center'><input type='hidden' value='" + $ACT_form_ie.val() + "'>" + $ACT_form_txt.eq(0).val()+"</td><td align='center'>" + $ACT_form_txt.eq(2).val() + "</td><td align='center'>" + $DATSEG_form_ie.val() + "</td><td>" + $ACT_form_com.val() + "</td></tr>");
// lo agrega a la tabla de seguimientos
$tr.appendTo($SEG_body1);
// elimina la etapa seleccionada de la tabla etapas
$ETAPA_row.remove().appendTo($ETAPA_body2);
// limpia formulario
$ACT_form_ie.val('')// ID ETAPA
$ACT_form_txt.eq(0).val('');// ACT
$ACT_form_txt.eq(1).val('');// DESCRIPC
$ACT_form_txt.eq(2).val('');// FECH
$ACT_form_com.val('');// OBSERV
$SEG_rows = $('tr', $SEG_body1);// obtiene la coleccion de seguimientos
$SEG_rows.bind('click', function() {
$SEG_row = $(this);
$SEG_rows.removeClass('selected');// deselecciona todas los seguimientos
$SEG_row.addClass('selected');// resalta la fila seleccionada
});
$ETAPA_row = null;
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
break;
case "Guardar Cambios":
// comprueba que no haya campos vacios
if(!$ACT_form_ie.val() || !$ACT_form_txt.eq(2).val() || !$ACT_form_com.val()) { alert("Todos los campos son requeridos"); return; }
// comprueba si se estan guardando pero con los mismos datos
if($ACT_form_ie.val()==$BAK_ie && $ACT_form_txt.eq(2).val()==$BAK_fs && $ACT_form_com.val()==$BAK_os){
$ACT_form_ie.val('0');
$ACT_form_txt.val('');
$ACT_form_com.val('');
$ACT_form_btn.eq(0).attr('disabled',false).css('display','inline');
$ACT_form_btn.eq(1).attr('disabled',true).css('display','none');
$ACT_form_btn.eq(2).attr('disabled',true).css('display','none');
// vuelve a programa el evento click sobre cada fila seguimiento
$SEG_rows.bind('click', function() {
$SEG_row = $(this);
$SEG_rows.removeClass('selected');// deselecciona todas los seguimientos
$SEG_row.addClass('selected');// resalta la fila seleccionada
});
$ACT_form_md.val('nuevo');
return;// no es necesario
}
var ajax_params = "mode=useg&in="+$IN+"&ie=" + $ACT_form_ie.val() + "&iea=" + $BAK_ie +  "&fe=" + $ACT_form_txt.eq(2).val() + "&o=" + $ACT_form_com.val();
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
data: ajax_params,
async:$async,
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
case "SEG_UPDATED":
// una ves actualizado el seguimiento se COMPRUEBA SI SE HIZO CAMBIOS TAMBIEN EN LA ETAPA
if($BAK_ie != $ACT_form_ie.val()){
// SE CAMBIO DE ETAPA
var $tmp = $('input', $SEG_cells.eq(0));// guarda antes el input
$SEG_cells.eq(0).text($ACT_form_txt.eq(0).val());// ACTVIDAD
$SEG_cells.eq(0).prepend($tmp);// se agrega el input a la celda ACTIVIDAD
$tmp.val($ETAPA_ie.val());// se cambia el valor del input CON EL ID DE LA NUEVA ETAPA SELECCIONADA
loadEtapas();// RECARGA ETAPAS
//$ETAPA_row.remove();
//$SEG_Eta_upd.appendTo
}
$SEG_cells.eq(1).text($ACT_form_txt.eq(2).val());//FECHA
//$SEG_cells.eq(2).text(...);//VISITADOR (ID) no es necesario actualizar
$SEG_cells.eq(3).text($ACT_form_com.val());//OBSERVACIONES
// limpia formulario
$ACT_form_ie.val('')// ID ETAPA
$ACT_form_txt.eq(0).val('');// ACT
$ACT_form_txt.eq(1).val('');// DESCRIPC
$ACT_form_txt.eq(2).val('');// FECH
$ACT_form_com.val('');// OBSERV
$SEG_rows = $('tr', $SEG_body1);// obtiene la coleccion de seguimientos
$SEG_rows.bind('click', function() {
$SEG_row = $(this);
$SEG_rows.removeClass('selected');// deselecciona todas los seguimientos
$SEG_row.addClass('selected');// resalta la fila seleccionada
});
//$ETAPA_row = null;// 
$ACT_form_ie.val('0');
$ACT_form_txt.val('');
$ACT_form_com.val('');
$ACT_form_btn.eq(0).attr('disabled',false).css('display','inline');
$ACT_form_btn.eq(1).attr('disabled',true).css('display','none');
$ACT_form_btn.eq(2).attr('disabled',true).css('display','none');
// vuelve a programa el evento click sobre cada fila seguimiento
$SEG_rows.bind('click', function() {
$SEG_row = $(this);
$SEG_rows.removeClass('selected');// deselecciona todas los seguimientos
$SEG_row.addClass('selected');// resalta la fila seleccionada
});
$ACT_form_md.val('nuevo');
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
break;
case "Cancelar":
$ACT_form_ie.val('0');
$ACT_form_txt.val('');
$ACT_form_com.val('');
$ACT_form_btn.eq(0).attr('disabled',false).css('display','inline');
$ACT_form_btn.eq(1).attr('disabled',true).css('display','none');
$ACT_form_btn.eq(2).attr('disabled',true).css('display','none');
// vuelve a programa el evento click sobre cada fila seguimiento
$SEG_rows.bind('click', function() {
$SEG_row = $(this);
$SEG_rows.removeClass('selected');// deselecciona todas los seguimientos
$SEG_row.addClass('selected');// resalta la fila seleccionada
});
$ACT_form_md.val('nuevo');
break;
default: alert('error: ' + $BUTTON.text()); break;
}
});
// botones EDITAR/ELIMINAR un SEGUIMIENTO
$SEG_btns.bind('click', function(e) {
e.preventDefault();
$this = $(this);
$mod = null;
if($ACT_form_md.val()=='edit'){ return; }
// sino se ha definido ningun nucleo entonces significa que no hay seguimientos cargados, asi q no tiene caso hacer mas comprobaciones
if(!$IN) return;
if($SEG_noData.parent().hasClass('tbBody')){
alert('El nucleo no tienen ningun seguimiento');
return;
}
// obtiene fila seleccionada
$SEG_row = $('tr.selected',$SEG_body1);
// si no hay ninguna fila seleccionada...
if(!$SEG_row.length){
alert('Seleccione un seguimiento'); return;
}
// obtiene la coleccion de celdas de la fila selecionada
$SEG_cells = $('td',$SEG_row);
switch($this.text()) {
// EDITAR
case "Editar":
$ACT_form_md.val('edit');
var ajax_params = "mode=loadE&ie=" + $('input', $SEG_row).val()+"&in=" + $IN;
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
data: ajax_params,
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
case "SEG_LOADED":
$SEG_Eta_upd = $(SERVER_MESAGE);// DATOS DE ETAPA X
var $TDs = $('td', $SEG_Eta_upd);// COLECCION DE CAMPOS DE ETAPA X
// hace una copia de los datos antes de cualquier modificacion para comprobar posteriormente 
// si al guardar es nesario o no la actualizacion del seguimiento contra la base de datos
$BAK_ie = $('input',$SEG_cells.eq(0)).val();// copia de id etapa
$BAK_fs = $SEG_cells.eq(1).text();// copia de fecha
$BAK_os = $SEG_cells.eq(3).text();// copia de observaciones
// carga de datos del seguimiento seleccionado al formulario
$ACT_form_ie.val($BAK_ie);// ID ETAPA
$ACT_form_txt.eq(0).val($TDs.eq(1).text());// ACTIVIDAD
$ACT_form_txt.eq(1).val($TDs.eq(2).text());// DESCRIPCION
$ACT_form_txt.eq(2).val($SEG_cells.eq(1).text());// FECHA
$ACT_form_com.eq(0).val($SEG_cells.eq(3).text());// OBSERVACION
// desabilita TABLA SEGUIMIENTOS
$SEG_rows.unbind('click');
// restaura BOTONES PREDETERMINADOS
$ACT_form_btn.eq(0).attr('disabled',true).css('display','none');
$ACT_form_btn.eq(1).attr('disabled',false).css('display','inline');
$ACT_form_btn.eq(2).attr('disabled',false).css('display','inline');
break;
case "NOETAP": 
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
// ELIMINAR
case "Eliminar":
if(!confirm("Eliminar seguimiento seleccionado?")){ return; }
var ajax_params = "mode=delseg&ie=" + $('input', $SEG_row).val()+"&in=" + $IN;
//alert(ajax_params);
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
data: ajax_params,
async:$async,
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
case "SEG_DELETED":
$SEG_row.remove();
$SEG_rows = $('tr',$SEG_body1);
if(!$SEG_rows.length){
//$SEG_body1.append($("<tr><td colspan=4 class='no-data'>no hay seguimientos por el momento para este nucleo</td></tr>"));
$SEG_noData.remove().appendTo($SEG_body1);
$SEG_noData_td.text('nucleo sin seguimientos');
}
loadEtapas();
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
break;
// test
case "Test":
alert($SEG_row.html());
break;
// DEFAULT
degault:
alert('indice no soportado');
break;
}
});
// carga... nucleos designados al visitante que inicia su sesion
function loadNucl() {
$NUC_noData_td.text('Cargando...');
params = "mode=ls2&ie="+$IE;
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
data: "mode=ls2&ie="+$IE,
async:$async,
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
case "OK_LIST":
$NUC_body1.html(SERVER_MESAGE);// agrega contenido (seguimientos) como codigo html
$NUC_rows = $('tr', $NUC_body1);// obtiene la coleccion de seguimientos
// programa evento click sobre cada fila seguimiento
$NUC_rows.bind('click', function() {
$NUC_row = $(this);
$NUC_rows.removeClass('selected');// deselecciona todas los seguimientos
$NUC_row.addClass('selected');// resalta la fila seleccionada
$NUC_cells = $('td', $NUC_row);
$IN = $NUC_cells.eq(0).text()
cargarDatosDeNucleo();// carga datos y seguimientos de un nucleo
});
break;
case "NOHASNUCL": 
$NUC_noData_td.text(SERVER_MESAGE);
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
}
// carga las etapas faltantes de un nucleo especifico
function loadEtapas() {
// reset de variables
$ETAPA_rows = null;// coleccion de filas
$ETAPA_row = null;// fila campos de la etapa seleccionada
$ETAPA_cells = null;// coleccion de campos de la fila de la etapa seleccionada
$ETAPA_ie = null;// id etapa seleccionada
if($ETAPA_noData.parent().attr('class')=='tbHide') {
$ETAPA_body1.html('');
$ETAPA_noData.remove().appendTo($ETAPA_body1);
}
$ETAPA_noData_td.text('Cargando...');
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
async:$async,
data: "mode=lstet&in="+$IN,
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
case "OK_LIST":
$ETAPA_noData.remove().appendTo($ETAPA_body2);
$ETAPA_noData_td.text('no-data');
$ETAPA_body1.html(SERVER_MESAGE);
$ETAPA_rows = $('tr', $ETAPA_body1);
$ETAPA_rows_enabled = $('tr:[class!=disabled]',$ETAPA_body1);
// si no hay ningun seguimiento, se desabilita el formulario "Nuevo Seguimiento"
if($ETAPA_rows_enabled.length==0) {
//$ACT_form_in.attr('disabled',true);// desabilita formulario (NO SE DEBE HACER ESTO)
} else { // si hay seguimientos... 
$ACT_form_in.attr('disabled',false);// habilita formulario
$ACT_form_btn.eq(1).attr('disabled',true);//.css('display','none');
$ACT_form_btn.eq(2).attr('disabled',true);//.css('display','none');
}
$ETAPA_rows.bind('click',function() {
$ETAPA_row = $(this);
if($ETAPA_row.hasClass('disabled'))return; // en etapas desabilitadas no se debe hacer nada, es decir no deben ser "seleccionables"
$ETAPA_ie = $('input:hidden', $ETAPA_row);// ID de la etapa seleccionada
$ETAPA_cells = $('td', $ETAPA_row);
$ETAPA_rows.removeClass('selected');
$ETAPA_row.addClass('selected');
$ACT_form_ie.val($ETAPA_ie.val());
$ACT_form_txt.eq(0).val($ETAPA_cells.eq(1).text());
$ACT_form_txt.eq(1).val($ETAPA_cells.eq(2).text());
});
break;
case "NOETAPS": 
//alert('sin etapas');
if($ETAPA_noData.parent().attr('class')=='tbBody'){
// si no-data ya se encuentra en tbBody solo le cambia el texto
$ETAPA_noData_td.text(SERVER_MESAGE);
} else if($ETAPA_noData.parent().attr('class')=='tbHide') {
$ETAPA_body1.html('');
$ETAPA_noData.remove().appendTo($ETAPA_body1);
$ETAPA_noData_td.text(SERVER_MESAGE)
} else {
alert(SERVER_MESAGE)
}
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
}
// carga los seguimientos hechos a un nucleo especifico
function loadSeg() {
// reset variables
$SEG_rows = null;// tr: coleccion de filas
$SEG_row = null;// tr: fila actual
$SEG_cells = null;// td: coleccion del celdas de una fila
$SEG_row_ie = null;// input: id de etapa del seguimiento seleccionado
if($SEG_noData.parent().attr('class')=='tbHide') {
$SEG_body1.html('');
$SEG_noData.remove().appendTo($SEG_body1);
} 
$SEG_noData_td.text('Cargando...');
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
data: "mode=lstseg&in="+$IN,
async:$async,
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
case "OK_LIST":
$SEG_noData.remove().appendTo($SEG_tbHide);
$SEG_noData_td.text('no-data');
$SEG_body1.html(SERVER_MESAGE);// agrega contenido (seguimientos) como codigo html
$SEG_rows = $('tr', $SEG_body1);// obtiene la coleccion de seguimientos
// programa evento click sobre cada fila seguimiento
$SEG_rows.bind('click', function() {
$SEG_row = $(this);// etapa seleccionada
$SEG_cells = $('td', $SEG_row);// celdas de etapa seleccionada
$SEG_row_ie = $('input', $SEG_cells.eq(0));// input: id de etapa seleccionada
$SEG_rows.removeClass('selected');// deselecciona todas los seguimientos
$SEG_row.addClass('selected');// resalta la fila seleccionada
});
break;
case "NOHASSEG":
if($SEG_noData.parent().attr('class')=='tbBody'){
// si no-data ya se encuentra en tbBody solo le cambia el texto
$SEG_noData_td.text(SERVER_MESAGE);
} else if($SEG_noData.parent().attr('class')=='tbHide') {
$SEG_body1.html('');
$SEG_noData.remove().appendTo($SEG_body1);
$SEG_noData_td.text(SERVER_MESAGE);
} else {
alert(SERVER_MESAGE)
}
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
}
// carga los datos de un nucleo especifico y datos de su visitante
function loadDatos() {
$.ajax({
type:'post',
url:'./ajax/ajax_agent_nucl.php',
data: "mode=segdat&in="+$IN,
async:$async,
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
case "OK_DAT":
$td = $('td', SERVER_MESAGE);// coleccion de celdas temporales
$DATSEG_form_ie = $('input:hidden', $td.eq(0));
$DATSEG_form_hid.eq(0).val($DATSEG_form_ie.val());// id del empleado
$DATSEG_form_txt.eq(0).val($td.eq(0).text());// nombre de empleado (con input del id del empleado)
$DATSEG_form_txt.eq(1).val($td.eq(1).text());// municipio
$DATSEG_form_txt.eq(2).val($td.eq(2).text());// nucleo
$DATSEG_form_txt.eq(3).val($td.eq(3).text());// superficie
$DATSEG_form_txt.eq(4).val($td.eq(4).text());// tipo
break;
default:
alert(SERVER_CODE + "\n" + SERVER_MESAGE);
break;
}
},
error: function(err) { alert("error: " + err); }
});
}
// si se ha especificado el id de un EMPLEADO carga todos los nucleos a su cargo
function cargarNucleosDeVisitante() {
if($IE){
loadNucl();
}
}
// carga nucleos a cargo de un visitante especifico
function cargarDatosDeNucleo() {
// si se ha especificado el id de un NUCLEO carga todos los datos sobre este
if($IN) {
loadSeg();
loadDatos();
loadEtapas();
$ACT_form_md.val('nuevo');
$ACT_form_txt.val('');
$ACT_form_txt.eq(2).attr('readonly', false);// habilita campo fecha
$ACT_form_com.val('').attr('readonly', false);// habilita campo observaciones
$ACT_form_btn.eq(0).attr('disabled',false).css('display','inline');//oculta boton 'Guardar cambios'
$ACT_form_btn.eq(1).attr('disabled',true).css('display','none');//oculta boton 'Guardar cambios'
$ACT_form_btn.eq(2).attr('disabled',true).css('display','none');//oculta boton 'Cancelar'
} else {
// sino se especifica ningun nucleo para su carga de datos, entonces no es necesario habilitar los controles de formulario
$ACT_form_txt.eq(2).val('').attr('readonly', true);// habilita campo fecha
$ACT_form_com.val('').attr('readonly', true);// habilita campo observaciones
$ACT_form_btn.attr('disabled');// desabilita todos los botones
$ACT_form_btn.eq(1).css('display','inline');//oculta boton 'Guardar cambios'
$ACT_form_btn.eq(1).css('display','none');//oculta boton 'Guardar cambios'
$ACT_form_btn.eq(2).css('display','none');//oculta boton 'Cancelar'
}
}
cargarNucleosDeVisitante();
cargarDatosDeNucleo();
//---------------------------- TESTEOS ------------------------------------
function print_Data_Seg() {
var cad = "";
cad += "id: " + $DATSEG_form_ie.val() + "\n";
cad += "nombre: " + $DATSEG_form_txt.eq(0).val() + "\n";
cad += "municipio: " + $DATSEG_form_txt.eq(1).val() + "\n";
cad += "nucleo: " + $DATSEG_form_txt.eq(2).val() + "\n";
cad += "superficie: " + $DATSEG_form_txt.eq(3).val() + "\n";
cad += "tipo: " + $DATSEG_form_txt.eq(4).val() + "\n";
alert(cad);
}
//alert($SEG_noData.parent().attr('class'));
//$DATSEG_form_txt.eq(0).bind('click', function(){
//print_Data_Seg();
//});
});
