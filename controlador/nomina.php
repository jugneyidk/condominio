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
				$o->set_rif_cedula($emplado->rif_cedula);
				$o->set_tipo_identificacion($emplado->tipo_identificacion);
				$o->set_descripcion($_POST["descripcion"]);
				$o->set_obj_pagos($_POST["obj_pagos"]);


				echo json_encode($o->incluir_s());
			} else if ($accion == 'modificar') {
				$emplado = json_decode($_POST["empleado"]);
				$o->set_rif_cedula($emplado->rif_cedula);
				$o->set_tipo_identificacion($emplado->tipo_identificacion);
				$o->set_descripcion($_POST["descripcion"]);
				$o->set_obj_pagos($_POST["obj_pagos"]);
				$respuesta = $o->modificar_s();
				echo json_encode($respuesta);
			} else if ($accion == 'eliminar') {

				// $respuesta = $o->eliminar($_POST['id']);
				$o->set_id(json_decode($_POST["id"]));
				echo json_encode($o->eliminar_s());
			} else if ($accion == "listadoNomina"){

				echo json_encode($o->listadonomina());
			} else if ($accion == 'listadoEmpleados') {
				$respuesta = $o->listadoEmpleados();
				echo json_encode($respuesta);
			} else if ($accion == "seleccionar_empleado"){
				$o->set_rif_cedula($_POST["cedula_rif"]);
				$o->set_tipo_identificacion($_POST["tipo_identificacion"]);
				echo json_encode($o->listadoEmpleados());
			} else if ($accion == "seleccionar_nomina"){
				$o->set_id($_POST["id"]);
				echo json_encode($o->listadonomina());
			} else if ($accion == "incluir_2"){
				$o->set_rif_cedula($_POST["cedula_rif"]);
				$o->set_tipo_identificacion($_POST["tipo_identificacion"]);
				$o->set_nombres($_POST["nombres"]);
				$o->set_apellidos($_POST["apellidos"]);
				$o->set_fechac($_POST["fechac"]);
				$o->set_salario($_POST["salario"]);
				$o->set_domicilio_fiscal($_POST["domicilio_fiscal"]);
				$o->set_telefono($_POST["telefono"]);
				$o->set_correo($_POST["correo"]);
				$o->set_cargo($_POST["cargo"]);
				$o->set_fechan($_POST["fechan"]);
				$o->set_estado_civil($_POST["estado_civil"]);


				echo json_encode($o->incluir_empleado_s());
			} else if ($accion == "eliminar_empleado"){
				$o->set_rif_cedula($_POST["cedula_rif"]);
				$o->set_tipo_identificacion($_POST["tipo_identificacion"]);
				echo json_encode($o->eliminar_empleado_s());
			} else if ($accion == "modificar_empleado"){
				$o->set_id(json_decode($_POST["id"]));
				$o->set_rif_cedula($_POST["cedula_rif"]);
				$o->set_tipo_identificacion($_POST["tipo_identificacion"]);
				$o->set_nombres($_POST["nombres"]);
				$o->set_apellidos($_POST["apellidos"]);
				$o->set_fechac($_POST["fechac"]);
				$o->set_salario($_POST["salario"]);
				$o->set_domicilio_fiscal($_POST["domicilio_fiscal"]);
				$o->set_telefono($_POST["telefono"]);
				$o->set_correo($_POST["correo"]);
				$o->set_cargo($_POST["cargo"]);
				$o->set_fechan($_POST["fechan"]);
				$o->set_estado_civil($_POST["estado_civil"]);

				echo json_encode($o->modificar_empleado_s());
			} else echo "no programado";
			exit;
		}
		require_once("vista/" . $p . ".php");
	} else {
		require_once("vista/404.php");
	}
?>