<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5"> 
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Servicios</h2>
			<hr />
		</div>

		<form method="post" action="" id="f"> 
			<div class="row">
				<div class="col">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
					<input autocomplete="off" type="text" class="form-control" name="id" id="id" style="display: none;">
					<div class="container">
						<div class="row mb-3">
							<div class="col-6 col-md-5">
								<label for="servicio">Servicio</label>
								<select class="form-control" id="service" name="service" data-span="sservicio">
									<option value='' disabled selected>-</option>
									<option value='corpoelec'>Corpoelec</option>
									<option value='corpoelec'>CANTV</option>
									<option value='otro'>Otro</option>
								</select>
								<span id="sservicio" class="text-danger"></span>
							</div>
							<div class="col-12 col-md-5">
								<label for="descripcion">Descripción:</label>
								<input autocomplete="off" class="form-control" type="text" id="descripcion" name="descripcion" data-span="sdescripcion"/>
								<span id="sdescripcion" class="text-danger"></span>
							</div>

						</div>

						<div class="row mb-3">
							<div class="col-12 col-md-3 mt-3 mt-md-0">
								<label for="monto">Monto:</label>
								<input autocomplete="off" class="form-control text-right" type="text" id="monto" name="monto" data-span="smonto"/>
								<span id="smonto" class="text-danger"></span>
							</div>
							<div class="col-10 col-md-4 mt-3 mt-md-0">
								<label for="referencia">Referencia:</label>
								<input autocomplete="off" class="form-control" type="text" id="referencia" name="referencia" data-span="sreferencia"/>
								<span id="sreferencia" class="text-danger"></span>
							</div>
							<div class="col-6 col-md-3">
								<label for="descripcion">Fecha:</label>
								<input autocomplete="off" class="form-control" type="date" id="fecha" name="fecha" data-span="sfecha"/>
								<span id="sfecha" class="text-danger"></span>
							</div>
						</div>
					</div>
					<hr>
					<div class="row justify-content-center">
						<?php //if ($permisos[2] == 1): ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-primary w-100 small-width" id="incluir" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
							</div>
						<?php //endif; ?>
						<?php //if ($permisos[3] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-info w-100 small-width" id="consultar" data-toggle="modal" data-target="#modalservicios" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
							</div>
						<?php// endif; ?>
						<?php //if ($permisos[4] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-warning w-100 small-width" id="modificar" name="modificar" disabled>MODIFICAR<span class="fa fa-pencil-square-o ml-2"></span></button>
							</div>
						<?php// endif; ?>
						<?php// if ($permisos[5] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-danger w-100 small-width" id="eliminar" name="eliminar" disabled>ELIMINAR<span class="fa fa-trash ml-2"></span></button>
							</div>
						<?php //endif; ?>
					</div>
				</div>


		</form>
	</div>
	</div>

	<!-- modal-->
	<div class="modal fade" tabindex="-1" role="dialog" id="modalservicios">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Realizados</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover rows-pointer" id="tablaservicios">
						<thead>
							<tr>
								<th>ID</th>
								<th>Servicio</th>
								<th>Descripción</th>
								<th>Monto</th>
								<th>Fecha</th>
								<th>Referencia</th>
							</tr>
						</thead>
						<tbody id="listadoservicios">

						</tbody>
					</table>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<script src="js/carga.js"></script>
	<script src="js/comun_x.js"></script>
  	<script src="js/servicio.js"></script>
  	<script>
  		$(document).ready(function(){
  			borrar();
  			loadListados();
			eventoKeypress(document.getElementById('descripcion'),/^[0-9a-zA-Z\s]*$/);
			eventoKeypress(document.getElementById('monto'),/^[0-9]*$/);
			eventoKeypress(document.getElementById('referencia'),/^[0-9]*$/);
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
			eventoKeyup(document.getElementById('fecha'), fechaExp, "Ingrese una fecha valida",undefined,function(elem){console.log(elem.value);});
			$("#fecha").on("change",function(e){
				validarKeyUp(fechaExp, $("#fecha"), "Ingrese una fecha valida");
			});

			$("#limpiar").on("click",function(){
				borrar();
				cambiarbotones();
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



		});

  		function loadListados(){
  			lista_select_servicios();
  			lista_pagos_servicios();
  		}
  		function lista_pagos_servicios(){
  			var datos = new FormData();
  			datos.append("accion", "listadoPagosservicios");

  			enviaAjax(datos,function(respuesta){
  				var lee = JSON.parse(respuesta);
  				console.log(lee);
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
	</script>
	<?php require_once('comunes/foot.php'); ?>
</body>
