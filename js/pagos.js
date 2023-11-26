$(document).ready(function () {
  carga_pagos();
  carga_pagos_pendientes();
  carga_pagos_confirmados();
  carga_pagos_declinados();
  $(function () {
	var hash = document.location.hash;
	if (hash) {
	  $(".nav-tabs a[href=\\" + hash + "]").tab("show");
	}
	$('a[data-toggle="tab"]').on("show.bs.tab", function (e) {
	  window.location.hash = e.target.hash;
	});
  });
});

function carga_pagos() {
  var datos = new FormData();
  datos.append("accion","listadopagos");
  enviaAjax(datos,function(respuesta){
  
	var lee = JSON.parse(respuesta);
	if(lee.resultado == "listadopagos"){
	  if ($.fn.DataTable.isDataTable("#tablapagos")) {
		$("#tablapagos").DataTable().destroy();
	  }
	  $("#listadopagos").html("");
	  if (!$.fn.DataTable.isDataTable("#tablapagos")) {
		$("#tablapagos").DataTable({
            data:lee.mensaje,
			columns:[
				{ data: "id_pago" },
				{ data: "num_letra_apartamento" },
				{ data: "torre" },
				{ data: "fecha" },
				{ data: "total_pago",render:function(data,type,row,meta){
					return sepMiles(data)+" Bs";
				} },
				{ data: "estado" },
				{ data: "extra" }

			],
			createdRow: function(row,data){
				console.log(row);
                row.querySelector("td:nth-child(1)").classList.add("align-middle");
                row.querySelector("td:nth-child(2)").classList.add("apartamento","align-middle");
                row.querySelector("td:nth-child(3)").classList.add("align-middle","d-none","d-md-table-cell");
                row.querySelector("td:nth-child(4)").classList.add("align-middle","text-nowrap");
                row.querySelector("td:nth-child(5)").classList.add("align-middle","text-nowrap");
                row.querySelector("td:nth-child(6)").classList.add("align-middle", "text-capitalize");
                row.querySelector("td:nth-child(7)").classList.add("align-middle");
                var estado = "Pendiente";
                if(data.estado == 1 ){ estado = "Declinado";}
                else if(data.estado == 2 ){ estado = "Confirmado";}
                row.querySelector("td:nth-child(6)").innerHTML = estado;

                var div = crearElem("div", "class, btn-group,role, group");

                if(estado == "Pendiente"){
		            div.appendChild(crearElem("button", "class/btn btn-primary/style/font-size:12px;/onclick/confirmar_declinar_pago(this,1)","Confirmar","/"));
		            div.appendChild(crearElem("button", "class/btn btn-secondary/style/font-size:12px;/onclick/carga_detalles_pago(this,1)","Ver","/"));
		            div.appendChild(crearElem("button", "class/btn btn-danger/style/font-size:12px;/onclick/confirmar_declinar_pago(this,2)","Declinar","/"));
                }
                else if (estado == "Declinado" || estado == "Confirmado"){
		            div.appendChild(crearElem("button", "class/btn btn-secondary w-100 btn-ver/style/font-size:12px;/onclick/carga_detalles_pago(this,1)","Ver","/"));
                }
                div = crearElem("div","class,row justify-content-around mx-0",div);

                row.querySelector("td:nth-child(7)").appendChild(div);

			},
			columnDefs: [
				{ targets: [5, 6], orderable: false, searchable: false },
			],
			language: {
			lengthMenu: "Mostrar _MENU_ por página",
			zeroRecords: "No se encontraron pagos",
			info: "Mostrando página _PAGE_ de _PAGES_",
			infoEmpty: "No hay pagos disponibles",
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
			order: [[0, "desc"]],
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


  //enviaAjax(datos);
}
function carga_pagos_pendientes() {
  var datos = new FormData();
  datos.append("accion","listadopagospendientes");
  enviaAjax(datos,function(respuesta){
  
	var lee = JSON.parse(respuesta);
	if(lee.resultado == "listadopagospendientes"){

	  if ($.fn.DataTable.isDataTable("#tablapendiente")) {
		$("#tablapendiente").DataTable().destroy();
	  }
	  $("#listadopagospendientes").html("");
	  if (!$.fn.DataTable.isDataTable("#tablapendiente")) {
		$("#tablapendiente").DataTable({
            data:lee.mensaje,
			columns:[
				{ data: "id_pago" },
				{ data: "num_letra_apartamento" },
				{ data: "torre" },
				{ data: "fecha" },
				{ data: "total_pago",render:function(data,type,row,meta){
					return sepMiles(data)+" Bs";
				} },
				{ data: "estado" },
				{ data: "extra" }

			],
			createdRow: function(row,data){
				console.log(row);
                row.querySelector("td:nth-child(1)").classList.add("align-middle");
                row.querySelector("td:nth-child(2)").classList.add("apartamento","align-middle");
                row.querySelector("td:nth-child(3)").classList.add("align-middle","d-none","d-md-table-cell");
                row.querySelector("td:nth-child(4)").classList.add("align-middle","text-nowrap");
                row.querySelector("td:nth-child(5)").classList.add("align-middle","text-nowrap");
                row.querySelector("td:nth-child(6)").classList.add("align-middle", "text-capitalize");
                row.querySelector("td:nth-child(7)").classList.add("align-middle");
                var estado = "Pendiente";
                if(data.estado == 1 ){ estado = "Declinado";}
                else if(data.estado == 2 ){ estado = "Confirmado";}
                row.querySelector("td:nth-child(6)").innerHTML = estado;

                var div = crearElem("div", "class, btn-group,role, group");

                if(estado == "Pendiente"){
		            div.appendChild(crearElem("button", "class/btn btn-primary/style/font-size:12px;/onclick/confirmar_declinar_pago(this,1)","Confirmar","/"));
		            div.appendChild(crearElem("button", "class/btn btn-secondary/style/font-size:12px;/onclick/carga_detalles_pago(this,1)","Ver","/"));
		            div.appendChild(crearElem("button", "class/btn btn-danger/style/font-size:12px;/onclick/confirmar_declinar_pago(this,2)","Declinar","/"));
                }
                else if (estado == "Declinado" || estado == "Confirmado"){
		            div.appendChild(crearElem("button", "class/btn btn-secondary w-100 btn-ver/style/font-size:12px;/onclick/carga_detalles_pago(this,1)","Ver","/"));
                }
                div = crearElem("div","class,row justify-content-around mx-0",div);

                row.querySelector("td:nth-child(7)").appendChild(div);

			},
		  columnDefs: [
			{ targets: [5, 6], orderable: false, searchable: false },
		  ],
		  language: {
			lengthMenu: "Mostrar _MENU_ por página",
			zeroRecords: "No se encontraron pagos pendientes",
			info: "Mostrando página _PAGE_ de _PAGES_",
			infoEmpty: "No hay pagos pendientes disponibles",
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
		  order: [[0, "desc"]],
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
function carga_pagos_confirmados() {
  var datos = new FormData();
  datos.append("accion","listadopagosconfirmados");
  enviaAjax(datos,function(respuesta){
  
	var lee = JSON.parse(respuesta);
	if(lee.resultado == "listadopagosconfirmados"){

	  if ($.fn.DataTable.isDataTable("#tablaconfirmado")) {
		$("#tablaconfirmado").DataTable().destroy();
	  }
	  $("#listadopagosconfirmados").html("");
	  if (!$.fn.DataTable.isDataTable("#tablaconfirmado")) {
		$("#tablaconfirmado").DataTable({
            data:lee.mensaje,
			columns:[
				{ data: "id_pago" },
				{ data: "num_letra_apartamento" },
				{ data: "torre" },
				{ data: "fecha" },
				{ data: "total_pago",render:function(data,type,row,meta){
					return sepMiles(data)+" Bs";
				} },
				{ data: "estado" },
				{ data: "extra" }

			],
			createdRow: function(row,data){
				console.log(row);
                row.querySelector("td:nth-child(1)").classList.add("align-middle");
                row.querySelector("td:nth-child(2)").classList.add("apartamento","align-middle");
                row.querySelector("td:nth-child(3)").classList.add("align-middle","d-none","d-md-table-cell");
                row.querySelector("td:nth-child(4)").classList.add("align-middle","text-nowrap");
                row.querySelector("td:nth-child(5)").classList.add("align-middle","text-nowrap");
                row.querySelector("td:nth-child(6)").classList.add("align-middle", "text-capitalize");
                row.querySelector("td:nth-child(7)").classList.add("align-middle");
                var estado = "Pendiente";
                if(data.estado == 1 ){ estado = "Declinado";}
                else if(data.estado == 2 ){ estado = "Confirmado";}
                row.querySelector("td:nth-child(6)").innerHTML = estado;

                var div = crearElem("div", "class, btn-group,role, group");

                if(estado == "Pendiente"){
		            div.appendChild(crearElem("button", "class/btn btn-primary/style/font-size:12px;/onclick/confirmar_declinar_pago(this,1)","Confirmar","/"));
		            div.appendChild(crearElem("button", "class/btn btn-secondary/style/font-size:12px;/onclick/carga_detalles_pago(this,1)","Ver","/"));
		            div.appendChild(crearElem("button", "class/btn btn-danger/style/font-size:12px;/onclick/confirmar_declinar_pago(this,2)","Declinar","/"));
                }
                else if (estado == "Declinado" || estado == "Confirmado"){
		            div.appendChild(crearElem("button", "class/btn btn-secondary w-100 btn-ver/style/font-size:12px;/onclick/carga_detalles_pago(this,1)","Ver","/"));
                }
                div = crearElem("div","class,row justify-content-around mx-0",div);

                row.querySelector("td:nth-child(7)").appendChild(div);

			},
		  columnDefs: [
			{ targets: [5, 6], orderable: false, searchable: false },
		  ],
		  language: {
			lengthMenu: "Mostrar _MENU_ por página",
			zeroRecords: "No se encontraron pagos confirmados",
			info: "Mostrando página _PAGE_ de _PAGES_",
			infoEmpty: "No hay pagos confirmados",
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
		  order: [[0, "desc"]],
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
function carga_pagos_declinados() {

  var datos = new FormData();
  datos.append("accion","listadopagosdeclinados");
  enviaAjax(datos,function(respuesta){
  
	var lee = JSON.parse(respuesta);
	if(lee.resultado == "listadopagosdeclinados"){

	  if ($.fn.DataTable.isDataTable("#tabladeclinado")) {
		$("#tabladeclinado").DataTable().destroy();
	  }
	  $("#listadopagosdeclinados").html("");
	  if (!$.fn.DataTable.isDataTable("#tabladeclinado")) {
		$("#tabladeclinado").DataTable({
            data:lee.mensaje,
			columns:[
				{ data: "id_pago" },
				{ data: "num_letra_apartamento" },
				{ data: "torre" },
				{ data: "fecha" },
				{ data: "total_pago",render:function(data,type,row,meta){
					return sepMiles(data)+" Bs";
				} },
				{ data: "estado" },
				{ data: "extra" }

			],
			createdRow: function(row,data){
				console.log(row);
                row.querySelector("td:nth-child(1)").classList.add("align-middle");
                row.querySelector("td:nth-child(2)").classList.add("apartamento","align-middle");
                row.querySelector("td:nth-child(3)").classList.add("align-middle","d-none","d-md-table-cell");
                row.querySelector("td:nth-child(4)").classList.add("align-middle","text-nowrap");
                row.querySelector("td:nth-child(5)").classList.add("align-middle","text-nowrap");
                row.querySelector("td:nth-child(6)").classList.add("align-middle", "text-capitalize");
                row.querySelector("td:nth-child(7)").classList.add("align-middle");
                var estado = "Pendiente";
                if(data.estado == 1 ){ estado = "Declinado";}
                else if(data.estado == 2 ){ estado = "Confirmado";}
                row.querySelector("td:nth-child(6)").innerHTML = estado;

                var div = crearElem("div", "class, btn-group,role, group");

                if(estado == "Pendiente"){
		            div.appendChild(crearElem("button", "class/btn btn-primary/style/font-size:12px;/onclick/confirmar_declinar_pago(this,1)","Confirmar","/"));
		            div.appendChild(crearElem("button", "class/btn btn-secondary/style/font-size:12px;/onclick/carga_detalles_pago(this,1)","Ver","/"));
		            div.appendChild(crearElem("button", "class/btn btn-danger/style/font-size:12px;/onclick/confirmar_declinar_pago(this,2)","Declinar","/"));
                }
                else if (estado == "Declinado" || estado == "Confirmado"){
		            div.appendChild(crearElem("button", "class/btn btn-secondary w-100 btn-ver/style/font-size:12px;/onclick/carga_detalles_pago(this,1)","Ver","/"));
                }
                div = crearElem("div","class,row justify-content-around mx-0",div);

                row.querySelector("td:nth-child(7)").appendChild(div);

			},
		  columnDefs: [
			{ targets: [5, 6], orderable: false, searchable: false },
		  ],
		  language: {
			lengthMenu: "Mostrar _MENU_ por página",
			zeroRecords: "No se encontraron pagos declinados",
			info: "Mostrando página _PAGE_ de _PAGES_",
			infoEmpty: "No hay pagos declinados",
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
		  order: [[0, "desc"]],
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
function carga_detalles_pago(linea) {
  var linea = $(linea).closest("tr");
  var id = $(linea).find("td:eq(0)").text();
  var datos = new FormData();
  datos.append("accion", "detallespago");
  datos.append("id", id);

  
  enviaAjax(datos,function(respuesta){
  
	var lee = JSON.parse(respuesta);
	if(lee.resultado == "detallespago"){

	  $("#detallespago").html(lee.mensaje);
	  $("#modaldetalles").modal("show");

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
function confirmar_declinar_pago(linea, accion) {
  var linea = $(linea).closest("tr");
  var id = $(linea).find("td:eq(0)").text();
  if (accion == 1) {
	$("#accion").val("confirmar");
	$("#id").val(id);
	Swal.fire({
	  title: "¿Estás Seguro?",
	  text: "¿Está seguro que desea confirmar este pago?",
	  showCancelButton: true,
	  confirmButtonText: "Confirmar",
	  confirmButtonColor: "#007bff",
	  cancelButtonText: `Cancelar`,
	  icon: "warning",
	}).then((result) => {
	  if (result.isConfirmed) {
		var datos = new FormData();
		datos.append("accion", $("#accion").val());
		datos.append("id", $("#id").val());
		//enviaAjax(datos);

		enviaAjax(datos,function(respuesta){
		
		  var lee = JSON.parse(respuesta);
		  if (lee.resultado == "confirmado") {
				muestraMensaje(lee.mensaje, "", "success");
				carga_pagos();
				carga_pagos_pendientes();
				carga_pagos_confirmados();
				carga_pagos_declinados();
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
  } else if (accion == 2) {
	$("#accion").val("declinar");
	$("#id").val(id);
	Swal.fire({
	  title: "¿Estás Seguro?",
	  text: "¿Está seguro que desea declinar este pago?",
	  showCancelButton: true,
	  confirmButtonText: "Declinar",
	  confirmButtonColor: "#dc3545",
	  cancelButtonText: `Cancelar`,
	  icon: "warning",
	}).then((result) => {
	  if (result.isConfirmed) {
		var datos = new FormData();
		datos.append("accion", $("#accion").val());
		datos.append("id", $("#id").val());
		//enviaAjax(datos);

		enviaAjax(datos,function(respuesta){
		
		  var lee = JSON.parse(respuesta);
		  if (lee.resultado == "declinado") {
				muestraMensaje(lee.mensaje, "", "success");
				carga_pagos();
				carga_pagos_pendientes();
				carga_pagos_confirmados();
				carga_pagos_declinados();
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
  }
}