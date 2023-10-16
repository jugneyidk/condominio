<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
	
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary">Bitácora</h2> 
			<hr />
		</div>

		<div class="table-responsive">
			<table class="table table-striped table-hover " id="tabla_bitacora">
				<thead>
					<tr>
						<th>usuario</th>
						<th>descripción</th>
						<th>fecha</th>
					</tr>
				</thead>
				<tbody id="listado_bitacora">

				</tbody>
			</table>
		</div>
		

	</div>
	<?php require_once("comunes/carga.php"); ?>
	<script src="js/carga.js"></script>
	<script src="js/comun_x.js"></script>
	<script>

		$(document).ready(function (){
			load_bitacora();
		});

		function load_bitacora(){
			var datos = new FormData();
			datos.append("accion","load_bitacora");
			enviaAjax(datos,function(respuesta){
			
				var lee = JSON.parse(respuesta);
				if(lee.resultado == "load_bitacora"){

					if ($.fn.DataTable.isDataTable("#tabla_bitacora")) {
						$("#tabla_bitacora").DataTable().destroy();
					}
					$("#listado_bitacora").html(lee.mensaje);
					if (!$.fn.DataTable.isDataTable("#tabla_bitacora")) {
						$("#tabla_bitacora").DataTable({
							language: {
								lengthMenu: "Mostrar _MENU_ por página",
								zeroRecords: "No se encontraron registros de Bitácora",
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
							order: [[2, "desc"]]
						});
					}
					






				}
				else if (lee.resultado == 'is-invalid'){
					muestraMensaje("ERROR", lee.mensaje,"error");
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
		}

		


		
	</script>

	<?php require_once('comunes/foot.php'); ?>

</body>

</html>