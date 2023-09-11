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

<form method="post" action="" id="f">
			<div class="row">
				<div class="col">
            <input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
            <input autocomplete="off" type="text" class="form-control" name="id" id="id" style="display: none;">
            <div class="container">
            
        
                <div class="row mb-3">
                	<div class="col-6 col-md-5">
						<label for="metodo">Servicio</label>
						<select class="form-control" id="service" name="service">
							<option value='' disabled selected>-</option>
							<option value='corpoelec'>Corpoelec</option>
							<option value='otro'>Otro</option>
						</select>
						<span id="smetodo" class="text-danger"></span>
					</div>
                    <div class="col-12 col-md-5">
                        <label for="descripcion">Descripción:</label>
                        <input autocomplete="off" class="form-control" type="text" id="descripcion" name="descripcion" />
                        <span id="sdescripcion" class="text-danger"></span>
                    </div>
                    
                </div>

 		<div class="row mb-3">
 			<div class="col-12 col-md-3 mt-3 mt-md-0">
 				<label for="monto">Monto:</label>
                        <input autocomplete="off" class="form-control" type="text" id="monto" name="monto" />
                        <span id="smonto" class="text-danger"></span>
 				</div>
                        <div class="col-10 col-md-4 mt-3 mt-md-0">
 				<label for="referencia">Referencia:</label>
                        <input autocomplete="off" class="form-control" type="text" id="referencia" name="referencia" />
                        <span id="sreferencia" class="text-danger"></span>
 					</div>
 					<div class="col-6 col-md-3">
                        <label for="descripcion">Fecha:</label>
                        <input autocomplete="off" class="form-control" type="date" id="fecha" name="fecha" />
                        <span id="sfecha" class="text-danger"></span>
                    </div>
                    </div>
		 </div>
  <hr>
		 <div class="row justify-content-center">
			
					<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
						<button type="button" class="btn btn-primary w-100 small-width" id="pagado" data-toggle="modal" data-target="#modalapartamentos" name="consultar">PAGADO<span class="fa fa-plus-circle ml-2"></span></button>
					</div>
			
					<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
						<button type="button" class="btn btn-danger w-100 small-width" id="cancelar" name="cancelar" >CANCELAR<span class="fa fa-trash ml-2"></span></button>
					</div>
				
			</div>
            </div>

    </form>


</div>
	</div>
	<?php require_once('comunes/foot.php'); ?>
</body>
