
<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>

		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Crear aviso</h2>
			<hr />
		</div>

		<form method="post" action="" id="f"> 
			<div class="row">
				<div class="col">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
					<input autocomplete="off" type="text" class="form-control" name="id" id="id" style="display: none;">
					<div class="container">
						<div class="row mb-3 justify-content-center">
							<div class="col-12 col-md-9">
								
								<label for="titulo">Titulo:</label>
								<input autocomplete="off" class="form-control" type="text" id="titulo" name="titulo" data-span="stitulo"/>
								<span id="stitulo" class="text-danger"></span>
							</div>
							<div class="col-12 col-md-9">
								<label for="descripcion">Descripción:</label>
								<textarea class="form-control text-justify" id="descripcion" name="descripcion" data-span="sdescripcion" style="resize: vertical;min-height: 120px"></textarea>
								<span id="sdescripcion" class="text-danger"></span>
							</div>

						</div>

						<div class="row mb-3 justify-content-around">
							<div class="col-6 col-md-2">
								<label for="descripcion">Desde:</label>
								<input autocomplete="off" class="form-control" type="date" id="fecha" name="fecha" data-span="sfecha"/>
								<span id="sfecha" class="text-danger"></span>
							</div>
							<div class="col-6 col-md-2">
								<label for="descripcion">Hasta:</label>
								<input autocomplete="off" class="form-control" type="date" id="fecha2" name="fecha2" data-span="sfecha2"/>
								<span id="sfecha2" class="text-danger"></span>
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
								<button type="button" class="btn btn-info w-100 small-width" id="consultar" data-toggle="modal" data-target="#modalAvisos" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
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


	<div class="modal fade" tabindex="-1" role="dialog" id="modalAvisos">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Realizados</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover rows-pointer" id="tablaAvisos">
						<thead>
							<tr>
								<th>N</th>
								<th>Titulo</th>
								<th>Descripción</th>
								<th>Desde</th>
								<th>Hasta</th>
							</tr>
						</thead>
						<tbody id="listadoAvisos">

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
	<script src="js/comun_x.js"></script>
	<script>
		$(document).ready(function(){
			borrar();
			loadAvisos();

			eventoKeypress(document.getElementById('titulo'),/^[0-9a-zA-Z\s:-?¿!¡/,.+\-()]*$/);
			// eventoKeypress(document.getElementById('descripcion'),/^[0-9a-zA-Z\s]*$/);
			eventoKeypress(document.getElementById('fecha'),/^[0-9]*$/);
			eventoKeypress(document.getElementById('fecha2'),/^[0-9]*$/);

			eventoKeyup(document.getElementById('titulo'),/^[0-9a-zA-Z\s-]{1,80}$/,"ingrese un titulo valido, solo se permiten letras, números y los caracteres (:-?¿!¡/,.+-), no puede estar el campo vació");//-----------
			$("#titulo").on("change",function(e){
				validarKeyUp(/^[0-9a-zA-Z\s:-?¿!¡/,.+\-()]{1,80}$/,$(this),"ingrese un titulo valido, solo se permiten letras, números y los caracteres (:-?¿!¡/,.+-), no puede estar el campo vació");
			});
			document.getElementById('titulo').maxLenth = 80;
			document.getElementById('descripcion').maxLenth = 30000;

			eventoKeyup(document.getElementById('fecha'), fechaExp, "Ingrese una fecha valida");
			$("#fecha").on("change",function(e){
				validarKeyUp(fechaExp, $("#fecha"), "Ingrese una fecha valida");
			});
			eventoKeyup(document.getElementById('fecha2'), fechaExp, "Ingrese una fecha valida");
			$("#fecha2").on("change",function(e){
				validarKeyUp(fechaExp, $("#fecha2"), "Ingrese una fecha valida");
			});

			$("#incluir").on("click", function () {
				if (validarAviso()) {
					$("#accion").val("incluir");
					//$("#descripcion").val( removeSpace($("#descripcion").val()) );
					$("#titulo").val( removeSpace($("#titulo").val()) );

					var datos = new FormData($("#f")[0]);
					enviaAjax(datos,function(respuesta){

						var lee = JSON.parse(respuesta);
						if(lee.resultado == "incluir"){
							borrar();
							loadAvisos();
							muestraMensaje(lee.mensaje, "", "success");

						}
						else if(lee.resultado == "error_no_borrar"){
							muestraMensaje("ERROR", lee.mensaje,"error");
							loadAvisos();
						}
						else{
							muestraMensaje("ERROR", lee.mensaje,"error");
							loadAvisos();
							borrar();
						}

					});
				}
			});

			$("#modificar").on("click", function () {
				if (validarAviso()) {
					if($("#id").val()==''){
						muestraMensaje("ERROR","Debe seleccionar un aviso","error");
						return false;
					}
					Swal.fire({
						title: "¿Estás Seguro?",
						text: "¿Está seguro que desea modificar el aviso?",
						showCancelButton: true,
						confirmButtonText: "Modificar",
						confirmButtonColor: "#007bff",
						cancelButtonText: `Cancelar`,
						icon: "warning",
					}).then((result) => {
						if (result.isConfirmed) {
							$("#accion").val("modificar");
							var datos = new FormData($("#f")[0]);
							enviaAjax(datos,function(respuesta){
								var lee = JSON.parse(respuesta);
								if(lee.resultado == "modificar"){
									borrar();
									loadAvisos();
									muestraMensaje(lee.mensaje, "", "success");

								}
								else if(lee.resultado == "error_no_borrar"){
									muestraMensaje("ERROR", lee.mensaje,"error");
									loadAvisos();
								}
								else{
									muestraMensaje("ERROR", lee.mensaje,"error");
									loadAvisos();
									borrar();
								}
							});
						}
					});
				}
			});


			$("#eliminar").on("click", function () {
				if($("#id").val()==''){
					muestraMensaje("ERROR","Debe seleccionar un aviso","error");
					return false;
				}
				Swal.fire({
					title: "¿Estás Seguro?",
					text: "¿Está seguro que desea eliminar el pago de Servicios?",
					showCancelButton: true,
					confirmButtonText: "Eliminar",
					confirmButtonColor: "#dc3545",
					cancelButtonText: `Cancelar`,
					icon: "warning",
				}).then((result) => {
					if (result.isConfirmed) {
						$("#accion").val("eliminar");
						var datos = new FormData($("#f")[0]);
						enviaAjax(datos,function(respuesta){
							var lee = JSON.parse(respuesta);
							if(lee.resultado == "eliminar"){
								borrar();
								loadAvisos();
								muestraMensaje(lee.mensaje, "", "success");

							}
							else{
								muestraMensaje("ERROR", lee.mensaje,"error");
								loadAvisos();
								borrar();
							}
						});
					}
				});
			});

			rowsEvent("listadoAvisos",function(e){
				$("#id").val($(e).find("td").eq(1).text());
				$("#titulo").val($(e).find("td").eq(2).text());
				$("#descripcion").val($(e).find("td").eq(3).text());
				$("#fecha").val($(e).find("td").eq(4).text().replace(/^(\d\d).(\d\d).(\d\d\d\d)$/,"$3-$2-$1"));
				$("#fecha2").val($(e).find("td").eq(5).text().replace(/^(\d\d).(\d\d).(\d\d\d\d)$/,"$3-$2-$1"));
				$("#modalAvisos").modal("hide");
				cambiarbotones(false);
			});

			$("#limpiar").on("click",function(){
				borrar();
			});		
		});
		function loadAvisos(){
			var datos = new FormData();
			datos.append("accion", "listaAvisos");

			enviaAjax(datos,function(respuesta){
				var lee = JSON.parse(respuesta);
				if (lee.resultado == "listaAvisos") {
					if ($.fn.DataTable.isDataTable("#tablaAvisos")) {
						$("#tablaAvisos").DataTable().destroy();
					}
					$("#listadoAvisos").html(lee.mensaje);
					if (!$.fn.DataTable.isDataTable("#tablaAvisos")) {
						$("#tablaAvisos").DataTable({
							language: {
								lengthMenu: "Mostrar _MENU_ por página",
								zeroRecords: "No se encontraron registros de Avisos",
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
							{ data: "col6" }
							]
						});
					}
				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
				}

			});
		}
		function validarAviso(){

			$("#descripcion").val( removeSpace($("#descripcion").val()) );
			if($("#descripcion").text()!=''){
				muestraMensaje("ERROR", "La descripción no puede estar vacío","error");
				return false;   
			}
			if(!validarKeyUp(/^[0-9a-zA-Z\s:-?¿!¡/,.+\-()]{1,80}$/,$("#titulo"),"ingrese un titulo valido solo se permiten letras y números")){
				muestraMensaje("ERROR", "ingrese un titulo valido, solo se permiten letras, números y los caracteres (:-?¿!¡/,.+-)","error");
				return false;   
			}
			if(!validarKeyUp(fechaExp,$("#fecha"),"Ingrese una fecha valida")){
				muestraMensaje("ERROR", "Ingrese una fecha valida", "error");
				return false;
			}
			if(!validarKeyUp(fechaExp,$("#fecha2"),"Ingrese una fecha valida")){
				muestraMensaje("ERROR", "Ingrese una fecha valida", "error");
				return false;
			}
			if(Date.parse($("#fecha2").val()) <= Date.parse($("#fecha").val())){
				muestraMensaje("ERROR", 'La fecha "Desde" debe ser anterior o igual a la fecha "Hasta"', "error");
				return false; 
			}


			vaciarSpanError();
			return true;


		}
		function borrar(func) {
			$("form input").val("");
			$("form select").val("");
			$("form textarea").val("");
			limpiarvalidacion();
			cambiarbotones();
		}
		function cambiarbotones(parametro=true) {
			$("#modificar").prop("disabled", parametro);
			$("#eliminar").prop("disabled", parametro);
			$("#incluir").prop("disabled", !parametro);
		}
		function limpiarvalidacion() {
			$("form input").removeClass("is-valid");
			$("form input").removeClass("is-invalid");
			$("form select").removeClass("is-valid");
			$("form select").removeClass("is-invalid");
		}


	</script>


<?php require_once('comunes/foot.php'); ?>

</body>
</html>