$(document).ready(function () {
  carga_pagos();
  carga_pagos_pendientes();
  carga_pagos_confirmados();
  carga_pagos_declinados();

  $("#modaldetalles").on("shown.bs.modal",()=>{
  	document.body.classList.add("modal-open");
  });
  $("#modaldetalles").on("hidden.bs.modal",()=>{
  	document.body.classList.remove("modal-open");
  });


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
	console.log(lee);
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
				//console.log(row);
                row.querySelector("td:nth-child(1)").classList.add("align-middle");
                row.querySelector("td:nth-child(2)").classList.add("apartamento","align-middle");
                row.querySelector("td:nth-child(3)").classList.add("align-middle","d-none","d-md-table-cell");
                row.querySelector("td:nth-child(4)").classList.add("align-middle","text-nowrap");
                row.querySelector("td:nth-child(5)").classList.add("align-middle","text-nowrap","d-none","d-sm-table-cell");
                row.querySelector("td:nth-child(6)").classList.add("align-middle", "text-capitalize","d-none","d-md-table-cell");
                row.querySelector("td:nth-child(7)").classList.add("align-middle");
                var estado = "Pendiente";
                var estado_2 = 0;
                if(data.estado == 1 ){ estado = "Declinado";estado_2 = 1;}
                else if(data.estado == 2 ){ estado = "Confirmado";estado_2 = 2;}
                row.querySelector("td:nth-child(6)").innerHTML = estado;

                var div = crearElem("div", "class, btn-group w-100,role, group");

                if(estado == "Pendiente"){
		            div.appendChild(crearElem("button", "class/btn btn-primary btn-group-1/style/font-size:12px;/onclick/confirmar_declinar_pago(this,1)","Confirmar","/"));
		            div.appendChild(crearElem("button", "class/btn btn-secondary btn-group-1/style/font-size:12px;/onclick/carga_detalles_pago(this,1)","Ver","/"));
		            div.appendChild(crearElem("button", "class/btn btn-danger btn-group-1/style/font-size:12px;/onclick/confirmar_declinar_pago(this,2)","Declinar","/"));
                }
                else if (estado == "Declinado" || estado == "Confirmado"){
		            div.appendChild(crearElem("button", "class/btn btn-secondary btn-group-2/style/font-size:12px;/onclick/carga_detalles_pago(this,1)","Ver","/"));
		            div.appendChild(crearElem("button", "class/btn btn-info btn-group-2/style/font-size:12px;/onclick/confirmar_declinar_pago(this,3)","Deshacer","/"));
                }
                div = crearElem("div","class,row justify-content-around mx-0",div);

                row.querySelector("td:nth-child(7)").appendChild(div);
                row.querySelector("td:nth-child(7)").dataset.sort = estado_2;
                row.querySelector("td:nth-child(7)").dataset.order = estado_2;

			},
			columnDefs: [
				{ targets: [5], orderable: false, searchable: false },
				{ targets: [6], searchable: false, orderData:[5,3] },
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
			//order: [[0, "desc"]],
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
		//order: [[0, "desc"]],
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
		  //order: [[0, "desc"]],
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
		  //order: [[0, "desc"]],
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
		console.log("debo eliminar esta condicional");
		console.log(id);
			var deuda = lee.mensaje;
			$("#detallespago").html("");
			// var tbody = document.getElementById('detallespago');
			// var tr = crearElem("tr", "class,text-center");
			// var td = crearElem("td");

			// td.appendChild(crearElem("span","class,font-weight-bold","Apartamento"));
			// td.appendChild(crearElem("span","class,d-block",deuda.apartamento));
			// td.appendChild(crearElem("span","class,font-weight-bold","Torre"));
			// td.appendChild(crearElem("span","class,d-block",deuda.torre));
			// td.appendChild(crearElem("span","class,font-weight-bold","Piso"));
			// td.appendChild(crearElem("span","class,d-block",deuda.piso));
			// td.appendChild(crearElem("span","class,font-weight-bold","Propietario"));
			// td.appendChild(crearElem("span","class,d-block",deuda.propietario));

			document.getElementById('detallespago').innerHTML = `<tr class="text-center">
						<td>
							<span class="font-weight-bold">Apartamento</span>
							<span class="d-block">${deuda.apartamento}</span>
						</td>
						<td>
							<span class="font-weight-bold">torre</span>
							<span class="d-block">${deuda.torre}</span>
						</td>
						<td>
							<span class="font-weight-bold">piso</span>
							<span class="d-block">${deuda.piso}</span>
						</td>
						<td>
							<span class="font-weight-bold">propietario</span>
							<span class="d-block">${deuda.propietario}</span>
						</td>
					</tr>
					<tr class="text-center">
						<td colspan="1">
							<span class="font-weight-bold">Fecha de Deuda</span>
							<span class="d-block">${deuda.fecha_generada }</span>
						</td>
						<td colspan="2">
							<span class="font-weight-bold">Concepto</span>
							<span class="d-block">${deuda.concepto }</span>
						</td>
						<td colspan="1" title="${deuda.total_divisa} $">
							<span class="font-weight-bold">Monto Total</span>
							<span class="d-block">${deuda.total_bs } Bs</span>
						</td>
					</tr>`;

			if ($.fn.DataTable.isDataTable("#tabla_resumen_deuda")) {
				$("#tabla_resumen_deuda").DataTable().destroy();
			}
			
			$("#detallespago_resumen_deuda").html("");
			
			if (!$.fn.DataTable.isDataTable("#tabla_resumen_deuda")) {
				$("#tabla_resumen_deuda").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de Deudas",
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
					data:deuda.resumen_d,
					columns:[
						{data : "concepto" },
						{data : "monto_divisa" },
						{data : "monto_bs" }
					],
					createdRow: function(row,data){
						row.querySelector("td:nth-child(2)").className = "text-right";
						row.querySelector("td:nth-child(3)").className = "text-right";
						row.querySelector("td:nth-child(2)").innerText = sepMiles(data.monto_divisa);
						row.querySelector("td:nth-child(3)").innerText = sepMiles(data.monto_bs);
					},
					autoWidth: false,
					searching:false,
					info: false,
					ordering: false,
					paging: false
					//order: [[1, "asc"]],
					
				});
			}

			if ($.fn.DataTable.isDataTable("#tabla_resumen_pagos")) {
				$("#tabla_resumen_pagos").DataTable().destroy();
			}
			
			$("#detallespago_resumen_pagos").html("");
			
			if (!$.fn.DataTable.isDataTable("#tabla_resumen_pagos")) {
				$("#tabla_resumen_pagos").DataTable({
					language: {
						lengthMenu: "Mostrar _MENU_ por página",
						zeroRecords: "No se encontraron registros de Pagos",
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
					data:deuda.resumen_p,
					columns:[
						{data:"fecha"},
						{data:"tipo_pago"},
						{data:"monto_bs"},
					],
					createdRow: function(row,data){
						row.querySelector("td:nth-child(3)").innerText +=" Bs";
						row.querySelector("td:nth-child(3)").title = data.monto_divisa+" $";
					},
					autoWidth: false,
					searching:false,
					info: false,
					ordering: false,
					paging: false
					//order: [[1, "asc"]],
					
				});
			}
		$("#modaldetalles").modal("show");
		document.getElementById('titulomodal').innerText ="Detalles del pago Nº "+id;
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
	if (accion == 1) { //confirmar
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
				  console.log(lee);
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
	} else if (accion == 2) { // declinar
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
  	else if (accion == 3) { // por_confirmar
  			$("#accion").val("deshacer_conf");
  			$("#id").val(id);
  			Swal.fire({
  				title: "¿Estás Seguro?",
  				text: "¿Está seguro que desea deshacer el estado de este pago?",
  				showCancelButton: true,
  				confirmButtonText: "Deshacer",
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
  					  if (lee.resultado == "deshacer_conf") {
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