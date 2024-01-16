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
			$o->set_titulo($_POST["titulo"]);
			$o->set_descripcion($_POST["descripcion"]);


			$o->set_create_by($_SESSION['id_habitante']);


			$respuesta = $o->incluir_s();
			echo json_encode($respuesta);

		} else if ($accion == 'modificar') {


			$o->set_id($_POST["id"]);
			$o->set_titulo($_POST["titulo"]);
			$o->set_descripcion($_POST["descripcion"]);

			$respuesta = $o->modificar_s();
			echo json_encode($respuesta);

		} else if ($accion == 'eliminar') {
			$o->set_id($_POST["id"]);
			echo json_encode($o->eliminar_s());
			
		}
		exit;
	}
		require_once("vista/" . $p . ".php");
} else {
	require_once("vista/404.php");
}
?>