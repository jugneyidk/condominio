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
			$o->set_fecha($_POST['fecha']);
			$o->set_monto($_POST['monto']);
			$o->set_referencia($_POST['referencia']); 

			$respuesta = $o->incluir();

			echo json_encode($respuesta);
		} else if ($accion == 'modificar') {
			$o->set_servicio($_POST['service']);
			$o->set_descripcion($_POST['descripcion']);
			$o->set_fecha($_POST['fecha']);
			$o->set_monto($_POST['monto']);
			$o->set_referencia($_POST['referencia']); 
			$o->set_id_pago_serv($_POST["id"]);

			$respuesta = $o->modificar();

			echo json_encode($respuesta);
	} else if ($accion == 'eliminar') {
		$o->set_id_pago_serv($_POST["id"]);
		
		echo json_encode($o->eliminar());
	} else if ($accion == 'listadoPagosservicios') {
		$respuesta = $o->listadoPagosservicios();
		echo json_encode($respuesta);
	}
	exit;
}
require_once("vista/" . $p . ".php");
} else {
	echo "pagina en construccion";
}
?>