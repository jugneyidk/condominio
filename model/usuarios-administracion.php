<?php

require_once('model/datos.php');
require_once("model/bitacora.php");



class usuarios extends datos
{
	PRIVATE $id, $rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol;

	PUBLIC function incluir_S($rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol){
		return $this->incluir($rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol);
	}
	PUBLIC function modificar_S($id, $rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol){
		return $this->modificar($id, $rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol);
	}
	PUBLIC function eliminar_S($id){
		return $this->eliminar($id);
	}


	PUBLIC function chequearpermisos(){
		$id_rol = $_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol'];
		$modulo = $_GET['p'];
		$co = $this->conecta(); 
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$guarda = $co->query("SELECT * FROM `roles_modulos` inner join `modulos` on roles_modulos.id_modulo = modulos.id inner join `roles` on roles_modulos.id_rol = roles.id where modulos.nombre = '$modulo' and roles_modulos.id_rol = '$id_rol'");
		$guarda->execute();
		$fila = array();
		$fila = $guarda->fetch(PDO::FETCH_NUM);
		return $fila;		
	}
	PRIVATE function incluir($rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol)
	{
		if (!$this->existe($rif_cedula, $tipo_identificacion, 1)) {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$r = array();
			try {
				$guarda = $co->query("insert into datos_usuarios(rif_cedula,tipo_identificacion,razon_social,domicilio_fiscal,telefono,correo) 
		   values ('$rif_cedula','$tipo_identificacion','$razon_social','$domicilio_fiscal','$telefono','$correo')");
				$lid = $co->lastInsertId();
				$contrasena = password_hash($password, PASSWORD_DEFAULT);
				$gd = $co->query("insert into usuarios_roles(id_usuario,id_rol,clave) 
		   values ('$lid','$rol','$contrasena')");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  "Usuario Incluido";
				$bitacora = new Bitacora();
				$bitacora->b_incluir();

				

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
			$resultado = $co->query("SELECT
			DISTINCT
			    id,
			    rif_cedula,
			    tipo_identificacion,
			    razon_social,
			    domicilio_fiscal,
			    telefono,
			    correo,
			    id_rol
			FROM
			    datos_usuarios
			INNER JOIN usuarios_roles WHERE usuarios_roles.id_usuario = datos_usuarios.id
			ORDER BY
			    id
			DESC
			    ");
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
	PRIVATE function modificar($id, $rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol)
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
					$contrasena = password_hash($password, PASSWORD_DEFAULT);
					$co->query("Update usuarios_roles set 
							id_rol = '$rol',
							clave = '$contrasena'
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
				$bitacora = new Bitacora();
				$bitacora->b_modificar();

			} catch (Exception $e) {
				return $e->getMessage();
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Usuario no encontrado";
		}
		return $r;
	}
	PRIVATE function eliminar($id)
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
				
				$bitacora = new Bitacora();
				$bitacora->b_eliminar();
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


	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
	PUBLIC function get_rif_cedula(){
		return $this->rif_cedula;
	}
	PUBLIC function set_rif_cedula($value){
		$this->rif_cedula = $value;
	}
	PUBLIC function get_tipo_identificacion(){
		return $this->tipo_identificacion;
	}
	PUBLIC function set_tipo_identificacion($value){
		$this->tipo_identificacion = $value;
	}
	PUBLIC function get_razon_social(){
		return $this->razon_social;
	}
	PUBLIC function set_razon_social($value){
		$this->razon_social = $value;
	}
	PUBLIC function get_domicilio_fiscal(){
		return $this->domicilio_fiscal;
	}
	PUBLIC function set_domicilio_fiscal($value){
		$this->domicilio_fiscal = $value;
	}
	PUBLIC function get_telefono(){
		return $this->telefono;
	}
	PUBLIC function set_telefono($value){
		$this->telefono = $value;
	}
	PUBLIC function get_correo(){
		return $this->correo;
	}
	PUBLIC function set_correo($value){
		$this->correo = $value;
	}
	PUBLIC function get_password(){
		return $this->password;
	}
	PUBLIC function set_password($value){
		$this->password = $value;
	}
	PUBLIC function get_rol(){
		return $this->rol;
	}
	PUBLIC function set_rol($value){
		$this->rol = $value;
	}



}
