<?php
require_once("model/foro.php");

if (is_file("vista/" . $p . ".php")) {
	$o = new Foro();
	$postId = $_GET['postId'];
	if (!empty($_POST)) {
		$accion = $_POST['accion'];
		if ($accion == 'listaPost') {	
			//$o->set_post_id($postId);
			$respuesta = $o->listaPost($postId,null);
			echo json_encode($respuesta);
		} else if ($accion == 'listaComentarios') {
			// $o->set_post_id();
			$respuesta = $o->listaComentarios($postId);
			echo json_encode($respuesta);
		}
		else if($accion = "cambiar_estado"){
			echo json_encode($o->cambiar_estados($_POST["control"], $_POST["value"],$postId));
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
