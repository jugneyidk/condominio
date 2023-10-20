<?php

require_once('model/datos.php');
require_once("model/bitacora.php");



class detallesdeuda extends datos
{
	PRIVATE $obj_pagos,$id;
    PUBLIC function listadodeudas()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $usuario = $_SESSION['id_habitante'];
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

        	$consulta = $co->prepare("SELECT a.num_letra_apartamento,a.torre,dis.concepto, dis.fecha,
        	(SUM( IF(dd.tipo_monto = 1,dd.monto,((SELECT ROUND((dd.monto/divs.monto),2) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1)) ) )) AS monto, NULL as extra, d.id_deuda,p.estado, a.propietario
        	FROM deudas AS d 
        	JOIN apartamento as a on a.id_apartamento = d.id_apartamento 
        	JOIN distribuciones as dis ON dis.id_distribucion = d.id_distribucion
        	LEFT JOIN detalles_deudas as dd on dd.id_deuda = d.id_deuda
            LEFT JOIN deuda_pagos ON deuda_pagos.id_deuda = d.id_deuda
            LEFT JOIN pagos AS p ON p.id_pago = deuda_pagos.id_pago
        	WHERE a.propietario = :habitante AND ((p.estado <> 0 AND p.estado <> 2) OR p.estado IS NULL)  GROUP by a.num_letra_apartamento;");

        	      $consulta->bindValue(":habitante",$_SESSION["id_habitante"]);
        	      $consulta->execute();
        	      $respuesta = $consulta->fetchall(PDO::FETCH_NUM);


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
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$usuario = $_SESSION['id_habitante'];
		$r = array();
		try {
			$resultado = $co->query("SELECT DISTINCT p.id_pago, a.num_letra_apartamento, a.torre, a.piso, p.* FROM deuda_pendiente dp JOIN apartamento a ON dp.id_apartamento = a.id_apartamento JOIN habitantes h ON a.propietario = h.id LEFT JOIN pago p ON dp.id = p.deuda WHERE a.propietario = '$usuario' AND (dp.id IN (SELECT deuda from pago WHERE estado = 'confirmado' OR estado = 'pendiente' OR estado = 'declinado')) ORDER BY p.id_pago DESC;");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[0];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle font-weight-bold'>";
					$respuesta = $respuesta . $r[1];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='d-none d-lg-table-cell align-middle'>";
					$respuesta = $respuesta . $r[2];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='d-none d-lg-table-cell align-middle'>";
					$respuesta = $respuesta . $r[3];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$fecha_original = $r[6];
                    $nuevo_formato = "d-m-Y";
                    $fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
					$respuesta = $respuesta . $fecha_cambiada;
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[5];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[7];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[8];
					$respuesta = $respuesta . "$</td>";
					$respuesta = $respuesta . "<td class='align-middle text-capitalize'>";
					$respuesta = $respuesta . $r[11];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'historialpagos';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
    public function registrarpago_s{
        return $this->registrarpago();
    }
    PRIVATE function registrarpago(){

    	try {
    		$this->con = $this->conecta();
    		$this->validar_conexion($this->con);
    		$V = new Validaciones;
    		$V->validarPagos($this->obj_pagos);
    		$this->con->beginTransaction();
    		
    		$consulta = $this->con->prepare("INSERT INTO pagos (concepto_pago,estado) VALUES (?,?)");
    		$consulta->execute(["", 0]);

    		$this->obj_pagos->id_pago = $this->con->lastInsertId();

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
    		$r['mensaje'] =  $e->getMessage().": Code : ".$e->getLine();
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
}
