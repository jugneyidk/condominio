<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Tipos de Apartamentos</h2>
			<hr />
		</div>
		<form method="post" action="" id="f">
			<input type="text" name="accion" id="accion" style="display:none" />
			<input autocomplete="off" type="text" class="d-none" name="id_tipo_apartamento" id="id_tipo_apartamento">
			<div class="container">
				<div class="row mb-3">
					<div class="col-12 col-md-6">
						<label for="descripcion">Descripcion</label>
						<input autocomplete="off" type="text" class="form-control" name="descripcion" id="descripcion">
						<span id="sdescripcion" class="text-danger"></span>
					</div>
					<div class="col mt-3 mt-md-0">
						<label for="alicuota">Alicuota</label>
						<input autocomplete="off" type="text" class="form-control" name="alicuota" id="alicuota">
						<span id="salicuota" class="text-danger"></span>
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
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Listado de tipos</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Descripcion</th>
								<th>Alicuota</th>
							</tr>
						</thead>
						<tbody id="listadotipos">

						</tbody>
					</table>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<?php if ($permisos[4] == 1 || $permisos[5] == 1) : ?>
		<?php require_once('comunes/modalconfirmacion.php'); ?>
	<?php endif; ?>
	<script src="js/carga.js"></script>
	<script src="js/tipoapto.js"></script>
	<?php require_once('comunes/foot.php'); ?>
</body>

</html>