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

			$consulta = $co->prepare("SELECT estado,id_deuda FROM pagos LEFT JOIN deuda_pagos as dp on dp.id_pago = pagos.id_pago WHERE pagos.id_pago = ?");
			$consulta->execute([$this->id]);
			if($consulta = $consulta->fetch(PDO::FETCH_ASSOC)){
				if($consulta["estado"] == 1 and $this->accion!== "deshacer_conf"){//declinado
					throw new Validaciones("No puede cambiar el estado porque ya esta Declinado", 1);
				}
				else if($consulta["estado"] == 2 and $this->accion!== "deshacer_conf"){//confirmado
					throw new Validaciones("No puede cambiar el estado porque ya esta Confirmado", 1);
				}
				else{
					$id_deuda_de_pago = $consulta["id_deuda"];
					$consulta = $co->prepare("UPDATE pagos SET estado = :estado, usuario_id = :usuario_id WHERE id_pago = :id_pago");
					$consulta->bindValue(":id_pago",$this->id);
					$consulta->bindValue(":usuario_id",$this->usuario_id);
					if($this->accion ==="confirmar" ){
						$consulta->bindValue(":estado",2);// 2 = confirmar
					}
					else if($this->accion === "declinar"){
						$consulta->bindValue(":estado",1);// 1 = declinar
					}
					else if($this->accion === "deshacer_conf"){
						$consulta->bindValue(":estado",0);// 0 = por confirmar
					}
					else{
						throw new Exception("Opción no disponible :: ($this->accion)", 1);
						
					}
					$consulta->execute();

					$consulta = $co->prepare("SELECT verificar_estado_deuda(?)");
					$consulta->execute([$id_deuda_de_pago]);
					$respuesta_verificador = $consulta->fetch(PDO::FETCH_ASSOC);
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
				$bitacora->b_registro("Confirmo pago Nº \"$this->id\"");
				// $mensajews = new enviarws();
				// $ws = $mensajews->enviarws("El pago número " . $id . " ha sido " . $r['resultado'] . " correctamente");
				// $r['ws'] =  $ws;
			}
			else if($this->accion =="declinar") {
				$r['resultado'] = 'declinado';
				$r['mensaje'] =  "Pago declinado";
				$bitacora = new Bitacora();
				$bitacora->b_registro("Declino pago Nº \"$this->id\"");
				// $mensajews = new enviarws();
				// $ws = $mensajews->enviarws("El pago número " . $id . " ha sido " . $r['resultado'] . " correctamente");
				// $r['ws'] =  $ws;
			}
			else if($this->accion =="deshacer_conf") {
				$r['resultado'] = 'deshacer_conf';
				$r['mensaje'] =  "Estado del pago removido";
				$bitacora = new Bitacora();
				$bitacora->b_registro("Removió el estado del pago Nº \"$this->id\"");
				// $mensajews = new enviarws();
				// $ws = $mensajews->enviarws("El pago número " . $id . " ha sido " . $r['resultado'] . " correctamente");
				// $r['ws'] =  $ws;
			}

			$co->commit();
		
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
		if(isset($respuesta_verificador)){
			$r["verificador"] = $respuesta_verificador;
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
// TODO cambiar la forma en que se manejan las fechas de los pagos

// SELECT TIMESTAMPDIFF(MONTH, '2012-01-05', '2012-02-16') AS total;

// SELECT
// *
// FROM deudas AS d
// LEFT JOIN distribuciones as dis on dis.id_distribucion = d.id_distribucion
// WHERE TIMESTAMPDIFF(MONTH, dis.fecha, CURRENT_TIMESTAMP) > (SELECT valor FROM config WHERE titulo = 'mes_moroso')


// lista dolar el año
//('2024-01-30', 36.0966), ('2024-01-29', 36.0514), ('2024-01-26', 36.1115), ('2024-01-25', 36.0283), ('2024-01-24', 36.0284), ('2024-01-23', 36.0376), ('2024-01-22', 36.0201), ('2024-01-19', 36.0555), ('2024-01-18', 36.0476), ('2024-01-17', 35.9859), ('2024-01-16', 35.9565), ('2024-01-15', 35.8845), ('2024-01-12', 35.9496), ('2024-01-11', 35.8730), ('2024-01-10', 35.8538), ('2024-01-09', 35.8273), ('2024-01-08', 35.8487), ('2024-01-05', 35.9165), ('2024-01-04', 35.9444), ('2024-01-03', 35.8597), ('2024-01-02', 35.7773), ('2024-01-01', 35.8457), ('2023-12-29', 35.8694), ('2023-12-28', 35.8407), ('2023-12-27', 35.7452), ('2023-12-26', 35.6932), ('2023-12-25', 35.7427), ('2023-12-22', 35.7239), ('2023-12-21', 35.6979), ('2023-12-20', 35.6427), ('2023-12-19', 35.6263), ('2023-12-18', 35.5649), ('2023-12-15', 35.6354), ('2023-12-14', 35.5512), ('2023-12-13', 35.5371), ('2023-12-12', 35.4835), ('2023-12-11', 35.5853), ('2023-12-08', 35.5357), ('2023-12-07', 35.5128), ('2023-12-06', 35.5084), ('2023-12-05', 35.4689), ('2023-12-04', 35.4340), ('2023-12-01', 35.4916), ('2023-11-30', 35.4243), ('2023-11-29', 35.4052), ('2023-11-28', 35.3897), ('2023-11-27', 35.3821), ('2023-11-24', 35.4133), ('2023-11-23', 35.3967), ('2023-11-22', 35.3031), ('2023-11-21', 35.3301), ('2023-11-20', 35.3625), ('2023-11-17', 35.3893), ('2023-11-16', 35.3412), ('2023-11-15', 35.3223), ('2023-11-14', 35.2934), ('2023-11-13', 35.2265), ('2023-11-10', 35.2822), ('2023-11-09', 35.2459), ('2023-11-08', 35.1577), ('2023-11-07', 35.0662), ('2023-11-06', 35.1929), ('2023-11-03', 35.1479), ('2023-11-02', 35.0845), ('2023-11-01', 35.0656), ('2023-10-31', 34.9776), ('2023-10-30', 35.0837), ('2023-10-27', 35.0398), ('2023-10-26', 35.0042), ('2023-10-25', 34.9346), ('2023-10-24', 34.9402), ('2023-10-23', 34.8905), ('2023-10-20', 34.8905), ('2023-10-19', 34.7713), ('2023-10-18', 34.7730), ('2023-10-17', 34.7256), ('2023-10-16', 34.7637), ('2023-10-13', 34.7904), ('2023-10-12', 34.8565), ('2023-10-11', 34.7981), ('2023-10-10', 34.7545), ('2023-10-09', 34.7328), ('2023-10-06', 34.7694), ('2023-10-05', 34.6630), ('2023-10-04', 34.6265), ('2023-10-03', 34.4250), ('2023-10-02', 34.3819), ('2023-09-29', 34.3393), ('2023-09-28', 34.2151), ('2023-09-27', 34.1651), ('2023-09-26', 34.0081), ('2023-09-25', 33.9382), ('2023-09-22', 33.9062), ('2023-09-21', 33.8441), ('2023-09-20', 33.7487), ('2023-09-19', 33.7316), ('2023-09-18', 33.5839), ('2023-09-15', 33.5765), ('2023-09-14', 33.4240), ('2023-09-13', 33.3566), ('2023-09-12', 33.2538), ('2023-09-11', 33.2985), ('2023-09-08', 33.2567), ('2023-09-07', 33.1327), ('2023-09-06', 33.0287), ('2023-09-05', 32.8571), ('2023-09-04', 32.7101), ('2023-09-01', 32.7331), ('2023-08-31', 32.5169), ('2023-08-30', 32.4266), ('2023-08-29', 32.3473), ('2023-08-28', 32.3560), ('2023-08-25', 32.2636), ('2023-08-24', 32.2149), ('2023-08-23', 32.0981), ('2023-08-22', 31.9279), ('2023-08-21', 31.7541), ('2023-08-18', 31.7677), ('2023-08-17', 31.6741), ('2023-08-16', 31.5327), ('2023-08-15', 31.4310), ('2023-08-14', 30.0521), ('2023-08-11', 31.4463), ('2023-08-10', 31.2770), ('2023-08-09', 31.1595), ('2023-08-08', 31.0212), ('2023-08-07', 30.8911), ('2023-08-04', 30.9051), ('2023-08-03', 30.0289), ('2023-08-02', 29.6815), ('2023-08-01', 29.5413), ('2023-07-31', 29.4299), ('2023-07-28', 29.4382), ('2023-07-27', 29.2319), ('2023-07-26', 29.2407), ('2023-07-25', 29.0314), ('2023-07-24', 29.0509), ('2023-07-21', 29.0145), ('2023-07-20', 28.9181), ('2023-07-19', 28.8067), ('2023-07-18', 28.7403), ('2023-07-17', 28.5978), ('2023-07-14', 28.5384), ('2023-07-13', 28.3845), ('2023-07-12', 28.2820), ('2023-07-11', 28.1361), ('2023-07-10', 28.1361), ('2023-07-07', 28.2340), ('2023-07-06', 28.2061), ('2023-07-05', 28.1296), ('2023-07-04', 28.0944), ('2023-07-03', 27.9813), ('2023-06-30', 27.9462), ('2023-06-29', 27.7850), ('2023-06-28', 27.7678), ('2023-06-27', 27.6463), ('2023-06-26', 27.5479), ('2023-06-23', 27.5406), ('2023-06-22', 27.4731), ('2023-06-21', 27.2318), ('2023-06-20', 27.1862), ('2023-06-19', 25.7444), ('2023-06-16', 27.2098), ('2023-06-15', 27.1058), ('2023-06-14', 27.0375), ('2023-06-13', 26.9505), ('2023-06-12', 27.9053), ('2023-06-09', 26.9534), ('2023-06-08', 26.7900), ('2023-06-07', 26.6370), ('2023-06-06', 26.5663), ('2023-06-05', 26.3963), ('2023-06-02', 26.5298), ('2023-06-01', 26.4010), ('2023-05-31', 26.2013), ('2023-05-30', 26.0969), ('2023-05-29', 26.1892), ('2023-05-26', 26.2057), ('2023-05-25', 26.0642), ('2023-05-24', 25.9719), ('2023-05-23', 25.9365), ('2023-05-22', 25.9730), ('2023-05-19', 25.9405), ('2023-05-18', 25.8381), ('2023-05-17', 25.7477), ('2023-05-16', 25.5838), ('2023-05-15', 25.4254), ('2023-05-12', 25.3714), ('2023-05-11', 25.2850), ('2023-05-10', 25.1162), ('2023-05-09', 25.0608), ('2023-05-08', 24.9769), ('2023-05-05', 24.9951), ('2023-05-04', 24.9673), ('2023-05-03', 24.8103), ('2023-05-02', 24.7536), ('2023-04-28', 24.6884), ('2023-04-27', 24.6686), ('2023-04-26', 24.5704), ('2023-04-25', 24.5917), ('2023-04-24', 24.5532), ('2023-04-21', 24.5868), ('2023-04-20', 24.5152), ('2023-04-18', 24.5019), ('2023-04-17', 24.4998), ('2023-04-14', 24.5125), ('2023-04-13', 24.4805), ('2023-04-12', 24.4267), ('2023-04-11', 24.4122), ('2023-04-10', 24.3311), ('2023-04-05', 24.4267), ('2023-04-04', 24.3803), ('2023-04-03', 24.4285), ('2023-03-31', 24.4643), ('2023-03-30', 24.4356), ('2023-03-29', 24.4262), ('2023-03-28', 24.3541), ('2023-03-27', 24.3307), ('2023-03-24', 24.3406), ('2023-03-23', 24.2485), ('2023-03-22', 24.1434), ('2023-03-21', 24.1809), ('2023-03-20', 24.1592), ('2023-03-17', 24.1429), ('2023-03-16', 24.0655), ('2023-03-15', 24.0075), ('2023-03-14', 23.9403), ('2023-03-13', 24.0579), ('2023-03-10', 24.1157), ('2023-03-09', 24.0747), ('2023-03-08', 24.1090), ('2023-03-07', 24.0953), ('2023-03-06', 24.2133), ('2023-03-03', 24.2560), ('2023-03-02', 24.2961), ('2023-03-01', 24.2962), ('2023-02-28', 24.3000), ('2023-02-27', 24.3026), ('2023-02-24', 24.3553), ('2023-02-23', 24.3099), ('2023-02-22', 24.3304), ('2023-02-17', 24.3404), ('2023-02-16', 24.3264), ('2023-02-15', 24.3043), ('2023-02-14', 24.2822), ('2023-02-13', 24.1925), ('2023-02-10', 24.1395), ('2023-02-09', 24.1380), ('2023-02-08', 23.7500), ('2023-02-07', 23.5537), ('2023-02-06', 23.0908), ('2023-02-03', 22.8489), ('2023-02-02', 22.6241), ('2023-02-01', 22.3820), ('2023-01-31', 22.3178), ('2023-01-30', 21.8963), ('2023-01-27', 21.9140), ('2023-01-26', 21.6728), ('2023-01-25', 21.2656), ('2023-01-24', 21.1215), ('2023-01-23', 20.6822), ('2023-01-20', 20.4759), ('2023-01-19', 20.2441), ('2023-01-18', 19.9345), ('2023-01-17', 19.9226), ('2023-01-16', 19.6146), ('2023-01-13', 19.4034), ('2023-01-12', 19.2302), ('2023-01-11', 19.0055), ('2023-01-10', 18.7622), ('2023-01-06', 18.5673), ('2023-01-05', 18.3486), ('2023-01-04', 17.8993), ('2023-01-03', 17.8196), ('2023-01-02', 17.5152)


/*

SET autocommit = 0;
START TRANSACTION;

SET @id_deuda = 837;
SET @apartamento = 4;


SELECT 

dis.id_distribucion
#,d.id_deuda
#,dis.concepto
#,dis.fecha as f1
,@dolar_temp:= (SELECT divi.monto FROM tipo_cambio_divisa as divi WHERE divi.fecha <= dis.fecha order by divi.fecha desc LIMIT 1 ) as monto_dolar
#,dd.concepto
#,dd.monto
,dd.tipo_monto
,( ( SUM(IF(dd.tipo_monto = 1,dd.monto,(ROUND(dd.monto / @dolar_temp, 2))) ))) AS monto_total_dolar
,(if(
    SUM(
        IF(
            dd.tipo_monto = 1,dd.monto,(ROUND(dd.monto / @dolar_temp, 2))
        )
    ) > 200,'pagado','moroso'
) ) AS test
 
FROM deudas as d
LEFT JOIN detalles_deudas as dd ON dd.id_deuda = d.id_deuda
LEFT JOIN distribuciones as dis ON d.id_distribucion = dis.id_distribucion

WHERE
d.id_deuda = @id_deuda
#d.id_apartamento = 4

#GROUP BY dd.concepto
;



ROLLBACK;
SET autocommit = 1;

*/


?>