<?php
if (!is_file("model/" . $p . ".php")) {
	echo "Falta definir la clase " . $p;
	exit;
}
require_once("model/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
	//if (isset($_SESSION['id_habitante'])) {
		$o = new detallesdeuda();
		if (!empty($_POST)) {
			$accion = $_POST['accion'];
			if ($accion == 'listadodeudas') {
				$respuesta = $o->listadodeudas();
				echo json_encode($respuesta);
			}else if($accion == 'historialpagos'){
					$respuesta = $o->historialpagos();
					echo json_encode($respuesta);
			} else if ($accion == 'registrarpago') {

				// $o->set_id();
				// $o->set_obj_pagos();
				$respuesta = $o->registrarpago_s(
					$_POST["id_deuda_tosend"],
					$_POST["obj_pagos"]
				);
				echo json_encode($respuesta);
			}
			else if ($accion == "eliminar_pagos"){
				// $o->set_id();
				// $o->set_id_pago();

				echo json_encode($o->eliminar_pagos_s(
					$_POST["eliminar_id"],
					$_POST["id_pago"]
				));
			}
			else if($accion == "detalles_deuda"){
				// $o->set_id();
				echo json_encode($o->detalles_deuda_s($_POST["id_deuda"]));
			}
			$o->set_con(null);
			exit;
		}
		$b_temp = new Bitacora;
  		$b_temp->b_registro("Ingreso en el modulo \"Consultar Deudas\"");
		require_once("vista/" . $p . ".php");
		$o->set_con(null);

	//} else {
	//	header("Location: ?p=consulta");
	//}
} else {
	require_once("vista/404.php");
}
?>