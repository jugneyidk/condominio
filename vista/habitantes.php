<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Habitantes</h2>
			<hr />
		</div>
		<form method="post" id="f" autocomplete="off">
			<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
			<input autocomplete="off" type="text" class="form-control" name="id" id="id" style="display: none;">
			<div class="container">
				<div class="row mb-3">
					<div class="col-md-4">
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
				<div class="row mb-3">
					<div class="col">
						<label for="domicilio_fiscal">Domicilio Fiscal</label>
						<input autocomplete="off" class="form-control" type="text" id="domicilio_fiscal" name="domicilio_fiscal" />
						<span id="sdomicilio_fiscal" class="text-danger"></span>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-12 col-md-6">
						<label for="telefono">Telefono</label>
						<input autocomplete="off" class="form-control" type="text" id="telefono" name="telefono" />
						<span id="stelefono" class="text-danger"></span>
					</div>
					<div class="col-12 col-md-6 mt-3 mt-md-0">
						<label for="correo">Correo electrónico</label>
						<input autocomplete="off" class="form-control" type="text" id="correo" name="correo" />
						<span id="scorreo" class="text-danger"></span>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-12 col-md-6">
						<label for="clave">Contraseña</label>
						<input autocomplete="off" class="form-control" type="password" id="clave" name="clave" />
						<span id="sclave" class="text-danger"></span>
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
		</form>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="modal1">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Listado de Habitantes</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="tablahabitantes">
						<thead>
							<tr>
								<th>Tipo</th>
								<th>Cedula</th>
								<th>Nombres</th>
								<th>Apellidos</th>
								<th>Telefono</th>
								<th>Correo</th>
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
	<script src="js/carga.js"></script>
	<script src="js/habitantes.js"></script>
	<?php require_once('comunes/foot.php'); ?>
</body>

</html>