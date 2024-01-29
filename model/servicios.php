<?php 



require_once("model/datos.php");
require_once("model/validaciones.php");
require_once("model/bitacora.php");
// $bitacora = new Bitacora();
/**
 * 
 // CONTROL referencia duplicada
 */
class servicio extends datos
{
	PRIVATE $con;
	PRIVATE $id_pago_serv;
	PRIVATE $servicio;
	PRIVATE $descripcion;
	PRIVATE $obj_pagos;

	function __construct()
	{
		$this->con = $this->conecta();
		if($this->con instanceof PDO) $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}


	PUBLIC function chequearpermisos()
	{
		if($this->con instanceof PDO){
			$id_rol = $_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol'];
			$modulo = $_GET['p'];
			$co = $this->con;
			$guarda = $co->prepare("SELECT * FROM `roles_modulos` INNER JOIN `modulos` ON roles_modulos.id_modulo = modulos.id INNER JOIN `roles` ON roles_modulos.id_rol = roles.id WHERE modulos.nombre = ? AND roles_modulos.id_rol = ?");
			$guarda->execute([$modulo, $id_rol]);
			$fila = array();
			$fila = $guarda->fetch(PDO::FETCH_NUM);
			return $fila;
		}
	}

	PUBLIC function incluir_s(){
		return $this->incluir();
	}
	PUBLIC function modificar_s(){
		return $this->modificar();
	}
	PUBLIC function eliminar_s(){
		return $this->eliminar();
	}

	PUBLIC function incluir_servicio_s(){
		return $this->incluir_servicio();
	}
	PUBLIC function modificar_servicio_s(){
		return $this->modificar_servicio();
	}
	PUBLIC function eliminar_servicio_s(){
		return $this->eliminar_servicio();
	}




	PRIVATE function incluir()
	{
		$r = array();
		try {

			$this->validar_conexion($this->con);
			$V = new Validaciones();


			if($V->validarPagos($this->obj_pagos)){

				$this->con->beginTransaction();
				$consulta = $this->con->prepare("SELECT * FROM `lista_servicios` WHERE `id_servicios` = ?");
				$consulta->execute([$this->servicio]);
				if($consulta->fetch()){

						$V->alfanumerico($this->descripcion,"0,100","La descripción del pago tiene caracteres no permitidos");

					// PRIMERO SE GUARDA EL  PAGO
						$consulta = $this->con->prepare("INSERT INTO pagos (concepto_pago,estado,usuario_id) VALUES (?,?,?)");
						$usuario_id = $_SESSION["id_usuario"];
						$consulta->execute([$this->descripcion, 2, $usuario_id]);

						$this->obj_pagos->id_pago = $this->con->lastInsertId();

						$consulta = $this->con->prepare("INSERT INTO servicios_pagos (id_servicio, id_pago) VALUES (?,?)");
						$consulta->execute([$this->servicio, $this->obj_pagos->id_pago]);


						//$consulta = $this->con->query("SELECT * from pagos")->fetchall(PDO::FETCH_ASSOC);

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




						$r['resultado'] = 'incluir';
						$r['mensaje'] =  'Registro Incluido';

						$bitacora = new Bitacora($this->con);
						$bitacora->b_registro("Registró pago de servicio con el Nº \"{$this->obj_pagos->id_pago}\"");
				}
				else{
					$r['resultado'] = 'error';
					$r['mensaje'] =  'El servicio seleccionado no esta disponible';
				}
			}
			//-------------------------------------------
			//-------------------------------------------
			//-------------------------------------------
			//-------------------------------------------
			//$this->con->rollBack();
			//-------------------------------------------
			//-------------------------------------------
			//-------------------------------------------
			//-------------------------------------------
			//-------------------------------------------
			//-------------------------------------------
			$this->con->commit();
		}
		catch (Validaciones $e){
			if($this->con instanceof PDO){

				if ($this->con->inTransaction())
			    {
			       $this->con->rollBack();
			    }
			}
			$r['resultado'] = 'is-invalid';
			//$r['resultado'] = 'console';
			$r['mensaje'] = $e->getMessage();
		}
		 catch (Exception $e) {
			if($this->con instanceof PDO){

				if ($this->con->inTransaction())
			    {
			       $this->con->rollBack();
			    }
			}
			$r['resultado'] = 'error';
			$r['resultado'] = 'mensajeLog';
			$r['mensaje'] =  $e->getMessage();
			$r['mensajeLog'] =  $e->__toString();
		}


		return $r;
		
	}

	PRIVATE function modificar(){
		$r=array();
		try {
			$this->validar_conexion($this->con);
			$V = new Validaciones();

			if($V->validarPagos($this->obj_pagos)){//si no valida los pagos estos lanzaran el error al catch
				$this->con->beginTransaction();
				$consulta = $this->con->prepare("SELECT * FROM `servicios_pagos` WHERE `id_pago` = ?");
				$consulta->execute([$this->id_pago_serv]);
				if($consulta->fetch()){//valido que el id del pago de servicio exista


				
					$consulta = $this->con->prepare("UPDATE `pagos` SET `concepto_pago`= :descripcion WHERE `id_pago` = :id_pagos_servicios");
					$consulta->bindValue(":descripcion",$this->descripcion);
					$consulta->bindValue(":id_pagos_servicios",$this->id_pago_serv);

					$consulta->execute();

					$consulta = $this->con->prepare("UPDATE `detalles_pagos` SET 
								`tipo_pago` = :tipo_pago,
								`monto` = :monto,
								`fecha` = :fecha,
								`tipo_monto` = :tipo_monto,
								`numero` = :numero,
								`emisor` = :emisor,
								`cedula_rif` = :cedula_rif,
								`telefono` = :telefono

								WHERE `id_detalles_pagos` = :id_detalles_pagos");

					$consulta->bindValue(':id_detalles_pagos', $this->obj_pagos->id_detalles_pagos);
					$consulta->bindValue(':tipo_pago', $this->obj_pagos->tipo_pago );
					$consulta->bindValue(':monto', preg_replace(["/\./","/,/"], ["","."], $this->obj_pagos->monto) );
					$consulta->bindValue(':fecha', $this->obj_pagos->fecha." ".$this->obj_pagos->hora.":00" );
					$consulta->bindValue(':tipo_monto', 0 );

					$consulta->bindValue(':numero', null );
					$consulta->bindValue(':emisor', null );
					$consulta->bindValue(':cedula_rif', null );
					$consulta->bindValue(':telefono', null );

					if($this->obj_pagos->tipo_pago == 2 || $this->obj_pagos->tipo_pago == 3){//transferencia y pago movil
						$consulta->bindValue(':cedula_rif', $this->obj_pagos->cedula );
						$consulta->bindValue(':numero', $this->obj_pagos->referencia );
						$consulta->bindValue(':emisor', $this->obj_pagos->banco );
					}
					if($this->obj_pagos->tipo_pago == 3){ // pago movil
						$consulta->bindValue(':telefono', $this->obj_pagos->telefono );
					}

					$consulta->execute();

					if($this->obj_pagos->tipo_pago == 4 and $this->obj_pagos->billetes_to_Modify == true){
						$consulta = $this->con->prepare("DELETE FROM divisa WHERE id_detalles_pagos = ?");
						$consulta->execute([$this->obj_pagos->id_detalles_pagos]);

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



					$r['resultado'] = 'modificar';
					$r['mensaje'] =  'Registro Modificado';
					$bitacora = new Bitacora($this->con);
					$bitacora->b_registro("Modificó pago de servicio con el Nº \"{$this->id_pago_serv}\"");

				}else{
					$r['resultado'] = 'error';
					$r['mensaje'] =  'El Registro no existe y no se puede modificar';
				}
			
			}
			
			$this->con->commit();

		} 
		catch (Validaciones $e){
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['mensaje'] =  $e->getMessage();
		}
		catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}

		return $r;
	}


	PRIVATE function eliminar(){
		$r = array();
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM pagos WHERE id_pago = ?");
			$consulta->execute([$this->id_pago_serv]);
			if ($consulta->fetch()) {
				$consulta = $this->con->prepare("DELETE FROM pagos WHERE id_pago = ?");
				$consulta->execute([$this->id_pago_serv]);
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  'Registro Eliminado';
				$bitacora = new Bitacora($this->con);
				$bitacora->b_registro("Eliminó pago de servicio con el Nº \"{$this->id_pago_serv}\"");
				// $bitacora->b_eliminar("pago de servicio");
			} else {
				$r['resultado'] = 'error';
				$r['mensaje'] =  "El Registro no existe";
			}
			$this->con->commit();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}


	PRIVATE function incluir_servicio(){
		$r = array();
		try {
			$V = new Validaciones();

			$V->alfanumerico($this->descripcion, "1,50", "El nombre de servicio tiene caracteres no permitidos");

			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM lista_servicios WHERE nombre = ?");
			$consulta->execute([$this->descripcion]);
			if(!$consulta->fetch()){
				$consulta = $this->con->prepare("INSERT INTO `lista_servicios` (nombre) VALUES (?)");
				$consulta->execute([$this->descripcion]);
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  'Registro Incluido';
				$bitacora = new Bitacora($this->con);
				$bitacora->b_registro("Registró un servicio \"$this->descripcion\"");
			}
			else{
				$r['resultado'] = 'error';
				$r['mensaje'] =  "El servicio ya esta registrado";
			}
			$this->con->commit();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PRIVATE function modificar_servicio(){
		$r = array();
		try {
			$V = new Validaciones;
			$V->alfanumerico($this->descripcion, "1,50", "El nombre de servicio tiene caracteres no permitidos");
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM lista_servicios WHERE nombre = ?");
			$consulta->execute([$this->descripcion]);
			if(!$consulta->fetch()){
				$consulta = $this->con->prepare("UPDATE `lista_servicios` SET `nombre` = ? WHERE `id_servicios` = ?");
				$consulta->execute([$this->descripcion,$this->id_pago_serv]);
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  'Registro Modificado';
				$bitacora = new Bitacora($this->con);
				$bitacora->b_registro("Modificó un servicio \"$this->descripcion\"");
			}
			else{
				$r['resultado'] = 'error';
				$r['mensaje'] =  "El servicio ya esta registrado";
			}
			$this->con->commit();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PRIVATE function eliminar_servicio(){
		$r = array();
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM lista_servicios WHERE id_servicios = ?");
			$consulta->execute([$this->id_pago_serv]);

			if($anterior = $consulta->fetch()){
				$consulta = $this->con->prepare("DELETE FROM lista_servicios WHERE id_servicios = ?");
				$consulta->execute([$this->id_pago_serv]);
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  'Registro Eliminado';
				$bitacora = new Bitacora($this->con);

				$bitacora->b_registro("Eliminó un servicio \"{$anterior['nombre']}\"");
			}
			else{
				$r['resultado'] = 'error';
				$r['mensaje'] =  "El Registro no existe";
			}
			$this->con->commit();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}



	








	PUBLIC function listadoPagosservicios() {
		$co = $this->con;
		$r = array();
		try {
			$this->validar_conexion($co);

			$co->beginTransaction();

			//$resultado = $co->query("SELECT s.`id_servicios`, s.`id_servicio`, l.`nombre` AS servcio, s.`descripcion`, s.`monto`, s.`fecha`, s.`referencia` FROM `servicios` AS s JOIN lista_servicios AS l ON l.`id_servicios` = s.`id_servicio`")->fetchall(PDO::FETCH_ASSOC);
			$resultado = $co->query("SELECT p.id_pago AS id, ls.id_servicios, ls.nombre AS servicio, p.concepto_pago AS descripcion, p.total_pago AS monto, (SELECT fecha FROM detalles_pagos AS dp WHERE dp.id_pago = p.id_pago ORDER BY fecha LIMIT 1 ) AS fecha, tp.tipo_pago, u.razon_social AS user FROM `servicios_pagos` AS sp JOIN pagos AS p ON p.id_pago = sp.id_pago JOIN lista_servicios AS ls ON ls.id_servicios = sp.id_servicio LEFT JOIN datos_usuarios AS u ON p.usuario_id = u.id JOIN tipo_pago AS tp ON tp.id_tipo_pago =(SELECT tipo_pago FROM detalles_pagos AS dp WHERE dp.id_pago = p.id_pago ORDER BY fecha LIMIT 1 ) WHERE 1 ORDER BY fecha LIMIT 200;")->fetchall(PDO::FETCH_ASSOC);


			// ob_start();
			// echo "<pre>\n";
			// var_dump($resultado);
			// echo "</pre>";

			// $valor = ob_get_clean();

			// $r['resultado'] = "console";
			// $r['mensaje'] = $valor;
			// return $r;

			$dom = new DOMDocument();
			if ($resultado) {
				foreach ($resultado as $elem) {

					$tr = $dom->createElement("tr");
					$tr->setAttribute("data-id",$elem["id"]);
					$tr->setAttribute("data-service",$elem["id_servicios"]);

					$tr->appendChild($dom->createElement( "td", $elem["id"] ));

					$td = $dom->createElement("td",$elem["id"]);
					$td->setAttribute("class","d-none");
					$tr->appendChild($td);

					$tr->appendChild($dom->createElement( "td", $elem["servicio"] ));
					$tr->appendChild($dom->createElement( "td", $elem["descripcion"] ));
					$tr->appendChild($dom->createElement( "td", number_format($elem["monto"],2,",",".") ));

					$td = $dom->createElement("td",$elem["fecha"]);
					$td->setAttribute("class","text-nowrap");
					$tr->appendChild($td);

					$tr->appendChild($dom->createElement( "td", $elem["tipo_pago"] ));
					$tr->appendChild($dom->createElement( "td", $elem["user"] ));
					$dom->appendChild($tr);
				}
				
			}
			$r['resultado'] = 'listadoPagosservicios';

			$r['mensaje'] =  $dom->saveHTML();

			$co->commit();

		} catch (Exception $e) {
			if($co instanceof PDO){
				if($co->inTransaction()){
					$co->rollBack();
				}
			}
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function listadoServicios(){
		try {
			$this->validar_conexion($this->con);
			$respuesta = $this->con->query("SELECT * FROM `lista_servicios`")->fetchall(PDO::FETCH_ASSOC);

			

			$dom = new DOMDocument();
			$cont = 1;
			foreach ($respuesta as $elem) {
				$tr = $dom->createElement("tr");
				$tr->appendChild($dom->createElement("td",str_pad($cont, 2, "0", STR_PAD_LEFT)));
				$td = $dom->createElement("td",$elem["id_servicios"]);
				$td->setAttribute("class","d-none");
				$tr->appendChild($td);
				$tr->appendChild($dom->createElement("td",$elem["nombre"]));
				$dom->appendChild($tr);
				$cont++;
			}
			$r['resultado'] = 'listadoServicios';
			$r['mensaje'] =  $dom->saveHTML();
			// $r['mensaje'] =  "hola";
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function lista_select_servicios(){
		try {
			$this->validar_conexion($this->con);
			$respuesta = $this->con->query("SELECT * FROM `lista_servicios`")->fetchall();

			$dom = new DOMDocument();
			$opt = $dom->createElement("option","-");
			$opt->setAttribute("value",'');
			$dom->appendChild($opt);
			foreach ($respuesta as $elem) {
				$opt = ($dom->createElement("option", $elem["nombre"]));
				$opt->setAttribute("value",$elem["id_servicios"]);
				$dom->appendChild($opt);
			}
			$r['resultado'] = 'lista_select_servicios';
			$r['mensaje'] =  $dom->saveHTML();
			// $r['mensaje'] =  "hola";
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function seleccionar_pago($id)
	{
		$tipos_de_pagos_array = [//ignorar es para referencias
			"1"=> 'efectivo',
			"2"=> 'transferencia',
			"3"=> 'pago_movil',
			"4"=> 'divisa',
		];
			try {
				$this->validar_conexion($this->con);
				$this->con->beginTransaction();
				$consulta = $this->con->prepare("SELECT `id_detalles_pagos`, `id_pago`, `tipo_pago`, `monto`, DATE_FORMAT(fecha, '%Y-%m-%d') as fecha, DATE_FORMAT(fecha, '%H:%i') AS hora, `tipo_monto`, `numero` as referencia, `emisor` as banco, `cedula_rif` as cedula, `telefono` FROM detalles_pagos AS dp WHERE id_pago = ? LIMIT 1");

				$consulta->execute([$id]);

				$obj_pagos = $consulta->fetch(PDO::FETCH_ASSOC);


				$obj_pagos['monto'] = number_format($obj_pagos["monto"],2,",",".");

				if($obj_pagos['tipo_pago'] == 4) {// es divisa hay que buscar los billetes
					$consulta = $this->con->prepare("SELECT serial, denominacion FROM divisa WHERE id_detalles_pagos = ?;");
					$consulta->execute([$obj_pagos["id_detalles_pagos"]]);
					$obj_pagos['billetes'] = $consulta->fetchall(PDO::FETCH_ASSOC);
					$obj_pagos["cantidad"] = count($obj_pagos["billetes"]);
				}

				$r['resultado'] = 'seleccionar_pago';
				// $r['resultado'] = 'console';

				// ob_start();
				// echo "<pre>\n";
				// var_dump($obj_pagos);
				// echo "</pre>";

				// $valor = ob_get_clean();
				// $r['mensaje'] = $valor;



				$r['obj_pagos'] =  $obj_pagos;

				//$this->con->rollBack();
				$this->con->commit();
			} catch (Exception $e) {
				if($this->con instanceof PDO){
					if($this->con->inTransaction()){
						$this->con->rollBack();
					}
				}
		
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
			return $r;

	}


	PUBLIC function get_con(){
		return $this->con;
	}
	PUBLIC function set_con($value){
		$this->con = $value;
	}
	PUBLIC function get_id_pago_serv(){
		return $this->id_pago_serv;
	}
	PUBLIC function set_id_pago_serv($value){
		$this->id_pago_serv = $value;
	}
	PUBLIC function get_servicio(){
		return $this->servicio;
	}
	PUBLIC function set_servicio($value){
		$this->servicio = $value;
	}
	PUBLIC function get_descripcion(){
		return $this->descripcion;
	}
	PUBLIC function set_descripcion($value){
		$this->descripcion = $value;
	}
	PUBLIC function get_obj_pagos(){
		return $this->obj_pagos;
	}
	PUBLIC function set_obj_pagos($value){
		$this->obj_pagos = $value;
	}

}







?>