<?php

 require_once('model/datos.php');
 require_once("model/bitacora.php");



class usuarios extends datos
{
	PRIVATE $id, $rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol;

	PUBLIC function incluir_S($rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol){

		$this->set_rif_cedula($rif_cedula);
		$this->set_tipo_identificacion($tipo_identificacion);
		$this->set_razon_social($razon_social);
		$this->set_domicilio_fiscal($domicilio_fiscal);
		$this->set_telefono($telefono);
		$this->set_correo($correo);
		$this->set_password($password);
		$this->set_rol($rol);
		return $this->incluir();
	}
	PUBLIC function modificar_S($id, $rif_cedula, $tipo_identificacion, $razon_social, $domicilio_fiscal, $telefono, $correo, $password, $rol){
		$this->set_id($id);
		$this->set_rif_cedula($rif_cedula);
		$this->set_tipo_identificacion($tipo_identificacion);
		$this->set_razon_social($razon_social);
		$this->set_domicilio_fiscal($domicilio_fiscal);
		$this->set_telefono($telefono);
		$this->set_correo($correo);
		$this->set_password($password);
		$this->set_rol($rol);
		return $this->modificar();
	}
	PUBLIC function eliminar_S($id){
		$this->set_id($id);
		return $this->eliminar();
	}


	PUBLIC function chequearpermisos(){
		$id_rol = $_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol'];
		$modulo = $_GET['p'];
		$co = $this->conecta(); 
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$guarda = $co->prepare("SELECT * FROM `roles_modulos` INNER JOIN `modulos` ON roles_modulos.id_modulo = modulos.id INNER JOIN `roles` ON roles_modulos.id_rol = roles.id WHERE modulos.nombre = ? AND roles_modulos.id_rol = ?");
		$guarda->execute([$modulo, $id_rol]);
		$fila = array();
		$fila = $guarda->fetch(PDO::FETCH_NUM);
		return $fila;		
	}
	PRIVATE function incluir()
	{
		$rif_cedula = $this->rif_cedula;
		$tipo_identificacion = $this->tipo_identificacion;
		$razon_social = $this->razon_social;
		$domicilio_fiscal = $this->domicilio_fiscal;
		$telefono = $this->telefono;
		$correo = $this->correo;
		$password = $this->password;
		$rol = $this->rol;
		if (!$this->existe($rif_cedula, $tipo_identificacion, 1)) {
			$co = $this->conecta();
			$this->validar_conexion($co);
			$r = array();
			try {
				$co->beginTransaction();
				$guarda = $co->prepare("INSERT INTO datos_usuarios(rif_cedula,tipo_identificacion,razon_social,domicilio_fiscal,telefono,correo) 
		   		VALUES (:rif_cedula,:tipo_identificacion,:razon_social,:domicilio_fiscal,:telefono,:correo)");
				$guarda->bindValue(":rif_cedula",$rif_cedula);
				$guarda->bindValue(":tipo_identificacion",$tipo_identificacion);
				$guarda->bindValue(":razon_social",$razon_social);
				$guarda->bindValue(":domicilio_fiscal",$domicilio_fiscal);
				$guarda->bindValue(":telefono",$telefono);
				$guarda->bindValue(":correo",$correo);
				$guarda->execute();

				$lid = $co->lastInsertId();
				$contrasena = password_hash($password, PASSWORD_DEFAULT);
				$gd = $co->prepare("INSERT INTO usuarios_roles(id_usuario,id_rol,clave) 
		   		VALUES (:lid,:rol,:contrasena)");
				$gd->bindValue(":lid",$lid);
				$gd->bindValue(":rol",$rol);
				$gd->bindValue(":contrasena",$contrasena);
				$gd->execute();

				$r['resultado'] = 'incluir';
				$r['mensaje'] =  "Usuario Incluido";
				$bitacora = new Bitacora($co);
				$bitacora->b_registro("Registró el nuevo usuario \"".TIPO_INDENT_ARRAY[$tipo_identificacion].$rif_cedula."\"");

				

				$co->commit();
			} catch (Exception $e) {
				if($co instanceof PDO){
					if($co->inTransaction()){
						$co->rollBack();
					}
				}
			
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
			}finally{$co = null;}
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
			INNER JOIN usuarios_roles WHERE usuarios_roles.id_usuario = datos_usuarios.id AND rif_cedula <> '000000000'
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
		}finally{$co = null;}
		return $r;
	}
	PRIVATE function modificar()
	{
		$id = $this->id;
		$rif_cedula = $this->rif_cedula;
		$tipo_identificacion = $this->tipo_identificacion;
		$razon_social = $this->razon_social;
		$domicilio_fiscal = $this->domicilio_fiscal;
		$telefono = $this->telefono;
		$correo = $this->correo;
		$password = $this->password;
		$rol = $this->rol;
		if ($this->existe($id, 0, 2)) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				$this->validar_conexion($co);
				$co->beginTransaction();
				$consulta = $co->prepare("SELECT rif_cedula,tipo_identificacion FROM datos_usuarios WHERE id = ?");
				$consulta->execute([$id]);
				$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

				if($consulta["rif_cedula"] != $rif_cedula or $consulta["tipo_identificacion"] != $tipo_identificacion){
					if ($this->existe($rif_cedula, $tipo_identificacion, 1)){
						throw new Validaciones("La nueva cedula ya esta registrada", 1);
					}

				}
				$consulta = $co->prepare("UPDATE datos_usuarios SET 
						rif_cedula = :rif_cedula,
						tipo_identificacion = :tipo_identificacion,
						razon_social = :razon_social,
						domicilio_fiscal = :domicilio_fiscal	,					
						telefono = :telefono,
						correo = :correo
						WHERE
						id = :id");

				$consulta->bindValue(":rif_cedula",$rif_cedula);
				$consulta->bindValue(":tipo_identificacion",$tipo_identificacion);
				$consulta->bindValue(":razon_social",$razon_social);
				$consulta->bindValue(":domicilio_fiscal",$domicilio_fiscal);
				$consulta->bindValue(":telefono",$telefono);
				$consulta->bindValue(":correo",$correo);
				$consulta->bindValue(":id",$id);
				$consulta->execute();


				if($password){
					$consulta = $co->prepare("UPDATE usuarios_roles SET 
								id_rol = :rol,
								clave = :contrasena
								WHERE
								id_usuario = :id
								");
					$contrasena = password_hash($password, PASSWORD_DEFAULT);
					$consulta->bindValue(":contrasena",$contrasena);
				}
				else{
					$consulta = $co->prepare("UPDATE usuarios_roles SET 
								id_rol = :rol
								WHERE
								id_usuario = :id
								");
				}



				$consulta->bindValue(":rol",$rol);
				$consulta->bindValue(":id",$id);
				$consulta->execute();

				$r['resultado'] = 'modificar';
				$r['mensaje'] =  "Usuario modificado correctamente";
				$bitacora = new Bitacora($co);
				$bitacora->b_registro("Modificó el usuario \"".TIPO_INDENT_ARRAY[$tipo_identificacion].$rif_cedula."\"");
				

				$co->commit();
			} catch (Validaciones $e){
				if($co instanceof PDO){
					if($co->inTransaction()){
						$co->rollBack();
					}
				}
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
				$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
			}
			 catch (Exception $e) {
				if($co instanceof PDO){
					if($co->inTransaction()){
						$co->rollBack();
					}
				}
			
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
			}finally{$co = null;}

		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Usuario no encontrado";
		}
		return $r;
	}
	PRIVATE function eliminar()
	{
		$id = $this->id;
		if ($this->existe($id, 0, 2)) {
			$co = $this->conecta();
			try {
				$this->validar_conexion($co);
				$co->beginTransaction();
				if(isset($_SESSION["id_usuario"]) and $this->id == $_SESSION["id_usuario"]){
					throw new Exception("No se puede eliminar el usuario de la sesión actual", 1);
				}
				
				$consulta = $co->prepare("DELETE FROM datos_usuarios WHERE id = :id ");
				$consulta->bindValue(":id",$id);
				$consulta->execute();

				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  "Usuario Eliminado";
				
				$bitacora = new Bitacora();
				$bitacora->b_registro("Eliminó el usuario \"$id\"");
				$co->commit();
			} catch (Exception $e) {
				if($co instanceof PDO){
					if($co->inTransaction()){
						$co->rollBack();
					}
				}
				$r['resultado'] = 'error';
				if ($e->getCode()=='23000') {
					$r['mensaje'] =  "El usuario no puede ser eliminado si ha procesado deudas/pagos.";
				}else{
					$r['mensaje'] =  $e->getMessage();					
				}
			}finally{$co = null;}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Usuario no encontrado";
		}
		return $r;
	}

	PUBLIC function existe($rif_cedula, $tipo_identificacion, $caso)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		switch ($caso) {
			case '1':
				try {
					$resultado = $co->prepare("Select * from datos_usuarios where rif_cedula= ? and tipo_identificacion= ? ");
					$resultado->execute([$rif_cedula, $tipo_identificacion]);
					$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
					if ($fila) {
						return true;
					} else {
						return false;
					}
				} catch (Exception $e) {
					return false;
				}finally{$co = null;}
				break;
			case '2':
				try {
					$resultado = $co->prepare("Select * from datos_usuarios where id=?");
					$resultado->execute([$rif_cedula]);
					$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
					if ($fila) {
						return true;
					} else {
						return false;
					}
				} catch (Exception $e) {
					return false;
				}finally{$co = null;}
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
