<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Deuda del condominio</h2>
			<hr />
		</div>

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<a class="nav-item nav-link active" id="nav-pagos-servicios-tab" data-toggle="tab" href="#nav-deudas" role="tab" aria-controls="nav-deudas" aria-selected="true">Deudas</a>
				<a class="nav-item nav-link" id="nav-cargos-tab" data-toggle="tab" href="#nav-cargos" role="tab" aria-controls="nav-cargos" aria-selected="false">Cargos</a>
				<!-- <a class="nav-item nav-link" id="nav-config-tab" data-toggle="tab" href="#nav-config" role="tab" aria-controls="nav-config" aria-selected="false">Configuración</a> -->
			</div>
		</nav>
		<br>
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active" id="nav-deudas" role="tabpanel" aria-labelledby="nav-pagos-servicios-tab">
				<div class="container">
					<div class="row">
						<div class="col">
							<h3>Distribución de deudas</h3>
						</div>
					</div>
					<form id="f_deudas" action="" method="POST" onsubmit="return false">
						<input type="hidden" id="id_hidden" class="d-none" name="id">
						<div class="row">
							<div class="col-12 col-md">
								<label for="deuda_fecha">Fecha</label>
								<input type="date" class="form-control" id="deuda_fecha" name="fecha" data-span="invalid-span-deuda_fecha">
								<span id="invalid-span-deuda_fecha" class="invalid-span text-danger"></span>
							</div>
							<div class="col-12 col-md">
								<label for="deuda_concepto">Concepto</label>
								<input type="text" class="form-control" id="deuda_concepto" name="concepto" data-span="invalid-span-deuda_concepto">
								<span id="invalid-span-deuda_concepto" class="invalid-span text-danger"></span>
							</div>
						</div>
					</form>

					<div class="row justify-content-center mt-3">
						<?php if ($permisos[2] == 1): ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-primary w-100 small-width" id="incluir_deuda" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
							</div>
						<?php endif; ?>
						<?php if ($permisos[3] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-info w-100 small-width" id="consultar_deuda"  name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
							</div>
						<?php endif; ?>
						<?php if ($permisos[4] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-warning w-100 small-width" id="modificar_deuda" name="modificar" disabled>MODIFICAR<span class="fa fa-pencil-square-o ml-2"></span></button>
							</div>
						<?php endif; ?>
						<?php if ($permisos[5] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-danger w-100 small-width" id="eliminar_deuda" name="eliminar" disabled>ELIMINAR<span class="fa fa-trash ml-2"></span></button>
							</div>
						<?php endif; ?>
					</div>


				</div>
			</div>
			<div class="tab-pane fade show" id="nav-cargos" role="tabpanel" aria-labelledby="nav-cargos-tab">
				<div class="container">
					<form id="f_cargos" action="" method="POST" onsubmit="return false">
						<input type="hidden" id="cargo_id_hidden" class="d-none" name="id">
						
						<div class="row">
							<div class="col-12 col-md-5">
								<label for="cargo_concepto">Concepto</label>
								<input type="text" maxlength="80" class="form-control" id="cargo_concepto" name="cargo_concepto" data-span="invalid-span-cargo_concepto">
								<span id="invalid-span-cargo_concepto" class="invalid-span text-danger"></span>
							</div>
							<div class="col-12 col-md-5">
								<label for="cargo_monto">Monto</label>
								<input type="tel" class="form-control text-right" id="cargo_monto" name="cargo_monto" data-span="invalid-span-cargo_monto">
								<span id="invalid-span-cargo_monto" class="invalid-span text-danger"></span>
							</div>
							<div class="col-5 col-md-3 col-xl-2 d-none">
								<label class="w-100">Tipo de monto</label>
								<div class="text-nowrap">
									<label class="cursor-pointer no-select" for="cargo_tipo_monto_divisa">Divisa</label>
									<input checked type="radio" class="dont-erase" id="cargo_tipo_monto_divisa" name="cargo_tipo_monto" data-span="invalid-span-cargo_tipo_monto_moneda" value="divisa">
									<label class="cursor-pointer no-select" for="cargo_tipo_monto_bolivar">Bolívar</label>
									<input type="radio" class="dont-erase" id="cargo_tipo_monto_bolivar" name="cargo_tipo_monto" data-span="invalid-span-cargo_tipo_monto_moneda" value="bolivar">
								</div>
								<span id="invalid-span-cargo_tipo_monto_moneda" class="invalid-span text-danger"></span>
							</div>
							<div class="col d-flex align-items-center">
								<span class="w-100 item-align-center" id="cargo_tipo_monto_info"></span>
							</div>

						</div>
						<hr>
						<p>Opciones del cargo</p>
						<hr>
						<div class="row">
							<div class="col-5 col-md-3 col-xl-2">
								<div class="text-nowrap">
									<label class="cursor-pointer no-select" for="cargo_tipo_cargo_mensual">Mensual</label>
									<input type="radio" class="dont-erase" id="cargo_tipo_cargo_mensual" name="cargo_tipo_cargo" data-span="invalid-span-cargo_tipo_cargo_unico_mens" value="mensual">
									<label class="cursor-pointer no-select" for="cargo_tipo_cargo_unico">Único</label>
									<input type="radio" class="dont-erase" id="cargo_tipo_cargo_unico" name="cargo_tipo_cargo" data-span="invalid-span-cargo_tipo_cargo_unico_mens" value="unico">
								</div>
								<span id="invalid-span-cargo_tipo_cargo_unico_mens" class="invalid-span text-danger"></span>
							</div>
							<div class="col d-flex align-items-center">
								<div class="w-100" id="cargo_tipo_cargo_info"></div>
							</div>
						</div>
						<div class="row border-top pt-1">
							<div class="col-12">
								<label class="w-100">Aplicar el cargo la siguiente vez?</label>
								<div class="text-nowrap">
									<label class="cursor-pointer no-select" for="cargo_tipo_cargo_aplicar_next">Si</label>
									<input type="radio" class="dont-erase" id="cargo_tipo_cargo_aplicar_next" name="cargo_aplicar_next" data-span="invalid-span-cargo_tipo_cargo_aplicar_si_no" value="aplicar">
									<label class="cursor-pointer no-select" for="cargo_tipo_cargo_no_aplicar">No</label>
									<input type="radio" class="dont-erase" id="cargo_tipo_cargo_no_aplicar" name="cargo_aplicar_next" data-span="invalid-span-cargo_tipo_cargo_aplicar_si_no" value="no aplicar">
								</div>
								<span id="invalid-span-cargo_tipo_cargo_aplicar_si_no" class="invalid-span text-danger"></span>
							</div>
						</div>

						<div class="row border-top pt-1">
							<div class="col-5 col-md-3 col-xl-2">
								<div class="text-nowrap">
									<label class="cursor-pointer no-select" for="cargo_tipo_cargo_global">Global</label>
									<input type="radio" class="dont-erase" id="cargo_tipo_cargo_global" name="cargo_tipo_cargo_global_dedicado" data-span="invalid-span-cargo_tipo_cargo_gd" value="global">
									<label class="cursor-pointer no-select" for="cargo_tipo_cargo_dedicado">Dedicado</label>
									<input type="radio" class="dont-erase" id="cargo_tipo_cargo_dedicado" name="cargo_tipo_cargo_global_dedicado" data-span="invalid-span-cargo_tipo_cargo_gd" value="dedicado">
								</div>
									<span id="invalid-span-cargo_tipo_cargo_gd" class="invalid-span text-danger"></span>
							</div>
							<div class="col d-flex align-items-center">
								<div id="cargo_tipo_cargo_gd_info" class="w-100"></div>
							</div>
						</div>
					</form>
					<div class="container d-none" id="cargo_dedicado_to_show">
						<div class="table-responsive" style="overflow-y: auto; max-height: 500px;">
							<table class="table table-striped table-hover" id="cargo_apartamento_table">
								<thead>
									<tr>
										<th>apartamento</th>
										<th>propietario</th>
										<th>torre</th>
										<th>piso</th>
										<th>tipo apartamento</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="cargo_apartamentos_seleccionados">
									<tr><td colspan="6" class="text-center">No ha seleccionado un apartamento</td></tr>
								</tbody>
							</table>
						</div>
						<div class="text-right mt-3">
							<button type="button" id="cargo_apartamento_nuevo_btn" class="btn btn-info">Nuevo +</button>
						</div>
					</div>

					


					


					<div class="row justify-content-center mt-3">
						<?php if ($permisos[2] == 1): ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-primary w-100 small-width" id="incluir_cargo" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
							</div>
						<?php endif; ?>
						<?php if ($permisos[3] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-info w-100 small-width" id="consultar_cargo"  name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
							</div>
						<?php endif; ?>
						<?php if ($permisos[4] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-warning w-100 small-width" id="modificar_cargo" name="modificar" disabled>MODIFICAR<span class="fa fa-pencil-square-o ml-2"></span></button>
							</div>
						<?php endif; ?>
						<?php if ($permisos[5] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-danger w-100 small-width" id="eliminar_cargo" name="eliminar" disabled>ELIMINAR<span class="fa fa-trash ml-2"></span></button>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="tab-pane fade show" id="nav-config" role="tabpanel" aria-labelledby="nav-config-tab">
				<div class="container">
					<h1>Config</h1>
				</div>
			</div>
		</div>

		<!-- <form method="post" action="" id="f" class="d-none">
			<input type="text" name="accion" id="accion" style="display:none" />
			<input autocomplete="off" type="text" class="d-none" name="id_deuda" id="id_deuda">
			<div class="container">
				<div class="row mb-3">
					<div class="col-8 col-md-8">
						<label for="fecha">Fecha de la deuda</label>
						<input autocomplete="off" type="date" class="form-control" name="fecha" id="fecha" style="-webkit-appearance: none;-moz-appearance: none;">
						<span id="sfecha" class="text-danger"></span>
					</div>
					<div class="col-4 col-md-4">
						<label for="monto">Monto</label>
						<input autocomplete="off" type="text" class="form-control" name="monto" id="monto">
						<span id="smonto" class="text-danger"></span>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col">
						<label for="concepto">Concepto</label>
						<input autocomplete="off" type="text" class="form-control" name="concepto" id="concepto">
						<span id="sconcepto" class="text-danger"></span>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<hr />
					</div>
				</div>
				<div class="row justify-content-center">
					<?php if ($permisos[2] == 1) : ?>
						<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
							<button type="button" class="btn btn-primary w-100 small-width" id="incluir" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
						</div>
					<?php endif; ?>
					<?php if ($permisos[3] == 1) : ?>
						<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
							<button type="button" class="btn btn-info w-100 small-width" id="consultar" data-toggle="modal" data-target="#modal1" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
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
		</form> -->
	</div>


	<script type="text/javascript">

		
		
	</script>




	<div class="modal fade" tabindex="-1" role="dialog" id="modal_cargo_lista_apartamentos">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Listado de Apartamentos</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="container">
					<div class="table-responsive">
						<table class="table table-striped table-hover row-vertical-middle" id="cargo_apartamento_table_info">
							<thead>
								<tr>
									<th></th>
									<th>Apartamento</th>
									<th>Propietario</th>
									<th>Torre</th>
									<th>Piso</th>
									<th>Tipo</th>
								</tr>
							</thead>
							<tbody id="cargo_apartamentos_seleccionados_info">

							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="modal_cargo_lista_cargos">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Listado de Cargos</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="container">
					<div class="table-responsive">
						<table class="table table-striped table-hover rows-pointer" id="cargo_table_info">
							<thead>
								<tr>
									<th>Concepto</th>
									<th>Monto</th>
									<th>Tipo de monto</th>
									<th colspan="2">Configuración</th>
									
									<th>Siguiente mes<!-- global/dedicado --></th>
								</tr>
							</thead>
							<tbody id="cargos_info">

							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal_resumen_cargos_deudas">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-primary">
					<h5 class="modal-title">Resumen De Distribución</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				

				<div class="container-fluid px-0">
					<div class="accordion" id="accordionExample">
						<div class="card border-0">
							<div class="card-header text-light bg-primary py-0" id="headingOne">
								<h5 class="mb-0">
									<button id="btn_collapse_global" class="btn collapsed text-light w-100 text-left py-3" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										Globales
									</button>
								</h5>
							</div>
							<div id="collapseOne" class="collapse multi_show" aria-labelledby="headingOne" data-parent="#accordionExample">
								<div class="card-body">
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Concepto</th>
												<th>Divisa (u)</th>
												<th>Bolívares (u)</th>
											</tr>
										</thead>
										<tbody id="deuda_lista_resumen_global">

										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="card border-0">
							<div class="card-header text-light bg-primary py-0" id="headingTwo">
								<h5 class="mb-0">
									<button id="btn_collapse_dedicado" class="btn collapsed text-light w-100 text-left py-3" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										Dedicados
									</button>
								</h5>
							</div>
							<div id="collapseTwo" class="collapse multi_show" aria-labelledby="headingTwo" data-parent="#accordionExample">
								<div class="card-body">
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Concepto</th>
												<th>Divisa (u)</th>
												<th>Bolívares (u)</th>
											</tr>
										</thead>
										<tbody id="deuda_lista_resumen_dedicado">

										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="card border-0 d-none">
							<div class="card-header text-light bg-primary py-0" id="headingThree">
								<h5 class="mb-0">
									<button class="btn collapsed text-light w-100 text-left py-3" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
										Collapsible Group Item #3
									</button>
								</h5>
							</div>
							<div id="collapseThree" class="collapse multi_show" aria-labelledby="headingThree" data-parent="#accordionExample">
								<div class="card-body">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer bg-light justify-content-around">
					<button type="button" id="deuda_btn_distribuir_modal" class="btn btn-primary">Distribuir deudas</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal_distribuciones">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Distribuciones</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="container">
					<div class="table-responsive">
						<table class="table table-striped table-hover rows-pointer" id="table_distribuciones_modal">
							<thead>
								<tr>
									<th>Fecha</th>
									<th>Concepto</th>
									<th>Usuario</th>
								</tr>
							</thead>
							<tbody id="tbody_distribuciones_modal">
	
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>






	<div class="modal fade" tabindex="-1" role="dialog" id="modal1">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Listado de Deudas</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="tabladeudas">
						<thead>
							<tr>
								<th class="d-none"></th>
								<th>Fecha</th>
								<th>Monto</th>
								<th>Concepto</th>
								<th>Registrada por</th>
								<th>Estado</th>
							</tr>
						</thead>
						<tbody id="listadodeudas">

						</tbody>
					</table>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<?php if ($permisos[3] || $permisos[4] == 1 || $permisos[5] == 1) : ?>
		<?php require_once('comunes/modalconfirmacion.php'); ?>
	<?php endif; ?>
	<script src="js/carga.js"></script>
	<script src="js/comun_x.js"></script>
	
	<script src="js/deuda-condominio.js"></script>
	<?php require_once('comunes/foot.php'); ?>
</body>

</html>