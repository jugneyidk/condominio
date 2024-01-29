<?php

require_once('model/datos.php');

require_once("model/bitacora.php");

class apto extends datos
{
	PRIVATE $id_apartamento, $num_letra_apartamento, $propietario, $inquilino, $torre, $piso, $tipo_apartamento;
	PUBLIC function chequearpermisos()
	{
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

	PUBLIC function incluir_s(){
		return $this->incluir();
	}
	PUBLIC function modificar_s(){
		return $this->modificar();
	}
	PUBLIC function eliminar_s(){
		return $this->eliminar();
	}



	PRIVATE function incluir()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if (!$this->existe($this->num_letra_apartamento, $this->torre, $this->piso, 1)) {
			try {
				// if ($inquilino) {
					$guarda = $co->prepare("INSERT INTO apartamento (num_letra_apartamento, propietario, inquilino, torre, piso, tipo_apartamento) VALUES (:numapto, :propietario, :inquilino, :torre, :piso, :tipoapto)");
					$guarda->bindValue(":numapto", $this->num_letra_apartamento);
					$guarda->bindValue(":propietario", $this->propietario);
					if( $this->inquilino ){
						$guarda->bindValue(":inquilino", $this->inquilino);
					}
					else{
						$guarda->bindValue(":inquilino", null);
					}
					$guarda->bindValue(":torre", $this->torre);
					$guarda->bindValue(":piso", $this->piso);
					$guarda->bindValue(":tipoapto", $this->tipo_apartamento);
					$guarda->execute();

				$r['resultado'] = 'incluir';
				$r['mensaje'] =  'Registro Incluido';
				$bitacora = new Bitacora();
				// $bitacora->b_incluir();
				$bitacora->b_registro("Registro un nuevo apartamento ($this->num_letra_apartamento)");

			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "El apartamento $this->num_letra_apartamento ya existe en la torre $this->torre";
			//$r['mensaje'] =  "El apartamento ya existe";
		}
		return $r;
	}

	PRIVATE function modificar()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($this->id_apartamento, 0, 0, 2)) {
			try {
				// if ($inquilino) {
					$consulta = $co->prepare("SELECT num_letra_apartamento, torre FROM apartamento where id_apartamento = ?");
					$consulta->execute([$this->id_apartamento]);
					$consulta = $consulta->fetch(PDO::FETCH_ASSOC);
					if($this->num_letra_apartamento != $consulta["num_letra_apartamento"] or $this->torre != $consulta["torre"]){
						if ($this->existe($this->num_letra_apartamento, $this->torre, $this->piso, 1)) {
							$r['resultado'] = 'error';
							$r['mensaje'] =  "El apartamento $this->num_letra_apartamento ya existe en la torre $this->torre";
							return $r;
						}


					}
					$consulta = $co->prepare("UPDATE apartamento SET 
							num_letra_apartamento = :num_letra_apartamento,
							propietario = :propietario,
							inquilino = :inquilino,
							torre = :torre,
							piso = :piso,
							tipo_apartamento = :tipo_apartamento
							WHERE
							id_apartamento = :id_apartamento");

					$consulta->bindValue(":num_letra_apartamento",$this->num_letra_apartamento);
					$consulta->bindValue(":propietario",$this->propietario);
					if($this->inquilino){
						$consulta->bindValue(":inquilino",$this->inquilino);
					}
					else{
						$consulta->bindValue(":inquilino",null);
					}
					$consulta->bindValue(":torre",$this->torre);
					$consulta->bindValue(":piso",$this->piso);
					$consulta->bindValue(":tipo_apartamento",$this->tipo_apartamento);
					$consulta->bindValue(":id_apartamento",$this->id_apartamento);
					$consulta->execute();

				$r['resultado'] = 'modificar';
				$r['mensaje'] =  'Registro modificado correctamente';

				$bitacora = new Bitacora();
				$bitacora->b_registro("Modifico el apartamento ($this->num_letra_apartamento)");
				// $bitacora->b_modificar();
				
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =   $e->getMessage()." line ".$e->getLine();
				return $r;
			}
			
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "El apartamento no fue encontrado";
		}
		return $r;
	}

	PRIVATE function eliminar()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($this->id_apartamento, 0, 0, 2)) {
			try {
				//$co->query("delete from apartamento where id_apartamento = '$id_apartamento'");
				$consulta = $co->prepare("DELETE FROM apartamento WHERE id_apartamento = ?");
				$consulta->execute([$this->id_apartamento]);
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  "Registro eliminado correctamente";
				$bitacora = new Bitacora();
				$bitacora->b_eliminar();
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode()=='23000') {
					$r['mensaje'] =  "El apartamento no puede ser eliminado si tiene una deuda o pago asociado.";
				}else{
					$r['mensaje'] =  $e->getMessage();					
				}
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "El apartamento no fue encontrado";
		}
		return $r;
	}

	PUBLIC function listadoapartamentos()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("SELECT a.id_apartamento, a.num_letra_apartamento, a.propietario, a.inquilino, a.tipo_apartamento, a.piso, a.torre, h1.nombres as nombre_propietario, h1.apellidos as apellido_propietario, tipo_apartamento.descripcion, h2.nombres as nombre_inquilino, h2.apellidos as apellido_inquilino  
			FROM apartamento a 
			left join habitantes h1 on a.propietario = h1.id
			left join habitantes h2 on a.inquilino = h2.id 
			INNER JOIN tipo_apartamento on a.tipo_apartamento = tipo_apartamento.id_tipo_apartamento ORDER BY a.id_apartamento DESC");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocaapartamento(this);'>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[0];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle font-weight-bold'>";
					$respuesta = $respuesta . $r[1];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[2];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[7] . " " . $r[8];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[3];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[10] . " " . $r[11];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[4];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[9];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[5];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[6];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listadoapartamentos';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function listadohabitantes()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array(); 
		try {
			$resultado = $co->query("SELECT id,  cedula_rif, tipo_identificacion,
			nombres,
			apellidos
			FROM 
			habitantes ORDER BY id DESC");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocahabitante(this);'>";
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
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listadohabitantes';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function listadotipos()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array(); // en este arreglo
		// se enviara la respuesta a la solicitud y el
		// contenido de la respuesta
		try {
			$resultado = $co->query("Select id_tipo_apartamento,  descripcion
			from 
			tipo_apartamento");
			$respuesta = '';
			$respuesta = $respuesta . "<option value='' disabled selected>-</option>";
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<option value='" . $r[0] . "'>";
					$respuesta = $respuesta . $r[1];
					$respuesta = $respuesta . "</option>";
				}
			}
			$r['resultado'] = 'listadotipo';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function existe($id_apartamento, $torre, $piso, $caso)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		switch ($caso) {
			case '1':
				try {

					$resultado = $co->prepare("SELECT 1 FROM apartamento WHERE num_letra_apartamento= ? AND torre= ?;");
					$resultado->execute([$id_apartamento, $torre]);
					$fila = $resultado->fetchall(PDO::FETCH_ASSOC);

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

					$resultado = $co->prepare("SELECT 1 FROM apartamento WHERE id_apartamento= ?");
					$resultado->execute([$id_apartamento]);
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


	PUBLIC function get_id_apartamento(){
		return $this->id_apartamento;
	}
	PUBLIC function set_id_apartamento($value){
		$this->id_apartamento = $value;
	}
	PUBLIC function get_num_letra_apartamento(){
		return $this->num_letra_apartamento;
	}
	PUBLIC function set_num_letra_apartamento($value){
		$this->num_letra_apartamento = $value;
	}
	PUBLIC function get_propietario(){
		return $this->propietario;
	}
	PUBLIC function set_propietario($value){
		$this->propietario = $value;
	}
	PUBLIC function get_inquilino(){
		return $this->inquilino;
	}
	PUBLIC function set_inquilino($value){
		$this->inquilino = $value;
	}
	PUBLIC function get_torre(){
		return $this->torre;
	}
	PUBLIC function set_torre($value){
		$this->torre = $value;
	}
	PUBLIC function get_piso(){
		return $this->piso;
	}
	PUBLIC function set_piso($value){
		$this->piso = $value;
	}
	PUBLIC function get_tipo_apartamento(){
		return $this->tipo_apartamento;
	}
	PUBLIC function set_tipo_apartamento($value){
		$this->tipo_apartamento = $value;
	}




}
