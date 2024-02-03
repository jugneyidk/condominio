<?php
	if (!is_file("model/" . $p . ".php")) {

		echo "Falta definir la clase " . $p;
		exit;
	}
	require_once("model/" . $p . ".php");
	if (is_file("vista/" . $p . ".php")) {
		$o = new nomina();
		$permisos = $o->chequearpermisos();
		if (!empty($_POST)) {
			$accion = $_POST['accion'];
			if ($accion == 'incluir') {
				$emplado = json_decode($_POST["empleado"]);
				// $o->set_rif_cedula();
				// $o->set_tipo_identificacion();
				// $o->set_descripcion();
				// $o->set_obj_pagos();


				echo json_encode($o->incluir_s(
					$emplado->rif_cedula,
					$emplado->tipo_identificacion,
					$_POST["descripcion"],
					$_POST["obj_pagos"]
				));

			} else if ($accion == 'modificar') {
				$emplado = json_decode($_POST["empleado"]);
				// $o->set_rif_cedula($emplado->rif_cedula);
				// $o->set_tipo_identificacion($emplado->tipo_identificacion);
				// $o->set_descripcion($_POST["descripcion"]);
				// $o->set_obj_pagos($_POST["obj_pagos"]);
				$respuesta = $o->modificar_s(
					$emplado->rif_cedula,
					$emplado->tipo_identificacion,
					$_POST["descripcion"],
					$_POST["obj_pagos"]
				);
				echo json_encode($respuesta);

			} else if ($accion == 'eliminar') {
				// $o->set_id(json_decode());
				echo json_encode($o->eliminar_s($_POST["id"]));

			} else if ($accion == "listadoNomina"){
				echo json_encode($o->listadonomina());

			} else if ($accion == 'listadoEmpleados') {
				$respuesta = $o->listadoEmpleados(null,null);
				echo json_encode($respuesta);

			} else if ($accion == "seleccionar_empleado"){
				echo json_encode($o->listadoEmpleados(
					$_POST["cedula_rif"],
					$_POST["tipo_identificacion"]
				));

			} else if ($accion == "seleccionar_nomina"){
				$o->set_id($_POST["id"]);
				echo json_encode($o->listadonomina());

			} else if ($accion == "incluir_2"){
				// $o->set_rif_cedula();
				// $o->set_tipo_identificacion();
				// $o->set_nombres();
				// $o->set_apellidos();
				// $o->set_fechac();
				// $o->set_salario();
				// $o->set_domicilio_fiscal();
				// $o->set_telefono();
				// $o->set_correo();
				// $o->set_cargo();
				// $o->set_fechan();
				// $o->set_estado_civil();


				echo json_encode($o->incluir_empleado_s(
					$_POST["cedula_rif"],
					$_POST["tipo_identificacion"],
					$_POST["nombres"],
					$_POST["apellidos"],
					$_POST["fechac"],
					$_POST["salario"],
					$_POST["domicilio_fiscal"],
					$_POST["telefono"],
					$_POST["correo"],
					$_POST["cargo"],
					$_POST["fechan"],
					$_POST["estado_civil"]
				));
			} else if ($accion == "eliminar_empleado"){
				// $o->set_rif_cedula($_POST["cedula_rif"]);
				// $o->set_tipo_identificacion($_POST["tipo_identificacion"]);
				echo json_encode($o->eliminar_empleado_s(
					$_POST["cedula_rif"],
					$_POST["tipo_identificacion"]

				));
			} else if ($accion == "modificar_empleado"){
				// $o->set_id();
				// $o->set_rif_cedula();
				// $o->set_tipo_identificacion();
				// $o->set_nombres();
				// $o->set_apellidos();
				// $o->set_fechac();
				// $o->set_salario();
				// $o->set_domicilio_fiscal();
				// $o->set_telefono();
				// $o->set_correo();
				// $o->set_cargo();
				// $o->set_fechan();
				// $o->set_estado_civil();

				echo json_encode($o->modificar_empleado_s(
					json_decode($_POST["id"]),
					$_POST["cedula_rif"],
					$_POST["tipo_identificacion"],
					$_POST["nombres"],
					$_POST["apellidos"],
					$_POST["fechac"],
					$_POST["salario"],
					$_POST["domicilio_fiscal"],
					$_POST["telefono"],
					$_POST["correo"],
					$_POST["cargo"],
					$_POST["fechan"],
					$_POST["estado_civil"]
				));
			} else echo "no programado";
			exit;
		}
		$b_temp = new Bitacora;
		$b_temp->b_registro("Ingreso en el modulo \"".$_GET["p"]."\"");
		require_once("vista/" . $p . ".php");
	} else {
		require_once("vista/404.php");
	}
?>