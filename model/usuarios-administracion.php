<?php

require_once('model/datos.php');

class usuarios extends datos
{
	PUBLIC function chequearpermisos(){
		$id_rol = $_SESSION['rol'];
		$modulo = $_GET['p'];
		$co = $this->conecta(); 
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$guarda = $co->query("SELECT * FROM `roles_modulos` inner join `modulos` on roles_modulos.id_modulo = modulos.id inner join `roles` on roles_modulos.id_rol = roles.id where modulos.nombre = '$modulo' and roles_modulos.id_rol = '$id_rol'");
		$guarda->execute();
		$fila = array();
		$fila = $guarda->fetch(PDO::FETCH_NUM);
		return $fila;		
	}
	PUBLIC function incluir($rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol)
	{
		if (!$this->existe($rif_cedula, $tipo_identificacion, 1)) {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$r = array();
			try {
				$guarda = $co->query("insert into datos_usuarios(rif_cedula,tipo_identificacion,razon_social,domicilio_fiscal,telefono,correo) 
		   values ('$rif_cedula','$tipo_identificacion','$razon_social','$domicilio_fiscal','$telefono','$correo')");
				$lid = $co->lastInsertId();
				$contrasena = password_hash($password, PASSWORD_BCRYPT);
				$gd = $co->query("insert into usuarios_roles(id_usuario,id_rol,clave) 
		   values ('$lid','$rol','$password')");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  "Usuario Incluido";
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "La cedula ingresada ya existe";
		}
		return $r;
	}
	PUBLIC function listadousuarios()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select id, 
			rif_cedula,
			tipo_identificacion,
			razon_social,
			domicilio_fiscal,
			telefono,
			correo,
			id_rol 
			from 
			datos_usuarios
			inner join usuarios_roles where usuarios_roles.id_usuario=datos_usuarios.id ORDER BY id DESC");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocausuario(this);'>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[0];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					if ($r[2] == 0) {
						$respuesta = $respuesta . 'V';
					} elseif ($r[2] == 1) {
						$respuesta = $respuesta . 'E';
					} elseif ($r[2] == 2) {
						$respuesta = $respuesta . 'J';
					}
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[1];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[3];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[4];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[5];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[6];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[2];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[7];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listado_usuarios';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function modificar($id, $rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id, 0, 2)) {
			try {
				$co->query("Update datos_usuarios set 
						rif_cedula = '$rif_cedula',
						tipo_identificacion = '$tipo_identificacion',
						razon_social = '$razon_social',
						domicilio_fiscal = '$domicilio_fiscal'	,					
						telefono = '$telefono',
						correo = '$correo'
						where
						id = '$id'
						");
				if ($password) {
					$contrasena = password_hash($password, PASSWORD_BCRYPT);
					$co->query("Update usuarios_roles set 
							id_rol = '$rol',
							clave = '$password'
							where
							id_usuario = '$id'
							");
				}else{
					$co->query("Update usuarios_roles set 
							id_rol = '$rol'
							where
							id_usuario = '$id'
							");
				}
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  "Usuario modificado correctamente";
			} catch (Exception $e) {
				return $e->getMessage();
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Usuario no encontrado";
		}
		return $r;
	}
	PUBLIC function eliminar($id)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id, 0, 2)) {
			try {
				// $co->query("delete from usuarios_roles
				// 		where
				// 		id_usuario = '$id'
				// 		");
				$co->query("delete from datos_usuarios
						where
						id = '$id'
						");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  "Usuario Eliminado";
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode()=='23000') {
					$r['mensaje'] =  "El usuario no puede ser eliminado si ha procesado deudas/pagos.";
				}else{
					$r['mensaje'] =  $e->getMessage();					
				}
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Usuario no encontrado";
		}
		return $r;
	}
	function existe($rif_cedula, $tipo_identificacion, $caso)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		switch ($caso) {
			case '1':
				try {
					$resultado = $co->query("Select * from datos_usuarios where rif_cedula='$rif_cedula' and tipo_identificacion='$tipo_identificacion'");
					$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
					if ($fila) {
						return true;
					} else {
						return false;
					}
				} catch (Exception $e) {
					return false;
				}
				break;
			case '2':
				try {
					$resultado = $co->query("Select * from datos_usuarios where id='$rif_cedula'");
					$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
					if ($fila) {
						return true;
					} else {
						return false;
					}
				} catch (Exception $e) {
					return false;
				}
				break;
			default:
				break;
		}
	}
}
