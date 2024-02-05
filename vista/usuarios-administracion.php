<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Usuarios</h2>
			<hr />
		</div>
		<form method="post" action="" id="f" autocomplete="off">
			<input autocomplete="off" type="text" class="d-none" name="accion" id="accion">
			<input autocomplete="off" type="text" class="d-none" name="id" id="id">
			<div class="container">
				<div class="row mb-3">
					<div class="col-md-4">
						<label for="rif_cedula">Cedula/RIF</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropIdent">-</button>
								<div class="dropdown-menu" disabled>
									<a class="dropdown-item" id="p-natural" href="">V</a>
									<a class="dropdown-item" id="p-extranjero" href="">E</a>
									<a class="dropdown-item" id="p-juridica" href="">J</a>
								</div>
							</div>
							<input autocomplete="off" type="text" class="form-control" name="rif_cedula" id="rif_cedula">
						</div>
						<select name="tipo_identificacion" id="tipo_identificacion" value="-" class="d-none">
							<option value="-">-</option>
							<option value="0">0</option>
							<option value="1">1</option>
							<option value="2">2</option>
						</select>
						<span id="srifcedula" class="text-danger"></span>
					</div>
					<div class="col mt-3 mt-md-0">
						<label for="password">Contraseña</label>
						<input autocomplete="off" class="form-control" type="password" id="password" name="password" data-span = 'spassword'/>
						<span id="spassword" class="text-danger"></span>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-12 col-md-6">
						<label for="razon_social">Razón Social</label>
						<input autocomplete="off" class="form-control" type="text" id="razon_social" name="razon_social" />
						<span id="srazon_social" class="text-danger"></span>
					</div>
					<div class="col-12 col-md-6 mt-3 mt-md-0">
						<label for="telefono">Teléfono</label>
						<input autocomplete="off" class="form-control" type="text" id="telefono" name="telefono" />
						<span id="stelefono" class="text-danger"></span>
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
						<label for="rol">Rol en el sistema</label>
						<select name="rol" id="rol" class="form-control">
							<option value="-" selected>-</option>
							<option value="1">Secretaria</option>
							<option value="2">Administrador</option>
						</select>
						<span id="srol" class="text-danger"></span>
					</div>
					<div class="col mt-3 mt-md-0">
						<label for="correo">Correo electrónico</label>
						<input autocomplete="off" class="form-control" type="text" id="correo" name="correo" />
						<span id="scorreo" class="text-danger"></span>
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
					<h5 class="modal-title">Listado de Usuarios</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="tablausuarios">
						<thead>
							<tr>
								<th class="d-none"></th>
								<th>Tipo</th>
								<th>Cedula/Rif</th>
								<th>Razón Social</th>
								<th>Domicilio Fiscal</th>
								<th>Teléfono</th>
								<th>Correo</th>
								<th class="d-none"></th>
								<th class="d-none"></th>
							</tr>
						</thead>
						<tbody id="listadousuarios">
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
	<script src="js/usuarios-administracion.js"></script>
	<?php require_once('comunes/foot.php'); ?>
</body>

</html>