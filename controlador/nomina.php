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
				$respuesta = $o->incluir(
					$_POST['cedula_rif'], 
					$_POST['tipo_identificacion'],
					$_POST['descripcion'],
					$_POST['metodo'],
					$_POST['fecha'],
					$_POST['monto'],
					$_POST['referencia']
				);
				echo json_encode($respuesta);
	} /*else if ($accion == 'modificar') {
	$respuesta = $o->modificar(
	$_POST['nombres'],
	$_POST['apellidos'],
	$_POST['cedula_rif'],
	$_POST['domicilio_fiscal'],
	$_POST['telefono'],
	$_POST['correo'],
	$_POST['descripcion'],
	$_POST['metodo'],
	$_POST['fecha'],
	$_POST['monto'],
	$_POST['referencia']
	);
	//echo json_encode($respuesta);
	} else*/ if ($accion == 'eliminar') {
	// $respuesta = $o->eliminar($_POST['id']);
	// echo json_encode($respuesta);
	} else if ($accion == 'listadoEmpleados') {
		$respuesta = $o->listadoEmpleados();
		echo json_encode($respuesta);
	}
	else if($accion == "incluir_2"){
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


		// ob_start();
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		// $valor = ob_get_clean();

		// $r['resultado'] = "console";
		// $r['mensaje'] = $valor;

		// echo json_encode($r);
		// die;




		echo json_encode($o->incluir_empleado_s());
	}
	exit;
	}
	require_once("vista/" . $p . ".php");
	} else {
		require_once("vista/404.php");
	}
?>