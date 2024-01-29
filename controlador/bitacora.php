<?php
	//verifica que exista la vista de
	//la pagina
	if(is_file("vista/".$p.".php")){

		require_once("model/datos.php");
		require_once("model/bitacora.php");
		$cl = new Bitacora();
		if(!empty($_POST)){
			$accion = $_POST["accion"];
			if($accion = "load_bitacora" ){
				echo json_encode( $cl->load_bitacora() );
			}
			die;
		}
	//si existe se la trae, ahora ve a la carpeta vista
	//y busca el archivo principal.php 
	
  	//$cl->b_registro("Ingreso en el modulo \"".$_GET["p"]."\"");
	require_once("vista/".$p.".php"); 
	}
	else{
	require_once("vista/404.php"); 
	}
?>