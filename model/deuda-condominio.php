<?php

require_once('model/datos.php');
require_once("model/bitacora.php");


class Deudacondominio extends datos
{
	PRIVATE $con;
	//cargos
	PRIVATE $id, $concepto, $monto, $tipo_monto, $tipo_cargo, $mensual, $aplicar_next_mes, $apartamentos, $fecha;

	PUBLIC function __construct(){
		$this->con = $this->conecta();
		$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	PUBLIC function incluir_cargo_s(){
		return $this->incluir_cargo();
	}
	PUBLIC function modificar_cargo_s(){
		return $this->modificar_cargo();
	}
	PUBLIC function eliminar_cargo_s(){
		return $this->eliminar_cargo();
	}
	PRIVATE function incluir_cargo(){


		// ob_start();
		
		// echo "<pre>\n";
		// var_dump($this);
		// echo "</pre>";
		
		// $valor = ob_get_clean();
		
		// $r["resultado"] = "console";
		// $r["mensaje"] = $valor;
		
		// // echo json_encode($r);
		// return $r;






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
			$bitacora->b_incluir("lista cargos");

			
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
				
				$consulta = $this->con->prepare("SELECT * FROM lista_cargos_d WHERE id_lista_cargos = ?;");
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
			$consulta = $this->con->prepare("SELECT * FROM lista_cargos_d WHERE id_lista_cargos = ?;");
			$consulta->execute([$this->id]);

			if($consulta->fetch()){
				$consulta = $this->con->prepare("DELETE FROM lista_cargos_d WHERE id_lista_cargos = ?;");
				$consulta->execute([$this->id]);
			}
			else throw new Validaciones("El cargo a no existe o fue eliminado", 1);
			
			
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

	PUBLIC function distribuir_deudas_s(){
		return $this->distribuir_deudas();
	}

	PRIVATE function distribuir_deudas(){
		try {
			$this->validar_conexion($this->con);
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



			require_once("model/enviar-correo.php");
			$mailer = new enviarcorreo;

			
			$this->con->commit();
			
			$r['resultado'] = 'distribuir_deudas';
			$r['mensaje2'] =  $consulta->rowCount();
			$r['mensaje'] =  $mailer->notificar_factura($this->id);;




		
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

	PUBLIC function consultar_distribucion_deuda(){
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->query("SELECT d.fecha,d.concepto,u.razon_social from distribuciones AS d JOIN datos_usuarios as u ON u.id = d.usuario;")->fetchAll(PDO::FETCH_NUM);

			
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



//SELECT * from detalles_deudas as dd LEFT JOIN deudas as d on d.id_deuda = dd.id_deuda LEFT JOIN apartamento as a ON a.id_apartamento = d.id_apartamento WHERE a.num_letra_apartamento = 'A-01'





	#id_deuda tabla deudas relacionada con el apartamento
#concepto ,monto,tipo_monto tabla lista_cargos_d

// SELECT deu.id_apartamento, deu.id_deuda, 'Estacionamiento' AS concepto, e.costo as monto, 1 as tipo_monto FROM estacionamiento AS e JOIN deudas AS deu ON deu.id_apartamento = e.id_apartamento AND deu.id_distribucion = 4 WHERE e.id_apartamento IS NOT NULL

// UNION

// SELECT a.id_apartamento, deu.id_deuda, CONCAT('Apartamento tipo ',t.descripcion) AS concepto, t.alicuota, 1 AS tipo_monto from apartamento AS a JOIN deudas AS deu ON deu.id_apartamento = a.id_apartamento AND deu.id_distribucion = 4 JOIN tipo_apartamento AS t ON a.tipo_apartamento = t.id_tipo_apartamento

// UNION

// SELECT a.id_apartamento,d.id_deuda, l.concepto,l.monto,l.tipo_monto FROM 
// lista_cargos_d AS l 
// CROSS JOIN 
// apartamento as a 
// LEFT JOIN deudas as d ON d.id_apartamento = a.id_apartamento AND d.id_distribucion = 4
// WHERE l.tipo_cargo = 1 AND L.aplicar_next_mes = 1

// UNION 

// SELECT a.id_apartamento, d.id_deuda, l.concepto, l.tipo_monto,l.tipo_monto  FROM apartamento as a JOIN apartamentos_lista_cargos as alc ON alc.id_apartamento = a.id_apartamento JOIN lista_cargos_d AS l ON l.id_lista_cargos = alc.id_lista_cargos JOIN deudas AS d on d.id_apartamento = a.id_apartamento AND d.id_distribucion = 4 WHERE l.tipo_cargo = 0 AND l.aplicar_next_mes = 1























	//SELECT deu.id_apartamento, deu.id_deuda, "Estacionamiento" AS concepto, e.costo as monto, 1 as tipo_monto FROM estacionamiento AS e JOIN deudas AS deu ON deu.id_apartamento = e.id_apartamento AND deu.id_distribucion = 4 WHERE e.id_apartamento IS NOT NULL











	// SELECT 
	// CONCAT(lc.concepto,' (',COUNT(alc.id_lista_cargos),')') AS concepto,
	// IF(lc.tipo_monto = 1,lc.monto, (SELECT ROUND((lc.monto/divs.monto),2) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1)) AS divisa,
	// IF(lc.tipo_monto = 0,lc.monto, (SELECT ROUND((lc.monto*divs.monto),2) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1)) AS bolivar
	// FROM lista_cargos_d AS lc
	// JOIN apartamentos_lista_cargos AS alc ON alc.id_lista_cargos = lc.id_lista_cargos
	// WHERE lc.tipo_cargo = 0 GROUP BY lc.id_lista_cargos;




	// SELECT
	//     CONCAT(
	//         "Puesto de Estacionamiento ",
	//         e.num_estacionamiento
	//     ) AS concepto,
	//     e.costo AS divisa,
	//     (
	//     SELECT
	//         (divisa*divs.monto)
	//     FROM
	//         tipo_cambio_divisa AS divs
	//     WHERE
	//         1
	//     ORDER BY divs.fecha DESC
	//     LIMIT 1
	// ) AS bolivares
	// FROM
	//     estacionamiento AS e
	// WHERE
	//     1


	// SELECT
	//     CONCAT(
	//         "Puesto de Estacionamiento ",
	//         e.num_estacionamiento
	//     ) AS concepto,
	//     e.costo AS divisa,
	//      ROUND((SELECT (divisa*divs.monto) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1),2) AS bolivares
	// FROM
	//     estacionamiento AS e WHERE e.id_apartamento IS NOT NULL
	// UNION
	// SELECT
	// 	CONCAT("Apartamento ",ta.descripcion," (",ta.cantidadHijos,")") AS concepto,
	//     ta.alicuota AS divisa,
	//     ROUND((SELECT (divisa*divs.monto) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1),2) as bolivares

	// FROM tipo_apartamento as ta
	// UNION 
	// SELECT
	// lcd.concepto,
	// IF(lcd.tipo_monto = 1,lcd.monto, ROUND((SELECT (lcd.monto/divs.monto) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1),2)) AS divisa,
	// IF(lcd.tipo_monto = 0,lcd.monto, ROUND((SELECT (lcd.monto*divs.monto) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1),2)) AS bolivares
	// FROM lista_cargos_d as lcd WHERE lcd.tipo_cargo = 1;









// SELECT
//     CONCAT(
//         "Puesto de Estacionamiento ",
//         e.num_estacionamiento
//     ) AS concepto,
//     e.costo AS divisa,
//     (
//     SELECT
//         (divisa*divs.monto)
//     FROM
//         tipo_cambio_divisa AS divs
//     WHERE
//         1
//     ORDER BY divs.fecha DESC
//     LIMIT 1
// ) AS bolivares
// FROM
//     estacionamiento AS e
// UNION
// SELECT
// 	CONCAT("Apartamento ",ta.descripcion," (",ta.cantidadHijos,")") AS concepto,
//     ta.alicuota AS divisa,
//     (SELECT (divisa*divs.monto) FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1) as bolivares

// FROM tipo_apartamento as ta
// UNION 
// SELECT * FROM 











	PUBLIC function incluir($monto, $concepto, $fecha)
	{
		$usuario = $_SESSION['id_usuario'];
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$guarda = $co->query("insert into deuda_condominio(fecha_generada,monto,concepto,usuario) 
		   values ('$fecha','$monto','$concepto',$usuario)");
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  "Registro Incluido";
			$bitacora = new Bitacora();
			$bitacora->b_incluir();

			
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function listadodeudas()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select DISTINCT deuda_condominio.id, fecha_generada, monto, concepto, razon_social, dp.id_deuda_condominio
			from 
			deuda_condominio
            LEFT JOIN deuda_pendiente dp ON dp.id_deuda_condominio = deuda_condominio.id
			inner join datos_usuarios where deuda_condominio.usuario=datos_usuarios.id ORDER BY deuda_condominio.id DESC;");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					if (!$r[5]) {
						$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocadeuda(this);'>";
					} else {
						$respuesta = $respuesta . "<tr data-toggle='tooltip' data-placement='top' title='' data-original-title='Las deudas distribuidas no pueden ser modificadas ni eliminadas'>";
					}
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[0];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$fecha_original = $r[1];
					$nuevo_formato = "d-m-Y";
					$fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
					$respuesta = $respuesta . $fecha_cambiada;
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[2];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[3];
					$respuesta = $respuesta . "</td>";
					if (!$r[5]) {
						$respuesta = $respuesta . "<td class='align-middle'>";
						$respuesta = $respuesta . $r[4];
						$respuesta = $respuesta . "</td>";
						$respuesta = $respuesta . "<td align='center' class='align-middle'>";
						$respuesta = $respuesta . "0";
						$respuesta = $respuesta . "</td>";
					} else {
						$respuesta = $respuesta . "<td class='align-middle'>";
						$respuesta = $respuesta . $r[4];
						$respuesta = $respuesta . "</td>";
						$respuesta = $respuesta . "<td align='center' class='align-middle'>1</td>";
					}
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listado_deudas';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function modificar($id_deuda, $monto, $concepto, $fecha)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id_deuda, 1)) {
			try {
				$co->query("Update deuda_condominio set 
						fecha_generada = '$fecha',
						monto = '$monto',
						concepto = '$concepto'
						where
						id = '$id_deuda'
						");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  "Registro modificado correctamente";
				$bitacora = new Bitacora();
				$bitacora->b_modificar();

				
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =   $e->getMessage();
				return $r;
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "La deuda especificada no existe";
		}
		return $r;
	}
	PUBLIC function calcular_deuda($id_deuda_condominio)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select t.id_tipo_apartamento,t.alicuota, t.descripcion
			from 
			tipo_apartamento t
			");
			$consulta = $co->query("Select d.monto, d.fecha_generada
			from 
			deuda_condominio d
			where id = '$id_deuda_condominio'
			");
			$consulta->execute();
			$deuda = $consulta->fetch(PDO::FETCH_NUM);
			$fecha = $deuda[1];
			$nueva_fecha = date('d-m-Y', strtotime($fecha . ' + 1 month'));
			$respuesta = '';
			$total = 0;
			if ($resultado) {
				$respuesta =  "¿Está seguro que desea distribuir esta deuda?<br>";
				$respuesta =  $respuesta . "Distribuida de la siguiente forma:<br>";
				$respuesta =  $respuesta . "Fecha tope para el pago: <b>" . $nueva_fecha . "</b><br>";
				foreach ($resultado as $r) {
					$total = $deuda[0] * ($r[1] / 100);
					$apartamentos = $co->query("Select id_apartamento
					from 
					apartamento where tipo_apartamento = '$r[0]'
					");
					if ($apartamentos) {
						$num_rows = $apartamentos->rowCount();
						$monto_por_apto = round($total / $num_rows, 2);
						$respuesta = $respuesta . "<b>" . $monto_por_apto . "$</b> por Apartamento (" . $num_rows . ") tipo " . $r[2] . "<br>";
					}
				}
				$respuesta =  $respuesta . "<span class='text-danger font-weight-bold'>LA DEUDA NO PODRÁ SER MODIFICADA NI ELIMINADA LUEGO DE SER DISTRIBUIDA</span>";
			}
			$r['resultado'] = 'calculo_deuda';
			$r['id'] = $id_deuda_condominio;
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function distribuir_deuda($id_deuda_condominio)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select t.id_tipo_apartamento,t.alicuota
			from 
			tipo_apartamento t
			");
			$consulta = $co->query("Select d.monto, d.fecha_generada
			from 
			deuda_condominio d
			where id = '$id_deuda_condominio'
			");
			$consulta->execute();
			$deuda = $consulta->fetch(PDO::FETCH_NUM);
			$fecha = $deuda[1];
			$nueva_fecha = date('Y-m-d', strtotime($fecha . ' + 1 month'));
			if ($resultado && !$this->existe($id_deuda_condominio, 2)) {
				foreach ($resultado as $r) {
					$total = $deuda[0] * ($r[1] / 100);
					$apartamentos = $co->query("Select id_apartamento
					from 
					apartamento where tipo_apartamento = '$r[0]'
					");
					if ($apartamentos) {
						$num_rows = $apartamentos->rowCount();
						$monto_por_apto = round($total / $num_rows, 2);
						foreach ($apartamentos as $a) {
							$guarda = $co->query("insert into deuda_pendiente(id_apartamento,id_deuda_condominio,fecha_a_cancelar,total) 
		   					values ('$a[0]','$id_deuda_condominio','$nueva_fecha','$monto_por_apto')");
						}
					}
				}
				$r['resultado'] = 'distribuir_deuda';
				$r['mensaje'] =  'La deuda se ha distruibuido exitosamente';
			} else if ($this->existe($id_deuda_condominio, 2)) {
				$r['resultado'] = 'distribuir_deuda';
				$r['mensaje'] =  'La deuda ya fue distribuida';
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function eliminar($id_deuda)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id_deuda, 1)) {
			try {
				$co->query("delete from deuda_condominio 
						where
						id = '$id_deuda'
						");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  "Registro Eliminado";
				$bitacora = new Bitacora();
				$bitacora->b_eliminar();
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode()=='23000') {
					$r['mensaje'] =  "Esta deuda no puede ser eliminada.";
				}else{
					$r['mensaje'] =  $e->getMessage();					
				}
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Debe seleccionar una deuda para eliminarla";
		}
		return $r;
	}
	PRIVATE function existe($id_deuda, $caso)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			switch ($caso) {
				case 1:
					$resultado = $co->query("Select * from deuda_condominio where id='$id_deuda'");
					$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
					if ($fila) {
						return true;
					} else {
						return false;
					}
					break;
				case 2:
					$resultado = $co->query("SELECT dp.id_deuda_condominio FROM deuda_condominio dc INNER JOIN deuda_pendiente dp WHERE '$id_deuda' = dp.id_deuda_condominio;");
					$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
					if ($fila) {
						return true;
					} else {
						return false;
					}
					break;
				default:
					break;
			}
		} catch (Exception $e) {
			return false;
		}
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