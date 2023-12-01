<?php require_once('comunes/head.php'); ?>
<link rel="stylesheet" type="text/css" href="css/pagos.css">

<body class="g-light">
  <?php require_once('comunes/carga.php'); ?>
  <?php require_once('comunes/menu.php'); ?>
  <div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
	<?php require_once('comunes/cabecera_modulos.php'); ?>
	<div>
	  <span class="text-center h1 fw-bold mb-0 text-wrap d-block">Conjunto Residencial José María Vargas</span>
	  <small class="h5 text-muted my-3 pb-3 text-center d-block">Consulta de Pagos</small>
	  </hr>
	</div>
	<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
	  <li class="nav-item">
		<a class="nav-link active" id="pagos-tab" data-toggle="tab" href="#pagos" role="tab" aria-controls="pagos" aria-selected="true">Todos los pagos</a>
	  </li>
	  <li class="nav-item">
		<a class="nav-link" id="pendiente-tab" data-toggle="tab" href="#pendiente" role="tab" aria-controls="pendiente" aria-selected="false">Pendientes</a>
	  </li>
	  <li class="nav-item">
		<a class="nav-link" id="confirmados-tab" data-toggle="tab" href="#confirmados" role="tab" aria-controls="confirmados" aria-selected="false">Confirmados</a>
	  </li>
	  <li class="nav-item">
		<a class="nav-link" id="declinados-tab" data-toggle="tab" href="#declinados" role="tab" aria-controls="declinados" aria-selected="false">Declinados</a>
	  </li>
	</ul>
	<div class="tab-content" id="myTabContent">
	  <div class="tab-pane fade show active" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
		<div class="table-responsive">
		  <table class="table table-striped" id="tablapagos">
			<thead>
			  <tr>
				<th scope="col" class="text-info">Nº pago</th>
				<th scope="col" class="text-info">Apto</th>
				<th scope="col" class="text-info d-none d-md-table-cell">Torre</th>
				<th scope="col" class="text-info">Fecha</th>
				<th scope="col" class="text-info">Monto</th>
				<th scope="col" class="text-info">Estado</th>
				<th scope="col" class="text-info text-center">Acción</th>
			  </tr>
			</thead>
			<tbody id="listadopagos">
			</tbody>
		  </table>
		</div>
	  </div>
	  <div class="tab-pane fade" id="pendiente" role="tabpanel" aria-labelledby="pendiente-tab">
		<div class="table-responsive">
		  <table class="table table-striped" id="tablapendiente">
			<thead>
			  <tr>
				<th scope="col" class="text-info">Nº pago</th>
				<th scope="col" class="text-info">Apto</th>
				<th scope="col" class="text-info">Torre</th>
				<th scope="col" class="text-info">Fecha</th>
				<th scope="col" class="text-info">Monto</th>
				<th scope="col" class="text-info">Estado</th>
				<th scope="col" class="text-info text-center">Acción</th>
			  </tr>
			</thead>
			<tbody id="listadopagospendientes">
			</tbody>
		  </table>
		</div>
	  </div>
	  <div class="tab-pane fade" id="confirmados" role="tabpanel" aria-labelledby="confirmados-tab">
		<div class="table-responsive">
		  <table class="table table-striped" id="tablaconfirmado">
			<thead>
			  <tr>
				<th scope="col" class="text-info">Nº pago</th>
				<th scope="col" class="text-info">Apto</th>
				<th scope="col" class="text-info">Torre</th>
				<th scope="col" class="text-info">Fecha</th>
				<th scope="col" class="text-info">Monto</th>
				<th scope="col" class="text-info">Estado</th>
				<th scope="col" class="text-info text-center">Acción</th>
			  </tr>
			</thead>
			<tbody id="listadopagosconfirmados">
			</tbody>
		  </table>
		</div>
	  </div>
	  <div class="tab-pane fade" id="declinados" role="tabpanel" aria-labelledby="declinados-tab">
		<div class="table-responsive">
		  <table class="table table-striped" id="tabladeclinado">
			<thead>
			  <tr>
				<th scope="col" class="text-info">Nº pago</th>
				<th scope="col" class="text-info">Apto</th>
				<th scope="col" class="text-info">Torre</th>
				<th scope="col" class="text-info">Fecha</th>
				<th scope="col" class="text-info">Monto</th>
				<th scope="col" class="text-info">Estado</th>
				<th scope="col" class="text-info text-center">Acción</th>
			  </tr>
			</thead>
			<tbody id="listadopagosdeclinados">
			</tbody>
		  </table>
		</div>
	  </div>
	</div>
  </div>
  </div>
  <div class="modal fade" tabindex="-1" role="dialog" id="modaldetalles">
	<div class="modal-dialog modal-lg" role="document">
	  <div class="modal-content">
		<div class="modal-header text-light bg-info">
		  <h5 class="modal-title" id="titulomodal">Detalles del pago</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="table-responsive">
			<table class="table table-striped mb-0" id="tabla_detallespago">
				<tbody class="w-100" id="detallespago">
					<tr class="text-center">
						<td>
							<span class="font-weight-bold">Apartamento</span>
							<span class="d-block">${deuda.apartamento}</span>
						</td>
						<td>
							<span class="font-weight-bold">torre</span>
							<span class="d-block">${deuda.torre}</span>
						</td>
						<td>
							<span class="font-weight-bold">piso</span>
							<span class="d-block">${deuda.piso}</span>
						</td>
						<td>
							<span class="font-weight-bold">propietario</span>
							<span class="d-block">${deuda.propietario}</span>
						</td>
					</tr>
					<tr class="text-center">
						<td colspan="1">
							<span class="font-weight-bold">Fecha de Deuda</span>
							<span class="d-block">${deuda.fecha_generada }</span>
						</td>
						<td colspan="2">
							<span class="font-weight-bold">Concepto</span>
							<span class="d-block">${deuda.concepto }</span>
						</td>
						<td colspan="1">
							<span class="font-weight-bold">Monto Total</span>
							<span class="d-block" title="${deuda.total_divisa}$">${deuda.total_bs }Bs</span>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="h4 text-center text-info">Resumen de Deuda</div>
			<div class="table-responsive px-5">
				<table class="table table-striped mb-0 table-sm" id="tabla_resumen_deuda">
					<thead>
						<tr class="text-center">
							<th >Concepto</th>
							<th >Monto $</th>
							<th >Monto Bs</th>
						</tr>
					</thead>
					<tbody class="w-100" id="detallespago_resumen_deuda">
					</tbody>
				</table>
			</div>
			<div class="h4 text-center text-info">Resumen de Pago</div>
			<div class="table-responsive px-5">
				<table class="table table-striped mb-0 table-sm" id="tabla_resumen_pagos">
					<thead>
						<tr class="text-center">
							<th>Fecha</th>
							<th>Tipo de pago</th>
							<th>Monto</th>
						</tr>
					</thead>
					<tbody class="w-100" id="detallespago_resumen_pagos">
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
  <?php require_once('comunes/modalconfirmacion.php'); ?>
  <input type="text" style='display:none;' id="id">
  <input type="text" style='display:none;' id="accion">
  <?php require_once('comunes/modal.php'); ?>
  <script src="js/carga.js"></script>
  <script src="js/pagos.js"></script>
  <script src="js/comun_x.js"></script>
  <?php require_once('comunes/foot.php'); ?>
</body>

</html>