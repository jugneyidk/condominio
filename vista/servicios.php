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





			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
					<a class="nav-item nav-link" id="nav-pagos-servicios-tab" data-toggle="tab" href="#nav-pagos-servicios" role="tab" aria-controls="nav-pagos-servicios" aria-selected="true">Pagos</a>
					<a class="nav-item nav-link active" id="nav-servicios-tab" data-toggle="tab" href="#nav-servicios" role="tab" aria-controls="nav-servicios" aria-selected="false">Servicios</a>
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
									<div class="row justify-content-center">
										<div class="col-6">
											<label for="servicio_2">Servicio</label>
											<input autocomplete="off" type="text" class="form-control" name="servicio" id="servicio_2" data-span="sservicio_2">
											<span id="sservicio_2" class="text-danger"></span>
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
											<button type="button" class="btn btn-info w-100 small-width" id="consultar_2" data-toggle="modal" data-target="#modalservicios_New" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
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

	<div class="modal fade" tabindex="-1" role="dialog" id="modalservicios_New">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Realizados</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover rows-pointer" id="tablaservicios_New">
						<thead>
							<tr>
								<th>N</th>
								<th>Servicio</th>
							</tr>
						</thead>
						<tbody id="listadoservicios_New">

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

	<?php require_once('comunes/foot.php'); ?>
</body>
