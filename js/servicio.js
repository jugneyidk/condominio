$(document).ready(function(){
	borrar();
	loadListados();
	eventoKeypress(document.getElementById('descripcion'),/^[0-9a-zA-Z\s]*$/);
	eventoKeypress(document.getElementById('monto'),/^[0-9]*$/);
	eventoKeypress(document.getElementById('referencia'),/^[0-9]*$/);
	eventoKeypress(document.getElementById('servicio_2'),/^[0-9a-zA-Z\s-]*$/);//-----------
	eventoKeyup(document.getElementById('servicio_2'),/^[0-9a-zA-Z\s-]{1,50}$/,"Solo se permiten letras y números");//-----------
	document.getElementById('servicio_2').maxLength=50;
	eventoKeyup(document.getElementById('monto'), montoExp, "Ingrese un monto valido", undefined, function(elem){
		elem.value = sepMiles(elem.value);
	});
	$("#monto").on("change",function(e){
		$(this).val( sepMiles($(this).val()) );
		validarKeyUp(montoExp,$(this),"ingrese un monto valido");
	});
	eventoKeyup(document.getElementById('descripcion'), /^[0-9a-zA-Z\s]{0,255}$/, "ingrese una descripción valida solo se permiten letras y números");
	document.getElementById('descripcion').maxLength = 255;
	eventoKeyup(document.getElementById('referencia'), /^[0-9]{0,30}$/, "Ingrese una referencia valida, solo se permiten números");
	document.getElementById('referencia').maxLength = 30;
	eventoKeyup(document.getElementById('fecha'), fechaExp, "Ingrese una fecha valida");
	$("#fecha").on("change",function(e){
		validarKeyUp(fechaExp, $("#fecha"), "Ingrese una fecha valida");
	});

	$("#limpiar").on("click",function(){
		borrar();
	});

	$("#incluir").on("click", function () {
		if (validarPagoServicios()) {
			$("#accion").val("incluir");
			$("#descripcion").val( removeSpace($("#descripcion").val()) );

			var datos = new FormData($("#f")[0]);
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
					var datos = new FormData($("#f")[0]);
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
		if (validarKeyUp(/^[0-9a-zA-Z\s-]{1,50}$/,$("#servicio_2"),"Solo se permiten letras y números")) {
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
		if (validarKeyUp(/^[0-9a-zA-Z\s-]{1,50}$/,$("#servicio_2"),"Solo se permiten letras y números")) {
			if($("#id_2").val()==''){
				muestraMensaje("ERROR","Debe seleccionar un servicio","error");
				return false;
			}
			Swal.fire({
				title: "¿Estás Seguro?",
				text: "¿Está seguro que desea modificar el Servicio?",
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







	rowsEvent("listadoservicios",function(e){//colocar en los campos
		$("#id").val($(e).find("td").eq(0).text());
		$("#service").val($(e).find("td").eq(1).text());
		$("#descripcion").val($(e).find("td").eq(3).text());

		$("#monto").val($(e).find("td").eq(4).text());
		$("#fecha").val($(e).find("td").eq(5).text());
		$("#referencia").val($(e).find("td").eq(6).text());
		$("#modalservicios").modal("hide");
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
	lista_pagos_servicios();
	lista_servicios();
}
function lista_pagos_servicios(){
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
					autoWidth: false,
					order: [[4, "desc"]],
					columns: [
					{ data: "col1" },
					{ data: "col2" },
					{ data: "col3" },
					{ data: "col4" },
					{ data: "col5" },
					{ data: "col6" },
					{ data: "col7" }
					]
				});
			}
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


if(!validarKeyUp(montoExp,$("#monto"),"Ingrese un monto Valido")){// valido numero de estacionamiento
	muestraMensaje("ERROR", "Ingrese un monto Valido","error");
	return false;
}
if(!validarKeyUp(/^[0-9a-zA-Z\s]{0,255}$/,$("#descripcion"),"ingrese una descripción valida solo se permiten letras y números")){
	muestraMensaje("ERROR", "ingrese una descripción valida solo se permiten letras y números","error");
	return false;   
}
if(!validarKeyUp(/^[0-9]{1,30}$/,$("#referencia"),"Ingrese una referencia valida, solo se permiten números")){
	muestraMensaje("ERROR", "Ingrese una referencia valida, solo se permiten números", "error");
	return false;

}if(!validarKeyUp(fechaExp,$("#fecha"),"Ingrese una fecha valida")){
	muestraMensaje("ERROR", "Ingrese una fecha valida", "error");
	return false;
}
if($("#service").val()==""){
	muestraMensaje("ERROR", "Seleccione un servicio", "error");
	return false;
}


vaciarSpanError();
return true;


}


function borrar(func) {
	$("form input").val("");
	$("form select").val("");
	limpiarvalidacion();
	cambiarbotones();
	cambiarbotones_2();
}

function limpiarvalidacion() {
	$("form input").removeClass("is-valid");
	$("form input").removeClass("is-invalid");
	$("form select").removeClass("is-valid");
	$("form select").removeClass("is-invalid");
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