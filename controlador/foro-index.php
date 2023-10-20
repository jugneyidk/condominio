<?php
if (!is_file("model/" . $p . ".php")) {
	echo "Falta definir la clase " . $p;
	exit;
}
require_once("model/" . $p . ".php");

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
	require_once("vista/" . $p . ".php");
} else {
	require_once("vista/404.php");
}
