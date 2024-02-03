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
			$respuesta = $o->incluir_s(
				$_POST["titulo"],
				$_POST["descripcion"],
				$_POST["fecha"],
				$_POST["fecha2"]
			);
			echo json_encode($respuesta);

		} else if ($accion == 'modificar') {

			$respuesta = $o->modificar_s(
				$_POST["id"],
				$_POST["titulo"],
				$_POST["descripcion"],
				$_POST["fecha"],
				$_POST["fecha2"]
			);
			echo json_encode($respuesta);

		} else if ($accion == 'eliminar') {
			echo json_encode($o->eliminar_s($_POST["id"]));
			
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