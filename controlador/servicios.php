<?php 
if (!is_file("model/" . $p . ".php")) {

  echo "Falta definir la clase " . $p;
  exit;
}
require_once("model/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
	$o = new servicio();
	$permisos = $o->chequearpermisos();
	if (!empty($_POST)) {
		$accion = $_POST['accion'];
		if ($accion == 'listaSelectServicios') {
			$respuesta = $o->lista_select_servicios();
			echo json_encode($respuesta);

		} else if ($accion == 'incluir') {
			// $o->set_servicio();
			// $o->set_descripcion();
			// $o->set_obj_pagos();// paso todo el objeto de pagos

			$respuesta = $o->incluir_s(
				$_POST['service'],
				$_POST['descripcion'],
				json_decode($_POST["tipo_pago"])
			);
			echo json_encode($respuesta);

		} else if ($accion == 'modificar') {
			// $o->set_servicio($_POST['service']);
			// $o->set_descripcion();
			// $o->set_id_pago_serv();
			// $o->set_obj_pagos();// paso todo el objeto de pagos

			$respuesta = $o->modificar_s(
				$_POST['descripcion'],
				$_POST["id"],
				json_decode($_POST["tipo_pago"])
			);
			echo json_encode($respuesta);

		} else if ($accion == 'eliminar') {
			// $o->set_id_pago_serv();
			echo json_encode($o->eliminar_s(
				$_POST["id"]
			));

		} else if ($accion == 'listadoPagosservicios') {
			$respuesta = $o->listadoPagosservicios();
			echo json_encode($respuesta);
		}else if($accion == 'listadoServicios'){
			$respuesta = $o->listadoServicios();
			echo json_encode($respuesta);
		}
		else if ($accion == 'incluir_s') {
			// $o->set_descripcion();
			echo json_encode($o->incluir_servicio_s(
				$_POST['servicio']
			));
		}
		else if ($accion == 'modificar_s') {
			// $o->set_id_pago_serv();
			// $o->set_descripcion();
			echo json_encode($o->modificar_servicio_s(
				$_POST["id"],
				$_POST['servicio']
			));
		}
		else if ($accion == 'eliminar_s') {
			// $o->set_id_pago_serv($_POST["id"]);
			echo json_encode($o->eliminar_servicio_s(
				$_POST["id"]
			));
		}
		else if($accion == 'seleccionar_pago'){
			echo json_encode($o->seleccionar_pago($_POST['id']));
		}
		$o->set_con(null);
		exit;
	}
	$b_temp = new Bitacora;
  	$b_temp->b_registro("Ingreso en el modulo \"".$_GET["p"]."\"");
	require_once("vista/" . $p . ".php");
	$o->set_con(null);
	} else {
		require_once("vista/404.php");
	}
?>