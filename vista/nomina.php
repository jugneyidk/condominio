<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Nomina</h2>
			<hr />
		</div>
		<form method="post" action="" id="f">
			

			</div>
			<div class="row">
				<div class="col">
					<hr />
				</div>
			</div>
			<div class="row justify-content-center">
				<?php //if ($permisos[2] == 1) : ?>
					<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
						<button type="button" class="btn btn-primary w-100 small-width" id="incluir" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
					</div>
				<?php// endif; ?>
				<?php// if ($permisos[3] == 1) : ?>
					<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
						<button type="button" class="btn btn-info w-100 small-width" id="consultar" data-toggle="modal" data-target="#modalapartamentos" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
					</div>
				<?php //endif; ?>
				<?php //if ($permisos[4] == 1) : ?>
					<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
						<button type="button" class="btn btn-warning w-100 small-width" id="modificar" name="modificar" disabled>MODIFICAR<span class="fa fa-pencil-square-o ml-2"></span></button>
					</div>
				<?php //endif; ?>
				<?php// if ($permisos[5] == 1) : ?>
					<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
						<button type="button" class="btn btn-danger w-100 small-width" id="eliminar" name="eliminar" disabled>ELIMINAR<span class="fa fa-trash ml-2"></span></button>
					</div>
				<?php //endif; ?>
			</div>
	</div>
	</form>
	</div>
	<!-- modal apartamentos-->
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
								<th class="d-none"></th>
								<th>Numero</th>
								<th class="d-none"></th>
								<th>Propietario</th>
								<th class="d-none"></th>
								<th>Inquilino</th>
								<th class="d-none"></th>
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
			<div class="modal-content table">
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
								<th style="display:none"></th>
								<th>Tipo</th>
								<th>Cedula</th>
								<th>Nombres</th>
								<th>Apellidos</th>
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

	<?php require_once('comunes/foot.php'); ?>
</body>

</html>