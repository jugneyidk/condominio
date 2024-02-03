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

				echo json_encode($cl->incluir_s(
					$_POST["numEstac"],
					$_POST["estacCosto"],
					$_POST["apartamentos_id"]
				));
			}
			else if($accion == "modificar"){

				echo json_encode($cl->modificar_s(
					$_POST["numEstac"],
					$_POST["estacCosto"],
					$_POST["apartamentos_id"],
					$_POST["id"]
				));
			}
			else if($accion == "eliminar"){
				echo json_encode($cl->eliminar_s($_POST["numEstac"]));
			}









			exit;
		}

		require_once("vista/".$p.".php"); 
	}
	else{
	require_once("vista/404.php"); 
	}
?>