<?php require_once('comunes/head.php'); ?>
<body class="bg-light">

<?php require_once('comunes/menu.php'); ?>
<div class="container bg-white p-5">
	<div>
		<h2 class="text-center h2 text-primary">Gastos</h2>
	<hr/>
	</div>	
	<form method="post" action="" id="f">
	<div class="container">	
		<div class="row">	
		<div class="col-md-4 form-group">
			   <label class="d-block" for="num_recibo">Número de recibo</label>
			   <div class="input-group">
			   		<input disabled class="form-control" type="text" id="num_recibo" name="num_recibo" />
					</span>
				</div>
			</div>		
			<div class="col">
				<label for="fecha">Fecha</label>
				<input class="form-control" type="date" id="fecha" name="fecha" />
			</div>	
		</div>		
		<div class="row">			
			<div class="col form-group">
			   <label class="d-block" for="tipo_gasto">Tipo de gasto</label>
			   <div class="input-group">
			   		<input disabled class="form-control" type="text" id="tipo_gasto" name="tipo_gasto" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-primary" id="seleccionarTipoGasto" 
				   data-toggle="modal" data-target="#modalTipoGastos" name="seleccionarTipoGasto">Seleccionar...</button>
					</span>
				</div>
			</div>	
			<div class="col">
				<label for="total">Gasto Total</label>
				<input class="form-control" type="text" id="total" name="total" />
			</div>		
		</div>		
		<div class="row">
			<div class="col">
				<hr/>
			</div>
		</div>
	
		<div class="row">
			<div class="col">
				   <button type="button" class="btn btn-primary" id="incluir" name="incluir">INCLUIR</button>
			</div>
			<div class="col">	
				   <button type="button" class="btn btn-primary" id="consultar" 
				   data-toggle="modal" data-target="#modal1" name="consultar">CONSULTAR</button>
			</div>
			<div class="col">	
				   <button type="button" class="btn btn-primary" id="modificar" name="modificar">MODIFICAR</button>
			</div>
			<div class="col">	
				   <button type="button" class="btn btn-primary" id="eliminar" name="eliminar">ELIMINAR</button>
			</div>
			<div class="col">	
				   <a href="." class="btn btn-primary">REGRESAR</a>
			</div>
		</div>
	</div>
	</form>
	</div> 
	<div class="modal fade" tabindex="-1" role="dialog"  id="modalTipoGastos">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-header text-light bg-info">
			<h5 class="modal-title">Seleccionar Tipo de Gasto</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
			  <span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-content">
			<table class="table table-striped table-hover">
			<thead>
			  <tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Observacion</th>
				<th>Presupuesto</th>
				<th>Nómina</th>
			  </tr>
			</thead>
			<tbody>
			 
			</tbody>
			</table>
		</div>
		<div class="modal-footer bg-light">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		</div>
	  </div>
	</div>
	<script src="js/departamentos.js"></script>
	<?php require_once('comunes/foot.php'); ?>
</body>
</html>