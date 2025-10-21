$(function(){
	var $bloquer1 = $('.layer-bloquer'),	// bloquea la pagina
	    $dialog1 = $('#dialog1'),				// dialogo
	    $dialog1_Acept = $('input:[type=button]', $dialog1).eq(0),
		 $dialog1_Cancel = $('input:[type=button]', $dialog1).eq(1),
		 $form1 = $('form', $dialog1),
		 $tblEmpleados = $('#tbl-empleados'),
		 $row = null, $cells=null;
	// boton ACEPTAR/CANCELAR del formulario NUEVO/EDITAR EMPLEADO
	$dialog1_Acept.bind('click', function() {
		// obtiene colecciones de textbox's, hidden's y checkbox's del formulario
		$text = $('input:[type=text]', $form1);
		$hidden = $('input:[type=hidden]', $form1);
		$checkbox = $('input:[type=checkbox]', $form1);
		// convierte en un valor BOOLEANO 
		$checkbox = ($checkbox.attr('checked')=='checked') ? 'true' : 'false';
		// comprueba el modo del formulario
		if($hidden.val()=='') {
			alert('Exception: no se ha establecido un modo al formulario');
			return;
		}
		$form_ok = true;
		// recorre la coleccion de textbox's...
		$text.each(function(i){
			if($.trim($(this).val())==''){	// ... y verifica si hay campos vacios
				$form_ok = false;
				return;
			}
		});
		if(!$form_ok){
			alert('Todos los campos son requeridos!');
			return false;	
		}
		// obtiene id para el nuevo empleado
		$iEmpl = $text.eq(0).val();
		$iEmpl = $.trim($iEmpl);
		// comprueba que el id sea un numero
		if(isNaN($iEmpl)){
			alert('El id de empleado debe ser un valor numerico');
			return false;	
		}
		$mode = '';
		var ajax_params = '';
		if($hidden.val()=='edit') {		// actualizar
			$mode = 'upd';
			ajax_params = "mode=" + $mode + "&id=" + $text.eq(0).val() + "&nombre=" + $text.eq(1).val() + "&ape1=" + $text.eq(2).val() + "&ape2=" + $text.eq(3).val() + "&dir=" + $text.eq(4).val() + "&prof=" + $text.eq(5).val() + "&tel=" + $text.eq(6).val() + "&act=" + $checkbox + "&na="+$cells.eq(8).text();
		} else if($hidden.val()=='nuevo'){	// nuevo empleado
			$mode = 'ins';
			ajax_params = "mode=" + $mode + "&id=" + $text.eq(0).val() + "&nombre=" + $text.eq(1).val() + "&ape1=" + $text.eq(2).val() + "&ape2=" + $text.eq(3).val() + "&dir=" + $text.eq(4).val() + "&prof=" + $text.eq(5).val() + "&tel=" + $text.eq(6).val() + "&act=" + $checkbox;
		}
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_empl.php',
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
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				switch (SERVER_CODE) {
					case "EMPL_SAVED":
						$(SERVER_MESAGE).prependTo($tbody1);
						$tbody1_rows = $('tr', $tbody1);
						$tbody1_rows.unbind('click');
						$tbody1_rows.bind('click', function() {
							$tbody1_rows.removeClass('tr-selected');
							$(this).addClass('tr-selected');
							$chk = $('input:radio[checked]', $tbody1);
							$row = $(this);
							$chk = $('input:radio', $row);									
							$chk.attr('checked',true);
						});
						break;
					case "ERROR_EXIST_EMPL_ID":
						alert(SERVER_MESAGE);
						return;
						break;
					case "EMPL_UPDATED":
						$row.replaceWith($(SERVER_MESAGE));
						$tbody1_rows = $('tr', $tbody1);
						$tbody1_rows.unbind('click');
						$tbody1_rows.bind('click', function() {
							$tbody1_rows.removeClass('tr-selected');
							$(this).addClass('tr-selected');
							$chk = $('input:radio[checked]', $tbody1);
							$row = $(this);
							$chk = $('input:radio', $row);									
							$chk.attr('checked',true);
						});
						break;
					default:
						alert(SERVER_CODE + "\n" + SERVER_MESAGE);
						return;
						break;
				}
				$dialog1.css('display','none');
				$bloquer1.css('display','none');
			},
			error: function(err) { alert("error: " + err); }
		});
	});
	$dialog1_Cancel.bind('click', function() {
		$bloquer1.css('display','none');
		$dialog1.css('display','none');
	});
	// inicializa tabla empleados/
	var $tbody1 = $('tbody',$tblEmpleados).eq(1),
		 $tbody1_rows = $('tr', $tbody1);
	// inicializa bloqueador
	$bloquer1.css('opacity',0.3);
	// inicializa evento resize del NAVEGADOR
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
	// inicializa botones
	var $buttons = $('#mnu-empl a');
	$buttons.bind('click',function(e) {
		e.preventDefault();
		$this = $(this);
		switch($this.text()) {
			// NUEVO
			case "Nuevo":
				$text = $('input:[type=text]', $form1);
				$hidden = $('input:[type=hidden]', $form1);
				$checkbox = $('input:[type=checkbox]', $form1);
				$text.val('');
				$hidden.val('');
				$checkbox.attr('checked',false);
				$hidden.val('nuevo');	// ESTABLECE MODO DE FORMULARIO
				//$text.eq(0).val("400");  				// clave
				//$text.eq(1).val("Jose Antonio");  	// nombre
				//$text.eq(2).val("Garcia");  			// apell1
				//$text.eq(3).val("Melendez");  		// apell2
				//$text.eq(4).val("Col.Jerico");  		// dir
				//$text.eq(5).val("Lic.Informatica"); // prof
				//$text.eq(6).val("775888996");  		// tel
				//$text.eq(7).val("antonio");  			// usuario
				//$text.eq(8).val("antox");  			// contraseña
				$text.eq(0).attr('disabled',false);
				$dialog1_w = $dialog1.width();
				$dialog1_h = $dialog1.height();
				$win_w = $win.width();
				$win_h = $win.height();
				$dialog1_x = ($win_w/2) - ($dialog1_w/2);
				$dialog1_y = ($win_h/2) - ($dialog1_h/2);
				$dialog1.css({'left':$dialog1_x,'top':$dialog1_y});
				$('th',$dialog1).text('Nuevo Empleado');
				$bloquer1.css('display','block');
				$dialog1.css('display','block');
				$text.eq(0).focus();
			break;
			// EDITAR
			case "Editar":
				// comprueba si la tabla empleados esta vacia
				if(!$tbody1_rows.length) { alert('Sin registros de empleados!!'); return; }
				// obtiene el radio seleccionado (y con este se obtendra la fila a la que pertenece, es decir la fila seleccionada)
				$checkbox = $('input:radio[checked]', $tbody1);
				// sino se encontro ningun radio seleccionado...
				if(!$checkbox.length){ alert('Seleccione un registro de un empleado'); return; }
				// obtiene la fila seleccionada
				$row = $checkbox.parent().parent().parent();
				$cells = $('td', $row);
				// obtiene textbox, hiddens y checkbox del formulario
				$text = $('input:text', $form1);
				$hidden = $('input:[type=hidden]', $form1);
				$checkbox = $('input:[type=checkbox]', $form1);
				// resetea/limpia el formulario
				$text.val('');
				$hidden.val('');
				$checkbox.attr('checked',false);
				$hidden.val('edit');	// ESTABLECE MODO DE FORMULARIO
				$text.eq(0).val($cells.eq(0).text());	// ID
				$text.eq(1).val($cells.eq(1).text());	// NOMBRE
				$text.eq(2).val($cells.eq(2).text());	// APELL1
				$text.eq(3).val($cells.eq(3).text());	// APELL2
				$text.eq(4).val($cells.eq(4).text());	// DIRECC
				$text.eq(5).val($cells.eq(5).text());	// PROFES
				$text.eq(6).val($cells.eq(6).text());	// TELEFONO
				$text.eq(0).attr('disabled',true);
				//$value = $cells.eq(7).text() == "SI" ? true : false;
				$span = $('span',$cells.eq(7));
				$value = null;
				if($span.hasClass('up')) { $value=true; } else if($span.hasClass('down')) { $value=false } else { alert($span[0].type) }
				$checkbox.attr('checked', $value);
				// calcula posicion y muestra el dialogo
				$dialog1_w = $dialog1.width();
				$dialog1_h = $dialog1.height();
				$win_w = $win.width();
				$win_h = $win.height();
				$dialog1_x = ($win_w/2) - ($dialog1_w/2);
				$dialog1_y = ($win_h/2) - ($dialog1_h/2);
				$dialog1.css({ 'left':$dialog1_x, 'top':$dialog1_y });
				$('th',$dialog1).text('Editar Empleado');
				$bloquer1.css('display','block');
				$dialog1.css('display','block');
			break;
			// ELIMINAR
			case "Eliminar":
				if(!$tbody1_rows.length){
					alert('Sin registros de empleados!!');
					return;
				}
				$checkbox = $('input:radio[checked]', $tbody1);
				if(!$checkbox.length){
					alert('Seleccione un registro de un empleado');
					return;
				}
				$row = $checkbox.parent().parent().parent();
				$cells = $('td', $row);
				if(!confirm("Eliminar empleado [" + $cells.eq(0).text() + ", " + $cells.eq(1).text() + " " +$cells.eq(2).text() + " " + $cells.eq(3).text() + "] ?"))
					return;
				$.ajax({
					type:'post',
					url:'./ajax/ajax_agent_empl.php',
					data: "mode=del&id=" + $cells.eq(0).text(),
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
							case "EMPL_DELETED":
								$row.remove();
								$tbody1_rows = $('tr', $tbody1);
								break;
							case "ERR_EMPL_REF":
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
		}
	});
	function getListEmpl() {
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_empl.php',
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
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				switch (SERVER_CODE) {
					case "OK_LIST":
						$tbody1.html(SERVER_MESAGE);
						$tbody1_rows = $('tr', $tbody1);
						if($tbody1_rows.length == 1 && $tbody1_rows.eq(0).attr('class')=='no-data'){
							// no hacer nada...
							alert("La tabla no contiene ningun dato");
						} else {
							$tbody1_rows.bind('click', function() {
								$tbody1_rows.removeClass('tr-selected');
								$(this).addClass('tr-selected');
								$chk = $('input:radio[checked]', $tbody1);
								$row = $(this);
								$chk = $('input:radio', $row);									
								$chk.attr('checked',true);
							});
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
	getListEmpl();
});
