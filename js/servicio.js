$(document).ready(function(){
	borrar();


	//********************************************
	//********************************************
	//********************************************
	//********************************************
	//********************************************

	loadListados();

	//********************************************
	//********************************************
	//********************************************
	//********************************************
	//********************************************
	//********************************************

	load_tipo_pago_comun();//eventos para el tipo de pago



	eventoKeypress(document.getElementById('descripcion'), /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]*$/);
	eventoKeypress(document.getElementById('servicio_2'), /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]*$/);//-----------
	eventoKeyup(document.getElementById('servicio_2'),   /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{1,50}$/,"El nombre de servicio tiene caracteres no permitidos");//-----------
	eventoKeyup(document.getElementById('descripcion'), /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{0,100}$/, "La descripción tiene caracteres no permitidos");
	document.getElementById('servicio_2').maxLength=50;
	document.getElementById('descripcion').maxLength = 100;


	$("#limpiar").on("click",function(){
		borrar();
	});

	$("#incluir").on("click", function () {
		if (validarPagoServicios()) {
			$("#accion").val("incluir");
			$("#descripcion").val( removeSpace($("#descripcion").val()) );
			var obj_pagos = objeto_tipo_pago_comun();
			var datos = new FormData($("#f")[0]);

			datos.append("tipo_pago", JSON.stringify(obj_pagos));

			enviaAjax(datos,function(respuesta){

				var lee = JSON.parse(respuesta);
				if(lee.resultado == "incluir"){
					borrar();
					loadListados();
					muestraMensaje(lee.mensaje, "", "success");

				}
				else if(lee.resultado == "error_no_borrar"){
					muestraMensaje("ERROR", lee.mensaje,"error");
					loadListados();
				}
				else if(lee.resultado == 'console'){
					console.log(lee.mensaje);
				}
				else if(lee.resultado == "mensajeError"){
					console.error(lee.mensajeError);
				}
				else if(lee.resultado == 'is-invalid'){
					muestraMensaje("ERROR", lee.mensaje,"error");
				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
					console.log(lee);
					loadListados();
					borrar();
				}

			});
		}
	});

	$("#modificar").on("click", function () {
		if (validarPagoServicios()) {
			if($("#id").val()==''){
				muestraMensaje("ERROR","Debe seleccionar un pago de servicio","error");
				return false;
			}
			Swal.fire({
				title: "¿Estás Seguro?",
				text: "¿Está seguro que desea modificar el pago de Servicios?",
				showCancelButton: true,
				confirmButtonText: "Modificar",
				confirmButtonColor: "#007bff",
				cancelButtonText: `Cancelar`,
				icon: "warning",
			}).then((result) => {
				if (result.isConfirmed) {

					$("#accion").val("modificar");
					$("#descripcion").val( removeSpace($("#descripcion").val()) );

					var obj_pagos = objeto_tipo_pago_comun();
					var datos = new FormData($("#f")[0]);

					datos.append("tipo_pago", JSON.stringify(obj_pagos));


					enviaAjax(datos,function(respuesta){
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "modificar"){
							borrar();
							loadListados();
							muestraMensaje(lee.mensaje, "", "success");

						}
						else if(lee.resultado == "error_no_borrar"){
							muestraMensaje("ERROR", lee.mensaje,"error");
						}
						else{
							muestraMensaje("ERROR", lee.mensaje,"error");
							borrar();
						}
					}).then(function(e) {
						try{
							e = JSON.parse(e);
							if(e.resultado == "console"){
								console.log(e.mensaje);
							}
						}
						catch(e){
							console.log(e.getMessage());
						}
					});
				}
			});
		}
	});
	$("#eliminar").on("click", function () {
		Swal.fire({
			title: "¿Estás Seguro?",
			text: "¿Está seguro que desea eliminar el pago de Servicios?",
			showCancelButton: true,
			confirmButtonText: "Eliminar",
			confirmButtonColor: "#dc3545",
			cancelButtonText: `Cancelar`,
			icon: "warning",
		}).then((result) => {
			if (result.isConfirmed) {
				$("#accion").val("eliminar");
				var datos = new FormData($("#f")[0]);
				enviaAjax(datos,function(respuesta){
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "eliminar"){
						borrar();
						loadListados();
						muestraMensaje(lee.mensaje, "", "success");

					}
					else{
						muestraMensaje("ERROR", lee.mensaje,"error");
						loadListados();
						borrar();
					}
				});
			}
		});
	});


	$("#incluir_2").on("click", function () {
		if (validarKeyUp(/^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{1,50}$/,$("#servicio_2"),"El nombre de servicio tiene caracteres no permitidos")) {
			$("#accion_2").val("incluir_s");
			$("#servicio_2").val( removeSpace($("#servicio_2").val()) );

			var datos = new FormData($("#f2")[0]);
			enviaAjax(datos,function(respuesta){

				var lee = JSON.parse(respuesta);
				if(lee.resultado == "incluir"){
					borrar();
					loadListados();
					muestraMensaje(lee.mensaje, "", "success");

				}
				else if(lee.resultado == "error_no_borrar"){
					muestraMensaje("ERROR", lee.mensaje,"error");
					loadListados();
				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
					loadListados();
					borrar();
				}

			});
		}
	});

	$("#modificar_2").on("click", function () {
		if (validarKeyUp(/^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{1,50}$/,$("#servicio_2"),"El nombre de servicio tiene caracteres no permitidos")) {
			if($("#id_2").val()==''){
				muestraMensaje("ERROR","Debe seleccionar un servicio","error");
				return false;
			}
			Swal.fire({
				title: "¿Estás Seguro?",
				text: "¿Está seguro que desea modificar el nombre de Servicio? \nEsto modificara todos los pagos relacionados previamente",
				showCancelButton: true,
				confirmButtonText: "Modificar",
				confirmButtonColor: "#007bff",
				cancelButtonText: `Cancelar`,
				icon: "warning",
			}).then((result) => {
				if (result.isConfirmed) {
					$("#accion_2").val("modificar_s");
					var datos = new FormData($("#f2")[0]);

					enviaAjax(datos,function(respuesta){
						var lee = JSON.parse(respuesta);
						if(lee.resultado == "modificar"){
							borrar();
							loadListados();
							muestraMensaje(lee.mensaje, "", "success");

						}
						else if(lee.resultado == "error_no_borrar"){
							muestraMensaje("ERROR", lee.mensaje,"error");
							loadListados();
						}
						else{
							muestraMensaje("ERROR", lee.mensaje,"error");
							loadListados();
							borrar();
						}
					});
				}
			});
		}
	});


	$("#eliminar_2").on("click", function () {
		Swal.fire({
			title: "¿Estás Seguro?",
			text: "¿Está seguro que desea eliminar el Servicio?",
			showCancelButton: true,
			confirmButtonText: "Eliminar",
			confirmButtonColor: "#dc3545",
			cancelButtonText: `Cancelar`,
			icon: "warning",
		}).then((result) => {
			if (result.isConfirmed) {
				$("#accion_2").val("eliminar_s");
				var datos = new FormData($("#f2")[0]);
				enviaAjax(datos,function(respuesta){
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "eliminar"){
						borrar();
						loadListados();
						muestraMensaje(lee.mensaje, "", "success");

					}
					else{
						muestraMensaje("ERROR", lee.mensaje,"error");
						loadListados();
						borrar();
					}
				});
			}
		});
	});

	$("#consultar").on("click",function() {
		lista_pagos_servicios(true);
	});







	rowsEvent("listadoservicios",function(e){//colocar en los campos
		var datos = new FormData();
		datos.append('accion',"seleccionar_pago");
		datos.append('id', $(e).data('id'));

		enviaAjax(datos,function(respuesta){
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "seleccionar_pago"){
				$("#id").val($(e).find("td").eq(0).text());
				$("#service").val($(e).data("service"));
				document.getElementById('service').disabled=true;
				$("#descripcion").val($(e).find("td").eq(3).text());



				cargar_tipo_pago_comun(lee.obj_pagos);
				limpiarvalidacion(true);
				
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
		}).then(function(e) {
			$("#modalservicios").modal("hide");
		});





		
		// $("#monto").val($(e).find("td").eq(4).text());
		// $("#fecha").val($(e).find("td").eq(5).text());
		// $("#referencia").val($(e).find("td").eq(6).text());
		cambiarbotones(false);
	});
	rowsEvent("listadoservicios_New",function(e){
		$("#id_2").val($(e).find("td").eq(1).text());
		$("#servicio_2").val($(e).find("td").eq(2).text());
		$("#modalservicios_New").modal("hide");
		cambiarbotones_2(false);
	});



});

function loadListados(){
	lista_select_servicios();
	//lista_pagos_servicios();
	lista_servicios();
}
function lista_pagos_servicios(show = false){
	var datos = new FormData();
	datos.append("accion", "listadoPagosservicios");

	enviaAjax(datos,function(respuesta){
		var lee = JSON.parse(respuesta);
		if (lee.resultado == "listadoPagosservicios") {
			if ($.fn.DataTable.isDataTable("#tablaservicios")) {
				$("#tablaservicios").DataTable().destroy();
			}
			$("#listadoservicios").html(lee.mensaje);
			if (!$.fn.DataTable.isDataTable("#tablaservicios")) {
				$("#tablaservicios").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de pagos",
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
					createdRow: function(row,data){
						row.querySelector("td:nth-child(6)").innerText = row.querySelector("td:nth-child(6)").innerText.replace(/\s*[0-9][0-9]:[0-9][0-9]:[0-9][0-9]$/, "");
					},
					autoWidth: false,
					order: [[4, "desc"]],
					columns: [
					{ data: "col1" },
					{ data: "col2" },
					{ data: "col3" },
					{ data: "col4" },
					{ data: "col5" },
					{ data: "col6" },
					{ data: "col7" },
					{ data: "col8" }
					]
				});
			}
			if(show == true){
				$("#modalservicios").modal("show");
			}
		}
		else if(lee.resultado == "console"){
			console.log(lee.mensaje);
		}
		else{
			muestraMensaje("ERROR", lee.mensaje,"error");
		}

	});
}

function lista_select_servicios(){
	var datos = new FormData();
	datos.append('accion',"listaSelectServicios");

	enviaAjax(datos,function(respuesta){

		var lee = JSON.parse(respuesta);
		if(lee.resultado == "lista_select_servicios"){
			$("#service").html(lee.mensaje);
		}
		else{
			muestraMensaje("ERROR", lee.mensaje,"error");
		}
	});
}
function lista_servicios(){
	var datos = new FormData();
	datos.append("accion", "listadoServicios");

	enviaAjax(datos,function(respuesta){
		var lee = JSON.parse(respuesta);
		if (lee.resultado == "listadoServicios") {
			if ($.fn.DataTable.isDataTable("#tablaservicios_New")) {
				$("#tablaservicios_New").DataTable().destroy();
			}
			$("#listadoservicios_New").html(lee.mensaje);
			if (!$.fn.DataTable.isDataTable("#tablaservicios_New")) {
				$("#tablaservicios_New").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de Servicios",
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
					autoWidth: false,
					order: [[0, "asc"]],
					columns: [
					{ data: "col1" },
					{ data: "col2" },
					{ data: "col3" }
					]
				});
			}
		}
		else{
			muestraMensaje("ERROR", lee.mensaje,"error");
		}

	});
}








function validarPagoServicios(){

	if(!validarKeyUp(/^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{0,100}$/,$("#descripcion"),"ingrese una descripción valida solo se permiten letras y números")){
		muestraMensaje("ERROR", "ingrese una descripción valida solo se permiten letras y números","error");
		return false;   
	}
	
	if($("#service").val()==""){
		muestraMensaje("ERROR", "Seleccione un servicio", "error");
		return false;
	}

	return validar_tipo_pago_comunes();
}


function borrar() {
	$('input:not(:checkbox):not(:radio):not(.no-delete)').val("");
	// $("form input").val("");
	$("form select:not(#tipo_pago_comun)").val("");
	limpiarvalidacion();
	cambiarbotones();
	cambiarbotones_2();
	if (document.getElementById('tipo_pago_comun-divisa_cantidad')) {
		document.getElementById('tipo_pago_comun-divisa_cantidad').dispatchEvent(new Event('input'));
	}
	document.getElementById('service').disabled=false;
	return obj = {then:function(func){
		func();
	}}
}

function limpiarvalidacion(span = false) {
	$("form input").removeClass("is-valid");
	$("form input").removeClass("is-invalid");
	$("form select").removeClass("is-valid");
	$("form select").removeClass("is-invalid");
	if(span === true){
		$("form input, form select").each(function(e) {
			if($(this).data("span")){
				$("#"+$(this).data("span")).html("");
			}
		});
	}
}
function cambiarbotones(parametro=true) {
	$("#modificar").prop("disabled", parametro);
	$("#eliminar").prop("disabled", parametro);
	$("#incluir").prop("disabled", !parametro);
}
function cambiarbotones_2(parametro=true) {
	$("#modificar_2").prop("disabled", parametro);
	$("#eliminar_2").prop("disabled", parametro);
	$("#incluir_2").prop("disabled", !parametro);
}