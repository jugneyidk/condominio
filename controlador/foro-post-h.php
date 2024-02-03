<?php

require_once("model/foro.php");

if (is_file("vista/" . $p . ".php")) {
	$o = new Foro();
	$postId = $_GET['postId'];
	if (!empty($_POST)) {
		$accion = $_POST['accion'];
		if ($accion == 'listaPost') {	
			
			$respuesta = $o->listaPost(
				$postId,
				$_SESSION['id_habitante']
			);

			echo json_encode($respuesta);
		} else if ($accion == 'incluirComentario') {
			
			$respuesta = $o->incluir_comentario_s(
				$postId,
				$_POST["comentario"],
				$_SESSION["id_habitante"]
			);

			echo json_encode($respuesta);
		} else if ($accion == 'listaComentarios') {
			$respuesta = $o->listaComentarios($postId);
			echo json_encode($respuesta);
		} else if ($accion == 'cambiarVoto') {
			
			$respuesta = $o->cambiar_voto_s(
				$postId,
				$_SESSION["id_habitante"],
				$_POST["voto"]
			);

			echo json_encode($respuesta);
		}
		$o->set_con(null);
		exit;
	}
	$b = new Bitacora();
	$b->b_registro("Ingreso al Foro \"$postId\"");
	require_once("vista/" . $p . ".php");
	$o->set_con(null);
} else {
	require_once("vista/404.php");
}
