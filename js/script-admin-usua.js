$(function(){
	/* ADM - EMPLEADOS */
	$bloquer1 = $('.layer-bloquer');	// bloquea la pagina
	$bloquer1.css('opacity',0.3);
	$win = $(window);
	$win.bind('resize',function(e) {
		if($US_dial.css('display')=='block'){
			$dialog1_w = $US_dial.width();
			$dialog1_h = $US_dial.height();
			$win_w = $win.width();
			$win_h = $win.height();
			$dialog1_x = ($win_w/2) - ($dialog1_w/2);
			$dialog1_y = ($win_h/2) - ($dialog1_h/2);
			$US_dial.css({
				'left':$dialog1_x,
				'top':$dialog1_y
			});
		}
	});	
	$buttons = $('#mnu-empl a');
	
	$async = true;
	
	// form
	$US_form = $('form:[name=frmMunicipio]');
	$US_dial = $US_form.parent();
	$US_btns = $('input:button', $US_form);
	$US_txt = $('input:text', $US_form);
	$US_hdn = $('input:[type=hidden]', $US_form);
	$US_select = $('select', $US_form);
	$US_options = $('option', $US_select);
	$US_option = null;
	// table
	$US_table = $('.tbl-usuarios');
	$US_tbBody = $('.tbBody', $US_table);
	$US_tbHide = $('.tbHide', $US_table);
	$US_rows = $('tr', $US_tbBody);
	$US_row = null;
	$US_cells = null;
	$US_rdo = null;
	$US_noData = $('.no-data', $US_table);;

	// boton ACEPTAR/CANCELAR del formulario NUEVO/EDITAR USUARIO
	$US_btns.eq(0).bind('click', function() {
		$US_option = $('option:selected', $US_select);
		if($US_hdn.val()==''){ alert('Exception: no se ha establecido un modo al formulario'); return; }
		if(!$US_txt.eq(0).val() || !$US_txt.eq(1).val()){ alert('Todos los campos son requeridos!'); return; }
		if($US_hdn.val()=='nuevo') { 
			$mode = 'ins';
			$ie = "&ie=" + $US_option.val();	// NOMBRE
			if($US_option.index()==0) { alert("Seleccione un empleado para el que se va a crear la cuenta"); return; }
		} else if($US_hdn.val()=='edit') { $mode = 'upd'; $ie = "&ine=" + $US_txt.eq(2).val() + "&ie=" + $US_rdo.val(); ; 
		} else { alert("Modo de formulario invalido"); return; }
		var ajax_params = "mode=" + $mode + $ie + "&u=" + $US_txt.eq(0).val() + "&p=" + $US_txt.eq(1).val() + "&ia=0";
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_usua.php',
			data: ajax_params,
			async: $async,
			cache: false,
			success: function(SERVER_RESPONSE) {
				if(!SERVER_RESPONSE) { alert("La respuesta del servidor esta vacia"); return; }
				var DATA = SERVER_RESPONSE.split('|');
				if( DATA.length != 3 ){	alert("La respuesta del servidor tienen un formato de respuesta incorrecta" + "\n: " + SERVER_RESPONSE); return; }
				SERVER_CODE = DATA[0], SERVER_MESAGE = DATA[1],	SERVER_TEST = DATA[2];
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				switch (SERVER_CODE) {
					case "USER_SAVED":
						$(SERVER_MESAGE).prependTo($US_tbBody);
						resetRowsUser();
						$US_option.remove();	// elimina el empleado del combo de "empleados sin cuenta"
						$US_option = null;
						break;
					case "USER_UPDATED":
						$US_row.replaceWith($(SERVER_MESAGE));
						resetRowsUser();
						break;
					case "ERROR_EXIST_USER":
						alert(SERVER_MESAGE);
						return;
						break;
					default:
						alert(SERVER_CODE + "\n" + SERVER_MESAGE);
						return;
						break;
				}
				$US_dial.css('display','none');
				$bloquer1.css('display','none');
			},
			error: function(err) { alert("error: " + err); }
		});
	});
	$US_btns.eq(1).bind('click', function() {
		$bloquer1.css('display','none');
		$US_dial.css('display','none');
	});
	// botones NUEVO, EDITAR, ELIMINAR
	$buttons.bind('click',function(e) {
		e.preventDefault();
		$this = $(this);
		switch($this.text()) {
			case "Nuevo":
				// establece un valor vacio a los textboxs y hiddens, y selecciona el item 0 del select
				$US_txt.val('');
				$US_hdn.val('');
				$US_options.eq(0).attr('selected',true);
				// oculta el campo de texto EMPLEADO y muestra el combo EMPLEADOS
				$US_txt.eq(2).css('display','none');
				// ESTABLECE MODO DE FORMULARIO y habilita el campo USUARIO
				$US_hdn.val('nuevo');
				$US_txt.eq(0).attr('disabled',false);
				$US_select.css('display','block');
				$('th',$US_dial).text('Nuevo Usuario');
				$US_txt.eq(0).focus();
				showDialogUs();
			break; // NUEVO
			case "Editar":
				// comprueba si la tabla esta vacia
				if($US_noData.parent().parent().hasClass('tbBody')) { alert('Sin registros de usuarios!!'); return; }
				// si no hay ningun radio seleccionado, significa que no se ha seleccionado ninguna fila
				if($US_row==null){ alert('Seleccione un registro de un usuario'); return; }
				// obtiene el id del empleado perteneciente al usuario seleccionado
				$ID_EMPL = $US_rdo.val();
				// resetea/limpia el formulario
				$US_txt.val('');								// cajas de texto
				$US_hdn.val('');							// campos ocultos
				$US_hdn.val('edit');						// ESTABLECE MODO DE FORMULARIO
				$US_select.css('display','none');	// oculta el combo
				$US_txt.css('display','block');			// muestra todos los textbox ocultos
				// toma los valores de la fila seleccionada y los coloca en el formulario para su modificacion
				$US_txt.eq(0).val($US_cells.eq(0).text());	// usuario
				$US_txt.eq(1).val($US_cells.eq(1).text());	// password
				$US_txt.eq(2).val($US_cells.eq(2).text());	// password
				$US_txt.eq(0).attr('disabled',true);		// desabilita campo USUARIO
				$US_txt.eq(2).attr('disabled',true);		// desabilita campo EMPLEADO
				$('th',$US_dial).text('Editar Usuario');	// 
				showDialogUs();
			break; // EDITAR
			case "Eliminar":
				if($US_noData.parent().parent().hasClass('tbBody')) { alert('Sin registros de usuarios!!'); return; }
				if($US_row==null){ alert('Seleccione un registro de un usuario'); return; }
				if(!confirm("Eliminar el Usuario [" + $US_cells.eq(0).text() + "] ?")) { return; }
				var params = "mode=del&u=" + $US_cells.eq(0).text() + "&ie="+$US_rdo.val();
				$.ajax({
					type:'post',
					url:'./ajax/ajax_agent_usua.php',
					data: params,
					async: $async,
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
						if(SERVER_TEST) {	alert(SERVER_TEST); }
						switch (SERVER_CODE) {
							case "USER_DELETED":
								$US_select.append("<option value='" + $US_rdo.val() + "'>" + $US_cells.eq(2).text() + "</option>"); 
								$US_row.remove();
								resetRowsUser();
								break;
							case "ERROR_IA": alert(SERVER_MESAGE); break;
							default: alert(SERVER_CODE + "\n" + SERVER_MESAGE); break;
						}
					},
					error: function(err) { alert("error: " + err); }
				});
			break; // ELIMINAR
		}
	});
	function showDialogUs(){
		$dialog1_w = $US_dial.width(); $dialog1_h = $US_dial.height();
		$win_w = $win.width(); $win_h = $win.height();
		$dialog1_x = ($win_w/2) - ($dialog1_w/2);	$dialog1_y = ($win_h/2) - ($dialog1_h/2);
		$US_dial.css({ 'left':$dialog1_x, 'top':$dialog1_y });
		$bloquer1.css('display','block');
		$US_dial.css('display','block');
	}
	function getCboEmpl() {
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_empl.php',
			data: "mode=cbo",
			async: $async,
			cache: false,
			success: function(SERVER_RESPONSE) {
				if(!SERVER_RESPONSE) {
					alert("La respuesta del servidor esta vacia");
					return false;
				}
				var DATA = SERVER_RESPONSE.split('|');
				if( DATA.length != 3 ) {
					alert("La respuesta del servidor tienen un formato de respuesta incorrecta");
					alert(": " + SERVER_RESPONSE);
					return false;
				}
				SERVER_CODE = DATA[0];
				SERVER_MESAGE = DATA[1];
				SERVER_TEST = DATA[2];
				
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				
				switch (SERVER_CODE) {
					case "OK_CBO":
						$divCbo = $('.select', $US_dial);
						$divCbo.html(SERVER_MESAGE);
						$US_select = $('select', $US_form);
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
	function getListUsua() {
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_usua.php',
			data: "mode=list",
			async: $async,
			cache: false,
			beforeSend: function(){
				if($US_noData.parent().parent().hasClass('tbHide')){
					$US_rows.remove();
					$US_noData.parent().remove().appendTo($US_tbBody);
				} 
				$US_noData.text('cargando usuarios...');
			},
			success: function(SERVER_RESPONSE) {
				if(!SERVER_RESPONSE) { alert("La respuesta del servidor esta vacia"); return false; }
				var DATA = SERVER_RESPONSE.split('|');
				if( DATA.length != 3 ){	alert("La respuesta del servidor tienen un formato de respuesta incorrecta: \n" + SERVER_RESPONSE); return false; }
				SERVER_CODE = DATA[0];
				SERVER_MESAGE = DATA[1];
				SERVER_TEST = DATA[2];
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				switch (SERVER_CODE) {
					case "OK_LIST": resetRowsUser(SERVER_MESAGE); break;
					case "NOHAS":
						if($US_noData.parent().parent().hasClass('tbHide')){
							$US_rows.remove();
							$US_noData.parent().remove().appendTo($US_tbBody);
						} 
						$US_noData.text(SERVER_MESAGE);
						break;
					case "OKTEST":
						if($US_noData.parent().parent().hasClass('tbHide')){
							$US_rows.remove();
							$US_noData.parent().remove().appendTo($US_tbBody);
						} 
						$US_noData.text(SERVER_MESAGE);
						break;
					default: alert(SERVER_CODE + "\n" + SERVER_MESAGE); break;
				}
			},
			error: function(err) { alert("error: " + err); }
		});
	}
	getListUsua();
	getCboEmpl();
	
	/******************  funciones  *****************************/
	function resetRowsUser(html_val) {
		if($US_noData.parent().parent().hasClass('tbBody')){
			$US_noData.parent().remove().appendTo($US_tbHide);
			$US_noData.text('no data');
		}
		if(html_val){ $US_tbBody.html(html_val); }
		$US_rows = $('tr', $US_tbBody);
		$US_row = null;
		$US_cells = null;
		$US_rdo = null;
		if(!$US_rows.length){
			if($US_noData.parent().parent().hasClass('tbHide')) 
				$US_noData.parent().remove().appendTo($US_tbBody);
			$US_noData.text('No hay cuentas de usuarios registradas');
		} else {
			$rowSel = ('.tr-selected', $US_tbBody);
			if($rowSel.length){ $('input:radio', $rowSel).attr('checked',false); }
			$US_rows.removeClass('tr-selected');
			$US_rows.bind('click', function() {
				$US_rows.removeClass('tr-selected');
				$US_row = $(this);
				$US_row.addClass('tr-selected');
				$US_cells = $('td', $US_row);
				$US_rdo = $('input:radio', $US_row).attr('checked',true);
			});
		}
	}
});