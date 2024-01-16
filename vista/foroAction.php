
<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
    <?php require_once('comunes/menu-habitante.php'); ?>
	<?//php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<div class="h5 mb-4 mt-4 mt-md-0 row justify-content-around">
		    <div class="col">
		        <a href="?p=foro-index-h" class="btn btn-secondary regresar_btn">
		            <span class="fa fa-chevron-left"></span><span class="ml-2">Regresar</span>
		        </a>
		    </div>

		    <div class="col d-flex justify-content-end">
		        <button class="btn btn-warning" id="limpiar">
		            <span class="ml-2">Limpiar</span><span class="fa fa-eraser ml-1"></span>
		        </button>
		    </div>
		</div>




		<div>
			<h2 class="text-center h2 text-primary">Crear Foro</h2>
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
					</div>
					<hr>
					<div class="row justify-content-center">
						<?php if (true ||$permisos[2] == 1): ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-primary w-100 small-width" id="incluir" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
							</div>
						<?php endif; ?>
						<?php if (true ||$permisos[3] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-info w-100 small-width" id="consultar" data-toggle="modal" data-target="#modalForo" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
							</div>
						<?php endif; ?>
						<?php if (true ||$permisos[4] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-warning w-100 small-width" id="modificar" name="modificar" disabled>MODIFICAR<span class="fa fa-pencil-square-o ml-2"></span></button>
							</div>
						<?php endif; ?>
						<?php if (true ||$permisos[5] == 1) : ?>
							<div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
								<button type="button" class="btn btn-danger w-100 small-width" id="eliminar" name="eliminar" disabled>ELIMINAR<span class="fa fa-trash ml-2"></span></button>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</form>
	</div>


	<div class="modal fade" tabindex="-1" role="dialog" id="modalForo">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header text-light bg-info">
					<h5 class="modal-title">Realizados</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover rows-pointer" id="tablaForo">
						<thead>
							<tr>
								<th>N</th>
								<th>Titulo</th>
								<th>Descripción</th>
								<th>Fecha</th>
							</tr>
						</thead>
						<tbody id="listaForo">

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
			loadForos();

			eventoKeypress(document.getElementById('titulo'),/^[0-9a-zA-Z\s:-?¿!¡/,.+\-()]*$/);
			// eventoKeypress(document.getElementById('descripcion'),/^[0-9a-zA-Z\s]*$/);

			eventoKeyup(document.getElementById('titulo'),/^[0-9a-zA-Z\s:-?¿!¡/,.+\-()]{1,80}$/,"Solo se permiten letras y números, no puede estar el campo vació");//-----------
			$("#titulo").on("change",function(e){
				validarKeyUp(/^[0-9a-zA-Z\s:-?¿!¡/,.+\-()]{1,80}$/,$(this),"Solo se permiten letras, números y los caracteres (:-?¿!¡/,.+-), no puede estar el campo vació");
			});
			document.getElementById('titulo').maxLength = 80;
			document.getElementById('descripcion').maxLength = 30000;
			$("#incluir").on("click", function () {
				if (validarForo()) {
					$("#accion").val("incluir");
					$("#descripcion").val( removeSpace($("#descripcion").val()) );
					$("#titulo").val( removeSpace($("#titulo").val()) );

					var datos = new FormData($("#f")[0]);
					enviaAjax(datos,function(respuesta){

						var lee = JSON.parse(respuesta);
						if(lee.resultado == "incluir"){
							borrar();
							loadForos();
							muestraMensaje(lee.mensaje, "", "success");

						}
						else if(lee.resultado == "error_no_borrar"){
							muestraMensaje("ERROR", lee.mensaje,"error");
							loadForos();
						}
						else{
							muestraMensaje("ERROR", lee.mensaje,"error");
							loadForos();
							borrar();
						}

					});
				}
			});

			$("#modificar").on("click", function () {
				if (validarForo()) {
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
									loadForos();
									muestraMensaje(lee.mensaje, "", "success");

								}
								else if(lee.resultado == "error_no_borrar"){
									muestraMensaje("ERROR", lee.mensaje,"error");
									loadForos();
								}
								else{
									muestraMensaje("ERROR", lee.mensaje,"error");
									loadForos();
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
								loadForos();
								muestraMensaje(lee.mensaje, "", "success");

							}
							else{
								muestraMensaje("ERROR", lee.mensaje,"error");
								loadForos();
								borrar();
							}
						});
					}
				});
			});

			rowsEvent("listaForo",function(e){

				$("#id").val($(e).data("id"));
				$("#titulo").val($(e).find("td").eq(1).text());
				$("#descripcion").val($(e).find("td").eq(2).text());
				$("#modalForo").modal("hide");
				cambiarbotones(false);
			});

			$("#limpiar").on("click",function(){
				borrar();
			});		
		});
		function loadForos(){
			var datos = new FormData();
			datos.append("accion", "listaForo");

			enviaAjax(datos,function(respuesta){
				var lee = JSON.parse(respuesta);
				if (lee.resultado == "listaForo") {

					if ($.fn.DataTable.isDataTable("#tablaForo")) {
						$("#tablaForo").DataTable().destroy();
					}
					console.log(lee.mensaje);
					for(var i = 0;i<lee.mensaje.length;i++){
						lee.mensaje[i].i=(i+1);
						lee.mensaje[i].fecha = lee.mensaje[i].fecha.replace(/\d\d:\d\d:\d\d$/, '');
					}
						

					$("#listaForo").html("");
					if (!$.fn.DataTable.isDataTable("#tablaForo")) {
						$("#tablaForo").DataTable({
							language: {
								lengthMenu: "Mostrar _MENU_ por página",
								zeroRecords: "No se encontraron registros de Foros",
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
							columns:[
								{data:"i"},
								{data:"titulo"},
								{data:"descripcion"},
								{data:"fecha"}
							],
							data:lee.mensaje,
							createdRow: function(row,data){
								row.dataset.id = data.id;
								row.querySelector("td:nth-child(4)").classList.add("text-nowrap");
							},
							autoWidth: false
							//searching:false,
							//info: false,
							//ordering: false,
							//paging: false
							//order: [[1, "asc"]],
							
						});
					}









				}
				else{
					muestraMensaje("ERROR", lee.mensaje,"error");
				}

			});
		}
		function validarForo(){

			$("#descripcion").val( removeSpace($("#descripcion").val()) );
			if($("#descripcion").text()!=''){
				muestraMensaje("ERROR", "La descripción no puede estar vacío","error");
				return false;   
			}
			if(!validarKeyUp(/^[0-9a-zA-Z\s:-?¿!¡/,.+\-()]+$/,$("#titulo"),"ingrese un titulo valido solo se permiten letras y números")){
				muestraMensaje("ERROR", "ingrese un titulo valido, solo se permiten letras, números y los caracteres (:-?¿!¡/,.+-)","error");
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