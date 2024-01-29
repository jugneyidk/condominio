<?php

require_once('model/datos.php');
require_once("model/bitacora.php");



class tipoapto extends datos
{
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
	PUBLIC function incluir($descripcion, $alicuota)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if (!$this->existe(0,$descripcion,2)) {
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
			}			
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "El tipo de apartamento ya existe"; 
		}
		return $r;
	}
	PUBLIC function listadotipos() 
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
		}
		return $r;
	}
	PUBLIC function modificar($id_tipo_apartamento, $descripcion, $alicuota)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id_tipo_apartamento,0,1)) {
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
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Tipo de apartamento no encontrado";
		}
		return $r;
	}
	PUBLIC function eliminar($id_tipo_apartamento)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id_tipo_apartamento,0,1)) {
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
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Tipo de apartamento no encontrado";
		}
		return $r;
	}
	 function existe($id_tipo_apartamento,$descripcion,$caso)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
				}				
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
				}		
				break;
			default:
				break;
		}
	}
}
