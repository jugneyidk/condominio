<?php
require_once('model/datos.php');
require_once("model/bitacora.php");


class habitantes extends datos
{
	PRIVATE $id, $cedula_rif, $tipo_identificacion, $nombres, $apellidos, $telefono, $correo, $domicilio_fiscal, $clave;

	PUBLIC function incluir_s(){
		return $this->incluir();
	}
	PUBLIC function modificar_s(){
		return $this->modificar();
	}
	PUBLIC function eliminar_s(){
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
	PUBLIC function incluir()
	{

		$cedula_rif = $this->cedula_rif;
		$tipo_identificacion = $this->tipo_identificacion;
		$nombres = $this->nombres;
		$apellidos = $this->apellidos;
		$telefono = $this->telefono;
		$correo = $this->correo;
		$domicilio_fiscal = $this->domicilio_fiscal;


		if (!$this->existe($cedula_rif, $tipo_identificacion, 1)) {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				$consulta = $co->prepare("INSERT INTO habitantes(
						cedula_rif,
						tipo_identificacion,
						nombres,
						apellidos,
						telefono,
						correo,
						domicilio_fiscal,
						clave
						)
						VALUES(
						:cedula_rif,
						:tipo_identificacion,
						:nombres,
						:apellidos,
						:telefono,
						:correo,
						:domicilio_fiscal,
						:clave
						)");

				$consulta->bindValue(':cedula_rif',$cedula_rif);
				$consulta->bindValue(':tipo_identificacion',$tipo_identificacion);
				$consulta->bindValue(':nombres',$nombres);
				$consulta->bindValue(':apellidos',$apellidos);
				$consulta->bindValue(':telefono',$telefono);
				$consulta->bindValue(':correo',$correo);
				$consulta->bindValue(':domicilio_fiscal',$domicilio_fiscal);
				$consulta->bindValue(':clave',$this->clave);

				$consulta->execute();



				$r['resultado'] = 'incluir';
				$r['mensaje'] =  "Registro Incluido";
				$bitacora = new Bitacora();
				$bitacora->b_incluir();

				
			} catch (Exception $e) {
				return $e->getMessage();
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "La cedula ingresada ya existe";
		}
		return $r;
	}
	PUBLIC function modificar()
	{
		$id = $this->id;
		$cedula_rif = $this->cedula_rif;
		$tipo_identificacion = $this->tipo_identificacion;
		$nombres = $this->nombres;
		$apellidos = $this->apellidos;
		$telefono = $this->telefono;
		$correo = $this->correo;
		$domicilio_fiscal = $this->domicilio_fiscal;
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id,0,2)) {
			try {
				$consulta = "UPDATE habitantes SET 
						cedula_rif = :cedula_rif,
						tipo_identificacion = :tipo_identificacion,
						nombres = :nombres,
						apellidos = :apellidos,
						telefono = :telefono,
						correo = :correo,
						domicilio_fiscal = :domicilio_fiscal";
				if($this->clave != ''){
					$consulta.= ", clave = :clave";
				}

				$consulta.=" WHERE id = :id;";
				$consulta = $co->prepare($consulta);

				$consulta->bindValue(":cedula_rif",$cedula_rif);
				$consulta->bindValue(":tipo_identificacion",$tipo_identificacion);
				$consulta->bindValue(":nombres",$nombres);
				$consulta->bindValue(":apellidos",$apellidos);
				$consulta->bindValue(":telefono",$telefono);
				$consulta->bindValue(":correo",$correo);
				$consulta->bindValue(":domicilio_fiscal",$domicilio_fiscal);
				$consulta->bindValue(":id",$id);

				if($this->clave != ''){
					$consulta->bindValue(":clave",$this->clave);
				}
				$consulta->execute();
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  "Registro modificado correctamente";
				$bitacora = new Bitacora();
				$bitacora->b_modificar();

				
			} catch (Exception $e) {
				return $e->getMessage().":".$e->getLine();
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Habitante no encontrado";
		}
		return $r;
	}
	PUBLIC function eliminar()
	{
		$id = $this->id;
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id,0,2)) {
			try {
				$co->prepare("DELETE FROM habitantes WHERE id = :id ");

				$consulta->bindValue(":id",$id);
				$consulta->execute();

				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  "Registro Eliminado";
				$bitacora = new Bitacora();
				$bitacora->b_eliminar();
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode()=='23000') {
					$r['mensaje'] =  "El habitante no puede ser eliminado si está asignado a un apartamento.";
				}else{
					$r['mensaje'] =  $e->getMessage();					
				}
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Habitante no encontrado";
		}
		return $r;
	}
	PUBLIC function listadohabitantes()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select * from habitantes ORDER BY id DESC");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='coloca(this);'>";
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
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[7];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[0];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[2];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listado_habitantes';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PRIVATE function existe($cedula_rif, $tipo_identificacion, $caso)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		switch ($caso) {
			case '1':
				try {
					$resultado = $co->query("Select * from habitantes where cedula_rif='$cedula_rif' and tipo_identificacion='$tipo_identificacion'");
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
					$resultado = $co->query("Select * from habitantes where id='$cedula_rif'");
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
	PUBLIC function get_cedula_rif(){
		return $this->cedula_rif;
	}
	PUBLIC function set_cedula_rif($value){
		$this->cedula_rif = $value;
	}
	PUBLIC function get_tipo_identificacion(){
		return $this->tipo_identificacion;
	}
	PUBLIC function set_tipo_identificacion($value){
		$this->tipo_identificacion = $value;
	}
	PUBLIC function get_nombres(){
		return $this->nombres;
	}
	PUBLIC function set_nombres($value){
		$this->nombres = $value;
	}
	PUBLIC function get_apellidos(){
		return $this->apellidos;
	}
	PUBLIC function set_apellidos($value){
		$this->apellidos = $value;
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
	PUBLIC function get_domicilio_fiscal(){
		return $this->domicilio_fiscal;
	}
	PUBLIC function set_domicilio_fiscal($value){
		$this->domicilio_fiscal = $value;
	}
	PUBLIC function get_clave(){
		return $this->clave;
	}
	PUBLIC function set_clave($value){
		if($value !=''){
			$this->clave = password_hash($value, PASSWORD_DEFAULT);
		}
		else
		{
			$this->clave = '';
		}
	}
	

}
