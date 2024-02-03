let Datos_divisa; 
let tipo_pago_array_global = Array();
tipo_pago_array_global['1'] = "efectivo";
tipo_pago_array_global['2'] = "transferencia";
tipo_pago_array_global['3'] = "pago_movil";
tipo_pago_array_global['4'] = "divisa";
//console.error("debo agregar la divisa");




let contador_cantidad_divisa = 0;

function tipo_pago_comun_update_divisa(){
	load_bcv(true,false);
	Datos_divisa = variable_divisa_global || {monto:35.27,fecha: "2000-12-03 10:30"};
	if(Datos_divisa.monto && Datos_divisa.fecha){
		// document.getElementById('tipo_cambio_divisa_monto_to_show').parentNode.parentNode.classList.remove("d-none");
		// document.getElementById('tipo_cambio_divisa_monto_to_show').innerHTML="BCV : $ "+sepMiles(Datos_divisa.monto);
		// document.getElementById('tipo_cambio_divisa_fecha_to_show').innerHTML=Datos_divisa.fecha;
		if(document.getElementById('monto_para_calcular')){
			document.getElementById('monto_para_calcular').classList.add("text-info");
			document.getElementById('monto_para_calcular').innerHTML="BCV : "+sepMiles(Datos_divisa.monto)+" Bs ( "+Datos_divisa.fecha.replace(/ [0-9][0-9].[0-9][0-9].[0-9][0-9]$/, "")+" )";
		}


	}
}

function tipo_pago_comun_resumen_load(list){
	if(list !== false){
		document.getElementById('tipo_pago_comun_extra_superior').classList.remove("d-none");
		if ($.fn.DataTable.isDataTable("#tipo_pago_comun_resumen_table")) {
			$("#tipo_pago_comun_resumen_table").DataTable().destroy();
		}
		
		$("#tipo_pago_comun_resumen_tbody").html("");
		
		if (!$.fn.DataTable.isDataTable("#tipo_pago_comun_resumen_table")) {
			$("#tipo_pago_comun_resumen_table").DataTable({
				language: {
					lengthMenu: "Mostrar _MENU_ por página",
					zeroRecords: "No se encontraron registros",
					info: "Mostrando página _PAGE_ de _PAGES_",
					infoEmpty: "No hay registros disponibles",
					infoFiltered: "(filtrado de _MAX_ registros totales)",
					search: "Buscar:",
					paginate: {
						first: "Primera",
						last: "Última",
						next: "Siguiente",
						previous: "Anterior",
					},
				},
				data:list,
				columns:[
					{data:"concepto", "width": "60%"},
					{data:"monto", "width": "40%"},
					],
				createdRow: function(row,data){
					// console.log(data);
					row.querySelector("td:nth-child(2)").className = "text-right text-nowrap align-middle";
					row.querySelector("td:nth-child(2)").innerText = sepMiles(data.monto.toString())+((data.tipo_monto == 0)?" Bs":" $");
					if(data.concepto == "Total" || data.concepto == "Total $" || data.concepto == "Total Bs"){
						row.querySelector("td:nth-child(1)").className = "text-info font-weight-bold text-right text-nowrap align-middle";
						row.querySelector("td:nth-child(2)").className = "font-weight-bold text-right text-nowrap align-middle";
					}
				},
				autoWidth: false,
				searching:false,
				info: false,
				ordering: false,
				paging: false
				//order: [[1, "asc"]],
				
			});
		}


	}
	else{
		document.getElementById('tipo_pago_comun_extra_superior').classList.add("d-none");
		document.getElementById('tipo_pago_comun_resumen_tbody').innerHTML = "";
	}
}



function load_tipo_pago_comun(){
	tipo_pago_comun_update_divisa();

	// comunes -------------------------------------
		// monto
		
		eventoMonto("tipo_pago_comun-monto_total",function(e){
			e.transform_to_dolar = true;
			e.value = sepMiles(e.value);
			if(e.transform_to_dolar===true && e.value !=''){
				var temp = sepMiles(e.value,true);
				temp = (temp/Datos_divisa.monto).toFixed(2);
				// console.log(temp);
				if(temp>0){
					document.getElementById('tipo_pago_comun-bs_to_dolar').innerText=sepMiles(temp.toString());
				}
				else{
					document.getElementById('tipo_pago_comun-bs_to_dolar').innerText='';

				}

			}
			else{
				document.getElementById('tipo_pago_comun-bs_to_dolar').innerText='';
			}
		});
		
		//tipo_pago
		document.getElementById('tipo_pago_comun').onchange=function(){
			if(this.value!= ''){
				document.getElementById('nav-tab_tipo_pago_comun_'+tipo_pago_array_global[this.value]).click();
				document.getElementById('tipo_pago_comun-monto_total').readOnly=(this.value=="4")?true:false;
				if(this.value != '4'){
					document.getElementById('tipo_pago_comun-monto_total').onkeyup();
				}
				else{
					document.getElementById('tipo_pago_comun-bs_to_dolar').innerText='';
				}
			}
		};


	// comunes ---------------------------------------------------------

	// transferencia--------------------------------------------
		cedulaKeypress(document.getElementById('tipo_pago_comun-cedula_transf'));
		eventoKeypress(document.getElementById('tipo_pago_comun-referencia_transf'), /^[0-9]$/);
		eventoKeypress(document.getElementById('tipo_pago_comun-banco_transf'), /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]*$/);
		document.getElementById('tipo_pago_comun-banco_transf').maxLength = 60;
		document.getElementById('tipo_pago_comun-referencia_transf').maxLength = 50;

		
	// transferencia--------------------------------------------
	// pago movil--------------------------------------------

		cedulaKeypress(document.getElementById('tipo_pago_comun-Cedula_pagoMovil'));
		eventoKeypress(document.getElementById('tipo_pago_comun-Banco_pagoMovil'), /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]*$/);
		eventoKeypress(document.getElementById('tipo_pago_comun-Referencia_pagoMovil'), /^[0-9]$/);
		eventoKeypress(document.getElementById('tipo_pago_comun-Telefono_pagoMovil'), /^[0-9]$/);
		document.getElementById('tipo_pago_comun-Banco_pagoMovil').maxLength = 60;
		document.getElementById('tipo_pago_comun-Referencia_pagoMovil').maxLength = 50;
		document.getElementById('tipo_pago_comun-Telefono_pagoMovil').maxLength = 11;

	// pago movil--------------------------------------------
	
	document.getElementById('tipo_pago_comun-divisa_cantidad').addEventListener("input",function(e){
		var cantidad = e.target.value || 0;
		cantidad = parseInt(cantidad);
		if (cantidad>contador_cantidad_divisa) {

		var campos = '';
			for(var i = contador_cantidad_divisa; i<cantidad;i++){
				var divisa_container = crearElem("div","id,divisa_container_"+(i+1));

				divisa_container.innerHTML=`<strong>Divisa Nº ${i+1}</strong>
							<div class="row mt-2 justify-content-center">
								<div class="col-6 col-md-3">
									<label for="tipo_pago_comun-denominacion_${i+1}">Denominación</label>
									<input autocomplete="off" maxlength="20" type="text" class="form-control denominacion" id="tipo_pago_comun-denominacion_${i+1}" data-span="invalid-span-tipo_pago_comun-denominacion_${i+1}">
									<span id="invalid-span-tipo_pago_comun-denominacion_${i+1}" class="invalid-span text-danger"></span>

								</div>
								<div class="col-6 col-md-6">
									<label for="tipo_pago_comun-serial_${i+1}">Serial</label>
									<input autocomplete="off" maxlength="20" type="text" class="form-control serial" id="tipo_pago_comun-serial_${i+1}" data-span="invalid-span-tipo_pago_comun-serial_${i+1}">
									<span id="invalid-span-tipo_pago_comun-serial_${i+1}" class="invalid-span text-danger"></span>

								</div>
							</div>`;

					document.getElementById('tipo_pago_comun_divisa_campos').appendChild(divisa_container);


					eventoKeypress(document.getElementById('tipo_pago_comun-denominacion_'+(i+1)),/^[0-9\.]*$/);
					eventoKeypress(document.getElementById('tipo_pago_comun-serial_'+(i+1)),/^[0-9]*$/);

					eventoKeyup(document.getElementById('tipo_pago_comun-denominacion_'+(i+1)), /^[0-9]{1,17}(?:[.,][0-9]{2})?$/, "Ingrese una denominación de billetes valida ej 5 o 5.25",undefined,function(){
						calcular_total_divisa();
					});
					eventoKeyup(document.getElementById('tipo_pago_comun-serial_'+(i+1)), /^[0-9]{1,20}$/, "Ingrese un serial valido de billete",undefined,function(){
						calcular_total_divisa();
					});

					document.getElementById('tipo_pago_comun-denominacion_'+(i+1)).onchange = function(){
						validarKeyUp(/^[0-9]{1,17}(?:[.,][0-9]{2})?$/,$(this), "Ingrese una denominación de billetes valida ej 5 o 5.25");
						calcular_total_divisa();
					}
					document.getElementById('tipo_pago_comun-serial_'+(i+1)).onchange=function(){
						validarKeyUp(/^[0-9]{1,20}$/,$(this), "Ingrese un serial valido de billete");
						calcular_total_divisa();
					}

				}
			contador_cantidad_divisa = cantidad;
		}
		else{
			for(i = contador_cantidad_divisa;i>=cantidad;i--){
				if(document.getElementById("divisa_container_"+(i+1))){
					var element = document.getElementById("divisa_container_"+(i+1));
					element.parentNode.removeChild(element);
				}
			}

			contador_cantidad_divisa = cantidad;
		}
		calcular_total_divisa();
	});
	

}
function delete_tipo_pago_comun(x){
	options = document.getElementById('tipo_pago_comun').getElementsByTagName('option');
	for(i = 0;i<options.length;i++){
		for(a=0;a<arguments.length;a++){
			if(options[i].value == arguments[a]){
				options[i].parentNode.removeChild(options[i]);
			}
		}
	}
	document.getElementById('tipo_pago_comun').value = document.getElementById('tipo_pago_comun').getElementsByTagName('option')[0].value;
	document.getElementById('tipo_pago_comun').onchange();


}

function reload_bcv(){
	load_bcv(true,true);// carga la variable global variable_divisa_global declarada en bcv.js
}







function validar_tipo_pago_comunes(){

	if(!validarKeyUp(V.fecha($("#tipo_pago_comun-fecha").val()), $("#tipo_pago_comun-fecha"), "Ingrese una fecha valida")){
		muestraMensaje("ERROR","Ingrese una fecha valida","error");
		return false;
	}
	document.getElementById('tipo_pago_comun-hora').value="00:00";
	if(!validarKeyUp(/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/,$("#tipo_pago_comun-hora"),"Ingrese una hora valida")){
		muestraMensaje("ERROR","Ingrese una hora valida","error");
		return false;
	}
	if(!validarKeyUp(montoExp,$("#tipo_pago_comun-monto_total"),"Ingrese un monto valido")){
		muestraMensaje("ERROR","Ingrese un monto valido","error");
		return false;
	}
	if($("#tipo_pago_comun-monto_total").val().length>29){
		muestraMensaje("ERROR","El monto es demasiado grande","error");
		return false;
	}
	if(!(parseFloat( sepMiles( document.getElementById('tipo_pago_comun-monto_total').value,true ) ) > 0 )){
		muestraMensaje("ERROR", "El monto debe ser mayor a 0", "error");
		return false;
	}


	var tipo_pago_comun = document.getElementById('tipo_pago_comun');
	if(tipo_pago_comun.value == "1"){
		//no se valida nada mas

	}
	else if(tipo_pago_comun.value == "2"){
		if(!validarKeyUp(V.cedula($("#tipo_pago_comun-cedula_transf").val()), $("#tipo_pago_comun-cedula_transf"), "Ingrese una cedula valida ej. V12345678")){
			muestraMensaje("ERROR","Ingrese una cedula valida","error");
			return false
		}
		if(!validarKeyUp(/^[0-9]{1,50}$/, $("#tipo_pago_comun-referencia_transf"), "Ingrese una referencia valida")){
			muestraMensaje("ERROR","Ingrese una referencia valida","error");
			return false
		}
		if(!validarKeyUp(V.alfanumerico( $("#tipo_pago_comun-banco_transf").val(),60), $("#tipo_pago_comun-banco_transf"), "Ingrese un nombre de banco valido")){
			muestraMensaje("ERROR","Ingrese un nombre de banco valido","error");
			return false
		}
	}
	else if(tipo_pago_comun.value == "3"){

		if(!validarKeyUp(V.cedula($("#tipo_pago_comun-Cedula_pagoMovil").val()), $("#tipo_pago_comun-Cedula_pagoMovil"), "Ingrese cedula una valida ej. V12345678")){
			muestraMensaje("ERROR","Ingrese cedula una valida","error");
			return false;
		}
		if(!validarKeyUp(V.telefono($("#tipo_pago_comun-Telefono_pagoMovil").val()), $("#tipo_pago_comun-Telefono_pagoMovil"), "Ingrese un Teléfono valido")){
			muestraMensaje("ERROR","Ingrese un Teléfono valido ej 04145555555","error");
			return false;
		}
		if(!validarKeyUp(/^[0-9]{1,50}$/, $("#tipo_pago_comun-Referencia_pagoMovil"), "Ingrese una referencia valida")){
			muestraMensaje("ERROR","Ingrese una referencia valida","error");
			return false;
		}
		if(!validarKeyUp(V.alfanumerico($("#tipo_pago_comun-Banco_pagoMovil").val()), $("#tipo_pago_comun-Banco_pagoMovil"), "Ingrese un nombre de banco valido")){
			muestraMensaje("ERROR","Ingrese un nombre de banco valido","error");
			return false;
		}

	}
	else if(tipo_pago_comun.value == "4"){
		var cantidad = parseInt($("#tipo_pago_comun-divisa_cantidad").val());

		if(!validarKeyUp((!isNaN(cantidad)&&cantidad>0)?true:false, $("#tipo_pago_comun-divisa_cantidad"), "Ingrese una cantidad de billetes valida")){
			muestraMensaje("ERROR","Ingrese una cantidad de billetes valida","error");
			return false;	
		}
		for(i=1; i <= cantidad; i++){
			var denominacion = parseInt($("#tipo_pago_comun-denominacion_"+i).val());
			if(!validarKeyUp((!isNaN(denominacion)&&denominacion>0)?true:false, $("#tipo_pago_comun-denominacion_"+i), "Ingrese una denominación de billetes valida ej 5 o 5.25")){
				muestraMensaje("ERROR","Ingrese una denominación de billetes valida en la divisa Nº "+i,"error");
				return false;
			}

			if(!validarKeyUp(/^[0-9]{1,20}$/, $("#tipo_pago_comun-serial_"+i), "Ingrese un serial valido de billete")){
				muestraMensaje("ERROR","Ingrese un serial de billete valido en la divisa Nº "+i,"error");
				return false;
			}
		}
	}
	else{
		muestraMensaje("ERROR","Seleccione un tipo de pago","error");
	}

	vaciarSpanError();
	return true;
}

function limpiar_tipo_pago_comun(){
	document.getElementById('tipo_pago_comun-hora').value = "00:00";
	options = document.getElementById('tipo_pago_comun').getElementsByTagName('option');
	document.getElementById('tipo_pago_comun').value = options[0].value;
	document.getElementById('tipo_pago_comun').onchange();
}

function objeto_tipo_pago_comun(){
	tipo_pago_comun = document.getElementById('tipo_pago_comun');
	if(tipo_pago_comun.value!=''){
		var obj={};

		obj.id_detalles_pagos = document.getElementById('id_tipo_pago_comun').value;
		if(document.getElementById('id_pago_comun').value != ''){
			obj.id_pago = document.getElementById('id_pago_comun').value;
		}

		if(tipo_pago_comun.value=="1"){
			if(validar_tipo_pago_comunes("efectivo")){
				obj.tipo_pago = "1";
				obj.fecha = document.getElementById('tipo_pago_comun-fecha').value;
				obj.hora = document.getElementById('tipo_pago_comun-hora').value;
				obj.monto = document.getElementById('tipo_pago_comun-monto_total').value;
			}
			else{obj.valid = false;}
		}
		if(tipo_pago_comun.value=="2"){
			if(validar_tipo_pago_comunes("transferencia")){
				obj.tipo_pago = "2";
				obj.fecha = document.getElementById('tipo_pago_comun-fecha').value;
				obj.hora = document.getElementById('tipo_pago_comun-hora').value;
				obj.monto = document.getElementById('tipo_pago_comun-monto_total').value;

				obj.cedula = document.getElementById('tipo_pago_comun-cedula_transf').value;
				obj.referencia = document.getElementById('tipo_pago_comun-referencia_transf').value;
				obj.banco = document.getElementById('tipo_pago_comun-banco_transf').value;




			}
			else{obj = false;}
		}
		if(tipo_pago_comun.value=="4"){
			if(validar_tipo_pago_comunes("divisa")){
				calcular_total_divisa();
				obj.tipo_pago = "4";
				obj.fecha = document.getElementById('tipo_pago_comun-fecha').value;
				obj.hora = document.getElementById('tipo_pago_comun-hora').value;
				obj.monto = document.getElementById('tipo_pago_comun-monto_total').value;
				obj.cantidad = document.getElementById('tipo_pago_comun-divisa_cantidad').value;

				obj.billetes = [];
				for(var i = 1; i <= obj.cantidad;i++){
					var denominacion = document.getElementById('tipo_pago_comun-denominacion_'+i).value;
					var serial = document.getElementById('tipo_pago_comun-serial_'+i).value;
					obj.billetes.push({serial: serial, denominacion: denominacion}); 
				}
				var tem = document.getElementById('tipo_pago_comun-billetes_obj').value;



				if(tem!=''){
					if(!(tem === JSON.stringify(obj.billetes))){
						obj.billetes_to_Modify = true;
					}
					else{
						obj.billetes_to_Modify = false;
					}
				}


			}
			else{obj = false;}
		}
		if(tipo_pago_comun.value=="3"){
			if(validar_tipo_pago_comunes("pago_movil")){
				obj.tipo_pago = "3";
				obj.fecha = document.getElementById('tipo_pago_comun-fecha').value;
				obj.hora = document.getElementById('tipo_pago_comun-hora').value;
				obj.monto = document.getElementById('tipo_pago_comun-monto_total').value;

				obj.telefono = document.getElementById('tipo_pago_comun-Telefono_pagoMovil').value;
				obj.cedula = document.getElementById('tipo_pago_comun-Cedula_pagoMovil').value;
				obj.referencia = document.getElementById('tipo_pago_comun-Referencia_pagoMovil').value;
				obj.banco = document.getElementById('tipo_pago_comun-Banco_pagoMovil').value;
			}
			else{obj = false;}
		}
		

		return obj;
	}
	else{
		muestraMensaje("ERROR","Seleccione un tipo de pago","error");
		return false;
	}
}

function calcular_total_divisa(){
	var denomi_total = 0;
	for(var i = 1; i <= contador_cantidad_divisa; i++){
		if(/^[0-9]{1,17}(?:[.,][0-9]{2})?$/.test($("#tipo_pago_comun-denominacion_"+i).val()) && /^[0-9]{1,20}$/.test($("#tipo_pago_comun-serial_"+i).val())){
			var denomi_temp = parseFloat($("#tipo_pago_comun-denominacion_"+i).val());
			if(!isNaN(denomi_temp)){
				denomi_total += denomi_temp;
			}
			else continue;

		}
		else continue;
	}
	$("#tipo_pago_comun-monto_total").val((denomi_total*Datos_divisa.monto).toFixed(2));
	document.getElementById('tipo_pago_comun-monto_total').onchange();
}

function cargar_tipo_pago_comun(obj){

	document.getElementById('id_tipo_pago_comun').value = obj.id_detalles_pagos || '';
	document.getElementById('id_pago_comun').value = obj.id_pago || '';


	document.getElementById('tipo_pago_comun').value = obj.tipo_pago;
	document.getElementById('tipo_pago_comun').onchange();
	document.getElementById('tipo_pago_comun-fecha').value = obj.fecha;
	document.getElementById('tipo_pago_comun-hora').value = obj.hora;
	document.getElementById('tipo_pago_comun-monto_total').value = obj.monto;
	document.getElementById('tipo_pago_comun-monto_total').onchange();


	if(obj.tipo_pago == "2"){//transferencia

		document.getElementById('tipo_pago_comun-cedula_transf').value = obj.cedula;
		document.getElementById('tipo_pago_comun-referencia_transf').value = obj.referencia;
		document.getElementById('tipo_pago_comun-banco_transf').value = obj.banco;

	}
	else if(obj.tipo_pago == "3"){//pagomovil
		document.getElementById('tipo_pago_comun-Cedula_pagoMovil').value = obj.cedula;
		document.getElementById('tipo_pago_comun-Referencia_pagoMovil').value = obj.referencia;
		document.getElementById('tipo_pago_comun-Banco_pagoMovil').value = obj.banco;
		document.getElementById('tipo_pago_comun-Telefono_pagoMovil').value = obj.telefono;

	}
	else if(obj.tipo_pago == "4"){//divisa
		document.getElementById('tipo_pago_comun-billetes_obj').value = JSON.stringify(obj.billetes);
		document.getElementById('tipo_pago_comun-divisa_cantidad').value = obj.cantidad;

		document.getElementById('tipo_pago_comun-divisa_cantidad').dispatchEvent(new Event('input'));

		for (var i = 0; i < obj.cantidad; i++){
			document.getElementById('tipo_pago_comun-denominacion_'+(i+1)).value = obj.billetes[i].denominacion;
			document.getElementById('tipo_pago_comun-serial_'+(i+1)).value = obj.billetes[i].serial;
		}
		document.getElementById('tipo_pago_comun-divisa_cantidad').dispatchEvent(new Event('input'));

	}
}

/*
obj.tipo_pago
obj.fecha
obj.hora
obj.monto
obj.cedula
obj.referencia
obj.banco
obj.telefono
obj.cantidad
obj.billetes
*/
