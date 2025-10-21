$(function(){
	
	$ID_MUNIC = null;
	$ID_NUCL = null;
	$ID_EMPL = null;
	$modeGen = null;
	
	$REP_form = $('form:[name=frmGetRep]');	
	$REP_fields = $('input:[type=hidden]',$REP_form);
	//alert($REP_fields.length);
 	$btnPdf = $('#btnPdf');
	$inputs = $('.form-design input');
	$chks = $('.form-design input:checkbox');
	$trCbos = $('.tbl-design tr');
	$selects = $('select');
	$trCbo2 = $selects.eq(1).parent().parent();	// municipios
	$trCbo3 = $selects.eq(2).parent().parent();	// nucleos agrarios
	$trCbo4 = $selects.eq(3).parent().parent();	// empleados
	
	$REPORT_table = $('.tbl-reporte');
	$REPORT_body = $('.tbBody',$REPORT_table);
	$REPORT_tbHide = $('.tbHide',$REPORT_table);
	$REPORT_noData = $('.no-data',$REPORT_table);
	$REPORT_rows = $('tr',$REPORT_body);
	
	
	$selects.eq(0).bind('change',function() {
		
		$this = $(this);	// select CRITERIO REPORTE
		
		var $option = $('option:selected', $this);
		var opt_index = $option.index();
		var opt_text = $option.text();
		var opt_value = $option.val();
		
		switch($option.index()){
			case 0: 	// ...
				$trCbo2.css('display','none');
				$trCbo3.css('display','none');
				$trCbo4.css('display','none');
				break;
			case 1:	// municipios
				//$this.attr('disabled',true);
				$trCbo2.css('display','block');
				$trCbo3.css('display','none');
				$trCbo4.css('display','none');
				break;
			case 2:	// nucleos agrarios
				$trCbo2.css('display','none');
				$trCbo3.css('display','block');
				$trCbo4.css('display','none');
				break;
			case 3:	// empleado
				//$this.attr('disabled',true);
				$trCbo2.css('display','none');
				$trCbo3.css('display','none');
				$trCbo4.css('display','block');
				break;
		}
	});
	$inputs.eq(0).bind('click', function() {	// BOTON GENERAR
		//test(); return;
		$selects = $('select');
		$trCbo2 = $selects.eq(1).parent().parent();	// municipios
		$trCbo3 = $selects.eq(2).parent().parent();	// nucleos agrarios
		$trCbo4 = $selects.eq(3).parent().parent();	// empleados
		$option = $('option:selected', $selects.eq(0));		// modo de generacion elegido por el usuario
		if($option.index()==0){
			alert('Seleccione un modo de generacion para el reporte');
			return;
		}
		$criterio = 'ninguno';
		$cr = '';			// criterio de generacion de reporte
		$ID_MUNIC = 0;		// id municipio
		$ID_NUCL = 0;		// id nucleo
		$ID_EMPL = 0;		// id visitante
		if($option.index()==1){		// generar reporte de nucleos por municipio
			$opt_mun = $('option:selected', $selects.eq(1));
			if($opt_mun.index()==0){	// si aun no se ha elegido un municipio
				alert('Seleccione un municipio');
				return;
			}
			$ID_MUNIC = $opt_mun.val();	// id del municipio seleccionado
			$cr = 'm';
		}
		if($option.index()==2){		// generar reporte de un nucleo especifico
			$opt_nucl = $('option:selected', $selects.eq(2));
			if($opt_nucl.index()==0){	// si aun no se ha seleccionado ningun nucleo agrario
				alert('Seleccione un nucleo');
				return;
			}
			$ID_NUCL = $opt_nucl.val();	// id del nucleo seleccionado
			$cr = 'n';
		}
		if($option.index()==3){	// generar reporte de nucleos a cargo de un visitante especifico
			$opt_empl = $('option:selected', $selects.eq(3));
			if($opt_empl.index()==0){				// si aun no se ha seleccionado ningun visitante
				alert('Seleccione un empleado');
				return;
			}
			$ID_EMPL = $opt_empl.val();	// id del empleado seleccionado
			$cr = 'e';
		}
		//alert("mode=rxm&c="+$cr+"&m="+$ID_MUNIC+"&n="+$ID_NUCL+"&e="+$ID_EMPL);
		//return;
		//$cr = 't';
		$emax = ($chks.eq(5).attr('checked')!='checked') ? false : true;
		$modeGen = $cr;
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_rep.php',
			data: "mode=gr&c="+$cr+"&m="+$ID_MUNIC+"&n="+$ID_NUCL+"&e="+$ID_EMPL + "&emax=" + $emax,
			cache: false,
			beforeSend: function(){
				if($REPORT_noData.parent().parent().hasClass('tbHide')){
					$REPORT_rows.remove();
					$REPORT_noData.parent().remove().appendTo($REPORT_body);
				} 
				$REPORT_noData.text('Generando reporte...');
				$btnPdf.css('display','none');
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
					case "OK_REPORT":
						if($REPORT_noData.parent().parent().hasClass('tbBody')){
							$REPORT_noData.parent().remove().appendTo($REPORT_tbHide);
							$REPORT_noData.text('nothing');
						} 
						$tbTmp = $('<tbody></tbody>');
						$tbTmp.html(SERVER_MESAGE);
						$tmp_rows = $('tr', $tbTmp);
						$showNuc = ($chks.eq(0).attr('checked')!='checked') ? 'none':'block';
						$showMun = ($chks.eq(1).attr('checked')!='checked') ? 'none':'block';
						$showFec = ($chks.eq(2).attr('checked')!='checked') ? 'none':'block';
						$showObs = ($chks.eq(3).attr('checked')!='checked') ? 'none':'block';
						$showSit = ($chks.eq(4).attr('checked')!='checked') ? 'none':'block';
						$tmp_rows.each(function(i){
							$row = $(this);
							$cells = $('td',$row);
							$cells.eq(2).css('display',$showNuc);	// nucleo agrario
							$cells.eq(1).css('display',$showMun);	// municipio
							$cells.eq(4).css('display',$showFec);	// fecha
							$cells.eq(5).css('display',$showObs);	// observaciones
							$cells.eq(6).css('display',$showSit);	// situacion
						});
						$cells = $('th', $REPORT_table);
						$cells.eq(2).css('display',$showNuc);	// nucleo agrario
						$cells.eq(1).css('display',$showMun);	// municipio
						$cells.eq(4).css('display',$showFec);	// fecha
						$cells.eq(5).css('display',$showObs);	// observaciones
						$cells.eq(6).css('display',$showSit);	// situacion
						$REPORT_body.html($tbTmp.html());
						$REPORT_rows = $('tr',$REPORT_body);
						$btnPdf.css('display','inline');
						break;
					case "NOSEG":
						if($REPORT_noData.parent().parent().hasClass('tbHide')){
							$REPORT_noData.parent().remove().appendTo($REPORT_body);
						} 
						$REPORT_noData.text(SERVER_MESAGE);
						$btnPdf.css('display','none');
						break;
					case "OK_TEST": 
						alert(SERVER_MESAGE); return;
						break;
					default:
						///alert(SERVER_CODE + "\n" + SERVER_MESAGE);
						if($REPORT_noData.parent().parent().hasClass('tbHide')){
							$REPORT_noData.parent().remove().appendTo($REPORT_body);
						} 
						$REPORT_noData.html(SERVER_CODE +"<br>"+ SERVER_MESAGE);
						$btnPdf.css('display','none');
						
						break;
				}
			},
			error: function(err) { alert("error: " + err); }
		});	
	
		//  codigo para generar tabla de reporte
		
	});
	$btnPdf.bind('click', function(e){
		e.preventDefault();
		$showNuc = ($chks.eq(0).attr('checked')!='checked') ? false : true;
		$showMun = ($chks.eq(1).attr('checked')!='checked') ? false : true;
		$showFec = ($chks.eq(2).attr('checked')!='checked') ? false : true;
		$showObs = ($chks.eq(3).attr('checked')!='checked') ? false : true;
		$showSit = ($chks.eq(4).attr('checked')!='checked') ? false : true;
		$eMax = ($chks.eq(5).attr('checked')!='checked') ? false : true;
		$REP_fields.eq(0).val($modeGen);
		//$REP_fields.eq(0).val('t');
		$REP_fields.eq(1).val($ID_MUNIC);
		$REP_fields.eq(2).val($ID_NUCL);
		$REP_fields.eq(3).val($ID_EMPL);
		$REP_fields.eq(4).val($showMun);
		$REP_fields.eq(5).val($showNuc);
		$REP_fields.eq(6).val($showFec);
		$REP_fields.eq(7).val($showObs);
		$REP_fields.eq(8).val($showSit);
		$REP_fields.eq(9).val($eMax);
		
		$REP_form.submit();
		//$out = "";
		//$REP_fields.each(function(){
		//	$field = $(this);
		//	$out += $field.attr('name') + " = " + $field.attr('value') + "\n";
		//});
		//alert($out);
	});
	
	function test(){
		$chks = $('.form-design input:checkbox');
		$params = "";
		for(c=0; c<$chks.length; c++){
 			$params += "&" + $chks.eq(c).attr('name') + "=" + (($chks.eq(c).attr('checked')) ? true : false);
		}
		//alert($params); return;
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_rep.php',
			
			//data: "mode=test&c="+$cr+"&m="+$ID_MUNIC+"&n="+$ID_NUCL+"&e="+$ID_EMPL,
			data: "mode=test" + $params,
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
					case "OK_DATA":
						alert(SERVER_MESAGE);
						break;
					default:
						alert(SERVER_CODE + "\n" + SERVER_MESAGE);
						break;
				}
			},
			error: function(err) { alert("error: " + err); }
		});
	}
	$chks.bind('click', function(){
		$field = $(this);
		//$ne = $('td:eq(1)',$REPORT_table).length;
		//alert($ne); return;
		switch($field.val()){
			case "fNuc":
				// no hacer nada
				break;
			case "fMun":
				$REPORT_rows.each(function(i){
					$('td:eq(1)',$(this)).css('display',(($field.attr('checked')!='checked') ? 'none':'block'))
				});
				$('th:eq(1)',$REPORT_table).css('display',(($field.attr('checked')!='checked') ? 'none':'block'))
				break;
			case "fFSeg":
				$REPORT_rows.each(function(i){
					$('td:eq(4)',$(this)).css('display',(($field.attr('checked')!='checked') ? 'none':'block'))
				});
				$('th:eq(4)',$REPORT_table).css('display',(($field.attr('checked')!='checked') ? 'none':'block'))
				break;
			case "fObs":
				$REPORT_rows.each(function(i){
					$('td:eq(5)',$(this)).css('display',(($field.attr('checked')!='checked') ? 'none':'block'))
				});
				$('th:eq(5)',$REPORT_table).css('display',(($field.attr('checked')!='checked') ? 'none':'block'))
				break;
			case "fSit":
				$REPORT_rows.each(function(i){
					$('td:eq(6)',$(this)).css('display',(($field.attr('checked')!='checked') ? 'none':'block'))
				});
				$('th:eq(6)',$REPORT_table).css('display',(($field.attr('checked')!='checked') ? 'none':'block'))
				break;
			default:
				// no hacer nada
				break;
		};
	});
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
						//$td = $('.select', $dialog1);
						$td = $selects.eq(1).parent();
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
	function getCboNucleos() {
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_nucl.php',
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
						//$td = $('.select', $dialog1);
						$td = $selects.eq(2).parent();
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
	function getCboEmpl() {
		$.ajax({
			type:'post',
			url:'./ajax/ajax_agent_empl.php',
			data: "mode=cboAll",
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
						$td = $selects.eq(3).parent();
						$td.html(SERVER_MESAGE);
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
	getCboMunicipios();	
	getCboNucleos();	
	getCboEmpl();
	//alert($selects.length);
	//alert($options1.length);
	
	
});