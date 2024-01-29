<?php

require_once("model/foro.php");

if (is_file("vista/" . $p . ".php")) {
	$o = new Foro();
	if (!empty($_POST)) {
		$accion = $_POST['accion'];
		if ($accion == 'listaForo') {
			$respuesta = $o->listaForo();
			echo json_encode($respuesta);
		}
		exit;
	}
	$b = new Bitacora();
	$b->b_registro("Ingreso al modulo \"Foro\"");
	require_once("vista/" . $p . ".php");
} else {
	require_once("vista/404.php");
}
