<?php 
if (!is_file("model/" . $p . ".php")) {

	echo "Falta definir la clase " . $p;
	exit;
}
require_once("model/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
	$o = new avisos();
	$permisos = $o->chequearpermisos();
	if (!empty($_POST)) {
		$accion = $_POST['accion'];
		if ($accion == 'listaAvisos') {
			$respuesta = $o->listaAvisos();
			echo json_encode($respuesta);

		} else if ($accion == 'incluir') {
			$o->set_titulo($_POST["titulo"]);
			$o->set_descripcion($_POST["descripcion"]);
			$o->set_desde($_POST["fecha"]);
			$o->set_hasta($_POST["fecha2"]); 
			$respuesta = $o->incluir();
			echo json_encode($respuesta);

		} else if ($accion == 'modificar') {


			$o->set_id($_POST["id"]);
			$o->set_titulo($_POST["titulo"]);
			$o->set_descripcion($_POST["descripcion"]);
			$o->set_desde($_POST["fecha"]);
			$o->set_hasta($_POST["fecha2"]);
			$respuesta = $o->modificar();
			echo json_encode($respuesta);

		} else if ($accion == 'eliminar') {
			$o->set_id($_POST["id"]);
			echo json_encode($o->eliminar());
			
		}
		exit;
	}
		require_once("vista/" . $p . ".php");
} else {
	echo "pagina en construccion";
}
?>