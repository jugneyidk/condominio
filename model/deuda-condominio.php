<?php

require_once('model/datos.php');
require_once("model/bitacora.php");
require_once("model/enviar-correo.php");



class Deudacondominio extends datos
{
	PRIVATE $con;
	//cargos
	PRIVATE $id, $concepto, $monto, $tipo_monto, $tipo_cargo, $mensual, $aplicar_next_mes, $apartamentos, $fecha;

	PUBLIC function __construct(){
		$this->con = $this->conecta();
		$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	PUBLIC function incluir_cargo_s($concepto, $monto, $tipo_monto, $tipo_cargo, $mensual, $aplicar_next_mes, $apartamentos){

		$this->set_concepto($concepto);
		$this->set_monto($monto);
		$this->set_tipo_monto($tipo_monto);
		$this->set_tipo_cargo($tipo_cargo);
		$this->set_mensual($mensual);
		$this->set_aplicar_next_mes($aplicar_next_mes);
		$this->set_apartamentos($apartamentos);
		return $this->incluir_cargo();
	}
	PUBLIC function modificar_cargo_s($id ,$concepto, $monto, $tipo_monto, $tipo_cargo, $mensual, $aplicar_next_mes, $apartamentos){
		$this->set_id($id);
		$this->set_concepto($concepto);
		$this->set_monto($monto);
		$this->set_tipo_monto($tipo_monto);
		$this->set_tipo_cargo($tipo_cargo);
		$this->set_mensual($mensual);
		$this->set_aplicar_next_mes($aplicar_next_mes);
		$this->set_apartamentos($apartamentos);

		return $this->modificar_cargo();
	}
	PUBLIC function eliminar_cargo_s($id){
		$this->set_id($id);
		return $this->eliminar_cargo();
	}
	PRIVATE function incluir_cargo(){

		try {
			$this->validar_conexion($this->con);
			$V = new Validaciones();
			$V->alfanumerico ($this->concepto,"1,80","El concepto tiene caracteres inválidos o esta vació");
			$V->monto($this->monto);
			if($this->tipo_monto === false){
				throw new Validaciones("Debe seleccionar un tipo de monto", 1);
			}
			if($this->tipo_cargo === false){
				throw new Validaciones("Debe seleccionar el tipo de cargo (global/dedicado)", 1);
				
			}
			if($this->mensual === false){
				throw new Validaciones("Debe seleccionar el tipo de cargo (mensual/único)", 1);
				
			}
			if($this->tipo_cargo === 0){
				if(!is_array($this->apartamentos)){
					throw new Validaciones("Debe seleccionar el/los apartamentos a ser aplicados los cargos", 1);
				}
			}
			if($this->aplicar_next_mes === false){
				throw new Validaciones("Debe seleccionar si desea aplicar el cargo el siguiente mes o no", 1);
			}

			$this->con->beginTransaction();
			$consulta = $this->con->prepare("INSERT INTO lista_cargos_d 
				(`concepto`,
				 `monto`,
				 `tipo_monto`,
				 `tipo_cargo`,
				 `mensual`,
				 `aplicar_next_mes`)
				 VALUES
				 (
					 :concepto,
					 :monto,
					 :tipo_monto,
					 :tipo_cargo,
					 :mensual,
					 :aplicar_next_mes
				 );");
			$consulta->bindValue(":concepto", $this->concepto);
			$consulta->bindValue(":monto", $this->monto);
			$consulta->bindValue(":tipo_monto", $this->tipo_monto);
			$consulta->bindValue(":tipo_cargo", $this->tipo_cargo);
			$consulta->bindValue(":mensual", $this->mensual);
			$consulta->bindValue(":aplicar_next_mes", $this->aplicar_next_mes);

			$consulta->execute();

			

			if($this->tipo_cargo == 0)//dedicado
			{
				$this->id = $this->con->lastInsertId();
				$consulta = $this->con->prepare("INSERT INTO apartamentos_lista_cargos (id_apartamento,id_lista_cargos)
					VALUES
					((SELECT id_apartamento FROM apartamento WHERE num_letra_apartamento = ?), ?);");
				for($i = 0;$i<count($this->apartamentos);$i++){
					$consulta->execute([$this->apartamentos[$i][1], $this->id]);
				}
			}

			$bitacora = new Bitacora();
			$bitacora->b_registro("Registró un nuevo cargo \"$this->concepto\"");

			
			//$consulta=$this->con->query("SELECT * FROM apartamentos_lista_cargos WHERE 1;")->fetchall(PDO::FETCH_ASSOC);
			
			
			

			
			//$r['resultado'] = 'console';
			$r['resultado'] = 'incluir_cargo';
			$r['mensaje'] =  '';
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

	}
	PRIVATE function modificar_cargo(){
		try {
			$this->validar_conexion($this->con);
			$V = new Validaciones();
			$V->alfanumerico ($this->concepto,"1,80","El concepto tiene caracteres inválidos o esta vació");
			$V->monto($this->monto);
			if($this->tipo_monto === false){
				throw new Validaciones("Debe seleccionar un tipo de monto", 1);
			}
			if($this->tipo_cargo === false){
				throw new Validaciones("Debe seleccionar el tipo de cargo (global/dedicado)", 1);
				
			}
			if($this->mensual === false){
				throw new Validaciones("Debe seleccionar el tipo de cargo (mensual/único)", 1);
				
			}
			if($this->tipo_cargo === 0){
				if(!is_array($this->apartamentos)){
					throw new Validaciones("Debe seleccionar el/los apartamentos a ser aplicados los cargos", 1);
				}
			}
			if($this->aplicar_next_mes === false){
				throw new Validaciones("Debe seleccionar si desea aplicar el cargo el siguiente mes o no", 1);
			}
			$this->con->beginTransaction();
				
				$consulta = $this->con->prepare("SELECT 1 FROM lista_cargos_d WHERE id_lista_cargos = ?;");
				$consulta->execute([$this->id]);
				if($consulta->fetch()){

					$consulta = $this->con->prepare("UPDATE `lista_cargos_d` SET `concepto`= :concepto, `monto`= :monto, `tipo_monto`= :tipo_monto, `tipo_cargo`= :tipo_cargo, `mensual`= :mensual, `aplicar_next_mes`= :aplicar_next_mes WHERE `id_lista_cargos` = :id_lista_cargos;");


					$consulta->bindValue(":id_lista_cargos", $this->id);
					$consulta->bindValue(":concepto", $this->concepto);
					$consulta->bindValue(":monto", $this->monto);
					$consulta->bindValue(":tipo_monto", $this->tipo_monto);
					$consulta->bindValue(":tipo_cargo", $this->tipo_cargo);
					$consulta->bindValue(":mensual", $this->mensual);
					$consulta->bindValue(":aplicar_next_mes", $this->aplicar_next_mes);
					$consulta->execute();


					if($this->tipo_cargo == 0)//dedicado
					{
						$consulta = $this->con->prepare("DELETE FROM apartamentos_lista_cargos WHERE id_lista_cargos = ?;");
						$consulta->execute([$this->id]);
						
						$consulta = $this->con->prepare("INSERT INTO apartamentos_lista_cargos (id_apartamento,id_lista_cargos)
							VALUES
							((SELECT id_apartamento FROM apartamento WHERE num_letra_apartamento = ?), ?);");

						for($i = 0;$i<count($this->apartamentos);$i++){
							$consulta->execute([$this->apartamentos[$i][1], $this->id]);
						}
					}


					
				}
				else throw new Validaciones("El cargo a no existe o fue eliminado", 1);


				$bitacora = new Bitacora();
				$bitacora->b_registro("Modificó el cargo \"$this->concepto\"");
			
			$r['resultado'] = 'modificar_cargo';
			$r['mensaje'] =  "";
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
	}
	PRIVATE function eliminar_cargo(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT concepto FROM lista_cargos_d WHERE id_lista_cargos = ?;");
			$consulta->execute([$this->id]);



			if($resp = $consulta->fetch(PDO::FETCH_ASSOC)){
				$consulta = $this->con->prepare("DELETE FROM lista_cargos_d WHERE id_lista_cargos = ?;");
				$consulta->execute([$this->id]);
			}
			else throw new Validaciones("El cargo a no existe o fue eliminado", 1);

			$this->concepto = $resp["concepto"];

			$bitacora = new Bitacora();
			$bitacora->b_registro("Eliminó el cargo \"$this->concepto\"");


			
			
			$r['resultado'] = 'eliminar_cargo';
			$r['mensaje'] =  "El cargo fue eliminado exitosamente";
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
	}

	PUBLIC function lista_apartamentos(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			if(!$this->id){
				$consulta = $this->con->prepare("SELECT
											NULL as extra,
										    a.num_letra_apartamento,
										    CONCAT(h.`nombres`,' ',h.apellidos) as nombre,
										    a.torre,
										    a.piso,
										    ta.descripcion AS tipo_apartamento
										FROM
										    `apartamento` AS a
										JOIN tipo_apartamento AS ta
										ON
										    a.tipo_apartamento = ta.id_tipo_apartamento
										JOIN habitantes as h on h.id = a.propietario
										WHERE
										    1 ORDER BY a.num_letra_apartamento");
				$consulta->execute();
				
				$r['resultado'] = 'lista_apartamentos';
				$r['mensaje'] =  $consulta->fetchall(PDO::FETCH_NUM);;
			}
			else{
				$consulta= $this->con->prepare("SELECT
								    `concepto`,
								    `monto`,
								    `tipo_monto`,
								    `tipo_cargo`,
								    `mensual`,
								    `aplicar_next_mes`,
								    lc.`id_lista_cargos`,
								    IF(tipo_cargo = 1,0,(SELECT COUNT(*) FROM apartamentos_lista_cargos AS alc WHERE alc.id_lista_cargos = lc.id_lista_cargos)) AS apartamentos
								FROM
								    `lista_cargos_d` AS lc
								WHERE
								    `id_lista_cargos` = ?");
				$consulta->execute([$this->id]);










				$resultado["cargo"] = $consulta->fetch(PDO::FETCH_ASSOC);
				$resultado['lista'] = [];
				$consulta = $this->con->prepare("SELECT NULL AS
							    extra,
							    a.num_letra_apartamento,
							    CONCAT(h.`nombres`, ' ', h.apellidos) AS nombre,
							    a.torre,
							    a.piso,
							    ta.descripcion AS tipo_apartamento
							FROM
							    `apartamento` AS a
							JOIN tipo_apartamento AS ta
							ON
							    a.tipo_apartamento = ta.id_tipo_apartamento
							JOIN habitantes AS h
							ON
							    h.id = a.propietario
							JOIN apartamentos_lista_cargos as alc ON alc.id_apartamento = a.id_apartamento
							WHERE
							    alc.id_lista_cargos = ?
							ORDER BY
							    a.num_letra_apartamento");
				$consulta->execute([$this->id]);
				$consulta = $consulta->fetchall(PDO::FETCH_NUM);
				foreach ($consulta as $elem) {
					$resultado[$elem[1]] = 1;
					$resultado['lista'][] = $elem;
				}

				$r['resultado'] = 'lista_apartamentos_select';
				//$r['resultado'] = 'console';
				$r['mensaje'] =  $resultado;
			}
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
	}

	PUBLIC function lista_cargos(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->query("SELECT
								    `concepto`,
								    `monto`,
								    `tipo_monto`,
								    `tipo_cargo`,
								    `mensual`,
								    `aplicar_next_mes`,
								    lc.`id_lista_cargos`,
								    IF(tipo_cargo = 1,0,(SELECT COUNT(*) FROM apartamentos_lista_cargos AS alc WHERE alc.id_lista_cargos = lc.id_lista_cargos)) AS apartamentos
								FROM
								    `lista_cargos_d` AS lc
								WHERE
								    1")->fetchall(PDO::FETCH_NUM);
			

			foreach ($consulta as &$elem) {
				$elem[1] = number_format($elem[1],2,",",".");
				$elem[2] = $elem[2]==0?"Bolívares":"Divisa";
				$elem[3] = $elem[3]==0?"Dedicado ({$elem[7]})":"Global";
				$elem[4] = $elem[4]==0?"Único":"Mensual";
				$elem[5] = $elem[5]==1?"Si":"No";
				//$elem[1]='x';
			}
			$r['resultado'] = 'lista_cargos';
			$r['mensaje'] =  $consulta;
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

	}

	PUBLIC function lista_resumen_cargos(){

		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = "SELECT
			    CONCAT(
			        'Puesto de Estacionamiento ',
			        e.num_estacionamiento
			    ) AS concepto,
			    e.costo AS divisa,
			     ROUND((SELECT (divisa*divs.monto) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1),2) AS bolivares
				FROM
				    estacionamiento AS e WHERE e.id_apartamento IS NOT NULL
				UNION
				SELECT
					CONCAT('Apartamento ',ta.descripcion,' (',ta.cantidadHijos,')') AS concepto,
				    ta.alicuota AS divisa,
				    ROUND((SELECT (divisa*divs.monto) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1),2) as bolivares

				FROM tipo_apartamento as ta
				UNION 
				SELECT
				lcd.concepto,
				IF(lcd.tipo_monto = 1,lcd.monto, ROUND((SELECT (lcd.monto/divs.monto) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1),2)) AS divisa,
				IF(lcd.tipo_monto = 0,lcd.monto, ROUND((SELECT (lcd.monto*divs.monto) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1),2)) AS bolivares
				FROM lista_cargos_d as lcd WHERE lcd.tipo_cargo = 1 AND lcd.aplicar_next_mes = 1;";

				$consulta = $this->con->prepare($consulta);
				$consulta->execute();


				$r['global'] =  $consulta->fetchall(PDO::FETCH_ASSOC);

				$consulta = $this->con->prepare("SELECT 
												CONCAT(lc.concepto,' (',COUNT(alc.id_lista_cargos),')') AS concepto,
											IF(lc.tipo_monto = 1,lc.monto, (SELECT ROUND((lc.monto/divs.monto),2) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1)) AS divisa,
											IF(lc.tipo_monto = 0,lc.monto, (SELECT ROUND((lc.monto*divs.monto),2) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1)) AS bolivares
											FROM lista_cargos_d AS lc
											JOIN apartamentos_lista_cargos AS alc ON alc.id_lista_cargos = lc.id_lista_cargos
											WHERE lc.tipo_cargo = 0 GROUP BY lc.id_lista_cargos;");

				$consulta->execute();
				$r["dedicados"] = $consulta->fetchall(PDO::FETCH_ASSOC);











				
				$r['resultado'] = 'lista_resumen_cargos';
				
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



	}

	PUBLIC function distribuir_deudas_s($fecha, $concepto){
		$this->set_fecha($fecha);
		$this->set_concepto($concepto);

		return $this->distribuir_deudas();
	}
	PUBLIC function modificar_distribucion_s($id, $fecha, $concepto){
		$this->set_id($id);
		$this->set_fecha($fecha);
		$this->set_concepto($concepto);		

		return $this->modificar_distribucion();
	}
	PUBLIC function eliminar_distribucion_s($id){
		$this->set_id($id);
		return $this->eliminar_distribucion();
	}



	PRIVATE function distribuir_deudas(){
		try {
			$this->validar_conexion($this->con);
			$V = new Validaciones();
			$V->fecha($this->fecha);
			$V->alfanumerico($this->concepto);
			$this->con->beginTransaction();

			$consulta = $this->con->prepare("INSERT INTO distribuciones (fecha,concepto,usuario) VALUES (:fecha,:concepto,:usuario);");

			$consulta->bindValue(":fecha", $this->fecha);
			$consulta->bindValue(":concepto", $this->concepto);
			$consulta->bindValue(":usuario", $_SESSION["id_usuario"]);

			$consulta->execute();

			$this->id = $this->con->lastInsertId(); // id de la distribucion se quedara hasta el final

			$consulta = $this->con->prepare("INSERT INTO deudas (id_apartamento, id_distribucion, moroso) SELECT id_apartamento, :id_distribucion, 0 FROM apartamento WHERE 1;");

			$consulta->bindValue(":id_distribucion",$this->id,PDO::PARAM_INT);
			$consulta->execute();

			

			$consulta = $this->con->prepare("INSERT INTO detalles_deudas (id_deuda,concepto,monto,tipo_monto) SELECT deu.id_deuda, 'Estacionamiento' AS concepto, e.costo as monto, 1 as tipo_monto FROM estacionamiento AS e JOIN deudas AS deu ON deu.id_apartamento = e.id_apartamento AND deu.id_distribucion = :id_distribucion WHERE e.id_apartamento IS NOT NULL

											UNION

											SELECT deu.id_deuda, CONCAT('Apartamento tipo ',t.descripcion) AS concepto, t.alicuota, 1 AS tipo_monto from apartamento AS a JOIN deudas AS deu ON deu.id_apartamento = a.id_apartamento AND deu.id_distribucion = :id_distribucion JOIN tipo_apartamento AS t ON a.tipo_apartamento = t.id_tipo_apartamento

											UNION

											SELECT d.id_deuda, l.concepto,l.monto,l.tipo_monto FROM 
											lista_cargos_d AS l 
											CROSS JOIN 
											apartamento as a 
											LEFT JOIN deudas as d ON d.id_apartamento = a.id_apartamento AND d.id_distribucion = :id_distribucion
											WHERE l.tipo_cargo = 1 AND L.aplicar_next_mes = 1

											UNION 

											SELECT d.id_deuda, l.concepto, l.tipo_monto,l.tipo_monto  FROM apartamento as a JOIN apartamentos_lista_cargos as alc ON alc.id_apartamento = a.id_apartamento JOIN lista_cargos_d AS l ON l.id_lista_cargos = alc.id_lista_cargos JOIN deudas AS d on d.id_apartamento = a.id_apartamento AND d.id_distribucion = :id_distribucion WHERE l.tipo_cargo = 0 AND l.aplicar_next_mes = 1 ;");
			$consulta->bindValue(":id_distribucion",$this->id,PDO::PARAM_INT);
			$consulta->execute();

			$consulta = $this->con->prepare("UPDATE lista_cargos_d SET aplicar_next_mes = 0 WHERE mensual = 0 AND aplicar_next_mes = 1");
			$consulta->execute();




			$b_temp = new Bitacora($this->con);
  			$b_temp->b_registro("distribuyo la deuda \"$this->concepto\"");

			// TODO agregar para que se envien los correos
			$mailer = new enviarcorreo;
			$resp = "287";
			//$resp = $mailer->notificar_factura($this->id, $this->con);
			$this->con->commit();
			
			$r['resultado'] = 'distribuir_deudas';
			$r['mensaje'] =  $resp;
			// $r['mensaje'] =  $mailer->notificar_factura($this->id);;




		
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
		$this->con = null;
		return $r;

	}

	PUBLIC function consultar_distribucion_deuda($id){
		try {
			$this->id = $id;
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			if(!isset($this->id)){
				$consulta = $this->con->query("SELECT d.fecha,d.concepto,u.razon_social,d.id_distribucion from distribuciones AS d JOIN datos_usuarios as u ON u.id = d.usuario;")->fetchAll(PDO::FETCH_NUM);
			}
			else{
				$consulta = $this->con->prepare("SELECT d.fecha,d.concepto,u.razon_social,d.id_distribucion from distribuciones AS d JOIN datos_usuarios as u ON u.id = d.usuario WHERE id_distribucion = ?;");
				$consulta->execute([$this->id]);

				$consulta = $consulta->fetch(PDO::FETCH_ASSOC);
				if(!$consulta){
					throw new Validaciones("La distribución seleccionada no existe", 1);
				}
			}

			
			$r['resultado'] = 'consultar_distribucion_deuda';
			$r['mensaje'] =  $consulta;
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

	PRIVATE function modificar_distribucion(){
		try {
			$this->validar_conexion($this->con);

			$V = new Validaciones();
			$V->fecha($this->fecha);
			$V->alfanumerico($this->concepto);

			$this->con->beginTransaction();

			$consulta = $this->con->prepare("SELECT * FROM distribuciones WHERE id_distribucion = ?");
			$consulta->execute([$this->id]);
			if(!($consulta = $consulta->fetch(PDO::FETCH_ASSOC))){
				throw new Validaciones("La distribución seleccionada no existe", 1);
			}
			$anterior = $consulta;

			if($anterior["concepto"] != $this->concepto){
				$consulta = $this->con->prepare("SELECT * FROM distribuciones WHERE concepto = ? AND id_distribucion <> ?");
				$consulta->execute([$this->concepto,$this->id]);

				if($consulta->fetch(PDO::FETCH_ASSOC)){
					throw new Validaciones("El concepto ya existe", 1);
				}
			}

			$consulta = $this->con->prepare("UPDATE distribuciones SET concepto = ?, fecha = ? WHERE id_distribucion = ?;");
			$consulta->execute([$this->concepto, $this->fecha, $this->id]);

			$b = new Bitacora();
			if($anterior["concepto"] != $this->concepto){
				$b_temp = new Bitacora;
				$b->b_registro("Modifico el concepto de \"{$anterior['concepto']}\"");
			}
			if($anterior["fecha"] != $this->fecha){
				$b->b_registro("Modifico la fecha de \"{$anterior['fecha']}\"");
			}









			
			$r['resultado'] = 'modificar_deuda';
			$r['mensaje'] =  "Distribución de deuda modificada exitosamente";
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

	PRIVATE function eliminar_distribucion(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM distribuciones WHERE id_distribucion = ?");
			$consulta->execute([$this->id]);
			$anterior = $consulta->fetch(PDO::FETCH_ASSOC);

			if(!$anterior){
				throw new Validaciones("La distribución seleccionada no existe", 1);
			}

			$consulta = $this->con->prepare("SELECT 1 FROM distribuciones as dis
											LEFT JOIN deudas as d on d.id_distribucion = dis.id_distribucion
											LEFT JOIN deuda_pagos as dp on dp.id_deuda = d.id_deuda
											LEFT JOIN pagos as p on p.id_pago = dp.id_pago
											WHERE dis.id_distribucion = ? AND dp.id_pago IS NOT NULL AND(p.estado IN (0,2) OR p.estado IS NULL)");
			$consulta->execute([$this->id]);

			if($consulta->fetch(PDO::FETCH_ASSOC)){
				throw new Validaciones("La distribución ya tiene un pagos confirmado o por confirmar y no puede ser eliminado", 1);
			}

			$consulta = $this->con->prepare("DELETE FROM distribuciones WHERE id_distribucion = ?");
			$consulta->execute([$this->id]);

			$b = new Bitacora();
			$b->b_registro("Modifico el concepto de \"{$anterior['concepto']}\"");


			
			
			$r['resultado'] = 'eliminar_deuda';
			$r['mensaje'] =  "La distribución ha sido eliminada exitosamente";
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




	PUBLIC function chequearpermisos()
	{
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


	PUBLIC function get_apartamentos(){
		return $this->apartamentos;
	}
	PUBLIC function set_apartamentos($value){
		$value = json_decode($value);
		$this->apartamentos = $value;
	}

	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
	PUBLIC function get_concepto(){
		return $this->concepto;
	}
	PUBLIC function set_concepto($value){
		$this->concepto = $value;
	}
	PUBLIC function get_monto(){
		return $this->monto;
	}
	PUBLIC function set_monto($value){
		$value = preg_replace(["/\./","/,/"], ["","."], $value);
		$this->monto = $value;
	}
	PUBLIC function get_tipo_monto(){
		return $this->tipo_monto;
	}
	PUBLIC function set_tipo_monto($value){
		$value = ($value == 'divisa')?1:(($value == 'bolivar')?0:false);
		$this->tipo_monto = $value;
	}
	PUBLIC function get_tipo_cargo(){
		return $this->tipo_cargo;
	}
	PUBLIC function set_tipo_cargo($value){
		$value = ($value == 'global')?1:(($value == "dedicado")?0:false);
		$this->tipo_cargo = $value;
	}
	PUBLIC function get_mensual(){
		return $this->mensual;
	}
	PUBLIC function set_mensual($value){
		$value = ($value == 'mensual')?1:(($value == "unico")?0:false);
		$this->mensual = $value;
	}
	PUBLIC function get_aplicar_next_mes(){
		return $this->aplicar_next_mes;
	}
	PUBLIC function set_aplicar_next_mes($value){
		$value = ($value == 'aplicar')?1:(($value == "no aplicar")?0:false);
		$this->aplicar_next_mes = $value;
	}


	PUBLIC function get_fecha(){
		return $this->fecha;
	}
	PUBLIC function set_fecha($value){
		$this->fecha = $value;
	}


}
?>