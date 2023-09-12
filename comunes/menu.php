<?php
if (isset($_SESSION['rol'])) {
	$rol = $_SESSION['rol'];
} else {
	$rol = "";
}
?>
<nav class="navbar navbar-expand-md navbar-dark bg-primary d-flex py-2 px-2 justify-content-around" id="navbar">
	<div class="container-fluid">
		<div class="col-12 col-md-4 col-lg-6 mr-auto">
			<a href="?p=principal"><img src="img/logo.png" style="width:45px;" id="navbar-logo"></a>
			<a class="navbar-brand d-none d-lg-inline-block" href="?p=principal">Conjunto Residencial José Maria
				Vargas</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>
		<div class="col-12 col-md-8 col-lg-6 mt-2 m-md-0">
			<div class="collapse navbar-collapse" id="navbarNavDropdown">
				<ul class="navbar-nav ml-auto">
					
							<a class="nav-link  text-light" href="?=dashboard" id="" data-toggle="">
								Inicio</a>
							
						
					<?php
					if ($rol == "1" or $rol == "2") {
					?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle text-light" href="#" id="navbardrop" data-toggle="dropdown">
								Gestionar</a>
							<div class="dropdown-menu">
								<?php
								if ($rol == "2") :
								?>
									<a class="dropdown-item" href="?p=usuarios-administracion">Usuarios</a>
									
								<?php endif; ?>
								<a class="dropdown-item" href="?p=habitantes">Habitantes</a>
								<a class="dropdown-item" href="?p=tipoapto">Tipos de Apartamento</a>
								<a class="dropdown-item" href="?p=apartamentos">Apartamentos</a>
								<a class="dropdown-item" href="?p=estacionamiento">Estacionamiento</a>
							</div>
							<div class="collapse navbar-collapse" id="navbarNavDropdown">
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle text-light" href="#" id="navbardrop" data-toggle="dropdown">
								Procesar
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="?p=deuda-condominio">Deuda del condominio</a>
								<a class="dropdown-item" href="?p=deudas">Deudas</a>
								<a class="dropdown-item" href="?p=pagos">Pagos</a>
								<a class="dropdown-item" href="?p=generar-reporte">Generar Reporte</a>

							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle text-light" href="#" id="navbardrop" data-toggle="dropdown">
								Pago
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="?p=nomina">Nomina</a>
								<a class="dropdown-item" href="?p=servicios">Servicios</a>
							</div>
						</li>
						<div class="collapse navbar-collapse" id="navbarNavDropdown">
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle text-light" href="#" id="navbardrop" data-toggle="dropdown">
									Ver
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="?p=movimiento">Movimientos/Balance</a>
									<a class="dropdown-item" href="?p=estadistica">Estadistica</a>
									
								</div>
							</li>
						</div>
						<div class="collapse navbar-collapse" id="navbarNavDropdown">
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle text-light" href="#" id="navbardrop" data-toggle="dropdown">
									Ayuda
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="?p=acerca">Acerca de</a>
									<a class="dropdown-item" href="?p=FAQ">FAQ</a>
								</div>
							</li>
						</div>
						<div class="d-none d-md-inline-flex">
							<li class="nav-item ml-2">
								<a class="btn btn-outline-light" href="?p=cerrarsesion">Cerrar sesión</a>
							</li>
						</div>
						<div class="d-md-none">
							<li class="nav-item">
								<a class="nav-link text-light" href="?p=cerrarsesion">Cerrar Sesión<span class="fa fa-sign-out ml-1"></span></a>
							</li>
						</div>
					<?php
					} else {
					?>
						<div class="d-md-none">
							<li class="nav-item">
								<a class="nav-link text-light" href="?p=login">Iniciar sesión<span class="fa fa-sign-in ml-1"></span></a>
							</li>
							<li class="nav-item">
								<a class="nav-link text-light" href="?p=consulta">Consultar deuda<span class="fa fa-calculator ml-1"></span></a>
							</li>
						</div>
						<div class="d-none d-md-inline-flex">
							<li class="nav-item">
								<a class="btn btn-outline-light" href="?p=login">Iniciar sesión</a>
							</li>
							<li class="nav-item ml-3">
								<a class="btn btn-outline-light" href="?p=consulta">Consultar deuda</a>
							</li>
						</div>
					<?php
					}
					?>
			</div>

		</div>

	</div>
</nav>