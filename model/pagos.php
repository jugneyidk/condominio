<?php

require_once('model/enviar-correo.php');
require_once('model/datos.php');
require_once("model/bitacora.php");


class pagos extends datos
{
	PUBLIC function listadopagos()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT pago.id_pago, pago.fecha_entrega, pago.total, pago.estado, apartamento.num_letra_apartamento, apartamento.piso, apartamento.torre FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento ORDER BY pago.id_pago DESC;");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['id_pago'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='apartamento align-middle'>";
					$respuesta = $respuesta . $r['num_letra_apartamento'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['torre'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$fecha_original = $r['fecha_entrega'];
					$nuevo_formato = "d-m-Y";
					$fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
					$respuesta = $respuesta . $fecha_cambiada;
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['total'];
					$respuesta = $respuesta . "$</td>";
					$respuesta = $respuesta . "<td class='align-middle text-capitalize'>";
					$respuesta = $respuesta . $r['estado'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					if ($r['estado'] == "pendiente") {
						$respuesta = $respuesta . "<div class='row justify-content-around mx-0'>";
						$respuesta = $respuesta . "<div class='btn-group' role='group'>";
						$respuesta = $respuesta . "<button class='btn btn-primary' style='font-size: 12px;' onclick='confirmar_declinar_pago(this,1)'>Confirmar</button>";
						$respuesta = $respuesta . "<button class='btn btn-secondary' style='font-size: 12px;'' onclick='carga_detalles_pago(this)'>Ver</button>";
						$respuesta = $respuesta . "<button class='btn btn-danger' style='font-size: 12px;' onclick='confirmar_declinar_pago(this,2)'>Declinar</button>";
						$respuesta = $respuesta . "</div>";
						$respuesta = $respuesta . "</div>";
					} elseif ($r['estado'] == "confirmado" || $r['estado'] == "declinado") {
						$respuesta = $respuesta . "<div class='row justify-content-around mx-0'>";
						$respuesta = $respuesta . "<button class='btn btn-secondary w-100 btn-ver' style='font-size: 12px;'' onclick='carga_detalles_pago(this)'>Ver</button>";
						$respuesta = $respuesta . "</div>";
					}
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listadopagos';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function listadopagospendientes()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT pago.id_pago, pago.fecha_entrega, pago.total, pago.estado, apartamento.num_letra_apartamento, apartamento.piso, apartamento.torre FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento WHERE pago.estado = 'pendiente' ORDER BY pago.id_pago DESC;");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['id_pago'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='apartamento align-middle'>";
					$respuesta = $respuesta . $r['num_letra_apartamento'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['torre'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$fecha_original = $r['fecha_entrega'];
					$nuevo_formato = "d-m-Y";
					$fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
					$respuesta = $respuesta . $fecha_cambiada;
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['total'];
					$respuesta = $respuesta . "$</td>";
					$respuesta = $respuesta . "<td class='align-middle text-capitalize'>";
					$respuesta = $respuesta . $r['estado'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					if ($r['estado'] == "pendiente") {
						$respuesta = $respuesta . "<div class='row justify-content-around mx-0'>";
						$respuesta = $respuesta . "<div class='btn-group' role='group'>";
						$respuesta = $respuesta . "<button class='btn btn-primary' style='font-size: 12px;' onclick='confirmar_declinar_pago(this,1)'>Confirmar</button>";
						$respuesta = $respuesta . "<button class='btn btn-secondary' style='font-size: 12px;'' onclick='carga_detalles_pago(this)'>Ver</button>";
						$respuesta = $respuesta . "<button class='btn btn-danger' style='font-size: 12px;' onclick='confirmar_declinar_pago(this,2)'>Declinar</button>";
						$respuesta = $respuesta . "</div>";
						$respuesta = $respuesta . "</div>";
					}
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listadopagospendientes';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function listadopagosconfirmados()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT pago.id_pago, pago.fecha_entrega, pago.total, pago.estado, apartamento.num_letra_apartamento, apartamento.piso, apartamento.torre FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento WHERE pago.estado = 'confirmado' ORDER BY pago.id_pago DESC;");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['id_pago'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='apartamento align-middle'>";
					$respuesta = $respuesta . $r['num_letra_apartamento'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['torre'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$fecha_original = $r['fecha_entrega'];
					$nuevo_formato = "d-m-Y";
					$fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
					$respuesta = $respuesta . $fecha_cambiada;
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['total'];
					$respuesta = $respuesta . "$</td>";
					$respuesta = $respuesta . "<td class='align-middle text-capitalize'>";
					$respuesta = $respuesta . $r['estado'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . "<div class='row justify-content-around mx-0'>";
					$respuesta = $respuesta . "<button class='btn btn-secondary w-100 btn-ver' style='font-size: 12px;'' onclick='carga_detalles_pago(this)'>Ver</button>";
					$respuesta = $respuesta . "</div>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listadopagosconfirmados';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function listadopagosdeclinados()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT pago.id_pago, pago.fecha_entrega, pago.total, pago.estado, apartamento.num_letra_apartamento, apartamento.piso, apartamento.torre FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento WHERE pago.estado = 'declinado' ORDER BY pago.id_pago DESC;");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['id_pago'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='apartamento align-middle'>";
					$respuesta = $respuesta . $r['num_letra_apartamento'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['torre'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$fecha_original = $r['fecha_entrega'];
					$nuevo_formato = "d-m-Y";
					$fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
					$respuesta = $respuesta . $fecha_cambiada;
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['total'];
					$respuesta = $respuesta . "$</td>";
					$respuesta = $respuesta . "<td class='align-middle text-capitalize'>";
					$respuesta = $respuesta . $r['estado'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . "<div class='row justify-content-around mx-0'>";
					$respuesta = $respuesta . "<button class='btn btn-secondary w-100 btn-ver' style='font-size: 12px;'' onclick='carga_detalles_pago(this)'>Ver</button>";
					$respuesta = $respuesta . "</div>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listadopagosdeclinados';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function detallespago($id)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT pago.id_pago, pago.referencia, pago.fecha_entrega, pago.tipo_pago, pago.total, pago.id_usuario, pago.estado, datos_usuarios.razon_social, apartamento.num_letra_apartamento, deuda_pendiente.id_apartamento, apartamento.piso, apartamento.torre FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento LEFT JOIN datos_usuarios on id_usuario=datos_usuarios.id WHERE pago.id_pago = '$id';");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr class='my-2 mx-5 text-center'>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . "<span class='font-weight-bold'>Referencia:</span>";
					$respuesta = $respuesta . "<span class='d-block'>";
					$respuesta = $respuesta . $r['referencia'];
					$respuesta = $respuesta . "</span>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td colspan='2'>";
					$respuesta = $respuesta . "<span class='font-weight-bold'>Método de pago:</span>";
					$respuesta = $respuesta . "<span class='d-block'>";
					$respuesta = $respuesta . $r['tipo_pago'];
					$respuesta = $respuesta . "</span>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
					$respuesta = $respuesta . "<tr class='my-2 mx-5 text-center'>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . "<span class='font-weight-bold'>Apartamento:</span>";
					$respuesta = $respuesta . "<span class='d-block'>";
					$respuesta = $respuesta . $r['num_letra_apartamento'];
					$respuesta = $respuesta . "</span>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . "<span class='font-weight-bold'>Torre:</span>";
					$respuesta = $respuesta . "<span class='d-block'>";
					$respuesta = $respuesta . $r['torre'];
					$respuesta = $respuesta . "</span>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . "<span class='font-weight-bold'>Piso:</span>";
					$respuesta = $respuesta . "<span class='d-block'>";
					$respuesta = $respuesta . $r['piso'];
					$respuesta = $respuesta . "</span>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
					$respuesta = $respuesta . "<tr class='my-2 mx-5 text-center'>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . "<span class='font-weight-bold'>Estado:</span>";
					$respuesta = $respuesta . "<span class='d-block text-capitalize'>";
					$respuesta = $respuesta . $r['estado'];
					$respuesta = $respuesta . "</span>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . "<span class='font-weight-bold'>Fecha:</span>";
					$respuesta = $respuesta . "<span class='d-block'>";
					$fecha_original = $r['fecha_entrega'];
					$nuevo_formato = "d-m-Y";
					$fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
					$respuesta = $respuesta . $fecha_cambiada;
					$respuesta = $respuesta . "</span>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . "<span class='font-weight-bold'>Monto:</span>";
					$respuesta = $respuesta . "<span class='d-block'>";
					$respuesta = $respuesta . $r['total'];
					$respuesta = $respuesta . "$</span>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
					if ($r['estado'] == "confirmado" || $r['estado'] == "declinado") {
						$respuesta = $respuesta . "<tr class='my-2 mx-5 text-center'>";
						$respuesta = $respuesta . "<td colspan='3'>";
						if ($r['estado'] == "confirmado") {
							$respuesta = $respuesta . "<span class='font-weight-bold'>Confirmado por:</span>";
						} elseif ($r['estado'] == "declinado") {
							$respuesta = $respuesta . "<span class='font-weight-bold'>Declinado por:</span>";
						}
						$respuesta = $respuesta . "<span class='d-block'>";
						$respuesta = $respuesta . $r['razon_social'];
						$respuesta = $respuesta . "</span>";
						$respuesta = $respuesta . "</td>";
						$respuesta = $respuesta . "</tr>";
					}
				}
			}
			$r['resultado'] = 'detallespago';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function confirmar_declinar_pago($id, $accion, $id_usuario)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			if ($accion == 'confirmar') {
				$co->query("Update pago set 
							estado = 'confirmado',
							id_usuario = '$id_usuario'											
							where
							id_pago = '$id'
							");
				$enviarcorreo = new enviarcorreo();
				$correo = $enviarcorreo->enviar_correo($id);
				if ($correo == true) {
					$r['resultado'] = 'confirmado';
					$r['correo'] = $correo;
					$r['mensaje'] =  "Pago confirmado correctamente";
					$bitacora = new Bitacora();
					$bitacora->b_accion("Confirmo pago en ");
					return $r;
				}
			} elseif ($accion == 'declinar') {
				$co->query("Update pago set 
							estado = 'declinado',
							id_usuario = '$id_usuario'												
							where
							id_pago = '$id'
							");
				$r['resultado'] = 'declinado';
				$r['mensaje'] =  "Pago declinado correctamente";
				$bitacora = new Bitacora();
				$bitacora->b_accion("Declino pago en ");
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
			return $r;
		}
		return $r;
	}
	PRIVATE function existe($id)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->prepare("SELECT id_pago from pago WHERE id_pago = '$id'");
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if ($fila) {
				$r['resultado'] = 'existe';
				return $r;
			}
			$r['resultado'] = 'noexiste';
			$r['mensaje'] =  "El pago no existe";
			return $r;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
			return $r;
		}
	}
}
