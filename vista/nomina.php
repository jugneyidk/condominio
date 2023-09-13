<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Pago nomina</h2> 
			<hr />
		</div>
		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<a class="nav-item nav-link" id="nav-pagos-servicios-tab" data-toggle="tab" href="#nav-pagos-servicios" role="tab" aria-controls="nav-pagos-servicios" aria-selected="true">Pagos</a>
				<a class="nav-item nav-link active" id="nav-servicios-tab" data-toggle="tab" href="#nav-servicios" role="tab" aria-controls="nav-servicios" aria-selected="false">Empleados</a>
			</div>
		</nav>
		<br>
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show" id="nav-pagos-servicios" role="tabpanel" aria-labelledby="nav-pagos-servicios-tab">
				<form method="post" action="" id="f"> 
					<div class="row">
						<div class="col">
							<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
							<input autocomplete="off" type="text" class="form-control" name="id" id="id" style="display: none;">
							<div class="container">
								<div class="row">
									<div class="col">
										<div class="container">
											<h3>Datos personales</h3>

											<div class="col-sm-12 col-md-5 col-xl-6">
												<label class="d-block" for="propietario">Empleado</label>
												<div class="input-group">
													<input class="form-control" type="text" id="apartamento_info" placeholder="- Sin SELECCIONAR -" name="apartamento_info" readonly data-span="sSelecApartamento"/>
													<input class="form-control" type="text" id="apartamentos_id" name="apartamentos_id" style="display:none" data-span="sSelecApartamento"/>
													<button type="button" class="btn btn-primary" id="btn_listaApartamentos" name="listadopropietarios">SELECCIONAR</button>
												</div>
												<span id="sSelecApartamento" class="text-danger"></span>
											</div>
										</div>
										<hr><hr>
										<h3>Datos de pago</h3>
										<div class="row mb-3">
											<div class="col-12 col-md-6">
												<label for="descripcion">Descripción:</label>
												<input autocomplete="off" class="form-control" type="text" id="descripcion" name="descripcion" />
												<span id="sdescripcion" class="text-danger"></span>
											</div>
											<div class="col-6 col-md-3">
												<label for="metodo">Metodo de pago</label>
												<select class="form-control" id="metodo" name="metodo">
													<option value='' disabled selected>-</option>
													<option value='pagomovil'>Pago movil</option>
													<option value='transferencia'>transferencia</option>
													<option value='dolar'>Dolar fisico</option>
													<option value='zelle'>Zelle</option>
												</select>
												<span id="smetodo" class="text-danger"></span>
											</div>
											<div class="col-6 col-md-3">
												<label for="descripcion">Fecha:</label>
												<input autocomplete="off" class="form-control" type="date" id="fecha" name="fecha" />
												<span id="sfecha" class="text-danger"></span>
											</div>
										</div>

										<div class="row mb-3">
											<div class="col-12 col-md-6 mt-3 mt-md-0">
												<label for="monto">Monto:</label>
												<input autocomplete="off" class="form-control" type="text" id="monto" name="monto" />
												<span id="smonto" class="text-danger"></span>
											</div>
											<div class="col-10 col-md-6 mt-3 mt-md-0">
												<label for="referencia">Referencia:</label>
												<input autocomplete="off" class="form-control" type="text" id="referencia" name="referencia" />
												<span id="sreferencia" class="text-danger"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row justify-content-center">
								<?php if ($permisos[2] == 1): ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-primary w-100 small-width" id="incluir" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
									</div>
								<?php endif; ?>
								<?php if ($permisos[3] == 1) : ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-info w-100 small-width" id="consultar" data-toggle="modal" data-target="#modalservicios" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
									</div>
								<?php endif; ?>
								<?php if ($permisos[4] == 1) : ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-warning w-100 small-width" id="modificar" name="modificar" disabled>MODIFICAR<span class="fa fa-pencil-square-o ml-2"></span></button>
									</div>
								<?php endif; ?>
								<?php if ($permisos[5] == 1) : ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-danger w-100 small-width" id="eliminar" name="eliminar" disabled>ELIMINAR<span class="fa fa-trash ml-2"></span></button>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane fade show active" id="nav-servicios" role="tabpanel" aria-labelledby="nav-servicios-tab">
				<form method="POST" action="" id="f2">
					<div class="row">
						<div class="col">
							<input autocomplete="off" type="text" class="form-control" name="accion" id="accion_2" style="display: none;">
							<input autocomplete="off" type="text" class="form-control" name="id" id="id_2" style="display: none;">
							<div class="container">

								<div class="row mb-3">
									<div class="col-12 col-md-4 mt-3 mt-md-0">
										<label for="cedula_rif">Cedula/RIF</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropIdent">-</button>
												<div class="dropdown-menu">
													<a class="dropdown-item" id="p-natural" href="">V</a>
													<a class="dropdown-item" id="p-extranjero" href="">E</a>
													<a class="dropdown-item" id="p-juridica" href="">J</a>
												</div>
											</div> 
											<input autocomplete="off" type="text" class="form-control" name="cedula_rif" id="cedula_rif">
										</div>
										<select name="tipo_identificacion" id="tipo_identificacion" value="-" class="d-none">
											<option value="-">-</option>
											<option value="0">0</option>
											<option value="1">1</option>
											<option value="2">2</option>
										</select>
										<span id="scedularif" class="text-danger"></span>

									</div>
									<div class="col-12 col-md-4 mt-3 mt-md-0">
										<label for="nombres">Nombres</label>
										<input autocomplete="off" class="form-control" type="text" id="nombres" name="nombres" />
										<span id="snombres" class="text-danger"></span>
									</div>
									<div class="col mt-3 mt-md-0">
										<label for="apellidos">Apellidos</label>
										<input autocomplete="off" class="form-control" type="text" id="apellidos" name="apellidos" />
										<span id="sapellidos" class="text-danger"></span>
									</div>
								</div>

								<div class="row mb-4">

									<div class="col-md-3 col-6">
										<label for="salario">Salario</label>
										<input class="form-control text-right" placeholder="0,00" type="text" id="salario" name="salario" data-span="salario"/>
										<span id="ssalario" class="text-danger"></span>
									</div>
									<div class="col-6 col-md-3">
										<label for="descripcion">Fecha contratacion:</label>
										<input autocomplete="off" class="form-control" type="date" id="fechac" name="fechac" data-span="sfechac"/>
										<span id="sfechac" class="text-danger"></span>
									</div>
									<div class="col-md-6">
										<label for="domicilio_fiscal">Domicilio Fiscal</label>
										<input autocomplete="off" class="form-control" type="text" id="domicilio_fiscal" name="domicilio_fiscal" />
										<span id="sdomicilio_fiscal" class="text-danger"></span>
									</div>

								</div>
								<div class="row mb-3">
									<div class="col-12 col-md-4">
										<label for="telefono">Telefono</label>
										<input autocomplete="off" class="form-control" type="text" id="telefono" name="telefono" />
										<span id="stelefono" class="text-danger"></span>
									</div>
									<div class="col-12 col-md-4 mt-3 mt-md-0">
										<label for="correo">Correo electrónico</label>
										<input autocomplete="off" class="form-control" type="text" id="correo" name="correo" />
										<span id="scorreo" class="text-danger"></span>
									</div>
									<div class="col-12 col-md-4 mt-3 mt-md-0">
										<label for="cargo">Cargo</label>
										<input autocomplete="off" class="form-control" type="text" id="cargo" name="cargo" />
										<span id="scargo" class="text-danger"></span>
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-6 col-md-3">
										<label for="descripcion">Fecha nacimiento:</label>
										<input autocomplete="off" class="form-control" type="date" id="fechan" name="fechan" data-span="sfechan"/>
										<span id="sfechan" class="text-danger"></span>
									</div>
									<div class="col-6 col-md-5">
										<label for="servicio">Estado civil</label>
										<select class="form-control" id="service" name="service" data-span="sservicio">
											<option value='' disabled selected>-</option>
											<option value='Soltero'>Soltero</option>
											<option value='Casado'>Casado</option>
											<option value='Divorciado'>Divorciado</option>
											<option value='Viudo'>Viudo</option>
										</select>
										<span id="sservicio" class="text-danger"></span>
									</div>
								</div>

							</div>
							<hr>
							<div class="row justify-content-center">
								<?php if ($permisos[2] == 1): ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-primary w-100 small-width" id="incluir_2" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
									</div>
								<?php endif; ?>
								<?php if ($permisos[3] == 1) : ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-info w-100 small-width" id="consultar_2" data-toggle="modal" data-target="#modalEmpleados" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
									</div>
								<?php endif; ?>
								<?php if ($permisos[4] == 1) : ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-warning w-100 small-width" id="modificar_2" name="modificar" disabled>MODIFICAR<span class="fa fa-pencil-square-o ml-2"></span></button>
									</div>
								<?php endif; ?>
								<?php if ($permisos[5] == 1) : ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-danger w-100 small-width" id="eliminar_2" name="eliminar" disabled>ELIMINAR<span class="fa fa-trash ml-2"></span></button>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>

				</form>
			</div>
		</div>

	</div>

	<!-- modal-->
	<div class="modal fade" tabindex="-1" role="dialog" id="modalEmpleados">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Realizados</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="tablaEmpleados">
						<thead>
							<tr>
								<th>Cedula</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th>Salario.</th>
								<th>Fecha de contrato</th>
								<th>Domicilio</th>
								<th>Teléfono</th>
								<th>Correo</th>
								<th>Cargo</th>
								<th>Fecha Nacim</th>
								<th>Estado Civil</th>
							</tr>
						</thead>
						<tbody id="listadoEmpleados">

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
	<script>

		listadoEmpleados();


		function listadoEmpleados(){
			var datos = new FormData();
			datos.append("accion", "listadoEmpleados");

			enviaAjax(datos,function(respuesta){
				var lee = JSON.parse(respuesta);
				if (lee.resultado == "listadoEmpleados") {
					if ($.fn.DataTable.isDataTable("#tablaEmpleados")) {
						$("#tablaEmpleados").DataTable().destroy();
					}
					$("#listadoEmpleados").html(lee.mensaje);
					if (!$.fn.DataTable.isDataTable("#tablaEmpleados")) {
						$("#tablaEmpleados").DataTable({
							language: {
								lengthMenu: "Mostrar _MENU_ por página",
								zeroRecords: "No se encontraron registros de Empleados",
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
							{ data: "col3" },
							{ data: "col4" },
							{ data: "col5" },
							{ data: "col6" },
							{ data: "col7" },
							{ data: "col8" },
							{ data: "col9" },
							{ data: "col10" },
							{ data: "col11" },
							]
						});
					}
				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
				}

			});
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
	</script>
	<!-- <script src="js/nomina.js"></script> -->
	<?php require_once('comunes/foot.php'); ?>
</body>

</html>