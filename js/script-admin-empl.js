$(function(){
	
	/* ADM - EMPLEADOS */
	
	/************ inicializa dialogo para nuevo/modificar un municipio ******************/
	$bloquer1 = $('.layer-bloquer').css('opacity',0.3);	// bloquea la pagina
	$('.infoUsr').css('opacity','0.8');
   $async = true;
	$win = $(window);
	$win.bind('resize',function(e) {
		if($EMPL_dialog.css('display')=='block'){
			$dialog1_w = $EMPL_dialog.width();
			$dialog1_h = $EMPL_dialog.height();
			$win_w = $win.width();
			$win_h = $win.height();
			$dialog1_x = ($win_w/2) - ($dialog1_w/2);
			$dialog1_y = ($win_h/2) - ($dialog1_h/2);
			$EMPL_dialog.css({'left':$dialog1_x,'top':$dialog1_y});
		}
	});
	
	$EMPL_table = $('#tbl-empleados');
	$EMPL_table_msg = $('.tbl-empl-log .message');
	$EMPL_tbBody = $('.tbBody',$EMPL_table);
	$EMPL_tbHide = $('.tbHide',$EMPL_table);
	$EMPL_rows = $('tr',$EMPL_tbBody);
	$EMPL_row = null;
	$EMPL_cells = null;
	$EMPL_rdo = null;
	$EMPL_noData = $('.no-data',$EMPL_table);;
	
	$EMPL_form = $('form:[name=frmEmpl]');
	$EMPL_dialog = $EMPL_form.parent();	// 1
	$EMPL_form_title = $('.title', $EMPL_form);	// 1
	$EMPL_form_msg = $('.message', $EMPL_form);	// 1
	$EMPL_form_txt = $('input:[type=text]', $EMPL_form);	// 7
	$EMPL_form_hdn = $('input:[type=hidden]', $EMPL_form);  // 1
	$EMPL_form_btn = $('input:[type=button]', $EMPL_form);  // 2
	$EMPL_form_chk = $('input:[type=checkbox]', $EMPL_form);  // 1
	
	// boton ACEPTAR/CANCELAR del formulario NUEVO/EDITAR EMPLEADO
	$EMPL_form_btn.bind('click', function(){
		var $button = $(this);
		if($button.val()=='Cancelar'){
			$bloquer1.css('display','none');
			$EMPL_dialog.css('display','none');
			return;
		}
		$checkbox = ($EMPL_form_chk.attr('checked')=='checked') ? 'true' : 'false';
		$form_ok = true;
		$EMPL_form_txt.each(function(i){
			var $textbox = $(this);
			if($.trim($textbox.val())==''){ $form_ok = false; return; } // ... y verifica si hay campos vacios
		});
		if(!$form_ok){	alert('Todos los campos son requeridos!'); return false; }
		if(isNaN($.trim($EMPL_form_txt.eq(0).val()))){ alert('El id de empleado debe ser un valor numerico'); return false; }
		$mode = '';
		$msg = ''
		var ajax_params = '';
		if($EMPL_form_hdn.val()=='edit') {		// actualizar
			$mode = 'upd';
			ajax_params = "mode=" + $mode + "&id=" + $EMPL_form_txt.eq(0).val() + "&nombre=" + $EMPL_form_txt.eq(1).val() + "&ape1=" + $EMPL_form_txt.eq(2).val() + "&ape2=" + $EMPL_form_txt.eq(3).val() + "&dir=" + $EMPL_form_txt.eq(4).val() + "&prof=" + $EMPL_form_txt.eq(5).val() + "&tel=" + $EMPL_form_txt.eq(6).val() + "&act=" + $checkbox + "&na="+$EMPL_cells.eq(8).text();
			$msg = 'Actualizando empleado...';
		} else if($EMPL_form_hdn.val()=='nuevo'){	// nuevo empleado
			$mode = 'ins';
			ajax_params = "mode=" + $mode + "&id=" + $EMPL_form_txt.eq(0).val() + "&nombre=" + $EMPL_form_txt.eq(1).val() + "&ape1=" + $EMPL_form_txt.eq(2).val() + "&ape2=" + $EMPL_form_txt.eq(3).val() + "&dir=" + $EMPL_form_txt.eq(4).val() + "&prof=" + $EMPL_form_txt.eq(5).val() + "&tel=" + $EMPL_form_txt.eq(6).val() + "&act=" + $checkbox;
			$msg = 'Registrando empleado...';
		}
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_empl.php',
			data: ajax_params,
			cache: false,
			async:$async,
			beforeSend:function(){ $EMPL_form_msg.text($msg).show(); },
			success: function(SERVER_RESPONSE) {
				$EMPL_form_msg.text('').hide();
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
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				switch (SERVER_CODE) {
					case "EMPL_SAVED":
						$(SERVER_MESAGE).prependTo($EMPL_tbBody); 
						resetRows();
						break;
					case "ERROR_EXIST_EMPL_ID":
						alert(SERVER_MESAGE);
						return;
						break;
					case "EMPL_UPDATED":
						$EMPL_row.replaceWith($(SERVER_MESAGE)); 
						resetRows();
						break;
					case "ERRIA":
						alert(SERVER_MESAGE);
						return;
						break;
					default:
						alert(SERVER_CODE + "\n" + SERVER_MESAGE);
						return;
						break;
				}
				$EMPL_dialog.css('display','none');
				$bloquer1.css('display','none');
			},
			error: function(err) { alert("error: " + err); }
		});
	});

	// inicializa botones
	var $Menu = $('#mnu-empl a');
	$Menu.bind('click',function(e) {
		e.preventDefault();
		$item = $(this);
		switch($item.text()) {
			case "Nuevo":
				$EMPL_form_txt.val('');
				$EMPL_form_chk.attr('checked',false);
				$EMPL_form_hdn.val('nuevo'); // ESTABLECE MODO DE FORMULARIO
				$EMPL_form_txt.eq(0).attr('disabled',false);
				$EMPL_form_title.text('Nuevo Empleado');
				$EMPL_form_txt.eq(0).focus();
				showDialogEmpl();
			break;  // NUEVO
			case "Editar":
				if($EMPL_noData.parent().parent().hasClass('tbBody')) { alert('Sin registros de empleados!!'); return; }
				if($EMPL_row==null){ alert('Seleccione un registro de empleado'); return; }
				// resetea/limpia el formulario
				$EMPL_form_hdn.val('edit');	// ESTABLECE MODO DE FORMULARIO
				$EMPL_form_txt.eq(0).val($EMPL_cells.eq(0).text()).attr('disabled',true);	// ID
				$EMPL_form_txt.eq(1).val($EMPL_cells.eq(1).text());	// NOMBRE
				$EMPL_form_txt.eq(2).val($EMPL_cells.eq(2).text());	// APELL1
				$EMPL_form_txt.eq(3).val($EMPL_cells.eq(3).text());	// APELL2
				$EMPL_form_txt.eq(4).val($EMPL_cells.eq(4).text());	// DIRECC
				$EMPL_form_txt.eq(5).val($EMPL_cells.eq(5).text());	// PROFES
				$EMPL_form_txt.eq(6).val($EMPL_cells.eq(6).text());	// TELEFONO
				$EMPL_form_txt.eq(0).attr('disabled',true);
				$span = $('span',$EMPL_cells.eq(7));
				$value = null;
				if($span.hasClass('up')) { $value=true; } else if($span.hasClass('down')) { $value=false } else { alert($span[0].type) }
				$EMPL_form_chk.attr('checked', $value);
				$EMPL_form_title.text('Editar Empleado');
				showDialogEmpl();
			break; // EDITAR
			case "Eliminar":
				if($EMPL_noData.parent().parent().hasClass('tbBody')) { alert('Sin registros de empleados!!'); return; }
				if($EMPL_row==null){ alert('Seleccione un registro de empleado'); return; }
				if(!confirm("Eliminar empleado [" + $EMPL_cells.eq(0).text() + ", " + $EMPL_cells.eq(1).text() + " " +$EMPL_cells.eq(2).text() + " " + $EMPL_cells.eq(3).text() + "] ?"))
					return;
				$.ajax({
					type:'post',
					url:'./ajax/ajax_agent_empl.php',
					data: "mode=del&id=" + $EMPL_cells.eq(0).text(),
					async: $async,
					cache: false,
					beforeSend:function(){ $EMPL_table_msg.text('Eliminando registro de empleado...').show(); },
					success: function(SERVER_RESPONSE) {
						$EMPL_table_msg.text('').hide();
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
						if(SERVER_TEST) {	alert(SERVER_TEST); }
						switch (SERVER_CODE) {
							case "EMPL_DELETED":
								$EMPL_row.remove();
								resetRows();
								break;
							case "ERR_EMPL_REF":
								alert(SERVER_MESAGE);
								break;
							case "ERRIA":
								alert(SERVER_MESAGE);
								break;
							default:
								alert(SERVER_CODE + "\n" + SERVER_MESAGE);
								break;
						}
					},
					error: function(err) { alert("error: " + err); }
				});
			break;  // ELIMINAR
		}
	});
	
	function getListEmpl() {
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_empl.php',
			data: "mode=list",
			async:$async,
			cache: false,
			beforeSend: function(){
				if($EMPL_noData.parent().parent().hasClass('tbHide')){
					$EMPL_rows.remove();
					$EMPL_noData.parent().remove().appendTo($EMPL_tbBody);
				} 
				$EMPL_noData.text('cargando empleados...');
			},
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
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				switch (SERVER_CODE) {
					case "OK_LIST":
						resetRows(SERVER_MESAGE);
						break;
					case "NOHAS": 
						if($EMPL_noData.parent().parent().hasClass('tbHide')){
							$EMPL_rows.remove();
							$EMPL_noData.parent().remove().appendTo($EMPL_tbBody);
						} 
						$EMPL_noData.text(SERVER_MESAGE);
					break;
					default: alert(SERVER_CODE + "\n" + SERVER_MESAGE); break;
				}
			},
			error: function(err) { alert("error: " + err); }
		});
	}
	getListEmpl();
	
	function showDialogEmpl(){
		$dialog1_w = $EMPL_dialog.width(); 
		$dialog1_h = $EMPL_dialog.height();
		$win_w = $win.width(); 
		$win_h = $win.height();
		$dialog1_x = ($win_w/2) - ($dialog1_w/2);	
		$dialog1_y = ($win_h/2) - ($dialog1_h/2);
		$bloquer1.css('display','block');
		$EMPL_dialog.css({ 'left':$dialog1_x, 'top':$dialog1_y, 'display':'block'});
	}
	function resetRows(html_val) {
		if($EMPL_noData.parent().parent().hasClass('tbBody')){
			$EMPL_noData.parent().remove().appendTo($EMPL_tbHide);
			$EMPL_noData.text('no data');
		}
		if(html_val){ $EMPL_tbBody.html(html_val); }
		$EMPL_rows = $('tr', $EMPL_tbBody);
		$EMPL_row = null;
		$EMPL_cells = null;
		$EMPL_rdo = null;
		if(!$EMPL_rows.length){
			if($EMPL_noData.parent().parent().hasClass('tbHide')){
				$EMPL_noData.parent().remove().appendTo($EMPL_tbBody);
			}
			$EMPL_noData.text('No hay empleados registrados');
		} else {
			$rowSel = ('.tr-selected', $EMPL_tbBody);
			if($rowSel.length){ $('input:radio', $rowSel).attr('checked',false); }
			$EMPL_rows.removeClass('tr-selected');
			$EMPL_rows.bind('click', function() {
				$EMPL_rows.removeClass('tr-selected');
				$EMPL_row = $(this);
				$EMPL_row.addClass('tr-selected');
				$EMPL_cells = $('td', $EMPL_row);
				$EMPL_rdo = $('input:radio', $EMPL_row).attr('checked',true);
			});
		}
	}
});