<?php

require_once('model/datos.php');
require_once("model/bitacora.php");



class detallesdeuda extends datos
{
	PRIVATE $obj_pagos,$id,$id_pago,$con, $id_habitante;

	function __construct()
	{
		$this->con = $this->conecta();
		if($this->con instanceof PDO) $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->id_habitante = $_SESSION['id_habitante'];
	}


	PUBLIC function listadodeudas()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$usuario = $this->id_habitante;
		try {
	 //        $resultado = $co->query("SELECT DISTINCT dp.*, a.num_letra_apartamento, a.torre, a.piso FROM deuda_pendiente dp JOIN apartamento a ON dp.id_apartamento = a.id_apartamento JOIN habitantes h ON a.propietario = h.id LEFT JOIN pago p ON dp.id = p.deuda WHERE a.propietario = '$usuario' AND (dp.id NOT IN (SELECT deuda from pago WHERE estado = 'confirmado' OR estado = 'pendiente')) ORDER BY dp.id DESC;");
	 //        $respuesta = '';
	 //        if ($resultado) {
	 //            foreach ($resultado as $r) {
	 //                $respuesta = $respuesta . "<tr>";
	 //                $respuesta = $respuesta . "<td style='display:none'>";
	 //                $respuesta = $respuesta . $r[0];
	 //                $respuesta = $respuesta . "</td>";
	 //                $respuesta = $respuesta . "<td class='align-middle font-weight-bold'>";
	 //                $respuesta = $respuesta . $r[5];
	 //                $respuesta = $respuesta . "</td>";
	 //                $respuesta = $respuesta . "<td class='d-none d-md-table-cell align-middle'>";
	 //                $respuesta = $respuesta . $r[6];
	 //                $respuesta = $respuesta . "</td>";
	 //                $respuesta = $respuesta . "<td class='align-middle'>";
					// $fecha_original = $r[3];
	 //                $nuevo_formato = "d-m-Y";
	 //                $fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
	 //                $respuesta = $respuesta . $fecha_cambiada;
	 //                $respuesta = $respuesta . "</td>";
	 //                $respuesta = $respuesta . "<td class='align-middle'>";
	 //                $respuesta = $respuesta . $r[4];
	 //                $respuesta = $respuesta . "$</td>";
	 //                $respuesta = $respuesta . "<td class='align-middle'>";
	 //                $respuesta = $respuesta . "<button class='btn btn-success' style='font-size: 13px;' onclick='mostrar_registrar_pago(this)'>Pagar</button>";
	 //                $respuesta = $respuesta . "</td>";
	 //                $respuesta = $respuesta . "</tr>";
	 //            }
		/*
					$consulta = $co->prepare("SELECT a.num_letra_apartamento,a.torre,dis.concepto, dis.fecha,
					(SUM( IF(dd.tipo_monto = 1,dd.monto,((SELECT ROUND((dd.monto/divs.monto),2) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1)) ) )) AS monto, NULL as extra, d.id_deuda,p.estado, a.propietario
					FROM deudas AS d 
					JOIN apartamento as a on a.id_apartamento = d.id_apartamento 
					JOIN distribuciones as dis ON dis.id_distribucion = d.id_distribucion
					LEFT JOIN detalles_deudas as dd on dd.id_deuda = d.id_deuda
					LEFT JOIN deuda_pagos ON deuda_pagos.id_deuda = d.id_deuda
					LEFT JOIN pagos AS p ON p.id_pago = deuda_pagos.id_pago
					WHERE a.propietario = :habitante AND ((p.estado <> 0 AND p.estado <> 2) OR p.estado IS NULL)  GROUP by a.num_letra_apartamento;");
		*/
			$co->query("SELECT @divisa_monto := divs.monto FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1;");

			$consulta = $co->prepare("
				SELECT
						a.num_letra_apartamento,
						a.torre,
						dis.concepto,
						dis.fecha, 
						(SUM(DISTINCT IF(dd.tipo_monto = 1, dd.monto, (ROUND(dd.monto / @divisa_monto, 2)) ) ) ) AS monto,
						NULL AS extra,
						d.id_deuda,
						p.estado,
						a.propietario,
						p.id_pago
						FROM
						deudas AS d
						JOIN apartamento AS a ON a.id_apartamento = d.id_apartamento
						LEFT JOIN distribuciones AS dis ON dis.id_distribucion = d.id_distribucion
						LEFT JOIN detalles_deudas AS dd ON dd.id_deuda = d.id_deuda
						LEFT JOIN
						(
						SELECT p.*,dp.id_deuda from pagos as p JOIN deuda_pagos as dp on dp.id_pago = p.id_pago WHERE 1
						)AS p on p.id_deuda = d.id_deuda
						WHERE (a.propietario = :habitante OR a.inquilino = :habitante) AND (p.estado = 0 OR p.estado IS NULL) GROUP BY a.num_letra_apartamento,p.estado, d.id_distribucion ORDER BY dis.fecha DESC , a.num_letra_apartamento;");

				  $consulta->bindValue(":habitante",$this->id_habitante);
				  $consulta->execute();
				  $respuesta = $consulta->fetchall(PDO::FETCH_ASSOC);


				$r['resultado'] = 'listadodeudas';
				$r['mensaje'] =  $respuesta;
			
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function historialpagos()
	{
		
		$usuario = $this->id_habitante;

		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT p.id_pago, a.num_letra_apartamento,
			GROUP_CONCAT(ddd.fecha SEPARATOR \"<br>\") as fecha,
			GROUP_CONCAT(tp.tipo_pago SEPARATOR \"<br>\") as tipo_pago,
			dis.concepto,
			p.total_pago,

			if(p.estado = 0,\"Por Confirmar\",if(p.estado = 1,\"Declinado\",if(p.estado = 2,\"Confirmado\",\"Por Confirmar\"))) as estado


			FROM pagos as p
			JOIN deuda_pagos as dp on dp.id_pago = p.id_pago
			JOIN deudas as d on d.id_deuda = dp.id_deuda
			JOIN distribuciones as dis on dis.id_distribucion = d.id_distribucion
			JOIN apartamento as a on a.id_apartamento = d.id_apartamento
			LEFT JOIN detalles_pagos as ddd on ddd.id_pago = p.id_pago
			LEFT JOIN tipo_pago as tp on ddd.tipo_pago = tp.id_tipo_pago
			WHERE a.propietario = :habitante OR a.inquilino = :habitante GROUP BY p.id_pago ORDER BY dis.fecha DESC;");
			$consulta->bindValue(":habitante", $usuario);
			$consulta->execute();
			
			$r['resultado'] = 'historialpagos';
			$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_NUM);;
			$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		return $r;




	}
	PUBLIC function registrarpago_s(){
		return $this->registrarpago();
	}
	PUBLIC function eliminar_pagos_s(){
		return $this->eliminar_pagos();
	}
	PRIVATE function registrarpago(){

		try {
			$this->validar_conexion($this->con);
			$V = new Validaciones;
			$V->validarPagos($this->obj_pagos);
			$this->con->beginTransaction();

			$consulta = $this->con->prepare("SELECT * FROM deuda_pagos as dp JOIN pagos as p on p.id_pago = dp.id_pago WHERE dp.id_deuda = ? AND (p.estado = 0 OR p.estado = 2)");
			$consulta->execute([$this->id]);
			if($consulta = $consulta->fetch(PDO::FETCH_ASSOC)){
				if($consulta["estado"] == 0){
					throw new Validaciones("Existe un pago por confirmar en esta deuda", 1);
				}
				else{
					throw new Validaciones("Existe un pago confirmado en esta deuda", 1);
				}
			}
			$consulta = $this->con->prepare("INSERT INTO pagos (concepto_pago,estado) VALUES (?,?)");
			$consulta->execute(["", 0]);

			$this->obj_pagos->id_pago = $this->con->lastInsertId();

			// $consulta = $this->con->prepare("SELECT id_deuda from deuda_pagos WHERE id_deuda = ?");
			// $consulta->execute([$this->id]);
			// if($consulta->fetch(PDO::FETCH_ASSOC)){
			// 	throw new Validaciones("Error del sistema Intente mas tarde", 1);
			// }

			$consulta = $this->con->prepare("INSERT INTO deuda_pagos (id_deuda, id_pago) VALUES (?,?)");
			$consulta->execute([$this->id, $this->obj_pagos->id_pago]);

			$consulta = $this->con->prepare("INSERT INTO `detalles_pagos`(
											`id_pago`,
											`tipo_pago`,
											`monto`,
											`fecha`,
											`tipo_monto`,
											`numero`,
											`emisor`,
											`cedula_rif`,
											`telefono`
										)
										VALUES(
											:id_pago,
											:tipo_pago,
											:monto,
											:fecha,
											:tipo_monto,
											:numero,
											:emisor,
											:cedula_rif,
											:telefono
										);");
				$consulta->bindValue(':id_pago', $this->obj_pagos->id_pago );
				$consulta->bindValue(':tipo_pago', $this->obj_pagos->tipo_pago );
				$consulta->bindValue(':monto', preg_replace(["/\./","/,/"], ["","."], $this->obj_pagos->monto) );
				$consulta->bindValue(':fecha', $this->obj_pagos->fecha." ".$this->obj_pagos->hora.":00" );
				$consulta->bindValue(':tipo_monto', 0 );

				$consulta->bindValue(':numero', null );
				$consulta->bindValue(':emisor', null );
				$consulta->bindValue(':cedula_rif', null );
				$consulta->bindValue(':telefono', null );

				if($this->obj_pagos->tipo_pago == 2){// transferencia
					$consulta->bindValue(':cedula_rif', $this->obj_pagos->cedula );
					$consulta->bindValue(':numero', $this->obj_pagos->referencia );
					$consulta->bindValue(':emisor', $this->obj_pagos->banco );
				}
				else if($this->obj_pagos->tipo_pago == 3){// pagomovil
					$consulta->bindValue(':cedula_rif', $this->obj_pagos->cedula );
					$consulta->bindValue(':numero', $this->obj_pagos->referencia );
					$consulta->bindValue(':emisor', $this->obj_pagos->banco );
					$consulta->bindValue(':telefono', $this->obj_pagos->telefono );
				}

				$consulta->execute();

				if($this->obj_pagos->tipo_pago == 4){//divisa

					$this->obj_pagos->id_detalles_pagos = $this->con->lastInsertId();

					$consulta = "(?,?,?),";

					$consulta = str_repeat($consulta, count($this->obj_pagos->billetes) - 1)."(?,?,?);";

					$consulta = "INSERT INTO divisa VALUES ".$consulta;
					$consulta = $this->con->prepare($consulta);
					$cont = 1;
					foreach ($this->obj_pagos->billetes as $key) {
						$consulta->bindValue($cont, $this->obj_pagos->id_detalles_pagos);
						$cont++;
						$consulta->bindValue($cont, $key->serial);
						$cont++;
						$consulta->bindValue($cont, $key->denominacion);
						$cont++;
					}
					$consulta->execute();
				}


				// require_once("model/enviar-ws.php");
				// $mensajeWS = new enviarws;
				// $ws = $mensajeWS->enviarws2("Su pago ha sido recibido correctamente le informaremos cuando este alla sido verificado");
			
			$r['resultado'] = 'registrarpago';
			$r['mensaje'] = "";
			$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		return $r;



		// $co = $this->conecta();
		// $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// $r = array();
		// $fecha = date('Y-m-d');
		// $monto = trim($monto,'$');
		// try {
		// 	$guarda = $co->query("insert into pago(referencia,fecha_entrega,tipo_pago,total,deuda,id_usuario,estado) 
		//    values ('$referencia','$fecha','$tipo_pago','$monto',$id_deuda,NULL,'pendiente')");
		// 	$r['resultado'] = 'registrado';
		// 	$r['mensaje'] =  "Pago Registrado";
		// 	$bitacora = new Bitacora();
		// 	$bitacora->b_incluir();
		// } catch (Exception $e) {
		// 	$r['resultado'] = 'error';
		// 	$r['mensaje'] =  $e->getMessage();
		// }
		// return $r;
	}

	PRIVATE function eliminar_pagos(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM deuda_pagos WHERE id_deuda = ? AND id_pago = ?");
			$consulta->execute([$this->id, $this->id_pago]);

			if(!($consulta = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Validaciones("El pago seleccionado no existe.\n Intente mas tarde", 1);
			}
			$id_pago = $consulta["id_pago"];

			$consulta = $this->con->prepare("DELETE FROM deuda_pagos WHERE id_deuda = ? AND id_pago = ?");
			$consulta->execute([$this->id, $this->id_pago]);
			$consulta = $this->con->prepare("DELETE FROM pagos WHERE id_pago = ?;");
			$consulta->execute([$id_pago]);


			
			$r['resultado'] = 'eliminar_pagos';
			$r['mensaje'] =  "El pago ha sido eliminado exitosamente $this->id";
			$this->con->commit();
		
		} catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['mensaje'] =  $e->getMessage();
			$r['console'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		return $r;
	}


	PUBLIC function get_obj_pagos(){
		return $this->obj_pagos;
	}
	PUBLIC function set_obj_pagos($value){
		$value = json_decode($value);
		$this->obj_pagos = $value;
	}
	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}

	PUBLIC function get_id_pago(){
		return $this->id_pago;
	}
	PUBLIC function set_id_pago($value){
		$this->id_pago = $value;
	}
	PUBLIC function get_id_habitante(){
		return $this->id_habitante;
	}
	PUBLIC function set_id_habitante($value){
		$this->id_habitante = $value;
	}
}


#SELECT a.num_letra_apartamento,a.torre,dis.concepto, dis.fecha,
#            (SUM( IF(dd.tipo_monto = 1,dd.monto,((SELECT ROUND((dd.monto/divs.monto),2) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1)) ) )) AS monto, NULL as extra, d.id_deuda,p.estado, a.propietario
#            FROM deudas AS d 
#            JOIN apartamento as a on a.id_apartamento = d.id_apartamento 
#            JOIN distribuciones as dis ON dis.id_distribucion = d.id_distribucion
#            LEFT JOIN detalles_deudas as dd on dd.id_deuda = d.id_deuda
#            LEFT JOIN deuda_pagos ON deuda_pagos.id_deuda = d.id_deuda
#            LEFT JOIN pagos AS p ON p.id_pago = deuda_pagos.id_pago
#            WHERE a.propietario = :habitante AND ((p.estado <> 0 AND p.estado <> 2) OR p.estado IS NULL)  GROUP by a.num_letra_apartamento;



/*

SELECT @divisa_monto := divs.monto FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1;

SELECT
    a.num_letra_apartamento,
    a.torre,
    dis.concepto,
    dis.fecha, (SUM(DISTINCT IF(dd.tipo_monto = 1, dd.monto, (ROUND(dd.monto / @divisa_monto, 2)) ) ) ) AS monto,
    NULL AS extra,
    d.id_deuda,
    p.estado,
    a.propietario,
    p.id_pago
FROM
    deudas AS d
JOIN apartamento AS a
ON
    a.id_apartamento = d.id_apartamento
LEFT JOIN distribuciones AS dis
ON
    dis.id_distribucion = d.id_distribucion
LEFT JOIN detalles_deudas AS dd
ON
    dd.id_deuda = d.id_deuda
LEFT JOIN deuda_pagos ON deuda_pagos.id_deuda = d.id_deuda
LEFT JOIN pagos AS p
ON
    p.id_pago = deuda_pagos.id_pago AND p.estado = 0
WHERE
    a.propietario = 1 OR a.inquilino = 1 AND p.estado = 0
GROUP BY
    a.num_letra_apartamento,
    d.id_distribucion
ORDER BY
    dis.fecha
DESC
    ,
    a.num_letra_apartamento;


*/