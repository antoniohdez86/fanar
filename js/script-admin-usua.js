$(function(){
	
	/* ADM - EMPLEADOS */
	
	/************ inicializa dialogo para nuevo/modificar un municipio ******************/
	var $bloquer1 = $('.layer-bloquer'),	// bloquea la pagina
	    $dialog1 = $('#dialog1'),				// dialogo
	    $dialog1_Acept = $('input:[type=button]', $dialog1).eq(0),
	    $dialog1_Cancel = $('input:[type=button]', $dialog1).eq(1),
	    $form1 = $('form', $dialog1),
	    $tblUsuarios = $('#tbl-usuarios'),
	    $row = null, a=null, b=null,c=null;

	// boton ACEPTAR/CANCELAR del formulario NUEVO/EDITAR USUARIO
	$dialog1_Acept.bind('click', function() {
		
		// obtiene textbox, hiddens del formulario
		$text = $('input:[type=text]', $form1);
		$hidden = $('input:[type=hidden]', $form1);
		$tdSelect = $('.select',$form1);
		$options = $('select option', $form1)
		$option = $('select option:selected', $form1)
		$radio = $('input:radio[checked]');
		
		// comprueba que se haya establecido un modo al formulario
		if($hidden.val()==''){ alert('Exception: no se ha establecido un modo al formulario'); return; }

		// verifica que todos los campos de texto no esten vacios
		if(!$text.eq(0).val() || !$text.eq(1).val()){ alert('Todos los campos son requeridos!'); return; }

		// comprueba el modo del formulario
		if($hidden.val()=='nuevo') { 
			$mode = 'ins';
			$ie = "&ie=" + $option.val();			// NOMBRE
			// verifica que se haya seleccionado un empleado
			if($option.index()==0) {	
				alert("Seleccione un empleado para el que se va a crear la cuenta"); 
				return; 
			}
		} else if($hidden.val()=='edit') { 
			$mode = 'upd'; 
			$ie = "&ine=" + $text.eq(2).val() + "&ie=" + $radio.val(); ; 
		} else { 
			alert("Modo de formulario invalido"); return; 
		}
		
		// parametros para meticion ajax
		var ajax_params = "mode=" + $mode + $ie + "&u=" + $text.eq(0).val() + "&p=" + $text.eq(1).val() + "&ia=0";

		// peticion ajax
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_usua.php',
			data: ajax_params,
			cache: false,
			success: function(SERVER_RESPONSE) {
				if(!SERVER_RESPONSE) { alert("La respuesta del servidor esta vacia"); return; }
				var DATA = SERVER_RESPONSE.split('|');
				if( DATA.length != 3 ){	alert("La respuesta del servidor tienen un formato de respuesta incorrecta" + "\n: " + SERVER_RESPONSE); return; }
				SERVER_CODE = DATA[0], SERVER_MESAGE = DATA[1],	SERVER_TEST = DATA[2];
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				switch (SERVER_CODE) {
					case "USER_SAVED":
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
						$option.remove();	// elimina el empleado del combo de "empleados sin cuenta"
						break;
					case "ERROR_EXIST_USER":
						alert(SERVER_MESAGE);
						return;
						break;
					case "USER_UPDATED":
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
	
	/************ inicializa tabla empleados ******************/
	
	var $tbody1 = $('tbody',$tblUsuarios).eq(1),
	$tbody1_rows = $('tr', $tbody1);
	
	/************ inicializa bloqueador ******************/
	
	$bloquer1.css('opacity',0.3);
	
	/************ inicializa evento resize del NAVEGADOR *********/
	$win = $(window);
	$win.bind('resize',function(e) {
		if($dialog1.css('display')=='block'){
			$dialog1_w = $dialog1.width();
			$dialog1_h = $dialog1.height();
			$win_w = $win.width();
			$win_h = $win.height();
			$dialog1_x = ($win_w/2) - ($dialog1_w/2);
			$dialog1_y = ($win_h/2) - ($dialog1_h/2);
			$dialog1.css({
				'left':$dialog1_x,
				'top':$dialog1_y
			});
		}
	});

	/******************  inicializa botones  ********************/
	var $buttons = $('#mnu-empl a');
	$buttons.bind('click',function(e) {
		e.preventDefault();
		$this = $(this);
		switch($this.text()) {
			// NUEVO
			case "Nuevo":
				// obtiene textbox, hiddens y checkbox del formulario
				$text = $('input:[type=text]', $form1);
				$hidden = $('input:[type=hidden]', $form1);
				$checkbox = $('input:[type=checkbox]', $form1);
				$select = $('select', $form1);
				$option = $('option',$select);
				
				// establece un valor vacio a los textbox, deselecciona el checkbox y selecciona el item 0 del select
				$text.val('');
				$hidden.val('');
				$checkbox.attr('checked',false);
				$option.eq(0).attr('selected',true);
				
				// oculta el campo de texto EMPLEADO y muestra el combo EMPLEADOS
				$text.eq(2).css('display','none');
				$select.css('display','block');
				
				// ESTABLECE MODO DE FORMULARIO y habilita el campo USUARIO
				$hidden.val('nuevo');
				$text.eq(0).attr('disabled',false);

				// calcula posicion y muestra el dialogo
				$dialog1_w = $dialog1.width();
				$dialog1_h = $dialog1.height();
				$win_w = $win.width();
				$win_h = $win.height();
				$dialog1_x = ($win_w/2) - ($dialog1_w/2);
				$dialog1_y = ($win_h/2) - ($dialog1_h/2);
				$dialog1.css({'left':$dialog1_x,'top':$dialog1_y});
				$('th',$dialog1).text('Nuevo Usuario');
				$bloquer1.css('display','block');
				$dialog1.css('display','block');
				$text.eq(0).focus();
			break;
			
			// EDITAR
			case "Editar":
			
				// comprueba si la tabla esta vacia
				if(!$tbody1_rows.length) { alert('Sin registros de usuarios!!'); return; }

				// obtiene el radio seleccionado
				$radio = $('input:radio[checked]', $tbody1);

				// si no hay ningun radio seleccionado, significa que no se ha seleccionado ninguna fila
				if(!$radio.length){ alert('Seleccione un registro de un usuario'); return; }
				
				// obtiene el id del empleado perteneciente al usuario seleccionado
				$ID_EMPL = $radio.val();
				
				// obtiene la fila entera en la que se encuentra el radio seleccionado
				$row = $radio.parent().parent().parent();
				
				// obtiene las celdas de la fila seleccionada
				$cells = $('td', $row);
			
				// obtiene textbox y hiddens del formulario
				$text = $('input:text', $form1);
				$hidden = $('input:[type=hidden]', $form1);
				
				// resetea/limpia el formulario
				$text.val('');								// cajas de texto
				$hidden.val('');							// campos ocultos
				
				$hidden.val('edit');						// ESTABLECE MODO DE FORMULARIO
				
				$select = $('select', $dialog1);		// oculta el combo
				$select.css('display','none');
				
				$text.css('display','block');			// muestra todos los textbox ocultos
				
				// toma los valores de la fila seleccionada y los coloca en el formulario para su modificacion
				$text.eq(0).val($cells.eq(0).text());	// usuario
				$text.eq(1).val($cells.eq(1).text());	// password
				$text.eq(2).val($cells.eq(2).text());	// password
				$text.eq(0).attr('disabled',true);		// desabilita campo USUARIO
				$text.eq(2).attr('disabled',true);		// desabilita campo EMPLEADO
				
				//$opt = $('option[value=' + $ID_EMPL + ']');
				
				// calcula posicion y muestra el dialogo
				$dialog1_w = $dialog1.width();
				$dialog1_h = $dialog1.height();
				$win_w = $win.width();
				$win_h = $win.height();
				$dialog1_x = ($win_w/2) - ($dialog1_w/2);
				$dialog1_y = ($win_h/2) - ($dialog1_h/2);
				$dialog1.css({ 'left':$dialog1_x, 'top':$dialog1_y });
				
				$('th',$dialog1).text('Editar Usuario');	// 
				
				$bloquer1.css('display','block');
				$dialog1.css('display','block');
			break;
			
			// ELIMINAR
			case "Eliminar":

				// comprueba que la tabla esta vacia
				if(!$tbody1_rows.length){ alert('Sin registros de usuarios!!'); return; }
				
				// obtiene el radio seleccionado
				$radio = $('input:radio[checked]', $tbody1);
				
				// si no hay ningun radio seleccionado, significa que no se ha seleccionado ninguna fila
				if(!$radio.length){ alert('Seleccione un registro de un usuario'); return; }

				// obtiene la fila entera en la que se encuentra el radio seleccionado
				$row = $radio.parent().parent().parent();
				
				// obtiene las celdas de la fila seleccionada
				$cells = $('td', $row);
				
				// antes de eliminar pide la confirmacion del usuario
				if(!confirm("Eliminar el Usuario [" + $cells.eq(0).text() + "] ?")) { return; }

				var params = "mode=del&u=" + $cells.eq(0).text() + "&ie="+$radio.val();

				// si la respuesta del usuario es afirmativa entonces se realiza la peticion al servidor (peticion AJAX)
				$.ajax({
					type:'post',
					url:'./ajax/ajax_agent_usua.php',
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
				
						if(SERVER_TEST) {	alert(SERVER_TEST); }
				
						switch (SERVER_CODE) {
							case "USER_DELETED":
								$('select', $dialog1).append("<option value='" + $radio.val() + "'>" + $cells.eq(2).text() + "</option>"); 
								$tds = $('td', $row);
								$tds.slideUp('slow',function(){
									$row.remove();								// elimina la fila de la tabla
									$tbody1_rows = $('tr', $tbody1);		// y se vuelve a muestrear las filas (REVISAR A PROFUNDIDAD SI ES NECESARIO ESTA LINEA DE CODIGO)
								});
								break;
							case "ERROR_IA":
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
	
	function getCboEmpl() {
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_empl.php',
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
				
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				
				switch (SERVER_CODE) {
					case "OK_CBO":
						$divCbo = $('.select', $dialog1);
						$divCbo.html(SERVER_MESAGE);
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
						//alert($tbody1.length)
						$tbody1.html(SERVER_MESAGE);
						resetRowsUser();
						break;
					default:
						alert(SERVER_CODE + "\n" + SERVER_MESAGE);
						break;
				}
			},
			error: function(err) { alert("error: " + err); }
		});
	}
	getListUsua();
	getCboEmpl();
	
	/******************  funciones  *****************************/
	function resetRowsUser(){
		$tbody1_rows = $('tr', $tbody1);
		if($tbody1_rows.length == 1 && $tbody1_rows.eq(0).attr('class')=='no-data'){
			// no hacer nada...
			alert("La tabla no contiene ningun dato");
			return;
		} 
		$tbody1_rows.bind('click', function() {
			$tbody1_rows.removeClass('tr-selected');
			$(this).addClass('tr-selected');
			$chk = $('input:radio[checked]', $tbody1);
			$row = $(this);
			$chk = $('input:radio', $row);
			$chk.attr('checked',true);
		});
	}
	function deleteUser(){
		
	}
	function editUser(){
		
	}
	
	
	
	
	
	
});