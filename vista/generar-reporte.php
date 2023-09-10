<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once('comunes/carga.php'); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Generar reporte</h2>
			<hr />
		</div>
		<form method="post" action="" id="f" target="_blank">
			<input type="text" name="id" style="display:none;" id="id">
			<div class="container">
				<div class="row">
					<div class="col">
						<label for="tiporeporte">Tipo de reporte</label>
						<div class="input-group">
							<select class="form-control" name="tiporeporte" id="tiporeporte">
								<option value="1">Reporte general de morosidad</option>
								<option value="2">Reporte de un propietario</option>
								<option value="3">Reporte de un apartamento</option>
							</select>
							<button type="button" class="btn btn-primary ml-2" id="listado" name="listado" disabled>SELECCIONAR</button>
						</div>
					</div>
				</div>

				<div class="row mt-3 d-none" id="mensaje_reporte">
					<div class="col">
						<hr />
						<span class="font-weight-bold">Reporte a generar:</span>
						<p id="reporte_a_generar"></p>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<hr />
					</div>
				</div>
				<div class="modal fade" tabindex="-1" role="dialog" id="modalapartamentos">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header text-light bg-info">
								<h5 class="modal-title">Listado de Apartamentos</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="table-responsive">
								<table class="table table-striped table-hover" id="tablaapartamentos">
									<thead>
										<tr>
											<th class="d-none">s</th>
											<th>Numero</th>
											<th>Propietario</th>
											<th>Inquilino</th>
											<th>Tipo</th>
											<th>Piso</th>
											<th>Torre</th>
										</tr>
									</thead>
									<tbody id="listadoapartamentos">
									</tbody>
								</table>
							</div>
							<div class="modal-footer bg-light">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" tabindex="-1" role="dialog" id="modalhabitantes">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header text-light bg-info">
								<h5 class="modal-title">Listado de Habitantes</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<input type="text" id="campo-habitante" readonly style="display: none;">
							<div class="table-responsive">
								<table class="table table-striped table-hover" id="tablahabitantes">
									<thead>
										<tr>
											<th>Tipo</th>
											<th>Cedula</th>
											<th>Nombres</th>
											<th>Apellidos</th>
											<th>Telefono</th>
											<th>Correo electrónico</th>
											<th>Domicilio Fiscal</th>
											<th class="d-none"></th>
											<th class="d-none"></th>
										</tr>
									</thead>
									<tbody id="listadohabitantes">
									</tbody>
								</table>
							</div>
							<div class="modal-footer bg-light">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col d-flex justify-content-center">
						<button type="submit" target="_blank" class="btn btn-primary" id="generar" name="generar">GENERAR<span class="fa fa-list-alt ml-2"></button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php require_once('comunes/modal.php'); ?>
	<script src="js/carga.js"></script>
	<script src="js/generar-reporte.js"></script>
	<?php require_once('comunes/foot.php'); ?>
</body>

</html>