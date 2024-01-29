<?php
require_once("model/foro.php");

if (is_file("vista/" . $p . ".php")) {
	$o = new Foro();
	$postId = $_GET['postId'];
	if (!empty($_POST)) {
		$accion = $_POST['accion'];
		if ($accion == 'listaPost') {	
			$o->set_post_id($postId);
			$respuesta = $o->listaPost();
			echo json_encode($respuesta);
		} else if ($accion == 'listaComentarios') {
			$o->set_post_id($postId);
			$respuesta = $o->listaComentarios();
			echo json_encode($respuesta);
		}
		else if($accion = "cambiar_estado"){
			$o->set_post_id($postId);
			echo json_encode($o->cambiar_estados($_POST["control"], $_POST["value"]));
		}
		exit;
	}
	$usuario_normal_no_habitante = 1;
	$b = new Bitacora();
	$b->b_registro("Ingreso al Foro \"$postId\"");
	require_once("vista/" . $p . ".php");
} else {
	require_once("vista/404.php");
}
