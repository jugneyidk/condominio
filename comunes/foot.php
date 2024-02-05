<footer class="bg-dark p-5 position-sticky container-fluid">
	<div class="row justify-content-between">
		<div class="col-md-4">
			<p class="text-light h5">Sistema de Control</p>
			<p class="text-light d-none d-md-block">Conjunto Residencial José Maria Vargas</p>
			<small class="text-muted">© 2022-2023</small>
		</div>
		<?php if (isset($_SESSION['id_usuario'])) : ?>
			<div class="col-md-1">
				<p class="text-light h5">Gestionar</p>
				<ul class="list-unstyled text-small">
					<?php if ($_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol']== 2) : ?>
						<li><a href="?p=usuarios-administracion" class="text-muted">Usuarios</a></li>
					<?php endif; ?>
					<li><a href="?p=habitantes" class="text-muted">Habitantes</a></li>
					<li><a href="?p=tipoapto" class="text-muted">Tipos de Apartamento</a></li>
					<li><a href="?p=apartamentos" class="text-muted">Apartamentos</a></li>
					<li><a href="?p=estacionamiento" class="text-muted">Estacionamiento</a></li>
					<li><a href="?p=deuda-condominio" class="text-muted">Deuda del condominio</a></li>
				</ul>
			</div>
			<div class="col-md-1">
				<p class="text-light h5">Procesar</p>
				<ul class="list-unstyled text-small">
					<li><a href="?p=deudas" class="text-muted">Deudas</a></li>
					<li><a href="?p=pagos" class="text-muted">Pagos</a></li>
					<li><a href="?p=generar-reporte" class="text-muted">Generar Reporte</a></li>
				</ul>
			</div>
			<div class="col-md-1">
				<p class="text-light h5">Pago</p>
				<ul class="list-unstyled text-small">
					<li><a href="?p=nomina" class="text-muted">Nomina</a></li>
					<li><a href="?p=servicios" class="text-muted">Servicios</a></li>
				</ul>
			</div>
			<div class="col-md-1">
				<p class="text-light h5">Ver</p>
				<ul class="list-unstyled text-small">
					<li><a href="?p=movimiento" class="text-muted">Balance</a></li>
					<li><a href="?p=estadistica" class="text-muted">Estadistica</a></li>
				</ul>
			</div>
			<div class="col-md-1">
				<p class="text-light h5">Ayuda</p>
				<ul class="list-unstyled text-small">
					<li><a href="?p=acerca" class="text-muted">Acerca de</a></li>
					<li><a href="?p=FAQ" class="text-muted">FAQ</a></li>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</footer>
<?php// require_once("comunes/bcv_button.php"); ?>

