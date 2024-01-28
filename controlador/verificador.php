<?php 
if(!in_array($p, $excepciones_p) || $p == "principal"){
	
	if(isset($_SESSION['id_usuario'])){
		require_once("model/datos.php");
		try {
			$c = new datos();
			$con = $c->conecta();
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$consulta = $con->prepare("SELECT rif_cedula, tipo_identificacion FROM datos_usuarios WHERE datos_usuarios.id = ?");
			$consulta->execute([$_SESSION["id_usuario"]]);
			$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

			if(!password_verify($consulta["rif_cedula"]."-".$consulta["tipo_identificacion"], $_SESSION["CONDOMINIO_TOKEN"])){
				$p = "cerrarsesion";
			}
		} catch (Exception $e) {
			//echo $e->getMessage();
		}
	}
	else if(isset($_SESSION['id_habitante'])){
		require_once("model/datos.php");
		try {
			$c = new datos();
			$con = $c->conecta();
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$consulta = $con->prepare("SELECT rif_cedula, tipo_identificacion FROM habitantes WHERE datos_usuarios.id = ?");
			$consulta->execute([$_SESSION["id_habitante"]]);
			$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

			if(!password_verify($consulta["rif_cedula"]."-".$consulta["tipo_identificacion"], $_SESSION["CONDOMINIO_TOKEN"])){
				$p = "cerrarsesion";
			}
		} catch (Exception $e) {
			//echo $e->getMessage();
		}
	}
	else if(!in_array($p, $excepciones_p)){
		$p = "cerrarsesion";
	}
}
?>