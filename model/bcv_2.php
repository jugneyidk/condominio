<?php 



session_start();

require_once("datos.php");
require_once("bitacora.php");
$c = new datos;
$con = $c->conecta();
if($con instanceof PDO){
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

//echo "http://".$_SERVER["HTTP_HOST"].preg_replace('/index\.php/', '', $_SERVER['PHP_SELF']);
if(isset($_POST["control"])){

		date_default_timezone_set("America/Caracas");

		

		



		$cliente=curl_init();
		//curl_setopt($cliente, CURLOPT_URL, 'http://localhost/items_x/condominio/condominio/');
		curl_setopt($cliente, CURLOPT_URL, 'https://www.bcv.org.ve/');
		curl_setopt($cliente, CURLOPT_HEADER, 0);
		curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cliente, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($cliente, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($cliente, CURLOPT_TIMEOUT, 15);
		$content=curl_exec($cliente);
		curl_close($cliente);

		if(is_string($content))
		{
			$dolar;
			preg_match('/USD.*\n\s*.*<strong>\s([\d,]+)/', $content,$dolar);
			$dolar=$dolar[1];
			$dolar = preg_replace("/,/", ".", $dolar);
			$dolar = number_format(($dolar),2);
		}

		
		if(isset($dolar) and ($con instanceof PDO) and preg_match("/^[0-9]+[.][0-9]{2}$/", $dolar)){
			try {
				$con->beginTransaction();
				// code

				$consulta = $con->query("SELECT * FROM tipo_cambio_divisa WHERE 1 ORDER BY fecha DESC LIMIT 1;")->fetch(PDO::FETCH_ASSOC);

					// Recibimos el timestamp de MySQL
					$tiempo_old = $consulta["fecha"];

					// Convertimos el timestamp a un objeto DateTime
					$fecha_old = DateTime::createFromFormat("Y-m-d H:i:s", $tiempo_old);

					// Obtenemos la fecha actual
					$fecha_actual = new DateTime();

					// Comparamos la fecha actual con el timestamp de MySQL
					if ($fecha_actual->format("Y-m-d") == $fecha_old->format("Y-m-d")) {
					    
					    // mismo día diferente monto
					    if($dolar != $consulta["monto"]){
					    	$consulta = $con->prepare("INSERT INTO tipo_cambio_divisa (monto) VALUES (?);");
					    	$consulta->execute([$dolar]);

					    	$bitacora = new Bitacora();
					    	$bitacora->set_usuario_id(null);
					    	$bitacora->set_tipo(null);
					    	$bitacora->b_registro("Registro nuevo monto BCV ($dolar)1");
					    	// $bitacora->set_c(null);

					    	echo json_encode(["resultado" => "actualizar" ,"mensaje" => ["fecha" => $fecha_actual->format("Y-m-d H:i:s"), "monto" => $dolar]]);
					    }
					    else {echo "no actualizado. 001";}//cuando el dolar es de el mismo dia y no ha cambiado
					} else if ($fecha_old < $fecha_actual) {

					    $consulta = $con->prepare("INSERT INTO tipo_cambio_divisa (monto) VALUES (?);");
					    $consulta->execute([$dolar]);
					    $bitacora = new Bitacora();
					    $bitacora->set_usuario_id(null);
					    $bitacora->set_tipo(null);
					    $bitacora->b_registro("Registro nuevo monto BCV ($dolar)2");
					    // $bitacora->set_c(null);

					    echo json_encode(["resultado" => "actualizar (de fechas diferentes)" ,"mensaje" => ["fecha" => $fecha_actual->format("Y-m-d H:i:s"), "monto" => $dolar]]);

					}
					else{
						echo "no actualizado. 002";//XD
					}
					
					$con->commit();
			} catch (Exception $e) {
				if($c instanceof PDO){
					if($c->inTransaction()){
						$c->rollBack();
					}
				}
			
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
				echo $r["mensaje"];
			}
			
		}
		else{
			echo "No se pudo optener el monto del bcv";
		}

}
else{
	try {
		$c->validar_conexion($con);
		$con->beginTransaction();
		$consulta = $con->query("SELECT * FROM tipo_cambio_divisa WHERE 1 ORDER by fecha DESC;")->fetch(PDO::FETCH_ASSOC);
		
		$r['resultado'] = 'bcv';
		$r['mensaje'] =  $consulta;
		$r["extra"] = $_POST;
		$con->commit();
	
	} catch (Exception $e) {
		if($con instanceof PDO){
			if($con->inTransaction()){
				$con->rollBack();
			}
		}
	
		$r['resultado'] = 'error';
		$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();

	}
	echo json_encode($r);
}

?>