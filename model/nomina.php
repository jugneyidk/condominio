 <?php




 require_once('model/datos.php');
 require_once("model/bitacora.php");
 require_once("model/validaciones.php");

class nomina extends datos
{

	PRIVATE $id;
	PRIVATE $rif_cedula;
	PRIVATE $tipo_identificacion;
	PRIVATE $nombres;
	PRIVATE $apellidos;
	PRIVATE $fechac;
	PRIVATE $salario;
	PRIVATE $domicilio_fiscal;
	PRIVATE $telefono;
	PRIVATE $correo;
	PRIVATE $cargo;
	PRIVATE $fechan;
	PRIVATE $estado_civil;
	PRIVATE $descripcion;
	PRIVATE $obj_pagos;

	function __construct()
	{
		$this->con = $this->conecta();
		if($this->con instanceof PDO) $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}




	PUBLIC function chequearpermisos(){
		$id_rol = $_SESSION['rol'];
		$modulo = $_GET['p'];
		$co = $this->conecta(); 
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$guarda = $co->query("SELECT * FROM `roles_modulos` inner join `modulos` on roles_modulos.id_modulo = modulos.id inner join `roles` on roles_modulos.id_rol = roles.id WHERE modulos.nombre = '$modulo' and roles_modulos.id_rol = '$id_rol'");
		$guarda->execute();
		$fila = array();
		$fila = $guarda->fetch(PDO::FETCH_NUM);
		return $fila;		
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
	PRIVATE function incluir()
	{
		try {
			$this->validar_conexion($this->con);
			$V = new Validaciones();
			$V->alfanumerico($this->descripcion, "1,100", "La descripción tiene caracteres inválidos");

			if($V->validarPagos($this->obj_pagos)){
				$this->con->beginTransaction();
				$consulta = $this->con->prepare("SELECT empleado_id FROM empleado WHERE rif_cedula = ? AND tipo_identificacion = ?");
				$consulta->execute([$this->rif_cedula, $this->tipo_identificacion]);
				if($respuesta = $consulta->fetch(PDO::FETCH_ASSOC)){
					$this->id = $respuesta['empleado_id'];

					$consulta = $this->con->prepare("INSERT INTO pagos (concepto_pago,estado,usuario_id) VALUES (?,?,?)");
					$usuario_id = $_SESSION["id_usuario"];
					$consulta->execute([$this->descripcion, 2, $usuario_id]);
					$bitacora = new Bitacora();

					$this->obj_pagos->id_pago = $this->con->lastInsertId();

					$consulta = $this->con->prepare("INSERT INTO nomina_pago (id_empleado, id_pago) VALUES (?, ?);");
					$consulta->execute([$this->id , $this->obj_pagos->id_pago ]);

					$bitacora->b_incluir("nomina_pago");


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
						$bitacora->b_incluir("detalles_pagos");


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
							$bitacora->b_incluir("divisa");

						}









					// $respuesta = $this->con->query("SELECT * from nomina_pago WHERE 1")->fetchall(PDO::FETCH_ASSOC);







					$r["resultado"] = "incluir";
					$r["mensaje"] = "";


					// ob_start();
					// echo "<pre>\n";
					// var_dump($respuesta);
					// echo "</pre>";

					// $valor = ob_get_clean();



					// $r["resultado"] = "console";
					// $r["mensaje"] = $valor;

				}
				else{
					$r["resultado"] = "is-invalid";
					$r["mensaje"] = "El empleado seleccionado no existe o fue eliminado";
				}


				
			
			}
			$this->con->commit();
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
	PRIVATE function modificar()
	{
		try {
			$this->validar_conexion($this->con);
			$V = new Validaciones();
			$V->alfanumerico($this->descripcion, "1,100", "La descripción tiene caracteres inválidos");
			if($V->validarPagos($this->obj_pagos)){
				$this->con->beginTransaction();

				$consulta = $this->con->prepare("SELECT empleado_id FROM empleado WHERE rif_cedula = ? AND tipo_identificacion = ?");
				$consulta->execute([$this->rif_cedula, $this->tipo_identificacion]);
				if($respuesta = $consulta->fetch(PDO::FETCH_ASSOC)){
					$this->id = $respuesta["empleado_id"];

					$consulta = $this->con->prepare("SELECT * FROM nomina_pago WHERE id_empleado = ? AND id_pago = ?;");
					$consulta->execute([$this->id, $this->obj_pagos->id_pago]);
					if($consulta->fetch(PDO::FETCH_ASSOC)){

						$consulta = $this->con->prepare("UPDATE `pagos` SET `concepto_pago`= ? WHERE `id_pago` = ?");
						$consulta->bindValue(1,$this->descripcion);
						$consulta->bindValue(2,$this->obj_pagos->id_pago);
						$consulta->execute();

						$bitacora = new Bitacora();
						$bitacora->b_modificar("pagos");


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

						$bitacora->b_modificar("detalles_pagos");

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

							$bitacora->b_modificar("divisa");
						}

						$r['resultado'] = 'modificar';
						$r['mensaje'] =  '';
						$bitacora = new Bitacora();
						$bitacora->b_modificar("pago de nomina");



					}
					else{
						throw new Validaciones("El pago seleccionado no existe o fue eliminado", 1);
					}


					





				}
				else{
					throw new Validaciones("El empleado seleccionado no existe o fue eliminado", 1);
					  
				}

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
		}
		 catch (Exception $e) {
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
	PRIVATE function eliminar()
	{
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM detalles_pagos WHERE id_pago = ? ORDER BY fecha LIMIT 1;");
			$consulta->execute([$this->id]);

			if($consulta->fetch(PDO::FETCH_ASSOC)){
				$consulta = $this->con->prepare("DELETE FROM pagos WHERE id_pago = ?");
				$consulta->execute([$this->id]);

				$bitacora = new Bitacora();
				$bitacora->b_eliminar("pago de nomina");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  "probando";
			}
			else{
				throw new Validaciones("El pago seleccionado no existe o fue eliminado", 1);
				
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

	PUBLIC function incluir_empleado_s(){
		return $this->incluir_empleado();
	}
	PUBLIC function modificar_empleado_s(){
		return $this->modificar_empleado();
	}
	PUBLIC function eliminar_empleado_s(){
		return $this->eliminar_empleado();
	}

	PRIVATE function incluir_empleado(){
		try {
			$this->validar_conexion($this->con);
			$estado_civil_array = [
				"Soltero",
				"Casado",
				"Divorciado",
				"Viudo",
				"Otros"
			];
			$V = new Validaciones();
			$tipo_identificacion_temp = TIPO_INDENT_ARRAY[$this->tipo_identificacion];
			$V->validarCedula($tipo_identificacion_temp.$this->rif_cedula);
			$V->validarNombre($this->nombres, "1,60", "El nombre no es valido");
			$V->validarNombre($this->apellidos, "1,60", "El apellido no es valido");
			$V->fecha($this->fechac, "fecha de contratación");
			$V->monto($this->salario, "El salario es invalido");
			$V->alfanumerico($this->domicilio_fiscal, "1,100", "El domicilio fiscal tiene caracteres inválidos");
			$V->validarTelefono($this->telefono);
			$V->validarEmail($this->correo);
			$V->alfanumerico($this->cargo, "1,60", "El cargo del empleado tiene caracteres inválidos");
			$V->fecha($this->fechan, "fecha de fecha nacimiento");
			if(!in_array($this->estado_civil, $estado_civil_array)){
				throw new Validaciones("Debe seleccionar un estado civil valido", 1);
			}






			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT nombre FROM empleado WHERE rif_cedula = ? and tipo_identificacion = ?");
			$consulta->bindValue(1,$this->rif_cedula);
			$consulta->bindValue(2,$this->tipo_identificacion);
			$consulta->execute();
			if(!$consulta->fetch()){
				$consulta = $this->con->prepare("INSERT INTO empleado (rif_cedula, tipo_identificacion, nombre, apellido, fecha_contratacion, salario, domicilio, telefono, correo, cargo, fecha_nacimiento, estado_civil) VALUES (:rif_cedula, :tipo_identificacion, :nombre, :apellido, :fecha_contratacion, :salario, :domicilio, :telefono, :correo, :cargo, :fecha_nacimiento, :estado_civil)");
				$consulta->bindValue(":rif_cedula",$this->rif_cedula);
				$consulta->bindValue(":tipo_identificacion",$this->tipo_identificacion);
				$consulta->bindValue(":nombre",$this->nombres);
				$consulta->bindValue(":apellido",$this->apellidos);
				$consulta->bindValue(":fecha_contratacion",$this->fechac);
				$consulta->bindValue(":salario",$this->salario);
				$consulta->bindValue(":domicilio",$this->domicilio_fiscal);
				$consulta->bindValue(":telefono",$this->telefono);
				$consulta->bindValue(":correo",$this->correo);
				$consulta->bindValue(":cargo",$this->cargo);
				$consulta->bindValue(":fecha_nacimiento",$this->fechan);
				$consulta->bindValue(":estado_civil",$this->estado_civil);

				$consulta->execute();
				$bitacora = new Bitacora();
				$bitacora->b_incluir("pagos");
				$r["resultado"] = "incluir_2";
				$r["mensaje"] = "El Empleado fue registrado exitosamente";

				//$r["mensaje"] = $this->con->query("SELECT * FROM empleado WHERE empleado_id = ".$this->con->lastInsertId())->fetch(PDO::FETCH_ASSOC);;
			}
			else{
				$r["resultado"] = "is-invalid";
				$r["mensaje"] = "La cedula del empleado ya esta registrada";
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
	PRIVATE function modificar_empleado(){
		try {
			$this->validar_conexion($this->con);
			$estado_civil_array = [
				"Soltero",
				"Casado",
				"Divorciado",
				"Viudo",
				"Otros"
			];
			$V = new Validaciones();
			$tipo_identificacion_temp = TIPO_INDENT_ARRAY[$this->tipo_identificacion];
			$V->validarCedula($tipo_identificacion_temp.$this->rif_cedula);
			$V->validarNombre($this->nombres, "1,60", "El nombre no es valido");
			$V->validarNombre($this->apellidos, "1,60", "El apellido no es valido");
			$V->fecha($this->fechac, "fecha de contratación");
			$V->monto($this->salario, "El salario es invalido");
			$V->alfanumerico($this->domicilio_fiscal, "1,100", "El domicilio fiscal tiene caracteres inválidos");
			$V->validarTelefono($this->telefono);
			$V->validarEmail($this->correo);
			$V->alfanumerico($this->cargo, "1,60", "El cargo del empleado tiene caracteres inválidos");
			$V->fecha($this->fechan, "fecha de fecha nacimiento");
			if(!in_array($this->estado_civil, $estado_civil_array)){
				throw new Validaciones("Debe seleccionar un estado civil valido", 1);
			}


			$this->con->beginTransaction();

			$consulta = $this->con->prepare("SELECT * FROM empleado WHERE rif_cedula = ? AND tipo_identificacion = ?;");
			$consulta->execute([$this->id->rif_cedula, $this->id->tipo_identificacion]);
			if($consulta->fetch()){ // si existe el que se va a modificar
				if(($this->id->rif_cedula != $this->rif_cedula)|| ($this->id->tipo_identificacion != $this->tipo_identificacion)){ // si cambio la cedula o el prefijo
					$consulta = $this->con->prepare("SELECT * FROM empleado WHERE rif_cedula = ? AND tipo_identificacion = ?;");
					$consulta->execute([$this->rif_cedula, $this->tipo_identificacion]);
					if($consulta->fetch()){ 
						$r["resultado"] = "is-invalid";
						$r["mensaje"] = "La nueva cedula ya esta registrada";
					}

				}else{
					$consulta = $this->con->prepare("UPDATE
														`empleado`
													SET
														`rif_cedula` = :rif_cedula,
														`tipo_identificacion` = :tipo_identificacion,
														`nombre` = :nombre,
														`apellido` = :apellido,
														`fecha_contratacion` = :fecha_contratacion,
														`salario` = :salario,
														`domicilio` = :domicilio,
														`telefono` = :telefono,
														`correo` = :correo,
														`cargo` = :cargo,
														`fecha_nacimiento` = :fecha_nacimiento,
														`estado_civil` = :estado_civil
													WHERE
														`rif_cedula` = :rif_cedula_id AND
														`tipo_identificacion` = :tipo_identificacion_id;");
					$consulta->bindValue(":rif_cedula",$this->rif_cedula);
					$consulta->bindValue(":tipo_identificacion",$this->tipo_identificacion);
					$consulta->bindValue(":nombre",$this->nombres);
					$consulta->bindValue(":apellido",$this->apellidos);
					$consulta->bindValue(":fecha_contratacion",$this->fechac);
					$consulta->bindValue(":salario",$this->salario);
					$consulta->bindValue(":domicilio",$this->domicilio_fiscal);
					$consulta->bindValue(":telefono",$this->telefono);
					$consulta->bindValue(":correo",$this->correo);
					$consulta->bindValue(":cargo",$this->cargo);
					$consulta->bindValue(":fecha_nacimiento",$this->fechan);
					$consulta->bindValue(":estado_civil",$this->estado_civil);




					$consulta->bindValue(":rif_cedula_id",$this->id->rif_cedula);
					$consulta->bindValue(":tipo_identificacion_id",$this->id->tipo_identificacion);
					$consulta->execute();

					$bitacora = new Bitacora();
					$bitacora->b_modificar("Empleado");
					$r["resultado"] = "modificar_empleado";
					$r["mensaje"] = "( ".TIPO_INDENT_ARRAY[$this->tipo_identificacion].$this->rif_cedula." )";

				}
			}
			else{
				$r["resultado"] = "is-invalid";
				$r["mensaje"] = "El empleado que desea modificar no existe";
			}
		
			$this->con->commit();
		} 
		catch (Validaciones $e) {
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
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		return $r;


	}
	PRIVATE function eliminar_empleado(){

		try {
			$this->validar_conexion($this->con);

			$V = new Validaciones();
			$V->numero($this->tipo_identificacion,"1,2", "Debe seleccionar un prefijo valido para la cedula ej V, E, J, G");
			$tipo_identificacion_temp = TIPO_INDENT_ARRAY[intval($this->tipo_identificacion)];

			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM empleado WHERE rif_cedula = ? AND tipo_identificacion = ?;");
			$consulta->execute([$this->rif_cedula, $this->tipo_identificacion]);
			if($consulta->fetch()){
				$consulta = $this->con->prepare("DELETE FROM Empleado WHERE rif_cedula = ? AND tipo_identificacion = ?;");
				$consulta->execute([$this->rif_cedula, $this->tipo_identificacion]);

				$bitacora = new Bitacora();
				$bitacora->b_eliminar("Empleado");

				$r["resultado"] = "eliminar_empleado";
				$r["mensaje"] = "El Empleado Fue Eliminado Exitosamente";
			}
			else{
				$r['resultado'] = 'no-exist';
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

 	PUBLIC function listadoEmpleados() {
 		try {
			$this->validar_conexion($this->con);
 			$co = $this->con;
 			$co->beginTransaction();
 			if(!($this->rif_cedula && isset($this->tipo_identificacion))){
				$respuesta = $co->query("SELECT * FROM `empleado`")->fetchall(PDO::FETCH_ASSOC);
				$dom = new DOMDocument();
				$cont = 1;
				foreach ($respuesta as $elem) {
					$tr = $dom->createElement("tr");
					$tr->setAttribute("data-cedula",$elem["rif_cedula"]);
					$tr->setAttribute("data-tipo_identificacion",$elem["tipo_identificacion"]);

					$identf = TIPO_INDENT_ARRAY[(intval($elem["tipo_identificacion"]))];

					$cedula = $identf.$elem["rif_cedula"];
					$td =$dom->createElement("td",$cedula);
					$td->setAttribute("class","text-nowrap");
					$tr->appendChild($td);
					$tr->appendChild($dom->createElement("td",$elem["nombre"]));
					$tr->appendChild($dom->createElement("td",$elem["apellido"]));

					$tr->appendChild($dom->createElement("td", number_format($elem["salario"],2,",",".") ));
					$td = $dom->createElement("td",$elem["fecha_contratacion"]);
					$td->setAttribute("class","text-nowrap");
					$tr->appendChild($td);
					$tr->appendChild($dom->createElement("td",$elem["domicilio"]));
					$td =$dom->createElement("td",$elem["telefono"]);
					$td->setAttribute("class","text-nowrap");
					$tr->appendChild($td);
					$tr->appendChild($dom->createElement("td",$elem["correo"]));
					$tr->appendChild($dom->createElement("td",$elem["cargo"]));

					$td =$dom->createElement("td",$elem["fecha_nacimiento"]);
					$td->setAttribute("class","text-nowrap");
					$tr->appendChild($td);
					$tr->appendChild($dom->createElement("td",$elem["estado_civil"]));
					$dom->appendChild($tr);
				}
				$r['resultado'] = 'listadoEmpleados';
				$r['mensaje'] =  $dom->saveHTML();
 			}
 			else{
 				$consulta = $this->con->prepare("SELECT * FROM empleado WHERE rif_cedula = ? AND tipo_identificacion = ?");
 				$consulta->execute([$this->rif_cedula, $this->tipo_identificacion]);

 				if($resultado = $consulta->fetch(PDO::FETCH_ASSOC)){
	 				$r["resultado"] = "seleccionar_empleado";
	 				$resultado['TIPO_INDENT_ARRAY'] = TIPO_INDENT_ARRAY;
	 				$r["mensaje"] = $resultado;
 				}
 				else{
 					throw new Exception("El empleado ya no existe", 1);
 					
 				}
 			}
			$co->commit();
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

	PUBLIC function listadonomina() 
	{
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			if(!isset($this->id)){

				$consulta = $this->con->query("SELECT
											    p.id_pago,
											    e.rif_cedula,
											    e.tipo_identificacion,
											    e.nombre,
											    e.apellido,
											    p.concepto_pago AS descripcion,
											    p.total_pago AS monto,
											    (
											    SELECT
											        fecha
											    FROM
											        detalles_pagos AS dp
											    WHERE
											        dp.id_pago = p.id_pago
											    ORDER BY
											        fecha
											    LIMIT 1
											) AS fecha, tp.tipo_pago, 
											u.razon_social AS user,
											u.rif_cedula as user_cedula,
											u.tipo_identificacion user_cedula_tipo

											FROM
											    `nomina_pago` AS np
											JOIN pagos AS p
											ON
											    p.id_pago = np.id_pago
											JOIN empleado AS e
											ON
											    e.empleado_id = np.id_empleado
											LEFT JOIN datos_usuarios AS u
											ON
											    p.usuario_id = u.id
											JOIN tipo_pago AS tp
											ON
											    tp.id_tipo_pago =(
											    SELECT
											        tipo_pago
											    FROM
											        detalles_pagos AS dp
											    WHERE
											        dp.id_pago = p.id_pago
											    ORDER BY
											        fecha
											    LIMIT 1
											)
											WHERE
											    1
											ORDER BY
											    fecha
											LIMIT 200");
				$dom = new DOMDocument();
				foreach ($consulta as $elem) {

					$tr = $dom->createElement("tr");
					$tr->setAttribute("data-id", $elem["id_pago"]);
					$tr->setAttribute("class", "text-nowrap");
					$cedula_temp = TIPO_INDENT_ARRAY[$elem["tipo_identificacion"]].$elem["rif_cedula"];
					$tr->appendChild($dom->createElement("td", $cedula_temp));
					$tr->appendChild($dom->createElement("td", $elem["nombre"]." ".$elem["apellido"]));
					$td = $dom->createElement("td",$elem["descripcion"]);
					$td->setAttribute("title", $elem["descripcion"]);
					$td->setAttribute("class", "cell-description");
					$tr->appendChild($td);
					$tr->appendChild($dom->createElement("td", number_format($elem["monto"],2,",",".")));
					$tr->appendChild($dom->createElement("td", $elem["fecha"]));
					$tr->appendChild($dom->createElement("td", $elem["tipo_pago"]));
					$cedula_temp = TIPO_INDENT_ARRAY[$elem["user_cedula_tipo"]].$elem["user_cedula"];
					$td = $dom->createElement("td",$elem["user"]);
					$td->setAttribute("title", $cedula_temp);
					$tr->appendChild($td);
					$dom->appendChild($tr);

				}


				$r['resultado'] = 'listadoNomina';
				$r['mensaje'] =  $dom->saveHTML();;
			}
			else{
				$consulta = $this->con->prepare("SELECT
											    e.rif_cedula as emp_cedula,
											    e.tipo_identificacion emp_tipo_ident,
											    e.nombre,
											    e.apellido,
											    p.id_pago,
											    p.concepto_pago,
											    dp.monto,
											    dp.tipo_pago,
											    dp.id_detalles_pagos,
											    DATE_FORMAT(dp.fecha, '%Y-%m-%d') as fecha, DATE_FORMAT(dp.fecha, '%H:%i') AS hora,
											    dp.numero as referencia,
											    dp.emisor as banco,
											    dp.cedula_rif as cedula,
											    dp.telefono
											    
											FROM
											    nomina_pago as np
											JOIN empleado as e on np.id_empleado = e.empleado_id
											JOIN pagos as p ON p.id_pago = np.id_pago
											JOIN detalles_pagos as dp on dp.id_pago = p.id_pago WHERE p.id_pago = ? ORDER BY dp.fecha LIMIT 1");

				$consulta->execute([$this->id]);
				if($respuesta = $consulta->fetch(PDO::FETCH_ASSOC)){

					$respuesta["empleado_cedula_total"] = TIPO_INDENT_ARRAY[$respuesta["emp_tipo_ident"]].$respuesta["emp_cedula"];

					if($respuesta['tipo_pago'] == 4) {// es divisa hay que buscar los billetes
						$consulta = $this->con->prepare("SELECT serial, denominacion FROM divisa WHERE id_detalles_pagos = ?;");
						$consulta->execute([$respuesta["id_detalles_pagos"]]);
						$respuesta['billetes'] = $consulta->fetchall(PDO::FETCH_ASSOC);
						$respuesta["cantidad"] = count($respuesta["billetes"]);
					}

					$r['resultado'] = 'seleccionar_nomina';
					$r['mensaje'] =  $respuesta;
					}
					else {
						$r['resultado'] = 'is-invalid';
						$r['mensaje'] =  "El pago de nomina seleccionado no existe o fue eliminado";
					}
					

			}
		
			$this->con->commit();
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




	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
	PUBLIC function get_tipo_identificacion(){
		return $this->tipo_identificacion;
	}
	PUBLIC function set_tipo_identificacion($value){
		$this->tipo_identificacion = $value;
	}
	PUBLIC function get_rif_cedula(){
		return $this->rif_cedula;
	}
	PUBLIC function set_rif_cedula($value){
		$this->rif_cedula = $value;
	}
	PUBLIC function get_nombres(){
		return $this->nombres;
	}
	PUBLIC function set_nombres($value){
		$this->nombres = $value;
	}
	PUBLIC function get_apellidos(){
		return $this->apellidos;
	}
	PUBLIC function set_apellidos($value){
		$this->apellidos = $value;
	}
	PUBLIC function get_salario(){
		return $this->salario;
	}
	PUBLIC function set_salario($value){
		$value = preg_replace(["/\./","/,/"], ["","."], $value);
		$this->salario = $value;
	}
	PUBLIC function get_fechac(){
		return $this->fechac;
	}
	PUBLIC function set_fechac($value){
		$this->fechac = $value;
	}
	PUBLIC function get_domicilio_fiscal(){
		return $this->domicilio_fiscal;
	}
	PUBLIC function set_domicilio_fiscal($value){
		$this->domicilio_fiscal = $value;
	}
	PUBLIC function get_telefono(){
		return $this->telefono;
	}
	PUBLIC function set_telefono($value){
		$this->telefono = $value;
	}
	PUBLIC function get_correo(){
		return $this->correo;
	}
	PUBLIC function set_correo($value){
		$this->correo = $value;
	}
	PUBLIC function get_cargo(){
		return $this->cargo;
	}
	PUBLIC function set_cargo($value){
		$this->cargo = $value;
	}
	PUBLIC function get_fechan(){
		return $this->fechan;
	}
	PUBLIC function set_fechan($value){
		$this->fechan = $value;
	}
	PUBLIC function get_estado_civil(){
		return $this->estado_civil;
	}
	PUBLIC function set_estado_civil($value){
		$this->estado_civil = $value;
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
		if(is_string($value)){
			$value = json_decode($value);
		}
		$this->obj_pagos = $value;
	}


	
	
}

?>