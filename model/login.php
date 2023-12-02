<?php

require_once('model/datos.php');
require_once("model/bitacora.php");



class login extends datos
{
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
				return $e->getMessage();
			}
		}
	}

} 