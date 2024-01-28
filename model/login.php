<?php

require_once('model/datos.php');
require_once("model/bitacora.php");
require_once("model/enviar-correo.php");



class login extends datos
{
	PRIVATE $cedula,$correo,$link,$type,$keyword,$id,$pass;
	function __construct()
	{
		$this->keyword = "a3b5G-Hui4sXXXa";
	}
	PUBLIC function iniciarSesion($usuario,$clave)
	{
		if (!empty($usuario) && !empty($clave)) {            
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);            
			try {
				$consulta = $co->query("SELECT id,rif_cedula,tipo_identificacion,id_rol,clave FROM datos_usuarios INNER JOIN usuarios_roles WHERE usuarios_roles.id_usuario=datos_usuarios.id AND datos_usuarios.rif_cedula='$usuario'");
				$resultado = $consulta->fetch();
				//if ($resultado && password_verify($clave,$resultado['clave'])) {
				if ($resultado && password_verify($clave, $resultado['clave'])) {
					session_start();
					$_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol'] = $resultado['id_rol'];
					$_SESSION['id_usuario'] = $resultado['id'];
					$_SESSION['CONDOMINIO_TOKEN'] = password_hash($resultado["rif_cedula"]."-".$resultado["tipo_identificacion"], PASSWORD_DEFAULT);
					$r["resultado"]="correcto";
					$bitacora = new Bitacora();
					$bitacora->b_accion("Inicio sesion");
					return $r;
				}else{
					$r['resultado'] = "incorrecto";
					$r['mensaje'] = "Los datos ingresados son incorrectos";
					return $r;
				}
			
			} catch (Exception $e) {
				session_unset();
				$r['resultado'] = "error";
				$r['mensaje'] = $e->getMessage().' -LINE- '.$e->getLine()." file: ".$e->getFile();
				return $r;
			}
			finally{
				$co = null;
			}
		}
	}

	PUBLIC function reset_pass(){
		$con = $this->conecta();
		try {
			$this->validar_conexion($con);
			$con->beginTransaction();
			
			$V = new Validaciones;
			$V->validar($this->pass,"/^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9]{6,20}$/","La contraseña no es valida");

			if($this->type == "usuario"){
				$consulta = "UPDATE usuarios_roles SET clave = ? WHERE id_usuario = ?";
			}
			else if ($this->type == "habitante"){
				$consulta = "UPDATE habitantes set clave = ? WHERE id = ?";
			}
			else
			{
				throw new Exception("Error en el tipo de usuario", 1);
			}

			$consulta = $con->prepare($consulta);



			$consulta->execute([password_hash($this->pass, PASSWORD_DEFAULT), $this->id]);

			
			$r['resultado'] = 'reset_pass';
			$r['mensaje'] =  "";
			$con->commit();
		
		} catch (Validaciones $e){
			if($con instanceof PDO){
				if($con->inTransaction()){
					$con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($con instanceof PDO){
				if($con->inTransaction()){
					$con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			$con = null;
		}
		return $r;
	}

	PUBLIC function reset_pass_request (){
		//para enviar correo
		$con = $this->conecta();
		try {
			$this->validar_conexion($con);
			$con->beginTransaction();

			/*
			***************************
			***************************
			***************************

			codigo para separar la cedula del tipo de cedula


			***************************
			***************************
			***************************
			***************************
			*/

			$V = new Validaciones();
			$V->validarCedula($this->cedula);
			$V->validarEmail($this->correo);

			$url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
			if(!preg_match("/reset-pass$/", $url)){
				throw new Exception("Error in URL ($url)", 1);
			}
			$tipo_identificacion = preg_replace("/[\s-]?[0-9]*$/", "", $this->cedula);
			$this->cedula = preg_replace("/^.[\s-]?/", "", $this->cedula);


			//$tipo_identificacion = 0;// V 
			switch ($tipo_identificacion) {
				case "V":
					$tipo_identificacion = 0;
					break;
				case "E":
					$tipo_identificacion = 1;
				break;
				case "J":
					$tipo_identificacion = 2;
				break;
				case "G":
					$tipo_identificacion = 3;
				break;
			}


			//****************************

			if($this->type == "usuario"){
				$consulta = "SELECT id,razon_social as nombres, rif_cedula, correo, tipo_identificacion FROM datos_usuarios WHERE rif_cedula = ? AND tipo_identificacion = ? AND correo = ?";
			}
			else if($this->type == "habitante"){			
				$consulta = "SELECT id, cedula_rif, nombres, correo, tipo_identificacion FROM habitantes WHERE cedula_rif = ? AND tipo_identificacion = ? AND correo = ?";
			}
			else{
				throw new Exception("Error en el tipo de usuario", 1);
			}
			
			$consulta = $con->prepare($consulta);

			$consulta->execute([ $this->cedula, $tipo_identificacion, $this->correo ]);
			if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
				$resp["type"] = $this->type;
				$resp["date"] = time();

				$temp = $resp;
				$resp = json_encode($resp);

				//$resp = urlencode($resp);

				$iv_size = openssl_cipher_iv_length("aes-256-cbc");  // Obtener el tamaño del IV con OpenSSL
				$iv = openssl_random_pseudo_bytes($iv_size);        // Generar un IV aleatorio con OpenSSL


				$cifrado = openssl_encrypt($resp, "aes-256-cbc", $this->keyword, 0, $iv);

				$texto_final = urlencode($cifrado.$iv);

				$url = $url."&a=".$texto_final;

				$desci = urldecode($texto_final);


				$html=<<<END
				<html>
				<head>
				<style type="text/css">
					.cont{
						display: flex;
						justify-content: center;
					}
					table{
						text-align: center;
					}
					*{border: none;}
					thead tr {background-color: #2E86C1;color: #fff};
				</style>
				</head>
				<body>
				<div>
					<div class="cont">
						<table>
							<thead>
								<tr><th><h4>Conjunto residencial Jose Maria Vargas</h4></th></tr>
							</thead>
							<tbody>
								<tr>
									<td>Hemos recibido una solicitud para restablecer tu contraseña con nosotros.</td>
								</tr>
								<tr>
									<td>
										Pulsa <a href="$url">Aqui</a> para completar el proceso
									</td>
								</tr>
								<tr>
									<td>La contraseña no sera modificada hasta que se siga el enlace proporcionado y cree una nueva</p></td>
								</tr>
								<tr>
									<td>Este enlace tiene un periodo de vida de 5 minutos</p></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				</body>
				</html>
				END;

				$Send_Email = new enviarcorreo;

				$Send_Email->custom_email($html,"Restablecer Contraseña",$temp["correo"],$temp["nombres"]);


			}
			else{
				$r["mensaje"] = "Usuario no encontrado";
			}
			
			$r['resultado'] = 'reset_pass_request';
			$r["mensaje2"] =$url;
			//$con->commit();
			return $r;
		
		} catch (Validaciones $e){
			if($con instanceof PDO){
				if($con->inTransaction()){
					$con->rollBack();
				}
			}

			//NO OCURRIRA NADA

			// $r['resultado'] = 'is-invalid';
			// $r['mensaje'] =  $e->getMessage();
			// $r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($con instanceof PDO){
				if($con->inTransaction()){
					$con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		finally{
			$con = null;
		}
		return $r;
	}






	PUBLIC function get_cedula(){
		return $this->cedula;
	}
	PUBLIC function set_cedula($value){
		$this->cedula = $value;
	}
	PUBLIC function get_correo(){
		return $this->correo;
	}
	PUBLIC function set_correo($value){
		$this->correo = $value;
	}
	PUBLIC function get_link(){
		return $this->link;
	}
	PUBLIC function set_link($value){
		$this->link = $value;
	}
	PUBLIC function get_type(){
		return $this->type;
	}
	PUBLIC function set_type($value){
		$this->type = $value;
	}
	PUBLIC function get_keyword(){
		return $this->keyword;
	}
	PUBLIC function set_keyword($value){
		$this->keyword = $value;
	}

	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
	PUBLIC function get_pass(){
		return $this->pass;
	}
	PUBLIC function set_pass($value){
		$this->pass = $value;
	}
} 