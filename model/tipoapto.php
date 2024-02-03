<?php

require_once('model/datos.php');
require_once("model/bitacora.php");



class tipoapto extends datos
{
	PRIVATE $id_tipo_apartamento, $descripcion, $alicuota;
	PUBLIC function chequearpermisos(){
		$id_rol = $_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol'];
		$modulo = $_GET['p'];
		$co = $this->conecta(); 
		$guarda = $co->prepare("SELECT * FROM `roles_modulos` INNER JOIN `modulos` ON roles_modulos.id_modulo = modulos.id INNER JOIN `roles` ON roles_modulos.id_rol = roles.id WHERE modulos.nombre = ? AND roles_modulos.id_rol = ?");
		$guarda->execute([$modulo, $id_rol]);
		$fila = array();
		$fila = $guarda->fetch(PDO::FETCH_NUM);
		return $fila;		
	}
	PUBLIC function incluir_s($descripcion, $alicuota){
		$this->set_descripcion($descripcion);
		$this->set_alicuota($alicuota);
		return $this->incluir();
	}
	PUBLIC function modificar_s($id_tipo_apartamento, $descripcion, $alicuota){
		$this->set_id_tipo_apartamento($id_tipo_apartamento);
		$this->set_descripcion($descripcion);
		$this->set_alicuota($alicuota);
		return $this->modificar();
	}
	PUBLIC function eliminar_s($id_tipo_apartamento){
		$this->set_id_tipo_apartamento($id_tipo_apartamento);
		return $this->eliminar();
	}






	PRIVATE function incluir()
	{
		$descripcion = $this->descripcion;
		$alicuota = $this->alicuota;
		$r = array();
		if (!$this->existe(0,$descripcion,2)) {
			$co = $this->conecta();
			try {
				$guarda = $co->prepare("INSERT INTO tipo_apartamento(descripcion,alicuota) VALUES (?,?)");
				$guarda->execute([$descripcion, $alicuota]);
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  "Registro Incluido";
				$bitacora = new Bitacora();
				$bitacora->b_registro("Registró el tipo de apartamento \"$descripcion\"");

				
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}finally{$co = null;}		
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "El tipo de apartamento ya existe"; 
		}
		return $r;
	}
	PUBLIC function listadotipos() 
	{
		$co = $this->conecta();
		$r = array();
		try {
			$resultado = $co->query("SELECT id_tipo_apartamento, 
			descripcion,
			alicuota 
			FROM 
			tipo_apartamento ORDER BY id_tipo_apartamento DESC")->fetchall(PDO::FETCH_NUM);
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocatipo(this);'>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[0];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[1];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[2];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listado_tipos';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}finally{$co = null;}
		return $r;
	}
	PRIVATE function modificar()
	{
		$id_tipo_apartamento = $this->id_tipo_apartamento;
		$descripcion = $this->descripcion;
		$alicuota = $this->alicuota;
		if ($this->existe($id_tipo_apartamento,0,1)) {
			$co = $this->conecta();
			try {
				$consulta = $co->prepare("UPDATE tipo_apartamento SET 
						descripcion = ?,
						alicuota = ?
						WHERE
						id_tipo_apartamento = ?");

				$consulta->execute([$descripcion, $alicuota, $id_tipo_apartamento]);
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  "Registro modificado correctamente";
				$bitacora = new Bitacora();
				$bitacora->b_registro("Modificó el tipo de apartamento \"$descripcion\"");

			} catch (Exception $e) {
				return $e->getMessage();
			}finally{$co = null;}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Tipo de apartamento no encontrado";
		}
		return $r;
	}
	PRIVATE function eliminar()
	{
		$id_tipo_apartamento = $this->id_tipo_apartamento;
		if ($this->existe($id_tipo_apartamento,0,1)) {
			$co = $this->conecta();
			try {
				$consulta = $co->prepare("DELETE FROM tipo_apartamento 
						WHERE
						id_tipo_apartamento = ?
						");
				$consulta->execute([$id_tipo_apartamento]);
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  "Registro Eliminado";
				
				$bitacora = new Bitacora();
				$bitacora->b_registro("Eliminó un tipo de apartamento");
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode()=='23000') {
					$r['mensaje'] =  "El tipo de apartamento no puede ser eliminado si está asignado a un apartamento.";
				}else{
					$r['mensaje'] =  $e->getMessage();					
				}
			}finally{$co = null;}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Tipo de apartamento no encontrado";
		}
		return $r;
	}
	PUBLIC function existe($id_tipo_apartamento,$descripcion,$caso)
	{
		$co = $this->conecta();
		switch ($caso) {
			case 1:
				try {
					$resultado = $co->prepare("SELECT * FROM tipo_apartamento WHERE id_tipo_apartamento = ?");
					$resultado->execute([$id_tipo_apartamento]);
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
			case 2:
				try {
				$resultado = $co->query("SELECT * FROM tipo_apartamento WHERE descripcion = ?");
					$resultado->execute([$descripcion]);
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


	PUBLIC function get_id_tipo_apartamento(){
		return $this->id_tipo_apartamento;
	}
	PUBLIC function set_id_tipo_apartamento($value){
		$this->id_tipo_apartamento = $value;
	}
	PUBLIC function get_descripcion(){
		return $this->descripcion;
	}
	PUBLIC function set_descripcion($value){
		$this->descripcion = $value;
	}
	PUBLIC function get_alicuota(){
		return $this->alicuota;
	}
	PUBLIC function set_alicuota($value){
		$this->alicuota = $value;
	}
}
