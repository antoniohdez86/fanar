$(function(){
	
	
	/* ADM - MUNICIPIOS */
	
	
	/************ inicializa dialogo para nuevo/modificar un municipio ******************/
	var $bloquer1 = $('.layer-bloquer'),	// bloquea la pagina
	    $dialog1 = $('#dialog1'),				// dialogo
	    $dialog1_Acept = $('input:[type=button]', $dialog1).eq(0),
		 $dialog1_Cancel = $('input:[type=button]', $dialog1).eq(1),
		 $form1 = $('form', $dialog1),
		 $tblNucleos = $('.tbl-nucleos'),
		 $text = null,
		 $row = null, 
		 $option = null;
	
	/********************  BOTONES :  ACEPTAR Y CANCELAR DEL FORMULARIO  *****************/
	$dialog1_Acept.bind('click', function() {
		
		// obtiene elementos del formulario
		$text = $('input:[type=text]', $form1);
		$hidden = $('input:[type=hidden]', $form1);
		$radios = $('input:[type=radio]', $form1);
		$select = $('select', $form1);
		
		// comprueba que se haya establecido un modo para el formulario
		if($hidden.val()==''){
			alert('Exception: no se ha establecido un modo al formulario');
			return;
		}

		$form_ok = true;

		// comprueba que no haya ningun TEXTBOX vacio
		$text.each(function(i){
			$this = $(this);
			if($this.val()==''){
				alert('Todos los campos son requeridos!');
				$form_ok = false;
				return false;
			}
		});
		
		if(!$form_ok)	return;
			
		// obtiene el mode para ser usado en la peticion AJAX
		if($hidden.val()=='edit') { $mode = 'upd'; } 
		else if($hidden.val()=='nuevo') { $mode = 'ins'; } 
		else { alert("Modo de formulario invalido");	return; }
		
		// obtiene el radio seleccionado (TIPO NUCLEO)
		$radio = $('input:radio[checked]', $form1);
		if($radio.length==0){
			alert('Elija un tipo de nucleo!');
			return;
		}
		
		$option = $('option:selected',$select);
		$options = $('option',$select);
		
		if($options.length<=1) {
			alert('No hay municipios registrados!');
			return;
		}
		
		if($option.index()==0) {
			alert('Seleccione un municipio!');
			return;
		}
		
		var ajax_params = "mode=" + $mode + "&id=" + $text.eq(0).val() + "&nucleo=" + $text.eq(1).val() + "&tipo=" + $radio.val() + "&superficie=" + $text.eq(2).val() + "&idMunicipio=" + $option.val();

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
				
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				
				switch (SERVER_CODE) {
					case "NUCL_SAVED":
						$(SERVER_MESAGE).prependTo($tbody1);
						$tbody1_rows = $('tr', $tbody1);
						$tbody1_rows.unbind('click');
						$tbody1_rows.bind('click', function() {
							$tbody1_rows.removeClass('tr-selected');
							$(this).addClass('tr-selected');
							//$chk = $('input:radio[checked]', $tbody1);
							$row = $(this);
							$chk = $('input:radio', $row);									
							$chk.attr('checked',true);
						});
						break;
					case "ERROR_EXIST_NUCL_ID":
						alert(SERVER_MESAGE);
						return;
						break;
					case "NUCL_UPDATED":
						$row.replaceWith($(SERVER_MESAGE));
						$tbody1_rows = $('tr', $tbody1);
						$tbody1_rows.unbind('click');
						$tbody1_rows.bind('click', function() {
							$tbody1_rows.removeClass('tr-selected');
							$(this).addClass('tr-selected');
							//$chk = $('input:radio[checked]', $tbody1);
							$row = $(this);
							$chk = $('input:radio', $row);									
							$chk.attr('checked',true);
						});
						break;
					default:
						alert(SERVER_CODE + "\n" + SERVER_MESAGE);
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
	
	var $tbody1 = $('tbody',$tblNucleos).eq(1),
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
			$dialog1.css({'left':$dialog1_x,'top':$dialog1_y});
		}
	});


	/******************  inicializa botones  ********************/
	var $buttons = $('#mnu-nucl a');
	$buttons.bind('click',function(e) {
		e.preventDefault();
		$this = $(this);
		switch($this.text()) {
			// NUEVO
			case "Nuevo":
			
				$text = $('input:[type=text]', $form1);
				$hidden = $('input:[type=hidden]', $form1);
				$text.val('');
				$hidden.val('');
				$hidden.val('nuevo');	// ESTABLECE MODO DE FORMULARIO
				$radio = $('input:[type=radio]', $form1);
				$('option', $form1).eq(0).attr('selected', true);
				$radio.attr('checked', false);
				
				$text.eq(0).attr('disabled',false);

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
				$text.eq(0).focus();
			break;
			
			// EDITAR
			case "Editar":
			
				if(!$tbody1_rows.length) { alert('Sin registros de nucleos Agrarios!!'); return; }
				
				$checkbox = $('input:radio[checked]', $tbody1);
				
				if(!$checkbox.length){ alert('Seleccione un registro de un nucleo Agrario'); return; }
				
				$row = $checkbox.parent().parent().parent();		// obtiene la fila al que pertenece el checbox marcado
				$cells = $('td', $row);									// obtiene el arreglo de celdas de la fila seleccionada
			
				// obtiene textbox, hiddens y checkbox del formulario
				$text = $('input:text', $form1);
				$hidden = $('input:[type=hidden]', $form1);
				$radio = $('input:[type=radio]', $form1);
				$select = $('select', $form1);
				
				// resetea/limpia el formulario
				$text.val('');
				$hidden.val('');
				
				$hidden.val('edit');	// ESTABLECE MODO DE FORMULARIO
				
				/* selecciona del dialogo la opcion con respecto al tipo de nucleo */
				$i = ($cells.eq(2).text()=='comunidad') ? 0 : 1;		// $cells.eq(2) : tipo nucleo
				$idMun = $('input:hidden', $cells.eq(4)).val(); 		// id del municipio
				$option = $('option:[value=' + $idMun + ']');			// obtiene el elemento option que coincida con un value X
				
				/* LLENADO DE DATOS DEL FORMULARIO CON LOS DATOS DE LA FILA SELECCIONADA */				
				
				$text.eq(0).val($cells.eq(0).text());	// id nucleo
				$text.eq(1).val($cells.eq(1).text());	// nucleo agrario
				$radio.eq($i).attr('checked',true);		// tipo
				$option.attr('selected', true);			// municipio
				$text.eq(2).val($cells.eq(3).text());	// nucleo agrario
				$text.eq(0).attr('disabled',true);		// desabilita campo ID nucleo
				
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
			
				if(!$tbody1_rows.length){
					alert('Sin registros de nucleos Agrarios!!');
					return;
				}
				
				$checkbox = $('input:radio[checked]', $tbody1);
				
				if(!$checkbox.length){
					alert('Seleccione un registro de un nucleo Agrario');
					return;
				}
				
				$row = $checkbox.parent().parent().parent();
				$cells = $('td', $row);
				
				if(!confirm("Eliminar Nucleo Agrario [" + $cells.eq(0).text() + ", " + $cells.eq(1).text() + "] ?"))
					return;

				$.ajax({
					type:'post',
					url:'./ajax/ajax_agent_nucl.php',
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
							case "NUCL_DELETED":
								$row.remove();
								$tbody1_rows = $('tr', $tbody1);
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
	
	function getListNucleos() {
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
				
				if(SERVER_TEST) {	alert(SERVER_TEST); }
				
				switch (SERVER_CODE) {
					case "OK_CBO":
						$td = $('.select', $dialog1);
						$td.html(SERVER_MESAGE);
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
	
});