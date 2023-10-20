<?php 
session_start();
//echo "http://".$_SERVER["HTTP_HOST"].preg_replace('/index\.php/', '', $_SERVER['PHP_SELF']);
	if(isset($_SESSION['id_usuario']) || isset($_SESSION['id_habitante'])){
		date_default_timezone_set("America/Caracas");

		// horas a la que revisara
		require_once("datos.php");
		require_once("bitacora.php");
		$c = new datos;
		

		$hora_guardada_para_bcv = ["00:00","10:00","13:00","17:00"];
		function control_hora_bcv($hora_guardada_para_bcv_2){
			try {
				$c = new datos;
				$con = $c->conecta();
				$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$c->validar_conexion($con);
				$con->beginTransaction();
				// code
				$consulta = $con->query("SELECT * FROM config WHERE titulo = ".CONFIG_ARRAY["BCV"][0]." LIMIT 1;")->fetch(PDO::FETCH_ASSOC);
				if($consulta){
					return $consulta["valor"];
				}else{
					$to_return;
					if($hora_guardada_para_bcv_2[0] < date("H:i")){ $to_return =0 ; }
					if($hora_guardada_para_bcv_2[1] < date("H:i")){ $to_return =1 ; }
					if($hora_guardada_para_bcv_2[2] < date("H:i")){ $to_return =2 ; }
					if($hora_guardada_para_bcv_2[3] < date("H:i")){ $to_return =3 ; }
					return $hora_guardada_para_bcv_2[$to_return];
				}

				
				
				//$con->commit();
			
			} catch (Exception $e) {
				if($con instanceof PDO){
					if($con->inTransaction()){
						$con->rollBack();
					}
				}
				$to_return;
				if($hora_guardada_para_bcv_2[0] < date("H:i")){ $to_return =0 ; }
				if($hora_guardada_para_bcv_2[1] < date("H:i")){ $to_return =1 ; }
				if($hora_guardada_para_bcv_2[2] < date("H:i")){ $to_return =2 ; }
				if($hora_guardada_para_bcv_2[3] < date("H:i")){ $to_return =3 ; }
				return $hora_guardada_para_bcv_2[$to_return];
			
			}
			
		}

		if(!isset($_SESSION["Siguiente_hora_para_revisar"])){
			$_SESSION["Siguiente_hora_para_revisar"] = control_hora_bcv($hora_guardada_para_bcv);
		}
		if(date("H:i") > $_SESSION["Siguiente_hora_para_revisar"]){
			$cliente=curl_init();
			//curl_setopt($cliente, CURLOPT_URL, 'http://localhost/items_x/condominio/condominio/');
			curl_setopt($cliente, CURLOPT_URL, 'https://www.bcv.org.ve/');
			curl_setopt($cliente, CURLOPT_HEADER, 0);
			curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cliente, CURLOPT_TIMEOUT, 9);

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

			if(isset($dolar)){

				

				try {
					$con = $c->conecta();
					$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					$c->validar_conexion($con);
					$con->beginTransaction();
					
					$consulta = $con->prepare("INSERT INTO tipo_cambio_divisa(
												monto
												)
												SELECT
												:dolar
												FROM DUAL
												WHERE (SELECT IF(:dolar <> d2.monto,1,0) FROM tipo_cambio_divisa as d2 WHERE 1 ORDER BY fecha DESC LIMIT 1);");
					$consulta->bindValue(":dolar",$dolar);
					$consulta->execute();

					if(isset($_SESSION["id_usuario"]) and $consulta->rowCount() > 0){
						$b = new Bitacora;
						$b->b_registro("Registro nuevo monto BCV (no involucra usuario)");
					}
					$consulta = $con->query("SELECT * FROM tipo_cambio_divisa WHERE 1 ORDER BY fecha DESC LIMIT 1;")->fetch(PDO::FETCH_ASSOC);
					
					$r['resultado'] = 'bcv';
					$r['mensaje'] =  $consulta;



					if($_SESSION["Siguiente_hora_para_revisar"] == $hora_guardada_para_bcv[0]) $_SESSION["Siguiente_hora_para_revisar"] = $hora_guardada_para_bcv[1];
					else if($_SESSION["Siguiente_hora_para_revisar"] == $hora_guardada_para_bcv[1]) $_SESSION["Siguiente_hora_para_revisar"] = $hora_guardada_para_bcv[2];
					else if($_SESSION["Siguiente_hora_para_revisar"] == $hora_guardada_para_bcv[2]) $_SESSION["Siguiente_hora_para_revisar"] = $hora_guardada_para_bcv[3];
					else if($_SESSION["Siguiente_hora_para_revisar"] == $hora_guardada_para_bcv[3]) $_SESSION["Siguiente_hora_para_revisar"] = $hora_guardada_para_bcv[0];

					if($con->query("SELECT * FROM config WHERE titulo = '".CONFIG_ARRAY["BCV"][0]."' LIMIT 1;")->fetch()){
						$con->query("UPDATE config set valor = '{$_SESSION['Siguiente_hora_para_revisar']}' WHERE titulo = '".CONFIG_ARRAY["BCV"][0]."';");
					}
					else{
						$con->query("INSERT INTO config (titulo,valor) VALUES ('".CONFIG_ARRAY['BCV'][0]."','{$_SESSION['Siguiente_hora_para_revisar']}');");
					}
					$r["extra"] = "INSERT INTO config (titulo,valor) VALUES ('".CONFIG_ARRAY['BCV'][0]."','{$_SESSION['Siguiente_hora_para_revisar']}');";

					$con->commit();


				
				} catch (Validaciones $e){
					if($con instanceof PDO){
						if($con->inTransaction()){
							$con->rollBack();
						}
					}
					$r['resultado'] = 'is-invalid';
					$r['mensaje'] =  $e->getMessage().": Code : ".$e->getLine();
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
			else{
				$con = $c->conecta();
				$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$consulta = $con->query("SELECT * FROM tipo_cambio_divisa WHERE 1 ORDER by fecha DESC;")->fetch(PDO::FETCH_ASSOC);
				echo json_encode(["resultado"=>"bcv","mensaje"=>$consulta, "extra" => "from bd 1 sesion: {$_SESSION['Siguiente_hora_para_revisar']} actual: ".date('H:i')]);
			}
		}
		else {
			$con = $c->conecta();
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$consulta = $con->query("SELECT * FROM tipo_cambio_divisa WHERE 1 ORDER by fecha DESC;")->fetch(PDO::FETCH_ASSOC);
			echo json_encode(["resultado"=>"bcv","mensaje"=>$consulta, "extra" => "from bd 2 sesion: {$_SESSION['Siguiente_hora_para_revisar']} actual: ".date('H:i')]);
		}
	}
	else{echo json_encode(["resultado"=>'vacio']);}
?>