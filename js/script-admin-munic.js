$(function(){
	/* ADM - MUNICIPIOS */
	$buttons = $('#mnu-munic a');
	$bloquer1 = $('.layer-bloquer');
	$bloquer1.css('opacity',0.3);
	$win = $(window);
	$win.bind('resize',function(e) {
		if($MUN_dial.css('display')=='block'){
			$dialog1_w = $MUN_dial.width();
			$dialog1_h = $MUN_dial.height();
			$win_w = $win.width();
			$win_h = $win.height();
			$dialog1_x = ($win_w/2) - ($dialog1_w/2);
			$dialog1_y = ($win_h/2) - ($dialog1_h/2);
			$MUN_dial.css({'left':$dialog1_x,'top':$dialog1_y});
		}
	});
	// form
	$MUN_form = $('form:[name=frmMunicipio]');
	$MUN_dial = $MUN_form.parent();
	$MUN_txt = $('input:text', $MUN_form);
	$MUN_hdn = $('input:[type=hidden]', $MUN_form);
	$MUN_btn = $('input:button', $MUN_form);
	// table
	$MUN_table = $('.tbl-municipios');
	$MUN_tbBody = $('.tbBody', $MUN_table);
	$MUN_tbHide = $('.tbHide', $MUN_table);
	$MUN_rows = $('tr', $MUN_tbBody);
	$MUN_row = null;
	$MUN_cells = null;
	$MUN_rdo = null;
	$MUN_noData = $('.no-data', $MUN_table);
	$MUN_btn.eq(0).bind('click', function() {
		if($MUN_hdn.val()==''){
			alert('Exception: no se ha establecido un modo al formulario');
			return;
		}
		$form_ok = true;
		$MUN_txt.each(function(i){
			if($(this).val()=='') {	$form_ok = false;	return; }
		});
		if(!$form_ok){ alert('Todos los campos son requeridos!'); return false; }
		if($MUN_hdn.val()=='edit') $mode = 'upd';
		else if($MUN_hdn.val()=='nuevo') $mode = 'ins';
		else { alert("Modo de formulario invalido"); return; }
		var ajax_params = "mode=" + $mode + "&id=" + $MUN_txt.eq(0).val() + "&municipio=" + $MUN_txt.eq(1).val();
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_munic.php',
			data: ajax_params,
			cache: false,
			success: function(SERVER_RESPONSE) {
				if(!SERVER_RESPONSE) { alert("La respuesta del servidor esta vacia"); return false; }
				var DATA = SERVER_RESPONSE.split('|');
				if(DATA.length!=3){
					alert("La respuesta del servidor tienen un formato de respuesta incorrecta");
					alert(": " + SERVER_RESPONSE);
					return false;
				}
				SERVER_CODE = DATA[0];
				SERVER_MESAGE = DATA[1];
				SERVER_TEST = DATA[2];
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				switch (SERVER_CODE) {
					case "MUNIC_SAVED": $(SERVER_MESAGE).prependTo($MUN_tbBody); resetRowsMunic(); break;
					case "MUNIC_UPDATED": $MUN_row.replaceWith($(SERVER_MESAGE)); resetRowsMunic(); break;
					case "ERROR_EXIST_MUNIC_ID": alert(SERVER_MESAGE); return; break;
					default:
						alert(SERVER_CODE + "\n" + SERVER_MESAGE);
						break;
				}
				$MUN_dial.css('display','none');
				$bloquer1.css('display','none');
			},
			error: function(err) { alert("error: " + err); }
		});
	});
	$MUN_btn.eq(1).bind('click', function() {
		$bloquer1.css('display','none');
		$MUN_dial.css('display','none');
	});
	$buttons.bind('click',function(e) {
		e.preventDefault();
		$this = $(this);
		switch($this.text()) {
			case "Nuevo":
				$MUN_txt.val('');
				$MUN_hdn.val('');
				$MUN_hdn.val('nuevo');	// ESTABLECE MODO DE FORMULARIO
				$MUN_txt.eq(0).attr('disabled',false);
				$('th',$MUN_dial).text('Nuevo Municipio');
				showDialogMunic();
				$MUN_txt.eq(0).focus();
			break; // NUEVO
			case "Editar":
				if($MUN_noData.parent().parent().hasClass('tbBody')) { alert('Sin registros de usuarios!!'); return; }
				if($MUN_row==null){ alert('Seleccione un registro de un usuario'); return; }
				$MUN_txt.val('');
				$MUN_hdn.val('');
				$MUN_hdn.val('edit');
				$MUN_txt.eq(0).val($MUN_cells.eq(0).text()).attr('disabled',true);	// ID
				$MUN_txt.eq(1).val($MUN_cells.eq(1).text());	// MUNICIPIO
				$('th',$MUN_dial).text('Editar Municipio');
				showDialogMunic();
			break; // EDITAR
			case "Eliminar":
				if($MUN_noData.parent().parent().hasClass('tbBody')) { alert('Sin registros de usuarios!!'); return; }
				if($MUN_row==null){ alert('Seleccione un registro de un usuario'); return; }
				if(!confirm("Eliminar municipio [" + $MUN_cells.eq(0).text() + ", " + $MUN_cells.eq(1).text() + "] ?"))
					return;
				$.ajax({
					type:'post',
					url:'./ajax/ajax_agent_munic.php',
					data: "mode=del&id=" + $MUN_cells.eq(0).text(),
					cache: false,
					success: function(SERVER_RESPONSE) {
						if(!SERVER_RESPONSE) { alert("La respuesta del servidor esta vacia"); return false; }
						var DATA = SERVER_RESPONSE.split('|');
						if( DATA.length != 3 ){
							alert("La respuesta del servidor tienen un formato de respuesta incorrecta");
							alert(": " + SERVER_RESPONSE);
							return false;
						}
						SERVER_CODE = DATA[0]; SERVER_MESAGE = DATA[1]; SERVER_TEST = DATA[2];
						if(SERVER_TEST) {	alert(SERVER_TEST); }
						switch (SERVER_CODE) {
							case "MUNIC_DELETED":
								$MUN_row.remove();
								resetRowsMunic();
								break;
							default:
								alert(SERVER_CODE + "\n" + SERVER_MESAGE);
								break;
						}
					},
					error: function(err) { alert("error: " + err); }
				});
			break; // ELIMINAR
		}
	});
	function showDialogMunic(){
		$dialog1_w = $MUN_dial.width(); $dialog1_h = $MUN_dial.height();
		$win_w = $win.width(); $win_h = $win.height();
		$dialog1_x = ($win_w/2) - ($dialog1_w/2);	$dialog1_y = ($win_h/2) - ($dialog1_h/2);
		$MUN_dial.css({ 'left':$dialog1_x, 'top':$dialog1_y });
		$bloquer1.css('display','block');
		$MUN_dial.css('display','block');
	}
	function getListMunic() {
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_munic.php',
			data: "mode=list",
			cache: false,
			beforeSend: function(){
				if($MUN_noData.parent().parent().hasClass('tbHide')){
					$MUN_rows.remove();
					$MUN_noData.parent().remove().appendTo($MUN_tbBody);
				} 
				$MUN_noData.text('cargando municipios...');
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
						resetRowsMunic(SERVER_MESAGE);
						break;
					case "NOHAS":
						if($MUN_noData.parent().parent().hasClass('tbHide')){
							$MUN_rows.remove();
							$MUN_noData.parent().remove().appendTo($MUN_tbBody);
						} 
						$MUN_noData.text(SERVER_MESAGE);
						break;
					default:
						alert(SERVER_CODE + "\n" + SERVER_MESAGE);
						break;
				}
			},
			error: function(err) { alert("error: " + err); }
		});
	}
	function resetRowsMunic(html_val) {
		if($MUN_noData.parent().parent().hasClass('tbBody')){
			$MUN_noData.parent().remove().appendTo($MUN_tbHide);
			$MUN_noData.text('no data');
		}
		if(html_val){ $MUN_tbBody.html(html_val); }
		$MUN_rows = $('tr', $MUN_tbBody);
		$MUN_row = null;
		$MUN_cells = null;
		$MUN_rdo = null;
		if(!$MUN_rows.length){
			if($MUN_noData.parent().parent().hasClass('tbHide')){
				$MUN_noData.parent().remove().appendTo($MUN_tbBody);
			}
			$MUN_noData.text('No hay cuentas de usuarios registradas');
		} else {
			$rowSel = ('.tr-selected', $MUN_tbBody);
			if($rowSel.length){
				$('input:radio', $rowSel).attr('checked',false);
			}
			$MUN_rows.removeClass('tr-selected');
			$MUN_rows.bind('click', function() {
				$MUN_rows.removeClass('tr-selected');
				$MUN_row = $(this);
				$MUN_row.addClass('tr-selected');
				$MUN_cells = $('td', $MUN_row);
				$MUN_rdo = $('input:radio', $MUN_row).attr('checked',true);
			});
		}
	}
	
	getListMunic();
});