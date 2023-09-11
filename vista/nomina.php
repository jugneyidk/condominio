<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Pago nomina</h2>
			<hr />
		</div>
		<form method="post" action="" id="f">
			<div class="row">
				<div class="col">
            <input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
            <input autocomplete="off" type="text" class="form-control" name="id" id="id" style="display: none;">
            <div class="container">
            	<h3>Datos personales</h3>
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
                <hr><hr>
                <h3>Datos de pago</h3>
                <div class="row mb-3">
                    <div class="col-12 col-md-6">
                        <label for="descripcion">Descripción:</label>
                        <input autocomplete="off" class="form-control" type="text" id="descripcion" name="descripcion" />
                        <span id="sdescripcion" class="text-danger"></span>
                    </div>
                    <div class="col-6 col-md-3">
						<label for="metodo">Metodo de pago</label>
						<select class="form-control" id="piso" name="piso">
							<option value='' disabled selected>-</option>
							<option value='pagomovil'>Pago movil</option>
							<option value='transferencia'>transferencia</option>
							<option value='dolar'>Dolar fisico</option>
							<option value='zelle'>Zelle</option>
						</select>
						<span id="smetodo" class="text-danger"></span>
					</div>
                </div>

 		<div class="row mb-3">
 			<div class="col-12 col-md-6 mt-3 mt-md-0">
 				<label for="monto">Monto:</label>
                        <input autocomplete="off" class="form-control" type="text" id="monto" name="monto" />
                        <span id="smonto" class="text-danger"></span>
 				</div>
                        <div class="col-10 col-md-6 mt-3 mt-md-0">
 				<label for="referencia">Referencia:</label>
                        <input autocomplete="off" class="form-control" type="text" id="referencia" name="referencia" />
                        <span id="sreferencia" class="text-danger"></span>
 					</div>
                    </div>
		 </div>
  
		 <div class="row justify-content-center">
			
					<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
						<button type="button" class="btn btn-primary w-100 small-width" id="consultar" data-toggle="modal" data-target="#modalapartamentos" name="consultar">PAGADO<span class="fa fa-plus-circle ml-2"></span></button>
					</div>
			
					<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
						<button type="button" class="btn btn-danger w-100 small-width" id="modificar" name="modificar" >CANCELAR<span class="fa fa-trash ml-2"></span></button>
					</div>
				
			</div>
            </div>

    </form>
					<hr />
				</div>
			</div>
			
	</div>

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


	<?php require_once('comunes/foot.php'); ?>
</body>

</html>