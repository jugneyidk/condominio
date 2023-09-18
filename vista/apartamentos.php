<?php require_once('comunes/head.php'); ?>


<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Apartamentos</h2>
			<hr />
		</div>
		<form method="post" action="" id="f">
			<input type="text" name="accion" id="accion" style="display:none" />
			<input type="text" name="id" id="id" style="display:none" />
			<div class="container">
				<div class="row">
					<div class="col-6 col-md-3 form-group">
						<label for="torre">Torre</label>
						<select class="form-control" id="torre" name="torre">
							<option value='' disabled selected>-</option>
							<option value='22'>22</option>
							<option value='23'>23</option>
							<option value='24'>24</option>
							<option value='25'>25</option>
						</select>
						<span id="storre" class="text-danger"></span>
					</div>
					<div class="col-6 col-md-3">
						<label for="piso">Piso</label>
						<select class="form-control" id="piso" name="piso">
							<option value='' disabled selected>-</option>
							<option value='1'>1</option>
							<option value='2'>2</option>
							<option value='3'>3</option>
							<option value='4'>4</option>
							<option value='5'>5</option>
							<option value='6'>6</option>
							<option value='7'>7</option>
							<option value='8'>8</option>
							<option value='9'>9</option>
							<option value='10'>10</option>
						</select>
						<span id="spiso" class="text-danger"></span>
					</div>
					<div class="col">
						<label for="numapto">Numero de Apartamento</label>
						<input class="form-control" type="text" id="numapto" name="numapto" style="text-transform: uppercase;" />
						<span id="snumapto" class="text-danger"></span>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-4 mt-3 mt-md-0">
						<label for="tipoapto">Tipo de Apartamento</label>
						<select class="form-control" id="tipoapto" name="tipoapto">

						</select>
						<span id="stipoapto" class="text-danger"></span>
					</div>
					<div class="col-md-8 input-group mt-3 mt-md-0">
						<label class="d-block" for="propietario">Propietario</label>
						<div class="input-group">
							<input class="form-control" type="text" id="propietarioci" name="propietarioci" readonly />
							<input class="form-control" type="text" id="propietario" name="propietario" style="display:none" />
							<button type="button" class="btn btn-primary" id="listadopropietarios" name="listadopropietarios">SELECCIONAR</button>
						</div>
						<span id="spropietario" class="text-danger"></span>
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-12 col-sm-2 col-md-4">
						<label class="d-block" for="existeInquilino">Inquilino</label>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="existeInquilino" id="inquilinobool1" value="true">
							<label class="form-check-label" for="inquilinobool1">
								Si
							</label>
						</div>
						<div class="form-check">
							<input checked class="form-check-input" type="radio" name="existeInquilino" id="inquilinobool2" value="false">
							<label class="form-check-label" for="inquilinobool2">
								No
							</label>
						</div>
					</div>
					<div class="col-12 col-sm-10 col-md-8 form-group mt-3 mt-md-0">
						<label class="d-block" for="inquilino">Inquilino</label>
						<div class="input-group">
							<input class="form-control" type="text" id="inquilinoci" name="inquilinoci" readonly />
							<input class="form-control" type="text" id="inquilino" name="inquilino" style="display:none" />
							<button type="button" class="btn btn-primary" id="listadoinquilinos" name="listadoinquilinos" disabled>SELECCIONAR</button>
						</div>
						<span id="sinquilino" class="text-danger"></span>
					</div>
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
						<button type="button" class="btn btn-info w-100 small-width" id="consultar" data-toggle="modal" data-target="#modalapartamentos" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
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
	<script src="js/carga.js"></script>
	<script src="js/apartamentos.js"></script>
	<?php require_once('comunes/foot.php'); ?>
</body>

</html>