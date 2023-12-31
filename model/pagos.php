<?php

require_once('model/enviar-correo.php');
require_once('model/datos.php');
require_once("model/bitacora.php");
require_once("model/enviar-ws.php");


class pagos extends datos
{
	private $id, $accion, $usuario_id;
	function __construct()
	{
		if(isset($_SESSION['id_usuario'])){
			$this->usuario_id = $_SESSION['id_usuario'];
		}
	}
	public function listadopagos()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado2 = $co->query("SELECT pago.id_pago, NULL as extra, pago.fecha_entrega as fecha, pago.total as total_pago, IF(pago.estado = 'pendiente', 0,IF(pago.estado = 'declinado',1,IF(pago.estado = 'confirmado',2,NULL) ) ) AS estado, apartamento.num_letra_apartamento, apartamento.piso, apartamento.torre FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento ORDER BY pago.id_pago DESC;")->fetchall(PDO::FETCH_ASSOC);
			$resultado = $co->query("SELECT 
									p.id_pago,
									a.num_letra_apartamento,
									a.torre,
									dis.fecha,
									p.total_pago,
									p.estado,
									NULL as extra
									FROM pagos as p 
									JOIN deuda_pagos as dp on dp.id_pago = p.id_pago 
									JOIN deudas as d on d.id_deuda = dp.id_deuda
									JOIN apartamento as a on a.id_apartamento = d.id_apartamento
									JOIN distribuciones as dis on d.id_distribucion = dis.id_distribucion
									WHERE 1 ORDER BY dis.fecha DESC;")->fetchall(PDO::FETCH_ASSOC);
			$r['resultado'] = 'listadopagos';
			$r['mensaje'] =  $resultado;

			return $r;


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
	public function listadopagospendientes()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			//$resultado = $co->query("SELECT pago.id_pago, pago.fecha_entrega as fecha, pago.total as total_pago, IF(pago.estado = 'pendiente', 0,IF(pago.estado = 'declinado',1,IF(pago.estado = 'confirmado',2,NULL) ) ) AS estado, apartamento.num_letra_apartamento, apartamento.piso, apartamento.torre FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento ORDER BY pago.id_pago DESC;")->fetchall(PDO::FETCH_ASSOC);
			$resultado = $co->query("SELECT 
									p.id_pago,
									a.num_letra_apartamento,
									a.torre,
									dis.fecha,
									p.total_pago,
									p.estado,
									NULL as extra
									FROM pagos as p 
									JOIN deuda_pagos as dp on dp.id_pago = p.id_pago 
									JOIN deudas as d on d.id_deuda = dp.id_deuda
									JOIN apartamento as a on a.id_apartamento = d.id_apartamento
									JOIN distribuciones as dis on d.id_distribucion = dis.id_distribucion
									WHERE p.estado = 0 OR p.estado IS NULL ORDER BY dis.fecha DESC;")->fetchall(PDO::FETCH_ASSOC);
			$r['resultado'] = 'listadopagospendientes';
			$r['mensaje'] =  $resultado;

			return $r;


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
	public function listadopagosconfirmados()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT pago.id_pago, pago.fecha_entrega, pago.total, pago.estado, apartamento.num_letra_apartamento, apartamento.piso, apartamento.torre FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento ORDER BY pago.id_pago DESC;");
			$resultado = $co->query("SELECT 
									p.id_pago,
									a.num_letra_apartamento,
									a.torre,
									dis.fecha,
									p.total_pago,
									p.estado,
									NULL as extra
									FROM pagos as p 
									JOIN deuda_pagos as dp on dp.id_pago = p.id_pago 
									JOIN deudas as d on d.id_deuda = dp.id_deuda
									JOIN apartamento as a on a.id_apartamento = d.id_apartamento
									JOIN distribuciones as dis on d.id_distribucion = dis.id_distribucion
									WHERE p.estado = 2 ORDER BY dis.fecha DESC;")->fetchall(PDO::FETCH_ASSOC);
			$r['resultado'] = 'listadopagosconfirmados';
			$r['mensaje'] =  $resultado;

			return $r;





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
	public function listadopagosdeclinados()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT pago.id_pago, pago.fecha_entrega, pago.total, pago.estado, apartamento.num_letra_apartamento, apartamento.piso, apartamento.torre FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento ORDER BY pago.id_pago DESC;");
			$resultado = $co->query("SELECT 
									p.id_pago,
									a.num_letra_apartamento,
									a.torre,
									dis.fecha,
									p.total_pago,
									p.estado,
									NULL as extra
									FROM pagos as p 
									JOIN deuda_pagos as dp on dp.id_pago = p.id_pago 
									JOIN deudas as d on d.id_deuda = dp.id_deuda
									JOIN apartamento as a on a.id_apartamento = d.id_apartamento
									JOIN distribuciones as dis on d.id_distribucion = dis.id_distribucion
									WHERE p.estado = 1 ORDER BY dis.fecha DESC;")->fetchall(PDO::FETCH_ASSOC);
			$r['resultado'] = 'listadopagosdeclinados';
			$r['mensaje'] =  $resultado;

			return $r;








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
	public function detallespago()
	{
		$id = $this->id;
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT pago.id_pago, pago.referencia, pago.fecha_entrega, pago.tipo_pago, pago.total, pago.id_usuario, pago.estado, datos_usuarios.razon_social, apartamento.num_letra_apartamento, deuda_pendiente.id_apartamento, apartamento.piso, apartamento.torre FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento LEFT JOIN datos_usuarios on id_usuario=datos_usuarios.id WHERE pago.id_pago = '$id';");

			$consulta = $co->prepare("SELECT 
									d.id_deuda,
									a.num_letra_apartamento AS apartamento,
									a.torre,
									a.piso,
									CONCAT(h.nombres,' ',h.apellidos) as propietario,
									dis.fecha as fecha_generada,
									dis.concepto,
									'xxxx' as total_divisa,
									'yyyy' as total_bs
									FROM deudas AS d
									JOIN deuda_pagos AS dp ON dp.id_deuda = d.id_deuda
									JOIN distribuciones AS dis ON dis.id_distribucion = d.id_distribucion
									JOIN apartamento AS a on d.id_apartamento = a.id_apartamento
									JOIN habitantes as h on h.id = a.propietario
									LEFT JOIN detalles_deudas as dd on dd.id_deuda = d.id_deuda
									WHERE dp.id_pago = ?
									LIMIT 1");

		
			$consulta->execute([$id]);
			$respuesta = $consulta->fetch(PDO::FETCH_ASSOC);

			$consulta = $co->prepare("SELECT 
									*,
									@divisa_monto := divs.monto
									FROM (SELECT MAX(dp.fecha_generada) AS fecha_generada_2 FROM detalles_pagos as dp WHERE dp.id_pago = ?) AS dp
									JOIN tipo_cambio_divisa as divs ON dp.fecha_generada_2 WHERE divs.fecha < dp.fecha_generada_2 ORDER BY divs.fecha DESC LIMIT 1;");
			$consulta->execute([$id]);

			$consulta = $co->prepare("SELECT
									dd.concepto,
									if(dd.tipo_monto = 0,dd.monto,ROUND((dd.monto * @divisa_monto),2)) as monto_bs,
									if(dd.tipo_monto = 1,dd.monto,ROUND((dd.monto / @divisa_monto),2)) as monto_divisa
									FROM detalles_deudas as dd
									LEFT JOIN deudas as d on d.id_deuda = dd.id_deuda
									LEFT JOIN deuda_pagos AS dp ON dp.id_deuda = d.id_deuda
									WHERE dp.id_pago = ?");
			$consulta->execute([$this->id]);
			$respuesta["resumen_d"] = $consulta->fetchall(PDO::FETCH_ASSOC);
			$respuesta["total_divisa"] = 0;
			$respuesta["total_bs"] = 0;
			for($i=0;$i<count($respuesta["resumen_d"]);$i++){
				$respuesta["total_bs"] += $respuesta["resumen_d"][$i]["monto_bs"];
				$respuesta["total_divisa"] += $respuesta["resumen_d"][$i]["monto_divisa"];
			}
			$respuesta["total_bs"] = number_format($respuesta["total_bs"],2,",",".");
			$respuesta["total_divisa"] = number_format($respuesta["total_divisa"],2,",",".");

			$consulta = $co->prepare("SELECT
									dp.fecha,
									tp.tipo_pago,
									IF(dp.tipo_monto = 1 ,dp.monto,ROUND((dp.monto / @divisa_monto),2)) AS monto_divisa,
									IF(dp.tipo_monto = 0 ,dp.monto,ROUND((dp.monto * @divisa_monto),2)) AS monto_bs
									FROM pagos as p
									LEFT JOIN detalles_pagos as dp ON dp.id_pago = p.id_pago
									LEFT JOIN tipo_pago as tp on tp.id_tipo_pago = dp.tipo_pago
									WHERE p.id_pago = ?;");

			$consulta->execute([$this->id]);

			$consulta = $consulta->fetchall(PDO::FETCH_ASSOC);
			$respuesta["total_pago_bs"] = 0;
			$respuesta["total_pago_divisa"] = 0;
			for($i=0;$i<count($consulta);$i++){
				$respuesta["total_pago_divisa"] = $consulta[$i]["monto_divisa"];
				$respuesta["total_pago_bs"] = $consulta[$i]["monto_bs"];
			}

			$respuesta["total_pago_divisa"] = number_format($respuesta["total_pago_divisa"],2,",",".");
			$respuesta["total_pago_bs"] = number_format($respuesta["total_pago_bs"],2,",",".");

			$respuesta["resumen_p"] = $consulta;







			$r['resultado'] = 'detallespago';
			$r['mensaje'] =  $respuesta;
			return $r;




			/// codigo viejo
		

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
			$r['mensaje'] =  $e->getMessage()." :: LINE ".$e->getLine()." ::";
		}
		finally{
			$co = null;
		}
		return $r;
	}
	public function confirmar_declinar_pago()
	{
		$id = $this->id;
		$accion = $this->accion;
		$id_usuario = $this->usuario_id;
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		try {
			$this->validar_conexion($co);
			$co->beginTransaction();

			$consulta = $co->prepare("SELECT * FROM pagos WHERE id_pago = ?");
			$consulta->execute([$this->id]);
			if($consulta = $consulta->fetch(PDO::FETCH_ASSOC)){
				if($consulta["estado"] == 1){//declinado
					throw new Validaciones("No puede cambiar el estado porque ya esta Declinado", 1);
				}
				else if($consulta["estado"] == 2){//confirmado
					throw new Validaciones("No puede cambiar el estado porque ya esta Confirmado", 1);
				}
				else{
					$consulta = $co->prepare("UPDATE pagos SET estado = :estado, usuario_id = :usuario_id WHERE id_pago = :id_pago");
					$consulta->bindValue(":id_pago",$this->id);
					$consulta->bindValue(":usuario_id",$this->usuario_id);
					$consulta->bindValue(":estado",($this->accion == "confirmar")?2:1);// 2 = confirmar, 1 = declinar
					$consulta->execute();
				}
			}
			else{
				throw new Validaciones("El pago seleccionado no existe", 1);
			}

			if($this->accion == "confirmar"){
				$r['resultado'] = 'confirmado';
				//$r['correo'] = $correo;
				$r['mensaje'] =  "Pago Confirmado";
				$bitacora = new Bitacora();
				$bitacora->b_accion("Confirmo pago Nº $this->id en ");
				// $mensajews = new enviarws();
				// $ws = $mensajews->enviarws("El pago número " . $id . " ha sido " . $r['resultado'] . " correctamente");
				// $r['ws'] =  $ws;
			}
			else {
				$r['resultado'] = 'declinado';
				$r['mensaje'] =  "Pago declinado";
				$bitacora = new Bitacora();
				$bitacora->b_accion("Declino pago Nº $this->id en ");
				// $mensajews = new enviarws();
				// $ws = $mensajews->enviarws("El pago número " . $id . " ha sido " . $r['resultado'] . " correctamente");
				// $r['ws'] =  $ws;
			}
			$co->commit();
			return $r;




			/*----------------------------------------------------------------------------------*/
			/*----------------------------------------------------------------------------------*/
			/*----------------------------------------------------------------------------------*/
			/*----------------------------------------------------------------------------------*/

			//$enviarcorreo = new enviarcorreo();
			//$correo = $enviarcorreo->enviar_correo($id);
			//if ($correo == true) {
				$r['resultado'] = 'confirmado';
				$r['correo'] = $correo;
				$r['mensaje'] =  "Pago confirmado correctamente";
				$bitacora = new Bitacora();
				$bitacora->b_accion("Confirmo pago en ");
				$mensajews = new enviarws();
				$ws = $mensajews->enviarws("El pago número " . $id . " ha sido " . $r['resultado'] . " correctamente");
				$r['ws'] =  $ws;
				return $r;
			//}






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
					$mensajews = new enviarws();
					$ws = $mensajews->enviarws("El pago número " . $id . " ha sido " . $r['resultado'] . " correctamente");
					$r['ws'] =  $ws;
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
				$mensajews = new enviarws();
				$ws = $mensajews->enviarws("El pago número " . $id . " ha sido " . $r['resultado'] . " correctamente");
				$r['ws'] =  $ws;
			}
			// code
			
			$r['resultado'] = 'console';
			$r['mensaje'] =  "";
			//$co->commit();
		
		} catch (Validaciones $e){
			if($co instanceof PDO){
				if($co->inTransaction()){
					$co->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($co instanceof PDO){
				if($co->inTransaction()){
					$co->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		return $r;




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
					$mensajews = new enviarws();
					$ws = $mensajews->enviarws("El pago número " . $id . " ha sido " . $r['resultado'] . " correctamente");
					$r['ws'] =  $ws;
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
				$mensajews = new enviarws();
				$ws = $mensajews->enviarws("El pago número " . $id . " ha sido " . $r['resultado'] . " correctamente");
				$r['ws'] =  $ws;
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
			return $r;
		}
		return $r;
	}

	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
	PUBLIC function get_accion(){
		return $this->accion;
	}
	PUBLIC function set_accion($value){
		$this->accion = $value;
	}
	PUBLIC function get_usuario_id(){
		return $this->usuario_id;
	}
	PUBLIC function set_usuario_id($value){
		$this->usuario_id = $value;
	}
}
