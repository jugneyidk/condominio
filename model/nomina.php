 <?php




 require_once('model/datos.php');
 require_once("model/bitacora.php");
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
		$guarda = $co->query("SELECT * FROM `roles_modulos` inner join `modulos` on roles_modulos.id_modulo = modulos.id inner join `roles` on roles_modulos.id_rol = roles.id where modulos.nombre = '$modulo' and roles_modulos.id_rol = '$id_rol'");
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
	PRIVATE function incluir($id_empleado,$descripcion,$metodo,$fecha,$monto,$referencia)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
	
			try {
				$guarda = $co->query("insert into nomina(id_empleado,descripcion,metodo,fecha,monto,referencia) 
			   values('$id_empleado','$descripcion','$metodo','$fecha','$monto','$referencia')");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  "Registro Incluido";
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}			
		
		return $r;
	}
	PRIVATE function modificar($id_tipo_apartamento, $descripcion, $alicuota)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id_tipo_apartamento,0,1)) {
			try {
				$co->query("Update tipo_apartamento set 
						descripcion = '$descripcion',
						alicuota = '$alicuota'
						where
						id_tipo_apartamento = '$id_tipo_apartamento'
						");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  "Registro modificado correctamente";
			} catch (Exception $e) {
				return $e->getMessage();
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Tipo de apartamento no encontrado";
		}
		return $r;
	}
	PRIVATE function eliminar($id_tipo_apartamento)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($this->existe($id_tipo_apartamento,0,1)) {
			try {
				$co->query("delete from tipo_apartamento 
						where
						id_tipo_apartamento = '$id_tipo_apartamento'
						");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  "Registro Eliminado";
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode()=='23000') {
					$r['mensaje'] =  "El tipo de apartamento no puede ser eliminado si está asignado a un apartamento.";
				}else{
					$r['mensaje'] =  $e->getMessage();					
				}
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "Tipo de apartamento no encontrado";
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
				$r["resultado"] = "incluir_2";
				$r["mensaje"] = "El Empleado fue registrado exitosamente";

				//$r["mensaje"] = $this->con->query("SELECT * FROM empleado where empleado_id = ".$this->con->lastInsertId())->fetch(PDO::FETCH_ASSOC);;
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



		// ob_start();
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		// $valor = ob_get_clean();

		// $r['resultado'] = "console";
		// $r['mensaje'] = $valor;

		// return $r;
	}
	PRIVATE function modificar_empleado(){

	}
	PRIVATE function eliminar_empleado(){

	}

 	PUBLIC function listadoEmpleados() {
 		try {
 			$co = $this->conecta();
			$respuesta = $co->query("SELECT * FROM `empleado`")->fetchall(PDO::FETCH_ASSOC);
			$dom = new DOMDocument();
			$cont = 1;
			foreach ($respuesta as $elem) {
				$tr = $dom->createElement("tr");
				if($elem["tipo_identificacion"] == 1 ) $identf="V-";
				else if($elem["tipo_identificacion"] == 2 ) $identf="E-";
				else if($elem["tipo_identificacion"] == 3 ) $identf="J-";
				$cedula = $identf.$elem["rif_cedula"];
				$td =$dom->createElement("td",$cedula);
				$td->setAttribute("class","text-nowrap");
				$tr->appendChild($td);
				$tr->appendChild($dom->createElement("td",$elem["nombre"]));
				$tr->appendChild($dom->createElement("td",$elem["apellido"]));
				$tr->appendChild($dom->createElement("td",$elem["salario"]));
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
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function listadonomina() 
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select id, id_empleado, descripcion,metodo,fecha,monto,referencia
			from 
			nomina ORDER BY id DESC");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocanomina(this);'>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[0];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[1];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[2];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[3];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[4];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[5];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[6];
					$respuesta = $respuesta . "</td>";
				}
 			}
 				$r['resultado'] = 'listado_empleados1';
 				$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
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


	
	
}




?>