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
								<label for="servicio">Servicio</label>
								<select class="form-control" id="service" name="service" data-span="sservicio">
									<option value='' disabled selected>-</option>
									<option value='corpoelec'>Corpoelec</option>
									<option value='corpoelec'>CANTV</option>
									<option value='otro'>Otro</option>
								</select>
								<span id="sservicio" class="text-danger"></span>
							</div>
							<div class="col-12 col-md-5">
								<label for="descripcion">Descripción:</label>
								<input autocomplete="off" class="form-control" type="text" id="descripcion" name="descripcion" data-span="sdescripcion"/>
								<span id="sdescripcion" class="text-danger"></span>
							</div>

						</div>

						<div class="row mb-3">
							<div class="col-12 col-md-3 mt-3 mt-md-0">
								<label for="monto">Monto:</label>
								<input autocomplete="off" class="form-control text-right" type="text" id="monto" name="monto" data-span="smonto"/>
								<span id="smonto" class="text-danger"></span>
							</div>
							<div class="col-10 col-md-4 mt-3 mt-md-0">
								<label for="referencia">Referencia:</label>
								<input autocomplete="off" class="form-control" type="text" id="referencia" name="referencia" data-span="sreferencia"/>
								<span id="sreferencia" class="text-danger"></span>
							</div>
							<div class="col-6 col-md-3">
								<label for="descripcion">Fecha:</label>
								<input autocomplete="off" class="form-control" type="date" id="fecha" name="fecha" data-span="sfecha"/>
								<span id="sfecha" class="text-danger"></span>
							</div>
						</div>
					</div>
					<hr>
					<div class="row justify-content-center">
						<?php //if ($permisos[2] == 1): ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-primary w-100 small-width" id="incluir" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
							</div>
						<?php //endif; ?>
						<?php //if ($permisos[3] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-info w-100 small-width" id="consultar" data-toggle="modal" data-target="#modalEstacionamiento" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
							</div>
						<?php// endif; ?>
						<?php //if ($permisos[4] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-warning w-100 small-width" id="modificar" name="modificar" disabled>MODIFICAR<span class="fa fa-pencil-square-o ml-2"></span></button>
							</div>
						<?php// endif; ?>
						<?php// if ($permisos[5] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-danger w-100 small-width" id="eliminar" name="eliminar" disabled>ELIMINAR<span class="fa fa-trash ml-2"></span></button>
							</div>
						<?php //endif; ?>
					</div>
				</div>

		</form>
	</div>
	</div>
	<script src="js/carga.js"></script>
	<script src="js/comun_x.js"></script>
  	<!-- <script src="js/estacionamiento.js"></script> -->
  	<script>
  		$(document).ready(function(){
  			
  			borrar();
			eventoKeypress(document.getElementById('descripcion'),/^[0-9a-zA-Z\s]*$/);
			eventoKeypress(document.getElementById('monto'),/^[0-9]*$/);
			eventoKeypress(document.getElementById('referencia'),/^[0-9]*$/);
			eventoKeyup(document.getElementById('monto'), montoExp, "Ingrese un monto Valido", undefined, function(elem){
				elem.value = sepMiles(elem.value);
			});
			eventoKeyup(document.getElementById('descripcion'), /^[0-9a-zA-Z\s]{0,255}$/, "ingrese una descripción valida solo se permiten letras y números");
			document.getElementById('descripcion').maxLength = 255;
			eventoKeyup(document.getElementById('referencia'), /^[0-9]{0,30}$/, "Ingrese una referencia valida, solo se permiten números");
			document.getElementById('referencia').maxLength = 30;
			eventoKeyup(document.getElementById('fecha'), fechaExp, "Ingrese una fecha valida",undefined,function(elem){console.log(elem.value);});
			$("#fecha").on("change",function(e){
				validarKeyUp(fechaExp, $("#fecha"), "Ingrese una fecha valida");
			});

			$("#incluir").on("click", function () {
				if (validarEnvioServicios()) {
					$("#accion").val("incluir");
					var datos = new FormData($("#f")[0]);
					enviaAjax(datos,function(respuesta){

						console.log(respuesta);
						var lee = JSON.parse(respuesta);
						muestraMensaje(lee.mensaje, "", "success");
						borrar();

					});
				}
			});
  		});

  		function validarEnvioServicios(){
  			

			if(!validarKeyUp(montoExp,$("#monto"),"Ingrese un monto Valido")){// valido numero de estacionamiento
				muestraMensaje("ERROR", "Ingrese un monto Valido","error");
				return false;
			}
			if(!validarKeyUp(/^[0-9a-zA-Z\s]{0,255}$/,$("#descripcion"),"ingrese una descripción valida solo se permiten letras y números")){
				muestraMensaje("ERROR", "ingrese una descripción valida solo se permiten letras y números","error");
				return false;   
			}
			if(!validarKeyUp(/^[0-9]{1,30}$/,$("#referencia"),"Ingrese una referencia valida, solo se permiten números")){
				muestraMensaje("ERROR", "Ingrese una referencia valida, solo se permiten números", "error");
				return false;

			}if(!validarKeyUp(fechaExp,$("#fecha"),"Ingrese una fecha valida")){
				muestraMensaje("ERROR", "Ingrese una fecha valida", "error");
				return false;
			}
			if($("#service").val()==""){
				muestraMensaje("ERROR", "Seleccione un servicio", "error");
				return false;
			}


			vaciarSpanError();
			return true;
			

  		}
  	</script>
	<?php require_once('comunes/foot.php'); ?>
</body>
