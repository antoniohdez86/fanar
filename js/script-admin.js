$(function(){
	
	/* ADM - EMPLEADOS */
	
	/************ inicializa dialogo para nuevo/modificar un municipio ******************/
	var $bloquer1 = $('.layer-bloquer'),	// bloquea la pagina
	    $dialog1 = $('#dialog1'),				// dialogo
	    $dialog1_Acept = $('input:[type=button]', $dialog1).eq(0),
		 $dialog1_Cancel = $('input:[type=button]', $dialog1).eq(1),
		 $form1 = $('form', $dialog1),
		 $tblEmpleados = $('#tbl-empleados');
	
	$('.infoUsr').css('opacity','0.8');
	
	$dialog1_Acept.bind('click', function(){
		//alert("nuevo municipio");
	});
	$dialog1_Cancel.bind('click', function(){
		//alert("cancelando municipio");
		$bloquer1.css('display','none');
		$dialog1.css('display','none');
	});
	
	/************ inicializa tabla empleados ******************/
	
	var $tbody1 = $('tbody',$tblEmpleados).eq(1),
		 $tbody1_rows = $('tr', $tbody1);
	
	
	/************ inicializa bloqueador ******************/
	$bloquer1.css('opacity',0.3);
	
	
	/************ inicializa evento resize del NAVEGADOR *********/
	$win = $(window);
	$win.bind('resize',function(e){
		if($dialog1.css('display')=='block'){
			$dialog1_w = $dialog1.width();
			$dialog1_h = $dialog1.height();
			$win_w = $win.width();
			$win_h = $win.height();
			$dialog1_x = ($win_w/2) - ($dialog1_w/2);
			$dialog1_y = ($win_h/2) - ($dialog1_h/2);
			$dialog1.css({
				'left':$dialog1_x,
				'top':$dialog1_y,
			});
		}
	});

	/******************  inicializa botones  ********************/
	var $buttons = $('#mnu-empl a');
	$buttons.bind('click',function(e){
		e.preventDefault();
		$this = $(this);
		switch($this.text()){
			// NUEVO
			case "Nuevo":
				$text = $('input:[type=text]', $form1);
				$hidden = $('input:[type=hidden]', $form1);
				$checkbox = $('input:[type=checkbox]', $form1);
				$text.val('');
				$hidden.val('');
				$checkbox.attr('checked',false);
				//alert($text.length + " : " + $hidden.length + " : " + $checkbox.length)
				$dialog1_w = $dialog1.width();
				$dialog1_h = $dialog1.height();
				$win_w = $win.width();
				$win_h = $win.height();
				$dialog1_x = ($win_w/2) - ($dialog1_w/2);
				$dialog1_y = ($win_h/2) - ($dialog1_h/2);
				$dialog1.css({
					'left':$dialog1_x,
					'top':$dialog1_y,
				});
				$('th',$dialog1).text('Nuevo Empleado');
				$bloquer1.css('display','block');
				$dialog1.css('display','block');
				$text.eq(0).focus();
			break;
			
			// EDITAR
			case "Editar":
			
				//alert($('tr',$tbody1).length);
			
				if(!$tbody1.length){
					alert('Sin registros de empleados!!');
					return;
				} 
			
				//-----------------!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! OBTENER CHECKBOX SELECCIONADO!!
			
				$checkbox = $('input:radio[checked]', $tbody1);
				
				if(!$checkbox.length){
					alert('Seleccione un registro de un empleado');
					return;
				}
				
				$row = $checkbox.parent().parent().parent();
				alert($row.html())
				
				
			
				$text = $('input:[type=text]', $form1);
				//$text.eq(0).val();
				
				$hidden = $('input:[type=hidden]', $form1);
				$checkbox = $('input:[type=checkbox]', $form1);
				$text.val('');
				$hidden.val('');
				$checkbox.attr('checked',false);
				
				$dialog1_w = $dialog1.width();
				$dialog1_h = $dialog1.height();
				$win_w = $win.width();
				$win_h = $win.height();
				$dialog1_x = ($win_w/2) - ($dialog1_w/2);
				$dialog1_y = ($win_h/2) - ($dialog1_h/2);
				$dialog1.css({
					'left':$dialog1_x,
					'top':$dialog1_y,
				});
				
				$('th',$dialog1).text('Editar Empleado');				
				
				$bloquer1.css('display','block');
				$dialog1.css('display','block');
			break;
			
			// ELIMINAR
			case "Eliminar":
				alert("eliminando");
			break;
		}
	});
	
	function clearForm1(){
		//$inputs = $('input:[type=text]', $form1);
		//alert($inputs.length)
	}	
	
	
});