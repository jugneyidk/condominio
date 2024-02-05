var lista_apartamentos_seleccionados = {lista:[]};
document.addEventListener("DOMContentLoaded", function(){


	load_extra_stuff();

	rowsEvent("cargo_apartamentos_seleccionados_info",function(row,elem){
		if(elem != row.getElementsByTagName('input')[0]){
			row.getElementsByTagName('input')[0].click();
		}
	});



	// cargarLoqueras();

	//--------------------------------------
	//--------------------------------------
	//--------- CARGOS ---------------------
	//--------------------------------------
	//--------------------------------------
		//document.getElementById('nav-cargos-tab').click();


		eventoAlfanumerico("cargo_concepto",80, "1,80", "Tiene caracteres inválidos o esta vació");
		eventoMonto("cargo_monto");
		$("#limpiar").on("click",borrar);





		document.getElementById('cargo_tipo_monto_divisa').onchange = document.getElementById('cargo_tipo_monto_bolivar').onchange = function(){
			var mensaje = {divisa:"El monto del cargo se calculara según la tasa del día", bolivar:"El monto sera fijo en bolívares"}
			if(this.checked == true){
				document.getElementById('cargo_tipo_monto_info').innerHTML = mensaje[this.value];
			}
			document.getElementById(this.dataset.span).innerHTML="";
		}

		document.getElementById('cargo_tipo_cargo_mensual').onchange = document.getElementById('cargo_tipo_cargo_unico').onchange = function(){
			var mensaje = {mensual:"El cargo se aplicara mensualmente",unico:"El cargo se aplicara una única vez y se desactivara una vez aplicado"};
			if(this.checked==true){
				document.getElementById('cargo_tipo_cargo_info').innerHTML = mensaje[this.value];
			}
			document.getElementById(this.dataset.span).innerHTML="";
		}
		document.getElementById('cargo_tipo_cargo_global').onchange = document.getElementById('cargo_tipo_cargo_dedicado').onchange = function(){
			var mensaje = {global:"El cargo se aplicara global-mente",dedicado:"El cargo se aplicara a los siguiente apartamentos"};
			if(this.checked==true){
				document.getElementById('cargo_tipo_cargo_gd_info').innerHTML = mensaje[this.value];
				if(this.value == "dedicado"){
					document.getElementById('cargo_dedicado_to_show').classList.remove("d-none");
				}
				else{
					document.getElementById('cargo_dedicado_to_show').classList.add("d-none");
				}
			}
			document.getElementById(this.dataset.span).innerHTML="";
		}

		document.getElementById('cargo_tipo_cargo_aplicar_next').onchange = document.getElementById('cargo_tipo_cargo_no_aplicar').onchange = function(){
			document.getElementById(this.dataset.span).innerHTML="";
		}




		$("#cargo_apartamento_nuevo_btn").on("click",()=>{
			var datos = new FormData();
			datos.append("accion","lista_apartamentos");
			enviaAjax(datos,function(respuesta){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "lista_apartamentos"){
					if ($.fn.DataTable.isDataTable("#cargo_apartamento_table_info")) {
						$("#cargo_apartamento_table_info").DataTable().destroy();
					}

					$("#cargo_apartamentos_seleccionados_info").html("");

					if (!$.fn.DataTable.isDataTable("#cargo_apartamento_table_info")) {
						$("#cargo_apartamento_table_info").DataTable({
							language: {
								lengthMenu: "Mostrar _MENU_ por página",
								zeroRecords: "No se encontraron registros de apartamentos",
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
							data:lee.mensaje,
							createdRow: function(row,data){
								var checkbox = crearElem("input","type,checkbox,class,apartamentos_check");
								if(lista_apartamentos_seleccionados[data[1]]){
									checkbox.checked = true;
								}

								checkbox.onchange=function(){
									checkbox_func(this,data);
								}
								row.querySelector("td:first-child").appendChild(checkbox);
								row.querySelector("td:nth-of-type(2)").classList.add("text-center");
								row.querySelector("td:nth-of-type(3)").classList.add("text-truncate");
								row.querySelector("td:nth-of-type(3)").style = "max-width:125px";
								row.querySelector("td:nth-of-type(3)").setAttribute("title",data[2]);


							},
							autoWidth: false,
							order: [[1, "asc"]],
							columnDefs: [
									{
									targets: [0],
									"orderable": false
									}
								]  
						});
					}
				}
				else if (lee.resultado == 'is-invalid'){
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
				else if(lee.resultado == "error"){
					muestraMensaje("ERROR", lee.mensaje,"error");
					console.error(lee.mensaje);
				}
				else if(lee.resultado == "console"){
					console.log(lee.mensaje);
				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
			}).then(()=>{
				$("#modal_cargo_lista_apartamentos").modal("show");
			});
		});

		$("#consultar_cargo").on("click",()=>{
			var datos = new FormData();
			datos.append("accion","lista_cargos");
			enviaAjax(datos,function(respuesta){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "lista_cargos"){

					//console.log(lee.mensaje);
					//return false;


					if ($.fn.DataTable.isDataTable("#cargo_table_info")) {
						$("#cargo_table_info").DataTable().destroy();
					}
					
					$("#cargos_info").html("");

					if (!$.fn.DataTable.isDataTable("#cargo_table_info")) {
						$("#cargo_table_info").DataTable({
							language: {
								lengthMenu: "Mostrar _MENU_ por página",
								zeroRecords: "No se encontraron registros de apartamentos",
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
							data:lee.mensaje,
							createdRow: function(row,data){
								row.dataset.id=data[6];
							},
							autoWidth: false
							//order: [[1, "asc"]],
							
						});
					}



















					
					
				}
				else if (lee.resultado == 'is-invalid'){
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
				else if(lee.resultado == "error"){
					muestraMensaje("ERROR", lee.mensaje,"error");
					console.error(lee.mensaje);
				}
				else if(lee.resultado == "console"){
					console.log(lee.mensaje);
				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
			}).then(()=>{
				$("#modal_cargo_lista_cargos").modal("show");
			});
		});

		rowsEvent("cargos_info",function(e){
			var datos = new FormData();
			datos.append("accion","lista_apartamentos_select");
			datos.append("id",e.dataset.id);
			enviaAjax(datos,function(respuesta){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "lista_apartamentos_select"){
					console.log(lee.mensaje);
					borrar();
					var cargo = lee.mensaje.cargo;
					document.getElementById('cargo_id_hidden').value = cargo.id_lista_cargos;



					$("#cargo_concepto").val(cargo.concepto);
					$("#cargo_monto").val(cargo.monto);
					document.getElementById('cargo_monto').onchange();
					var etiqueta = "cargo_tipo_monto_"+((cargo.tipo_monto == 1)?'divisa':"bolivar");
					document.getElementById(etiqueta).checked=true;
					document.getElementById(etiqueta).onchange();
					var etiqueta = "cargo_tipo_cargo_"+((cargo.mensual == 1)?'mensual':"unico");
					document.getElementById(etiqueta).checked=true;
					document.getElementById(etiqueta).onchange();
					var etiqueta = "cargo_tipo_cargo_"+((cargo.aplicar_next_mes == 1)?'aplicar_next':"no_aplicar");
					document.getElementById(etiqueta).checked=true;
					document.getElementById(etiqueta).onchange();
					var etiqueta = "cargo_tipo_cargo_"+((cargo.tipo_cargo == 1)?'global':"dedicado");
					document.getElementById(etiqueta).checked=true;
					document.getElementById(etiqueta).onchange();

					if(cargo.tipo_cargo === '0'){
						// delete lee.mensaje.cargo;
						set_cargos_seleccionados_in_table(lee.mensaje);
					}

					cambiarbotones_cargos(false);








				}
				else if (lee.resultado == 'is-invalid'){
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
				else if(lee.resultado == "error"){
					muestraMensaje("ERROR", lee.mensaje,"error");
					console.error(lee.mensaje);
				}
				else if(lee.resultado == "console"){
					console.log(lee.mensaje);
				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
			}).then(()=>{
				$("#modal_cargo_lista_cargos").modal("hide");
			});
		})


		$("#incluir_cargo").on("click",()=>{
			if(validar_cargos()){
				var datos = new FormData($("#f_cargos")[0]);
				datos.append("accion","incluir_cargo");
				if(document.getElementById('cargo_tipo_cargo_dedicado').checked){
					datos.append("lista", JSON.stringify(lista_apartamentos_seleccionados.lista));
				}
				enviaAjax(datos,function(respuesta){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "incluir_cargo"){
						muestraMensaje("El cargo fue registrado exitosamente", "", "success");
						borrar();
					}
					else if (lee.resultado == 'is-invalid'){
						muestraMensaje("ERROR", lee.mensaje,"error");
					}
					else if(lee.resultado == "error"){
						muestraMensaje("ERROR", lee.mensaje,"error");
						console.error(lee.mensaje);
					}
					else if(lee.resultado == "console"){
						console.log(lee.mensaje);
					}
					else{
						muestraMensaje("ERROR", lee.mensaje,"error");
					}
				});
			}
		});

		$("#modificar_cargo").on("click",()=>{
			Swal.fire({
				title: "¿Estás Seguro?",
				text: "¿Está seguro que desea modificar el cargo?",
				showCancelButton: true,
				confirmButtonText: "Modificar",
				confirmButtonColor: "#ffc107",
				cancelButtonText: `Cancelar`,
				icon: "warning",
			}).then((result) => {
				if (result.isConfirmed) {

					if(validar_cargos()){
						var datos = new FormData($("#f_cargos")[0]);
						datos.append("id",document.getElementById('cargo_id_hidden').value);
						datos.append("accion","modificar_cargo");
						if(document.getElementById('cargo_tipo_cargo_dedicado').checked){
							datos.append("lista", JSON.stringify(lista_apartamentos_seleccionados.lista));
						}
						enviaAjax(datos,function(respuesta){
						
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "modificar_cargo"){
								muestraMensaje("El cargo fue modificado exitosamente", "", "success");
								borrar();
							}
							else if (lee.resultado == 'is-invalid'){
								muestraMensaje("ERROR", lee.mensaje,"error");
							}
							else if(lee.resultado == "error"){
								muestraMensaje("ERROR", lee.mensaje,"error");
								console.error(lee.mensaje);
							}
							else if(lee.resultado == "console"){
								console.log(lee.mensaje);
							}
							else{
								muestraMensaje("ERROR", lee.mensaje,"error");
							}
						});
					}



				}
			});
		});

		$("#eliminar_cargo").on("click",()=>{

			Swal.fire({
				title: "¿Estás Seguro?",
				text: "¿Está seguro que desea eliminar el cargo?",
				showCancelButton: true,
				confirmButtonText: "eliminar",
				confirmButtonColor: "#c82333",
				cancelButtonText: `Cancelar`,
				icon: "warning",
			}).then((result) => {
				if (result.isConfirmed) {
					var datos = new FormData();
					datos.append("accion","eliminar_cargo");
					datos.append("id",document.getElementById('cargo_id_hidden').value);
					enviaAjax(datos,function(respuesta){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "eliminar_cargo"){
							muestraMensaje(lee.mensaje, '', "success");
							borrar();
						}
						else if (lee.resultado == 'is-invalid'){
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
						else if(lee.resultado == "error"){
							muestraMensaje("ERROR", lee.mensaje,"error");
							console.error(lee.mensaje);
						}
						else if(lee.resultado == "console"){
							console.log(lee.mensaje);
						}
						else{
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
					});
				}
			});
		});

	//--------------------------------------
	//--------------------------------------
	//--------------------------------------
	//--------------------------------------
	//--------------------------------------

	

});



//--------------------------------------
//--------------------------------------
//--------- CARGOS ---------------------
//--------------------------------------
//--------------------------------------
	function validar_cargos(){
		if(!document.getElementById('cargo_concepto').validarme()){
			muestraMensaje("Campo Invalido", "El concepto es invalido", "error");
			return false;
		}
		else if(!document.getElementById('cargo_monto').validarme()){
			muestraMensaje("Campo Invalido", "El monto es invalido", "error");
			return false;
		}
		else if(!validar_radio(document.querySelectorAll("input[name='cargo_tipo_monto']"))){
			document.getElementById(document.querySelector("input[name='cargo_tipo_monto']").dataset.span).innerHTML="Seleccione un valor";
			muestraMensaje("Campo Invalido", "Debe seleccionar un tipo de monto", "error");
			return false;
		}
		else if(!validar_radio(document.querySelectorAll("input[name='cargo_tipo_cargo']"))){
			document.getElementById(document.querySelector("input[name='cargo_tipo_cargo']").dataset.span).innerHTML="Seleccione un valor";
			muestraMensaje("Campo Invalido", "Debe seleccionar el tipo de cargo (mensual/único)", "error");
			return false;
		}
		else if(!validar_radio(document.querySelectorAll("input[name='cargo_aplicar_next']"))){
			document.getElementById(document.querySelector("input[name='cargo_aplicar_next']").dataset.span).innerHTML="Seleccione un valor";
			muestraMensaje("Campo Invalido", "Debe seleccionar si desea aplicar el cargo el siguiente mes o no", "error");
			return false;
		}
		else if(!validar_radio(document.querySelectorAll("input[name='cargo_tipo_cargo_global_dedicado']"))){
			document.getElementById(document.querySelector("input[name='cargo_tipo_cargo_global_dedicado']").dataset.span).innerHTML="Seleccione un valor";
			muestraMensaje("Campo Invalido", "Debe seleccionar el tipo de cargo (global/dedicado)", "error");
			return false;
		}
		else if(document.getElementById('cargo_tipo_cargo_dedicado').checked){
			if(!lista_apartamentos_seleccionados.lista.length){
				muestraMensaje("Campo Invalido", "Debe seleccionar el/los apartamentos a ser aplicados los cargos", "error");
				return false;
			}
		}
		


		console.error("");
		return true;
	}

	$("#modal_cargo_lista_apartamentos").on("hide.bs.modal",()=>{

		set_cargos_seleccionados_in_table(lista_apartamentos_seleccionados);

	});

	function set_cargos_seleccionados_in_table(obj){
		console.log(obj);
		//console.log(obj.lista);
		obj.lista.sort((r1, r2) => {return (r1[1] > r2[1]) ? 1 : (r1[1] < r2[1]) ? -1 : 0;});
		// console.log(obj.lista);

		document.getElementById('cargo_apartamentos_seleccionados').innerHTML='';
		for (x of obj.lista){
			// console.log(x);
			var tr = crearElem("tr",`id,cod_${x[0]}`);
			tr.appendChild(crearElem("td","class,text-nowrap",x[1]));
			tr.appendChild(crearElem("td","class,text-nowrap",x[2]));
			tr.appendChild(crearElem("td","class,text-nowrap",x[3]));
			tr.appendChild(crearElem("td","class,text-nowrap",x[4]));
			tr.appendChild(crearElem("td","class,text-nowrap",x[5]));
			// tr.appendChild(crearElem("td","class,text-nowrap",x[1]));
			// tr.appendChild(crearElem("td","class,text-nowrap",x[2]));
			// tr.appendChild(crearElem("td","class,text-nowrap",x[3]));
			// tr.appendChild(crearElem("td","class,text-nowrap",x[4]));
			document.getElementById('cargo_apartamentos_seleccionados').appendChild(tr);
		}
		if(document.getElementById('cargo_apartamentos_seleccionados').innerHTML == ""){
			var tr = crearElem("tr");
			tr.appendChild(crearElem("td","colspan,6,class,text-center","No ha seleccionado un apartamento"));
			document.getElementById('cargo_apartamentos_seleccionados').appendChild(tr);
		}
		lista_apartamentos_seleccionados = obj;
	}

	function checkbox_func(elem,data){
		if(elem.checked){
			lista_apartamentos_seleccionados.lista.push(data);
			lista_apartamentos_seleccionados[data[1]] = 1;
		}
		else{
			if(lista_apartamentos_seleccionados[data[1]]){
				for(var i = 0; i<lista_apartamentos_seleccionados.lista.length;i++){
					if(lista_apartamentos_seleccionados.lista[i][1] == data[1]){
						lista_apartamentos_seleccionados.lista.splice(i,1);
						break;
					}
				}
				delete lista_apartamentos_seleccionados[data[1]];
			}
		}
		//console.log(elem);
		//console.log(data);

		// console.log(lista_apartamentos_seleccionados);
	}

	 
//--------------------------------------
//--------------------------------------
//--------- CARGOS ---------------------
//--------------------------------------
//--------------------------------------
function validar_radio(elements){
	var ok = false;
	for(x of elements){
		if(x.checked){
			ok = true;
			break;
		}
	}
	return ok
}

function borrar() {
	$('form input:not(.dont-erase)').val("");
	// $("form input").val("");
	$("form select:not(.dont-erase)").val("");
	$("form input[type='radio']").each(function(a,b){
		b.checked=false;
	});

	document.getElementById('cargo_tipo_monto_divisa').checked=true;
	
	limpiarvalidacion();
	cambiarbotones_cargos();
	cambiarbotones_deuda();
	//cambiarbotones_2();
	lista_apartamentos_seleccionados = {lista:[]};
	document.getElementById('cargo_apartamentos_seleccionados').innerHTML='';
	var tr = crearElem("tr");
	tr.appendChild(crearElem("td","colspan,6,class,text-center","No ha seleccionado un apartamento"));
	document.getElementById('cargo_apartamentos_seleccionados').appendChild(tr);
	document.getElementById('cargo_tipo_monto_info').innerHTML="";
	document.getElementById('cargo_tipo_cargo_info').innerHTML="";
	document.getElementById('cargo_tipo_cargo_gd_info').innerHTML="";
	document.getElementById('cargo_dedicado_to_show').classList.add("d-none");
	placeholder_concepto();

}

function placeholder_concepto(){
	var hoy = new Date();
	var mensaje = "Factura del mes de "+hoy.toLocaleString('default',{month:'long',timeZone:"America/Caracas"});
	var mensaje = mensaje+" "+hoy.getFullYear();
	document.getElementById('deuda_concepto').value=mensaje;
	document.getElementById('deuda_concepto').onkeyup();
	//var hoy_format = hoy.getFullYear()+"-"+(hoy.getMonth() + 1)+"-"+(hoy.getDate())<10?"0"+hoy.getDate():hoy.getDate();
	var hoy_format = hoy.getFullYear()+"-";
	var month = hoy.getMonth() + 1;
	hoy_format += (parseInt(month) <10 )?"0":"";
	hoy_format += month+"-";
	hoy_format += (parseInt(hoy.getDate())<10)?"0"+hoy.getDate():hoy.getDate();
	document.getElementById('deuda_fecha').value = hoy_format;
}


function cambiarbotones_cargos(parametro = true) {
	$("#modificar_cargo").prop("disabled", parametro);
	$("#eliminar_cargo").prop("disabled", parametro);
	$("#incluir_cargo").prop("disabled", !parametro);
}
function cambiarbotones_deuda(parametro = true) {
	$("#modificar_deuda").prop("disabled", parametro);
	$("#eliminar_deuda").prop("disabled", parametro);
	$("#incluir_deuda").prop("disabled", !parametro);
}
function limpiarvalidacion() {
	$("form input").removeClass("is-valid");
	$("form input").removeClass("is-invalid");
	$("form select").removeClass("is-valid");
	$("form select").removeClass("is-invalid");
}

function load_extra_stuff(){
	eventoKeyup("deuda_fecha", /^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/, "La fecha es invalida");
	eventoAlfanumerico("deuda_concepto",100,"1,100","El concepto de la deuda tiene caracteres inválidos o esta vació");

	placeholder_concepto();



	$("#incluir_deuda").on("click",()=>{
		if(validar_deudas()){





			var datos = new FormData();
			datos.append("accion","lista_resumen_cargos");
			enviaAjax(datos,function(respuesta){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "lista_resumen_cargos"){
					document.getElementById('deuda_lista_resumen_global').innerHTML="";
					document.getElementById('deuda_lista_resumen_dedicado').innerHTML="";
					for(x of lee.global){
						console.log(x);
						var tr = crearElem("tr");
						tr.appendChild(crearElem("td",undefined,x.concepto));
						tr.appendChild(crearElem("td",undefined,sepMiles(x.divisa)));
						tr.appendChild(crearElem("td",undefined,sepMiles(x.bolivares)));
						document.getElementById('deuda_lista_resumen_global').appendChild(tr);

					}
					for (x of lee.dedicados){
						console.log(x);
						var tr = crearElem("tr");
						tr.appendChild(crearElem("td",undefined,x.concepto));
						tr.appendChild(crearElem("td",undefined,sepMiles(x.divisa)));
						tr.appendChild(crearElem("td",undefined,sepMiles(x.bolivares)));
						document.getElementById('deuda_lista_resumen_dedicado').appendChild(tr);
					}


					if(lee.global.length>0){
						document.getElementById('btn_collapse_global').innerHTML=`Globales (${lee.global.length})`;
					}else {
						document.getElementById('btn_collapse_global').innerHTML=`Globales`;
						document.getElementById('deuda_lista_resumen_global').innerHTML="<tr><td colspan='3'>No hay registros</td></tr>";
					}
					
					if(lee.dedicados.length>0){
						document.getElementById('btn_collapse_dedicado').innerHTML=`Dedicados (${lee.dedicados.length})`;
					}
					else{
						document.getElementById('btn_collapse_dedicado').innerHTML=`Dedicados`;
						document.getElementById('deuda_lista_resumen_dedicado').innerHTML="<tr><td colspan='3'>No hay registros</td></tr>";
					}

				}
				else if (lee.resultado == 'is-invalid'){
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
				else if(lee.resultado == "error"){
					muestraMensaje("ERROR", lee.mensaje,"error");
					console.error(lee.mensaje);
				}
				else if(lee.resultado == "console"){
					console.log(lee.mensaje);
				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
			}).then(()=>{
				$("#modal_resumen_cargos_deudas").modal("show");
			});














		}
	});

	$("#deuda_btn_distribuir_modal").on("click",()=>{
		Swal.fire({
			title: "¿Estás Seguro?",
			text: "Las deudas distribuidas no se pueden modificar ni eliminar si hay registros de pagos por parte de algún apartamento\n¿Desea continuar?",
			showCancelButton: true,
			confirmButtonText: "Distribuir",
			confirmButtonColor: "#007bff",
			cancelButtonText: `Cancelar`,
			focusConfirm:true,
			icon: "info",
		}).then((result) => {
			if (result.isConfirmed) {

				if(validar_deudas()){
					
					var datos = new FormData($("#f_deudas")[0]);
					datos.append("accion","distribuir_deudas");
					enviaAjax(datos,function(respuesta){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "distribuir_deudas"){
							//console.log(lee.mensaje);
							muestraMensaje("La deuda fue distribuida exitosamente", "", "success");
							console.log(lee);
							borrar();
						}
						else if (lee.resultado == 'is-invalid'){
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
						else if(lee.resultado == "error"){
							muestraMensaje("ERROR", lee.mensaje,"error");
							console.error(lee.mensaje);
						}
						else if(lee.resultado == "console"){
							console.log(lee.mensaje);
						}
						else{
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
					}).then(()=>{
						$("#modal_resumen_cargos_deudas").modal("hide");
					});
				}
				else{
					$("#modal_resumen_cargos_deudas").modal("hide");
				}
				
			}
		});

	});

	$("#consultar_deuda").on("click",()=>{
		var datos = new FormData();
		datos.append("accion","consultar_distribucion_deuda");
		enviaAjax(datos,function(respuesta){
		
			var lee = JSON.parse(respuesta);
			console.log(lee);
			if(lee.resultado == "consultar_distribucion_deuda"){


				if ($.fn.DataTable.isDataTable("#table_distribuciones_modal")) {
					$("#table_distribuciones_modal").DataTable().destroy();
				}
				
				$("#tbody_distribuciones_modal").html("");
				
				if (!$.fn.DataTable.isDataTable("#table_distribuciones_modal")) {
					$("#table_distribuciones_modal").DataTable({
						language: {
							lengthMenu: "Mostrar _MENU_ por página",
							zeroRecords: "No se encontraron registros de Distribuciones",
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
						data:lee.mensaje,
						createdRow: function(row,data){
							row.id = "distrib-"+data[3];
						},
						autoWidth: false
						//order: [[1, "asc"]],
						
					});
				}
				$("#modal_distribuciones").modal("show");




				
			}
			else if (lee.resultado == 'is-invalid'){
				muestraMensaje("ERROR", lee.mensaje,"error");
			}
			else if(lee.resultado == "error"){
				muestraMensaje("ERROR", lee.mensaje,"error");
				console.error(lee.mensaje);
			}
			else if(lee.resultado == "console"){
				console.log(lee.mensaje);
			}
			else{
				muestraMensaje("ERROR", lee.mensaje,"error");
			}
		});
	});

	rowsEvent("tbody_distribuciones_modal",(a,b)=>{
		if(/^distrib-/.test(a.id)){
			var id_f = a.id.replace(/distrib-/, "");

			var datos = new FormData();
			datos.append("accion","consultar_distribucion_deuda");
			datos.append("id_seleccionado",id_f);
			enviaAjax(datos,function(respuesta){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "consultar_distribucion_deuda"){
						document.getElementById('deuda_fecha').value = lee.mensaje.fecha;
						document.getElementById('deuda_concepto').value = lee.mensaje.concepto;
						document.getElementById('id_hidden').value = lee.mensaje.id_distribucion;
						cambiarbotones_deuda(false);
						
				}
				else if (lee.resultado == 'is-invalid'){
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
				else if(lee.resultado == "error"){
					muestraMensaje("ERROR", lee.mensaje,"error");
					console.error(lee.mensaje);
				}
				else if(lee.resultado == "console"){
					console.log(lee.mensaje);
				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
			}).then(()=>{
				$("#modal_distribuciones").modal("hide");
			});
			
		}
	})


	$("#modificar_deuda").on("click",()=>{

		Swal.fire({
			title: "¿Estás Seguro?",
			text: "¿Está seguro que desea modificar la distribución?",
			showCancelButton: true,
			confirmButtonText: "Modificar",
			confirmButtonColor: "#ffc107",
			cancelButtonText: `Cancelar`,
			icon: "warning",
		}).then((result) => {
			if (result.isConfirmed) {

				if(validar_deudas()){
					var datos = new FormData($("#f_deudas")[0]);
					datos.append("accion","modificar_deuda");
					enviaAjax(datos,function(respuesta){
					
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "modificar_deuda"){
							muestraMensaje(lee.mensaje, "", "success");
							borrar();
						}
						else if (lee.resultado == 'is-invalid'){
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
						else if(lee.resultado == "error"){
							muestraMensaje("ERROR", lee.mensaje,"error");
							console.error(lee.mensaje);
						}
						else if(lee.resultado == "console"){
							console.log(lee.mensaje);
						}
						else{
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
					});
				}



			}
		});
	});

	$("#eliminar_deuda").on("click",()=>{

		Swal.fire({
			title: "¿Estás Seguro?",
			text: "¿Está seguro que desea eliminar la distribución?",
			showCancelButton: true,
			confirmButtonText: "eliminar",
			confirmButtonColor: "#c82333",
			cancelButtonText: `Cancelar`,
			icon: "warning",
		}).then((result) => {
			if (result.isConfirmed) {
				var datos = new FormData();
				datos.append("accion","eliminar_deuda");
				datos.append("id",document.getElementById('id_hidden').value);
				enviaAjax(datos,function(respuesta){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "eliminar_deuda"){
						muestraMensaje(lee.mensaje, '', "success");
						borrar();
					}
					else if (lee.resultado == 'is-invalid'){
						muestraMensaje("ERROR", lee.mensaje,"error");
					}
					else if(lee.resultado == "error"){
						muestraMensaje("ERROR", lee.mensaje,"error");
						console.error(lee.mensaje);
					}
					else if(lee.resultado == "console"){
						console.log(lee.mensaje);
					}
					else{
						muestraMensaje("ERROR", lee.mensaje,"error");
					}
				});
			}
		});
	});

	function validar_deudas(){
		if(!document.getElementById('deuda_fecha').validarme()){
			muestraMensaje("La fecha es invalida", "", "error");
			return false;
		}
		if(!document.getElementById('deuda_concepto').validarme()){
			muestraMensaje("El concepto de la deuda tiene caracteres inválidos o esta vació", "", "error");
			return false;
		}
		return true;

	}
}