<?php
if (!is_file("model/" . $p . ".php")) {
	echo "Falta definir la clase " . $p;
	exit;
}
require_once("model/" . $p . ".php");

if (is_file("vista/" . $p . ".php")) {
	$o = new ForoPost();
	$postId = $_GET['postId'];
	if (!empty($_POST)) {
		$accion = $_POST['accion'];
		if ($accion == 'listaPost') {	
			$o->set_post_id($postId);
			$o->set_apto_id("2");
			$respuesta = $o->listaPost();
			echo json_encode($respuesta);
		} else if ($accion == 'incluirComentario') {
			$o->set_post_id($_GET['postId']);
			$o->set_comentario($_POST["comentario"]);
			$o->set_create_by("2"); // //por ahora el creador es diego falta las sesiones de los habitantes
			$respuesta = $o->incluir_s();
			echo json_encode($respuesta);
		} else if ($accion == 'listaComentarios') {
			$o->set_post_id($postId);
			$respuesta = $o->listaComentarios();
			echo json_encode($respuesta);
		} else if ($accion == 'cambiarVoto') {
			$o->set_post_id($postId);
			$o->set_apto_id("2"); // //por ahora el creador es diego falta las sesiones de los habitantes
			$o->set_voto($_POST['voto']);			
			$respuesta = $o->cambiar_voto_s();
			echo json_encode($respuesta);
		}
		exit;
	}
	require_once("vista/" . $p . ".php");
} else {
	require_once("vista/404.php");
}
