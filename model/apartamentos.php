<?php

require_once('model/datos.php');

require_once("model/bitacora.php");

class apto extends datos
{
	PUBLIC function chequearpermisos()
	{
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
	PUBLIC function incluir($torre, $piso, $numapto, $tipoapto, $propietario, $inquilino)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if (!$this->existe($numapto, $torre, $piso, 1)) {
			try {
				if ($inquilino) {
					$guarda = $co->query("insert into apartamento(num_letra_apartamento, propietario, inquilino, torre, piso, tipo_apartamento) 
					values('$numapto','$propietario','$inquilino','$torre','$piso','$tipoapto')");
				} else {
					$guarda = $co->query("insert into apartamento(num_letra_apartamento, propietario, inquilino, torre, piso, tipo_apartamento) 
					values('$numapto','$propietario',NULL,'$torre','$piso','$tipoapto')");
				}
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  'Registro Incluido';
				$bitacora = new Bitacora();
				$bitacora->b_incluir();

			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "El apartamento ya existe";
		}
		return $r;
	}

	PUBLIC function modificar($id_apartamento, $num_letra_apartamento, $propietario, $inquilino, $torre, $piso, $tipo_apartamento)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id_apartamento, 0, 0, 2)) {
			try {
				if ($inquilino) {
					$co->query("Update apartamento set 
							num_letra_apartamento = '$num_letra_apartamento',
							propietario = '$propietario',
							inquilino = '$inquilino',
							torre = '$torre',
							piso = '$piso',
							tipo_apartamento = '$tipo_apartamento'
							where
							id_apartamento = '$id_apartamento'
							");
				} else {
					$co->query("Update apartamento set 
						num_letra_apartamento = '$num_letra_apartamento',
						propietario = '$propietario',
						inquilino = NULL,
						torre = '$torre',
						piso = '$piso',
						tipo_apartamento = '$tipo_apartamento'
						where
						id_apartamento = '$id_apartamento'
						");
				}
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  'Registro modificado correctamente';

				$bitacora = new Bitacora();
				$bitacora->b_modificar();

				
			} catch (Exception $e) {
				return $e->getMessage();
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "El apartamento no fue encontrado";
		}
		return $r;
	}

	PUBLIC function eliminar($id_apartamento)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id_apartamento, 0, 0, 2)) {
			try {
				$co->query("delete from apartamento where id_apartamento = '$id_apartamento'
						");
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
			$resultado = $co->query("Select id,  cedula_rif, tipo_identificacion,
			nombres,
			apellidos
			from 
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
					$resultado = $co->query("Select * from apartamento where num_letra_apartamento='$id_apartamento' and torre='$torre' and piso='$piso'");
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
					$resultado = $co->query("Select * from apartamento where id_apartamento='$id_apartamento'");
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
