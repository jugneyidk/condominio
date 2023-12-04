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
		if(isset($_SESSION["id_habitante"])){
			$this->id_habitante = $_SESSION['id_habitante'];
		}
	}

	PUBLIC function chequearpermisos()
	{
		$id_rol = $_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol'];
		$modulo = $_GET['p'];
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$guarda = $co->prepare("SELECT * FROM `roles_modulos` inner join `modulos` on roles_modulos.id_modulo = modulos.id inner join `roles` on roles_modulos.id_rol = roles.id where modulos.nombre = ? and roles_modulos.id_rol = ?");
		$guarda->execute([$modulo,$id_rol]);
		$fila = array();
		$fila = $guarda->fetch(PDO::FETCH_NUM);
		$co = null;
		return $fila;
	}


	PUBLIC function listadodeudas()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$usuario = $this->id_habitante;
		try {
			$co->query("SELECT @divisa_monto := divs.monto FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1;");
			if(isset($this->id_habitante)){

				$consulta = $co->prepare("SELECT
							a.num_letra_apartamento,
							a.torre,
							a.piso,
							dis.concepto,
							dis.fecha, 
							(SUM(DISTINCT IF(dd.tipo_monto = 1, dd.monto, (ROUND(dd.monto / @divisa_monto, 2)) ) ) ) AS monto,
							NULL AS extra,
							d.id_deuda,
							IF(p.estado = 1,NULL,p.estado) AS estado,
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
							WHERE (a.propietario = :habitante OR a.inquilino = :habitante) AND 
							(p.estado = 0 OR p.estado IS NULL OR p.estado = 1) 
							GROUP BY 
							a.num_letra_apartamento,
							d.id_distribucion,
							d.id_deuda
							ORDER BY dis.fecha DESC ,
							a.num_letra_apartamento;");
			}
			else{
				$consulta = $co->prepare("SELECT
							a.num_letra_apartamento,
							a.torre,
							a.piso,
							dis.concepto,
							dis.fecha, 
							(SUM(DISTINCT IF(dd.tipo_monto = 1, dd.monto, (ROUND(dd.monto / @divisa_monto, 2)) ) ) ) AS monto,
							NULL AS extra,
							d.id_deuda,
							p.estado,
							a.propietario,
							p.id_pago,
							d.moroso
							FROM
							deudas AS d
							JOIN apartamento AS a ON a.id_apartamento = d.id_apartamento
							LEFT JOIN distribuciones AS dis ON dis.id_distribucion = d.id_distribucion
							LEFT JOIN detalles_deudas AS dd ON dd.id_deuda = d.id_deuda
							LEFT JOIN
							(
							SELECT p.*,dp.id_deuda from pagos as p JOIN deuda_pagos as dp on dp.id_pago = p.id_pago WHERE 1
							)AS p on p.id_deuda = d.id_deuda
							WHERE (p.estado = 0 OR p.estado IS NULL) GROUP BY a.num_letra_apartamento,p.estado, d.id_distribucion ORDER BY dis.fecha DESC , a.num_letra_apartamento LIMIT 600;");

			}

			if(isset($this->id_habitante)){
				$consulta->bindValue(":habitante",$this->id_habitante);
			}
			$consulta->execute();
			if(isset($this->id_habitante)){

				$respuesta = $consulta->fetchall(PDO::FETCH_ASSOC);
			}
			else{
				$consulta = $consulta->fetchall(PDO::FETCH_ASSOC);
				$respuesta['deuda_normal'] = array();
				$respuesta['deuda_moroso'] = array();

				foreach ($consulta as $elem) {
					if($elem["moroso"]== '0'){
						$respuesta['deuda_normal'][] = $elem;
					}
					else{
						$respuesta['deuda_moroso'][] = $elem;
					}
				}
			}


			$r['resultado'] = 'listadodeudas';
			$r['mensaje'] = $respuesta;
			
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine()." el habitante (".$this->id_habitante.")";
		}
		finally{$co=null;}
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
		finally{$this->con = null;}
		return $r;




	}
	PUBLIC function registrarpago_s(){
		return $this->registrarpago();
	}
	PUBLIC function eliminar_pagos_s(){
		return $this->eliminar_pagos();
	}
	PUBLIC function detalles_deuda_s(){
		return $this->detalles_deuda();
	}
	PRIVATE function detalles_deuda(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$this->con->query("SELECT @divisa_monto := divs.monto FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1;");
			$consulta = $this->con->prepare("SELECT 
							dd.id_deuda,
							dd.concepto,
							ROUND(dd.monto,2) as monto,
							dd.tipo_monto

							FROM detalles_deudas as dd 
							WHERE dd.id_deuda = ?
							ORDER BY dd.concepto;");
			$consulta->execute([$this->id]);

			$resultado = $consulta->fetchall(PDO::FETCH_ASSOC);

			$consulta = $this->con->prepare("SELECT 
							'Total' as concepto,
							(SUM(IF(dd.tipo_monto = 0, dd.monto, (ROUND(dd.monto * @divisa_monto, 2)) ) ) ) AS monto,
							0 as tipo_monto

							FROM detalles_deudas as dd 
							WHERE dd.id_deuda = ?
							ORDER BY id_deuda;");
			$consulta->execute([$this->id]);


			$consulta = $consulta->fetch(PDO::FETCH_ASSOC);
			$r["total"] = $consulta["monto"];

			array_push($resultado, $consulta);
			
			$r['resultado'] = 'detalles_deuda';
			$r['mensaje'] =  $resultado;
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
		finally{
			$this->con = null;
		}
		return $r;
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
				if(isset($_SESSION["id_usuario"])){
					$bitacora = new Bitacora();
					$bitacora->b_registro("Registro el pago Nº".$this->obj_pagos->id_pago." de la deuda Nª".$this->id);
					$bitacora->set_c(null);
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
			$consulta = $this->con->prepare("SELECT dp.*,p.estado FROM deuda_pagos as dp JOIN pagos as p on p.id_pago = dp.id_pago WHERE dp.id_deuda = ? AND dp.id_pago = ?");
			$consulta->execute([$this->id, $this->id_pago]);

			if(!($consulta = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Validaciones("El pago seleccionado no existe.", 1);
			}

			if($consulta["estado"] == '1' OR $consulta["estado"] == '2' ){
				$error = ($consulta["estado"] == '1')?"declinado":"confirmado";
				throw new Validaciones("El pago ya ha sido $error y no puede eliminarse", 1);
			}




			$id_pago = $consulta["id_pago"];

			$consulta = $this->con->prepare("DELETE FROM deuda_pagos WHERE id_deuda = ? AND id_pago = ?");
			$consulta->execute([$this->id, $this->id_pago]);
			$consulta = $this->con->prepare("DELETE FROM pagos WHERE id_pago = ?;");
			$consulta->execute([$id_pago]);


			
			$r['resultado'] = 'eliminar_pagos';
			$r['mensaje'] =  "El pago ha sido eliminado exitosamente $this->id";
			if(isset($_SESSION["id_usuario"])){
				$bitacora = new Bitacora();
				$bitacora->b_registro("Elimino el pago Nº".$this->id_pago." de la deuda Nª".$this->id);
				$bitacora->set_c(null);
			}
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