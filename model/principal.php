<?php

require_once('model/datos.php');
class principal extends datos
{
	PUBLIC function datos_barra()
	{
		$datos = array();
		try {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pagos_confirmados = $co->prepare("SELECT 
												d.id_deuda
												FROM deuda_pagos as dp
												JOIN pagos AS p ON p.id_pago = dp.id_pago
												JOIN deudas as d on d.id_deuda = dp.id_deuda
												JOIN distribuciones as dis on dis.id_distribucion = d.id_distribucion
												WHERE p.estado = 2 AND MONTH(dis.fecha) = MONTH(CURRENT_DATE())
												AND YEAR(dis.fecha) = YEAR(CURRENT_DATE());");
			$pagos_confirmados->execute();
			$num_rows = $pagos_confirmados->rowCount();
			$datos['pagos_confirmados'] = $num_rows;
			$pagos_pendientes = $co->prepare("SELECT 
											d.id_deuda
											FROM deuda_pagos as dp
											JOIN pagos AS p ON p.id_pago = dp.id_pago
											JOIN deudas as d on d.id_deuda = dp.id_deuda
											JOIN distribuciones as dis on dis.id_distribucion = d.id_distribucion
											WHERE p.estado = 0 OR p.estado IS NULL;");
			$pagos_pendientes->execute();
			$num_rows = $pagos_pendientes->rowCount();
			$datos['pagos_pendientes'] = $num_rows;

			$deudas_pendientes = $co->prepare("SELECT
											*
											FROM deudas AS d
											LEFT JOIN deuda_pagos AS dp ON dp.id_deuda = d.id_deuda
											LEFT JOIN pagos AS p ON p.id_pago = dp.id_pago

											WHERE (dp.id_deuda IS NULL OR p.estado = 1) GROUP BY d.id_deuda LIMIT 100");
			$deudas_pendientes->execute();
			$num_rows = $deudas_pendientes->rowCount();
			$datos['deudas_pendientes'] = $num_rows;

			$morosos = $co->prepare("SELECT * FROM deudas as d 
									LEFT JOIN deuda_pagos as dp on dp.id_deuda = d.id_deuda
									LEFT JOIN pagos as p on p.id_pago = dp.id_pago AND p.estado <> 2
									WHERE d.moroso = 1 GROUP BY d.id_deuda;");
			$morosos->execute();
			$num_rows = $morosos->rowCount();
			$datos['morosos'] = $num_rows;
			$datos['resultado'] = 'datos_barra';
		} catch (Exception $e) {
			$datos['resultado'] = 'error';
			$datos['mensaje'] =  $e->getMessage();
		}
		finally{$co = null;}
		return $datos;
	}
	PUBLIC function ultimos_pagos()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			//$resultado = $co->query("SELECT p.id_pago, a.num_letra_apartamento, a.torre, p.fecha_entrega, p.total, p.estado, p.tipo_pago FROM pago p INNER JOIN deuda_pendiente dp ON p.deuda = dp.id INNER JOIN apartamento a ON dp.id_apartamento = a.id_apartamento ORDER BY p.id_pago DESC LIMIT 5;");
			$resultado = $co->query("SELECT
								p.id_pago,
								a.num_letra_apartamento,
								a.torre,
								ddp.fecha,
								p.total_pago as total,

								IF(p.estado = 0,'Pendiente',IF(p.estado = 1,'Declinado',IF(p.estado = 2,'Confirmado',NULL))) AS estado,
								tp.tipo_pago


								FROM deudas as d
								JOIN apartamento as a on a.id_apartamento = d.id_apartamento
								JOIN deuda_pagos as dp on dp.id_deuda = d.id_deuda
								JOIN pagos as p on p.id_pago = dp.id_pago
								LEFT JOIN (SELECT MAX(ddp.fecha_generada) as fecha,ddp.id_pago,ddp.tipo_pago FROM detalles_pagos as ddp WHERE 1 GROUP BY ddp.id_pago) AS ddp on ddp.id_pago = p.id_pago
								LEFT JOIN tipo_pago as tp on tp.id_tipo_pago = ddp.tipo_pago
								WHERE 1 ORDER BY ddp.fecha DESC LIMIT 10");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[0];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='font-weight-bold'>";
					$respuesta = $respuesta . $r[1];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . $r[2];
					$respuesta = $respuesta . "</td>";
					$fecha_original = $r[3];
					$nuevo_formato = "d-m-Y";
					$fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . $fecha_cambiada;
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . number_format($r[4],2,',','.');
					$respuesta = $respuesta . " Bs</td>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . $r[6];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='text-capitalize'>";
					$respuesta = $respuesta . $r[5];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'ultimos_pagos';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		finally{$co = null;}

		return $r;
	}
	PUBLIC function mostrar_avisos(){

		try {
			$co = $this->conecta();

			$this->validar_conexion($co);
			$co->beginTransaction();
			$consulta = $co->query("SELECT * FROM `avisos` WHERE DATE_FORMAT(NOW(), '%Y-%m-%d') BETWEEN DATE_FORMAT(desde, '%Y-%m-%d') AND DATE_FORMAT(hasta, '%Y-%m-%d');");

			if($consulta = $consulta->fetchall(PDO::FETCH_ASSOC)){
				$r['resultado'] = 'avisos';
				$r['mensaje'] =  $consulta;
			}
			else{
				$r = null;
			}
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
		finally{
			$co = null;
		}
		return $r;
	}
}
