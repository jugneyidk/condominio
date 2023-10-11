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
			$o->set_servicio($_POST['service']);
			$o->set_descripcion($_POST['descripcion']);
			$o->set_obj_pagos(json_decode($_POST["tipo_pago"]));// paso todo el objeto de pagos

			$respuesta = $o->incluir_s();
			echo json_encode($respuesta);

		} else if ($accion == 'modificar') {
			$o->set_servicio($_POST['service']);
			$o->set_descripcion($_POST['descripcion']);
			$o->set_id_pago_serv($_POST["id"]);
			$o->set_obj_pagos(json_decode($_POST["tipo_pago"]));// paso todo el objeto de pagos

			$respuesta = $o->modificar_s();
			echo json_encode($respuesta);

		} else if ($accion == 'eliminar') {
			$o->set_id_pago_serv($_POST["id"]);
			echo json_encode($o->eliminar_s());

		} else if ($accion == 'listadoPagosservicios') {
			$respuesta = $o->listadoPagosservicios();
			echo json_encode($respuesta);
		}else if($accion == 'listadoServicios'){
			$respuesta = $o->listadoServicios();
			echo json_encode($respuesta);
		}
		else if ($accion == 'incluir_s') {
			$o->set_descripcion($_POST['servicio']);
			echo json_encode($o->incluir_servicio_s());
		}
		else if ($accion == 'modificar_s') {
			$o->set_id_pago_serv($_POST["id"]);
			$o->set_descripcion($_POST['servicio']);
			echo json_encode($o->modificar_servicio_s());
		}
		else if ($accion == 'eliminar_s') {
			$o->set_id_pago_serv($_POST["id"]);
			echo json_encode($o->eliminar_servicio_s());
		}
		else if($accion == 'seleccionar_pago'){
			echo json_encode($o->seleccionar_pago($_POST['id']));
		}
		exit;
	}
	require_once("vista/" . $p . ".php");
	} else {
		require_once("vista/404.php");
	}
?>