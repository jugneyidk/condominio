<?php
	if (!is_file("model/" . $p . ".php")) {

		echo "Falta definir la clase " . $p;
		exit;
	}
	require_once("model/".$p.".php");

	if(is_file("vista/".$p.".php")){

		$cl = new estac();
		$permisos = $cl->chequearpermisos();
		if(!empty($_POST)){

			$accion = $_POST['accion'];

			if ($accion == 'listadoapartamentos') {
				$respuesta = $cl->listadoapartamentos();
				echo json_encode($respuesta);
			}
			else if($accion == "listadoaEstacionamiento"){
				$respuesta = $cl->listadoaEstacionamiento();

				echo json_encode($respuesta);
			}
			else if($accion == "incluir"){
				$cl->set_num_estac($_POST["numEstac"]);
				$cl->set_costo($_POST["estacCosto"]);
				$cl->set_apartamento_id($_POST["apartamentos_id"]);

				echo json_encode($cl->incluir_s());
				//echo json_encode(["resultado"=>'incluir',"mensaje"=>"Registro Incluido", "envio" => $_POST]);
			}
			else if($accion == "modificar"){
				$cl->set_num_estac($_POST["numEstac"]);
				$cl->set_costo($_POST["estacCosto"]);
				$cl->set_apartamento_id($_POST["apartamentos_id"]);
				$cl->set_num_estac_original($_POST["id"]);

				echo json_encode($cl->modificar_s());
			}
			else if($accion == "eliminar"){
				$cl->set_num_estac($_POST["numEstac"]);
				echo json_encode($cl->eliminar_s());
			}









			exit;
		}

		require_once("vista/".$p.".php"); 
	}
	else{
	require_once("vista/404.php"); 
	}
?>