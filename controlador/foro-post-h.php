<?php

require_once("model/foro.php");

if (is_file("vista/" . $p . ".php")) {
	$o = new Foro();
	$postId = $_GET['postId'];
	if (!empty($_POST)) {
		$accion = $_POST['accion'];
		if ($accion == 'listaPost') {	
			$o->set_post_id($postId);
			$o->set_habitante_id($_SESSION['id_habitante']);
			//$o->set_habitante_id("2");
			$respuesta = $o->listaPost();
			echo json_encode($respuesta);
		} else if ($accion == 'incluirComentario') {
			$o->set_post_id($_GET['postId']);
			$o->set_comentario($_POST["comentario"]);
			$o->set_create_by($_SESSION["id_habitante"]);
			//$o->set_create_by("2"); // //por ahora el creador es diego falta las sesiones de los habitantes
			$respuesta = $o->incluir_comentario_s();
			echo json_encode($respuesta);
		} else if ($accion == 'listaComentarios') {
			$o->set_post_id($postId);
			$respuesta = $o->listaComentarios();
			echo json_encode($respuesta);
		} else if ($accion == 'cambiarVoto') {
			$o->set_post_id($postId);
			$o->set_habitante_id($_SESSION['id_habitante']);

			//$o->set_apto_id("2"); // //por ahora el creador es diego falta las sesiones de los habitantes
			$o->set_voto($_POST['voto']);		
			$respuesta = $o->cambiar_voto_s();
			echo json_encode($respuesta);
		}
		exit;
	}
	$b = new Bitacora();
	$b->b_registro("Ingreso al Foro \"$postId\"");
	require_once("vista/" . $p . ".php");
} else {
	require_once("vista/404.php");
}
