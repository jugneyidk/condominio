<?php 
if (is_file("vista/" . $p . ".php")) {
	require_once("model/login.php");

	if (!empty($_POST)) {
		$o = new login();
		$accion = $_POST['accion'];
		if($accion == "reset_pass_request"){
			// $o->set_cedula();
			// $o->set_correo();
			// $o->set_type();

			echo json_encode($o->reset_pass_request_s(
				$_POST["cedula_for_reset"],
				$_POST["email_for_reset"],
				$_POST["select_user_type"]
			));
		}
		else if($accion == "reset_pass"){
			$user = json_decode($_POST["i"]);
			// $o->set_id();
			// $o->set_pass();
			// $o->set_type();
			echo json_encode($o->reset_pass_s(
				$user->id,
				$_POST["nueva_contrasena"],
				$user->type
			));
		}
	
		exit;
	}
	if(isset($_GET["a"])){
		
		$data = $_GET["a"];
		$iv_size = openssl_cipher_iv_length("aes-256-cbc");  // Obtener el tamaño del IV con OpenSSL


		$iv = substr($data, -$iv_size);

		$o = new login;

		$texto_final = openssl_decrypt(substr($data, 0,-$iv_size), "aes-256-cbc", $o->get_keyword(),0,$iv);

		$data_user_reset = json_decode($texto_final);

		$cinco_minutos = 5 * 60;



		$tiempo_transcurrido = (time() - $data_user_reset->date);

		if ($tiempo_transcurrido > $cinco_minutos) {
		  // Han pasado más de 5 minutos
			$control_time_reset = false;
		} else {
		  // No han pasado más de 5 minutos
			$control_time_reset = true;
		}


		$data_user_reset = array('id' => $data_user_reset->id,'type' => $data_user_reset->type );

		$texto_final.="paso:".$tiempo_transcurrido;
	}

		require_once("vista/" . $p . ".php");
} else {
	require_once("vista/404.php");
}
?>