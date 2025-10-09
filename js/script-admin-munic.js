$(function(){
	
	
	/* ADM - MUNICIPIOS */
	
	
	/************ inicializa dialogo para nuevo/modificar un municipio ******************/
	var $bloquer1 = $('.layer-bloquer'),	// bloquea la pagina
	    $dialog1 = $('#dialog1'),				// dialogo
	    $dialog1_Acept = $('input:[type=button]', $dialog1).eq(0),
		 $dialog1_Cancel = $('input:[type=button]', $dialog1).eq(1),
		 $form1 = $('form', $dialog1),
		 $tblMunicipios = $('.tbl-municipios'),
		 $text = null,
		 $row = null;
	
	$dialog1_Acept.bind('click', function() {
		
		$text = $('input:[type=text]', $form1);
		$hidden = $('input:[type=hidden]', $form1);
		
		if($hidden.val()==''){
			alert('Exception: no se ha establecido un modo al formulario');
			return;
		}

		$text.each(function(i){
			if($(this).val()==''){
				alert('Todos los campos son requeridos!');
				return false;
			}
		});
		
		if($hidden.val()=='edit') {
			$mode = 'upd';
		} else if($hidden.val()=='nuevo'){
			$mode = 'ins';
		} else {
			alert("Modo de formulario invalido");
			return;
		}
				
		var ajax_params = "mode=" + $mode + "&id=" + $text.eq(0).val() + "&municipio=" + $text.eq(1).val();
		
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_munic.php',
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
					case "MUNIC_SAVED":
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
					case "ERROR_EXIST_MUNIC_ID":
						alert(SERVER_MESAGE);
						return;
						break;
					case "MUNIC_UPDATED":
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
	
	var $tbody1 = $('tbody',$tblMunicipios).eq(1),
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
	var $buttons = $('#mnu-munic a');
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
				
				/***** cargar datos ficticios ******/
				
				$text.eq(0).val("100");  				// clave
				$text.eq(1).val("HUEJUTLA");  	// nombre
				
				/***********************************/
				
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
				$bloquer1.css('opacity',0.2);
				$text.eq(0).focus();
			break;
			
			// EDITAR
			case "Editar":
			
				if(!$tbody1_rows.length) { alert('Sin registros de municipios!!'); return; }
				
				$checkbox = $('input:radio[checked]', $tbody1);
				
				if(!$checkbox.length){ alert('Seleccione un registro de un municipio'); return; }
				
				$row = $checkbox.parent().parent().parent();
				$cells = $('td', $row);
			
				// obtiene textbox, hiddens y checkbox del formulario
				$text = $('input:text', $form1);
				$hidden = $('input:[type=hidden]', $form1);
				// resetea/limpia el formulario
				$text.val('');
				$hidden.val('');
				
				$hidden.val('edit');	// ESTABLECE MODO DE FORMULARIO
				
				$text.eq(0).val($cells.eq(0).text());	// ID
				$text.eq(1).val($cells.eq(1).text());	// MUNICIPIO
				
				$text.eq(0).attr('disabled',true);
				
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
					alert('Sin registros de municipios!!');
					return;
				}
				
				$checkbox = $('input:radio[checked]', $tbody1);
				
				if(!$checkbox.length){
					alert('Seleccione un registro de un municipio');
					return;
				}
				
				$row = $checkbox.parent().parent().parent();
				$cells = $('td', $row);
				
				if(!confirm("Eliminar municipio [" + $cells.eq(0).text() + ", " + $cells.eq(1).text() + "] ?"))
					return;

				$.ajax({
					type:'post',
					url:'./ajax/ajax_agent_munic.php',
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
							case "MUNIC_DELETED":
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
	
	function getListMunic() {
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_munic.php',
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
	getListMunic();
	
});