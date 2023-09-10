<?php
if (!is_file("model/" . $p . ".php")) {
	echo "Falta definir la clase " . $p;
	exit;
}
require_once("model/" . $p . ".php");
require_once("model/habitantes.php");
require_once("model/apartamentos.php");
if (is_file("vista/" . $p . ".php")) {
	$o = new generarreporte();
	$h = new habitantes();
	$a = new apto();
	if (isset($_POST['tiporeporte']) && ($_POST['tiporeporte'] == '1' || ($_POST['tiporeporte'] == '2' && $_POST['id'] != '') || ($_POST['tiporeporte'] == '3' && $_POST['id'] != ''))) {
		$o->generarPDF($_POST);
	}else if (isset($_POST['accion'])) {
		$accion = $_POST['accion'];
		if ($accion == "listado_habitantes") {
			$respuesta = $h->listadohabitantes();
			echo json_encode($respuesta);
		}else if ($accion == "listadoapartamentos") {
			$respuesta = $a->listadoapartamentos();
			echo json_encode($respuesta);
		}
		exit;
	}
	require_once("vista/" . $p . ".php");
} else {
	require_once("vista/404.php");
}
