<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Pago nomina</h2> 
			<h1 style="color: red">cambiar el decimal del empleado en el salario</h1>
			<hr />
		</div>
		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<a class="nav-item nav-link active" id="nav-pagos-servicios-tab" data-toggle="tab" href="#nav-pagos-servicios" role="tab" aria-controls="nav-pagos-servicios" aria-selected="true">Pagos</a>
				<a class="nav-item nav-link" id="nav-servicios-tab" data-toggle="tab" href="#nav-servicios" role="tab" aria-controls="nav-servicios" aria-selected="false">Empleados</a>
			</div>
		</nav>
		<br>
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active" id="nav-pagos-servicios" role="tabpanel" aria-labelledby="nav-pagos-servicios-tab">
				<form method="post" action="" id="f"> 
					<div class="row">
						<div class="col">
							<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
							<input autocomplete="off" type="text" class="form-control" name="id" id="id" style="display: none;">
							<div class="container">
								<div class="row">
									<div class="col">
										<div class="container">
											<h3>Datos personales</h3>

											<div class="col-sm-12 col-md-5 col-xl-6">
												<label class="d-block" for="propietario">Empleado</label>
												<div class="input-group">
													<input class="form-control" type="text" id="pago_empleado_info" placeholder="- Sin SELECCIONAR -" name="pago_empleado_info" readonly data-span="sSelecApartamento"/>
													<input class="form-control" type="text" id="pago_empleado_id" name="pago_empleado_id" style="display:none" data-span="sSelecApartamento"/>
													<button type="button" class="btn btn-primary" id="btn_listaempleado">SELECCIONAR</button>
												</div>
												<span id="s_select_empleado" class="text-danger"></span>
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
											<!-- <div class="col-6 col-md-3">
												<label for="metodo">Metodo de pago</label>
												<select class="form-control" id="metodo" name="metodo">
													<option value='' disabled selected>-</option>
													<option value='pagomovil'>Pago movil</option>
													<option value='transferencia'>transferencia</option>
													<option value='dolar'>Dolar fisico</option>
													<option value='zelle'>Zelle</option>
												</select>
												<span id="smetodo" class="text-danger"></span>
											</div>
											<div class="col-6 col-md-3">
												<label for="descripcion">Fecha:</label>
												<input autocomplete="off" class="form-control" type="date" id="fecha" name="fecha" />
												<span id="sfecha" class="text-danger"></span>
											</div> -->
										</div>

										<?php require_once("comunes/tipo_pago.php"); ?>

										<!-- <div class="row mb-3">
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
										</div> -->
									</div>
								</div>
							</div>
							<hr>
							<div class="row justify-content-center">
								<?php if ($permisos[2] == 1): ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-primary w-100 small-width" id="incluir" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
									</div>
								<?php endif; ?>
								<?php if ($permisos[3] == 1) : ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-info w-100 small-width" id="consultar" data-toggle="modal" data-target="#modalservicios" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
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
					</div>
				</form>
			</div>
			<div class="tab-pane fade show" id="nav-servicios" role="tabpanel" aria-labelledby="nav-servicios-tab">
				<form method="POST" action="" id="f2">
					<div class="row">
						<div class="col">
							<input autocomplete="off" type="text" class="form-control" name="accion" id="accion_2" style="display: none;">
							<input autocomplete="off" type="text" class="form-control" name="id" id="id_2" style="display: none;">
							<div class="container">

								<div class="row mb-3">
									<div class="col-12 col-md-4 mt-3 mt-md-0">
										<label for="cedula_rif">Cedula/RIF</label>
										<div class="input-group">
											<select name="tipo_identificacion" id="tipo_identificacion" class="form-control dont-erase" style="max-width: 60px" data-span="invalid-span-cedula_rif">
												<option value="0">V</option>
												<option value="1">E</option>
												<option value="2">J</option>
												<option value="3">G</option>
											</select>

											<div class="input-group-append" style="flex: 1">
												<input autocomplete="off" type="text" class="form-control" id="cedula_rif" name="cedula_rif" data-span="invalid-span-cedula_rif">
											</div>
										</div>
										<span id="invalid-span-cedula_rif" class="invalid-span text-danger"></span>
										
									</div>
									<div class="col-12 col-md-4 mt-3 mt-md-0">
										<label for="nombres">Nombres</label>
										<input autocomplete="off" type="text" class="form-control" id="nombres" name="nombres" data-span="invalid-span-nombres">
										<span id="invalid-span-nombres" class="invalid-span text-danger"></span>
									</div>
									<div class="col mt-3 mt-md-0">
										<label for="apellidos">Apellidos</label>
										<input autocomplete="off" type="text" class="form-control" id="apellidos" name="apellidos" data-span="invalid-span-apellidos">
										<span id="invalid-span-apellidos" class="invalid-span text-danger"></span>
									</div>
								</div>

								<div class="row mb-4">

									<div class="col-md-3 col-6">
										<label for="salario">Salario</label>
										<div class="input-group">
											<input autocomplete="off" type="text" class="form-control text-right" placeholder="0,00" id="salario" name="salario" data-span="invalid-span-salario">
											<div class="input-group-append"><span class="input-group-text">$</span></div>
										</div>
										<span id="invalid-span-salario" class="invalid-span text-danger"></span>
									</div>
									<div class="col-6 col-md-3">
										<label for="fechac">Fecha Contratación</label>
										<input autocomplete="off" type="date" class="form-control" id="fechac" name="fechac" data-span="invalid-span-fechac">
										<span id="invalid-span-fechac" class="invalid-span text-danger"></span>
									</div>
									<div class="col-md-6">
										<label for="domicilio_fiscal">Domicilio Fiscal</label>
										<input autocomplete="off" type="text" class="form-control" id="domicilio_fiscal" name="domicilio_fiscal" data-span="invalid-span-domicilio_fiscal">
										<span id="invalid-span-domicilio_fiscal" class="invalid-span text-danger"></span>
									</div>

								</div>
								<div class="row mb-3">
									<div class="col-12 col-md-4">
										<label for="telefono">Teléfono</label>
										<input autocomplete="off" type="tel" class="form-control" id="telefono" name="telefono" data-span="invalid-span-telefono">
										<span id="invalid-span-telefono" class="invalid-span text-danger"></span>
									</div>
									<div class="col-12 col-md-4 mt-3 mt-md-0">
										<label for="correo">Correo Electrónico</label>
										<input autocomplete="off" type="text" class="form-control" id="correo" name="correo" data-span="invalid-span-correo">
										<span id="invalid-span-correo" class="invalid-span text-danger"></span>
									</div>
									<div class="col-12 col-md-4 mt-3 mt-md-0">
										<label for="cargo">Cargo</label>
										<input autocomplete="off" type="text" class="form-control" id="cargo" name="cargo" data-span="invalid-span-cargo">
										<span id="invalid-span-cargo" class="invalid-span text-danger"></span>
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-6 col-md-3">
										<label for="fechan">Fecha Nacimiento</label>
										<input type="date" class="form-control" id="fechan" name="fechan" data-span="invalid-span-fechan">
										<span id="invalid-span-fechan" class="invalid-span text-danger"></span>
									</div>
									<div class="col-6 col-md-5">
										<label for="estado_civil">Estado Civil</label>
										<select class="form-control" id="estado_civil" name="estado_civil" data-span="invalid-span-estado_civil">
											<option value='' disabled selected>-</option>
											<option value='Soltero'>Soltero</option>
											<option value='Casado'>Casado</option>
											<option value='Divorciado'>Divorciado</option>
											<option value='Viudo'>Viudo</option>
										</select>
										<span id="invalid-span-estado_civil" class="invalid-span text-danger"></span>
									</div>
								</div>

							</div>
							<hr>
							<div class="row justify-content-center">
								<?php if ($permisos[2] == 1): ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-primary w-100 small-width" id="incluir_2" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
									</div>
								<?php endif; ?>
								<?php if ($permisos[3] == 1) : ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-info w-100 small-width" id="consultar_2" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
									</div>
								<?php endif; ?>
								<?php if ($permisos[4] == 1) : ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-warning w-100 small-width" id="modificar_2" name="modificar" disabled>MODIFICAR<span class="fa fa-pencil-square-o ml-2"></span></button>
									</div>
								<?php endif; ?>
								<?php if ($permisos[5] == 1) : ?>
									<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
										<button type="button" class="btn btn-danger w-100 small-width" id="eliminar_2" name="eliminar" disabled>ELIMINAR<span class="fa fa-trash ml-2"></span></button>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>

				</form>
			</div>
		</div>

	</div>

	<!-- modal-->
	<div class="modal fade" tabindex="-1" role="dialog" id="modalEmpleados">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Realizados</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="tablaEmpleados">
						<thead>
							<tr>
								<th>Cedula</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th>Salario.</th>
								<th>Fecha de contrato</th>
								<th>Domicilio</th>
								<th>Teléfono</th>
								<th>Correo</th>
								<th>Cargo</th>
								<th>Fecha Nacim</th>
								<th>Estado Civil</th>
							</tr>
						</thead>
						<tbody id="listadoEmpleados">

						</tbody>
					</table>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
alejandro gonzales pedro de leon

	<script src="js/carga.js"></script>
	<script src="js/comun_x.js"></script>
	<script>

	$(document).ready(function (){

		document.getElementById('nav-servicios-tab').click();
		eventosValidaciones("cedula_rif",10, /^(?:[0-9]{7,10})$/, /^[0-9]*$/, "Ingrese una cedula valida");
		eventoNombre("nombres",60, "1,60", "nombre");
		eventoNombre("apellidos",60, "1,60", "apellido");
		eventoMonto("salario");
		eventoAlfanumerico("domicilio_fiscal",100,"1,100", "el domicilio fiscal tiene caracteres inválidos");
		eventosValidaciones("telefono", 11, V.expTelefono, /^[0-9]*$/, "Ingrese un teléfono valido ej. 04145555555");
		eventoAlfanumerico("cargo",60,"1,60", "El cargo tiene caracteres inválidos");
		eventoKeyup("correo", V.expEmail, "El correo es invalido");
		


		



		



		//listadoEmpleados();

		load_tipo_pago_comun();

		$("#consultar_2").on("click",()=>{listadoEmpleados(true); });
		$("#consultar").on("click",()=>{listadoEmpleados(true); });



		$("#incluir").on("click",()=>{
			$("#f input, #f select").each((e,elem)=>{
				console.log('name: '+elem.name+" | value");
				objeto_tipo_pago_comun();
			});
		});

		$("#incluir_2").on("click",()=>{
			if(validar_empleado()){
				$("#accion_2").val("incluir_2");
				var datos = new FormData($("#f2")[0]);

				enviaAjax(datos,function(respuesta){
				
					var lee = JSON.parse(respuesta);
					if(lee.resultado == "incluir_2"){
						console.log(lee.mensaje);
						muestraMensaje("Registro Exitoso", lee.mensaje, "success");
						borrar();
					}
					else if(lee.resultado == "is-invalid"){
						muestraMensaje("Campo Invalido", lee.mensaje, "error");
					}
					else if(lee.resultado == "error"){
						muestraMensaje("ERROR", lee.mensaje,"error");
						console.error(lee.mensaje);
					}
					else if(lee.resultado == "console"){
						console.log(lee.mensaje);
					}
					else{
						muestraMensaje("ERROR", lee.mensaje,"error");
					}
				});









				console.log("valido el perro");
			}
			else{
				console.log("invalido el perro");
			}
			 $("#f2 input, #f2 select").each((e,elem)=>{
			 	console.log('name: '+elem.name+" | value "+elem.value);
			 });
		});


	});

	


		

		function listadoEmpleados(){
			var datos = new FormData();
			datos.append("accion", "listadoEmpleados");

			enviaAjax(datos,function(respuesta){
				var lee = JSON.parse(respuesta);
				if (lee.resultado == "listadoEmpleados") {
					if ($.fn.DataTable.isDataTable("#tablaEmpleados")) {
						$("#tablaEmpleados").DataTable().destroy();
					}
					$("#listadoEmpleados").html(lee.mensaje);
					if (!$.fn.DataTable.isDataTable("#tablaEmpleados")) {
						$("#tablaEmpleados").DataTable({
							language: {
								lengthMenu: "Mostrar _MENU_ por página",
								zeroRecords: "No se encontraron registros de Empleados",
								info: "Mostrando página _PAGE_ de _PAGES_",
								infoEmpty: "No hay registros disponibles",
								infoFiltered: "(filtrado de _MAX_ registros totales)",
								search: "Buscar:",
								paginate: {
									first: "Primera",
									last: "Última",
									next: "Siguiente",
									previous: "Anterior",
								},
							},
							autoWidth: false,
							order: [[0, "asc"]],
							columns: [
							{ data: "col1" },
							{ data: "col2" },
							{ data: "col3" },
							{ data: "col4" },
							{ data: "col5" },
							{ data: "col6" },
							{ data: "col7" },
							{ data: "col8" },
							{ data: "col9" },
							{ data: "col10" },
							{ data: "col11" },
							]
						});
					}
				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
				}

			}).then((e)=>{
				$("#modalEmpleados").modal("show");
			});
		}

		function validar_empleado(){
			function valid_exeption (mensaje,etiqueta){
				this.mensaje = mensaje;
				this.etiqueta = etiqueta;
			}


			try{
				
				$("#cargo").val( (i,value)=>{return removeSpace(value)} );
				$("#domicilio_fiscal").val( (i,value)=>{return removeSpace(value)} );
				$("#nombres").val( (i,value)=>{return removeSpace(value)} );
				$("#apellidos").val( (i,value)=>{return removeSpace(value)} );
				if(!/[0-9]{7,10}/.test($("#cedula_rif").val())){
					throw new valid_exeption("La cedula no es valida","cedula_rif");
				}
				else if(!/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{1,60}$/.test($("#nombres").val() ) ){
					throw new valid_exeption("El nombre no es valido","nombres");
				}
				else if(!/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{1,60}$/.test($("#apellidos").val() ) ){
					throw new valid_exeption("El apellido no es valido","apellidos");
				}
				else if(!V.expMonto.test($("#salario").val() )){
					throw new valid_exeption("El monto no es valido", "salario");
				}
				else if(!V.fecha($("#fechac").val())){
					throw new valid_exeption("La fecha de Contratación no es valida","fechac");
				}
				else if(!/^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{1,100}/.test($("#domicilio_fiscal").val() )){
					throw new valid_exeption("El domicilio fiscal no es valido", "domicilio_fiscal");
				}
				else if(!V.expTelefono.test($("#telefono").val() )){
					throw new valid_exeption("El teléfono no es valido", "salario");
				}
				else if(!/^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{1,60}/.test($("#cargo").val() )){
					throw new valid_exeption("El cargo no es valido", cargo);
				}
				else if(!V.expEmail.test($("#correo").val() )){
					throw new valid_exeption("El correo no es valido", "correo");
				}
				else if(!V.fecha($("#fechan").val())){
					throw new valid_exeption("La fecha de nacimiento no es valida","fechan");
				}
				else if($("#estado_civil").val() == ''){
					throw new valid_exeption("Debe seleccionar un estado civil","estado_civil");
				}

				return true;
			}
			catch (e){
				if(e instanceof valid_exeption){
					validarKeyUp(false, e.etiqueta, e.mensaje);
					muestraMensaje("Campo Invalido", e.mensaje, "error");
				}
				else{
					console.error(e);
				}
				return false;
			}

		}
		function borrar(func) {
			$("form input:not(.dont-erase)").val("");
			$("form select:not(.dont-erase)").val("");
			limpiarvalidacion();
			cambiarbotones();
			cambiarbotones_2();
		}

		function limpiarvalidacion() {
			$("form input").removeClass("is-valid");
			$("form input").removeClass("is-invalid");
			$("form select").removeClass("is-valid");
			$("form select").removeClass("is-invalid");
		}
		function cambiarbotones(parametro=true) {
			$("#modificar").prop("disabled", parametro);
			$("#eliminar").prop("disabled", parametro);
			$("#incluir").prop("disabled", !parametro);
		}

		function cambiarbotones_2(parametro=true) {
			$("#modificar_2").prop("disabled", parametro);
			$("#eliminar_2").prop("disabled", parametro);
			$("#incluir_2").prop("disabled", !parametro);
		}
	</script>
	<script type="text/javascript" src="js/tipo_pago_comun.js"></script>
	<!-- <script src="js/nomina.js"></script> -->
	<?php require_once('comunes/foot.php'); ?>
</body>

</html>