<?php 
require_once("model/foro.php");
if (is_file("vista/" . $p . ".php")) {
	$o = new Foro();
	//$permisos = $o->chequearpermisos();
	if (!empty($_POST)) {
		$accion = $_POST['accion'];
		if ($accion == 'listaForo') {
			$o->set_create_by($_SESSION['id_habitante']);
			$respuesta = $o->listaForo();
			echo json_encode($respuesta);

		} else if ($accion == 'incluir') {
			// $o->set_titulo();
			// $o->set_descripcion();


			// $o->set_create_by();


			$respuesta = $o->incluir_s(
				$_POST["titulo"],
				$_POST["descripcion"],
				$_SESSION['id_habitante']
			);
			echo json_encode($respuesta);

		} else if ($accion == 'modificar') {


			// $o->set_id();
			// $o->set_titulo();
			// $o->set_descripcion();

			$respuesta = $o->modificar_s(
				$_POST["id"],
				$_POST["titulo"],
				$_POST["descripcion"]
			);
			echo json_encode($respuesta);

		} else if ($accion == 'eliminar') {
			//$o->set_id($_POST["id"]);
			echo json_encode($o->eliminar_s(
				$_POST["id"],
			));
			
		}
		$o->set_con(null);
		exit;
	}
	require_once("vista/" . $p . ".php");
	$o->set_con(null);
} else {
	require_once("vista/404.php");
}
?>