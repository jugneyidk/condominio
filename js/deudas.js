$(document).ready(function () {
	carga_deudas();
	load_tipo_pago_comun();
});

function carga_deudas(){
	var datos = new FormData();
	datos.append("accion","listadodeudas");
	enviaAjax(datos,function(respuesta){
	
		var lee = JSON.parse(respuesta);
		if(lee.resultado == "listadodeudas"){

			  if ($.fn.DataTable.isDataTable("#tabladeudas")) {
				$("#tabladeudas").DataTable().destroy();
			  }
			  $("#listadodeudas").html("");
			  if (!$.fn.DataTable.isDataTable("#tabladeudas")) {
				$("#tabladeudas").DataTable({
				  // columnDefs: [
				  //   { targets: [5], orderable: false, searchable: false },
				  // ],
				  data:lee.mensaje.deuda_normal,
				  language: {
					lengthMenu: "Mostrar _MENU_ por página",
					zeroRecords: "No hay deudas pendientes",
					info: "Mostrando página _PAGE_ de _PAGES_",
					infoEmpty: "No hay deudas pendientes",
					infoFiltered: "(filtrado de _MAX_ registros totales)",
					search: "Buscar:",
					paginate: {
					  first: "Primera",
					  last: "Última",
					  next: "Siguiente",
					  previous: "Anterior",
					},
				  },
				  "ordering": false,
				  columns: [
					  { data: "num_letra_apartamento" },
					  { data: "torre" },
					  { data: "concepto" },
					  { data: "fecha" },
					  { data: "monto" },
					  { data: "extra" }
						  ],
				  createdRow: function(row,data){
					console.table(data);
					
					
					row.dataset.id=data.id_deuda;
					if(data.id_pago){
					  row.dataset.id_pago = data.id_pago;
					}
					row.querySelector("td:nth-child(2)").classList.add("d-none","d-md-table-cell");
					row.querySelector("td:nth-child(3)").classList.add("d-none","d-md-table-cell");
					row.querySelector("td:nth-child(4)").classList.add("text-nowrap");
					row.querySelector("td:nth-child(5)").classList.add("money_icon");
					row.querySelector("td:nth-child(5)").innerText = sepMiles(data.monto);
					row.querySelector("td:last-child").classList.add("text-center");
					if(data.id_pago == null || data.estado !=0){
					  var button = crearElem("button", "class,btn btn-success, style, font-size:13px;","Pagar");
					  button.onclick = function (){
					  	mostrar_registrar_pago(this);
					  }
					  //row.querySelector("td:last-child").innerHTML="<button class='btn btn-success' style='font-size: 13px;' onclick='mostrar_registrar_pago(this)'>Pagar</button>";
					  row.querySelector("td:last-child").innerHTML="";
					  row.querySelector("td:last-child").appendChild(button);
					}
					else{
					  //row.querySelector("td:last-child").innerHTML="<u class=\"no-select\" style=\"font-size: .6rem;\">por confirmar</u><br><button class='btn btn-danger' style='font-size: 13px;' onclick='eliminar_pagos(this)'>eliminar</button>";
					  row.querySelector("td:last-child").innerHTML="<u class=\"no-select\" style=\"font-size: .6rem;\">por confirmar</u><br>";
					  var button = crearElem("button", "class,btn btn-danger, style, font-size:13px;","Eliminar");
					  button.onclick = function (){
					  	eliminar_pagos(this);
					  }
					  row.querySelector("td:last-child").appendChild(button);


					}
					
				  },

				  autoWidth: false,
				  //order: [[1, "desc"]],
				  // paging: false,
				  searching: false,
				  info: false,
				});
			  }


			    if ($.fn.DataTable.isDataTable("#tablamorosos")) {
			  	$("#tablamorosos").DataTable().destroy();
			    }
			    $("#listadomorosos").html("");
			    if (!$.fn.DataTable.isDataTable("#tablamorosos")) {
			  	$("#tablamorosos").DataTable({
			  	  // columnDefs: [
			  	  //   { targets: [5], orderable: false, searchable: false },
			  	  // ],
			  	  data:lee.mensaje.deuda_moroso,
			  	  language: {
			  		lengthMenu: "Mostrar _MENU_ por página",
			  		zeroRecords: "No hay deudas pendientes",
			  		info: "Mostrando página _PAGE_ de _PAGES_",
			  		infoEmpty: "No hay deudas pendientes",
			  		infoFiltered: "(filtrado de _MAX_ registros totales)",
			  		search: "Buscar:",
			  		paginate: {
			  		  first: "Primera",
			  		  last: "Última",
			  		  next: "Siguiente",
			  		  previous: "Anterior",
			  		},
			  	  },
			  	  "ordering": false,
			  	  columns: [
			  		  { data: "num_letra_apartamento" },
			  		  { data: "torre" },
			  		  { data: "concepto" },
			  		  { data: "fecha" },
			  		  { data: "monto" },
			  		  { data: "extra" }
			  			  ],
			  	  createdRow: function(row,data){
			  		//console.table(data);
			  		
			  		
			  		row.dataset.id=data.id_deuda;
			  		if(data.id_pago){
			  		  row.dataset.id_pago = data.id_pago;
			  		}
			  		row.querySelector("td:nth-child(2)").classList.add("d-none","d-md-table-cell");
			  		row.querySelector("td:nth-child(3)").classList.add("d-none","d-md-table-cell");
			  		row.querySelector("td:nth-child(4)").classList.add("text-nowrap");
			  		row.querySelector("td:nth-child(5)").classList.add("money_icon");
					row.querySelector("td:nth-child(5)").innerText = sepMiles(data.monto);
			  		row.querySelector("td:last-child").classList.add("text-center");
			  		if(data.id_pago == null || data.estado !=0){
			  		  row.querySelector("td:last-child").innerHTML="<button class='btn btn-success' style='font-size: 13px;' onclick='mostrar_registrar_pago(this)'>Pagar</button>";
			  		}
			  		else{
			  		  row.querySelector("td:last-child").innerHTML="<u class=\"no-select\" style=\"font-size: .6rem;\">por confirmar</u><br><button class='btn btn-danger' style='font-size: 13px;' onclick='eliminar_pagos(this)'>eliminar</button>";

			  		}
			  		
			  	  },

			  	  autoWidth: false,
			  	  //order: [[1, "desc"]],
			  	  // paging: false,
			  	  searching: false,
			  	  info: false,
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
	});
}



function mostrar_registrar_pago(linea) {

	

	var id_deuda_tosend = linea.parentNode.parentNode.dataset.id;
	$("#id_deuda_tosend").val(id_deuda_tosend);
	var datos = new FormData();
	datos.append("accion","detalles_deuda");
	datos.append("id_deuda",id_deuda_tosend);
	enviaAjax(datos,function(respuesta){
	
		var lee = JSON.parse(respuesta);
		if(lee.resultado == "detalles_deuda"){
			tipo_pago_comun_update_divisa();
			tipo_pago_comun_resumen_load(lee.mensaje);
			document.getElementById('monto_para_calcular').dataset.monto_a_pagar=lee.total;
			
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
		$("#registrarpago").modal("show");
	});
	
}





function registrar_pago() {
	if(validar_tipo_pago_comunes()){
		var monto_para_calcular_total = parseFloat(document.getElementById('monto_para_calcular').dataset.monto_a_pagar);
		var monto_a_registrar=Number(parseFloat(sepMiles(document.getElementById('tipo_pago_comun-monto_total').value,true)).toFixed(2));
		if(monto_a_registrar>=monto_para_calcular_total){


		Swal.fire({
			title: "¿Estás Seguro?",
			text: "¿Está seguro que desea registrar el pago?",
			showCancelButton: true,
			confirmButtonText: "Registrar",
			confirmButtonColor: "#007bff", // amarillo #ffc107 rojo #dc3545 azul #007bff
			cancelButtonText: `Cancelar`,
			icon: "info",
		}).then((result) => {
			if (result.isConfirmed) {
			
				var datos = new FormData($("#f")[0]);
				datos.append("accion","registrarpago");
				datos.append("obj_pagos", JSON.stringify(objeto_tipo_pago_comun()));
				enviaAjax(datos,function(respuesta){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "registrarpago"){
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
					$("#registrarpago").modal("hide");
					carga_deudas();
				});

			}
		});

	  }
	  else{
		muestraMensaje("El monto no es el correcto \n ( "+sepMiles(monto_para_calcular_total.toString())+"Bs )", '', "error");
	  }
	}
}


function eliminar_pagos(elem){
	var deuda_id = elem.parentNode.parentNode.dataset.id;
	var id_pago = elem.parentNode.parentNode.dataset.id_pago;
	if(deuda_id){
	Swal.fire({
		title: "¿Estás Seguro?",
		text: "¿Está seguro que desea Eliminar el pago?",
		showCancelButton: true,
		confirmButtonText: "Eliminar",
		confirmButtonColor: "#dc3545", // amarillo #ffc107 rojo #dc3545 azul #007bff
		cancelButtonText: `Cancelar`,
		icon: "warning",
	}).then((result) => {
		if (result.isConfirmed) {
			var datos = new FormData();
			datos.append("accion","eliminar_pagos");
			datos.append("eliminar_id", deuda_id);
			datos.append("id_pago", id_pago);

			enviaAjax(datos,function(respuesta){
		
			var lee = JSON.parse(respuesta);
			if(lee.resultado == "eliminar_pagos"){
				muestraMensaje("", lee.mensaje, "success");
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
			carga_deudas();
		});
	
	  }
	});
  }
}

function limpiarvalidacion() {
	$("form input").removeClass("is-valid");
	$("form select").removeClass("is-valid");
	$("form input").removeClass("is-invalid");
	$("form select").removeClass("is-invalid");
}
function borrar() {
	$('input:not(:checkbox):not(:radio)').val("");
	// $("form input").val("");
	$("form select").val("");
	limpiarvalidacion();
	limpiar_tipo_pago_comun();
}