 <?php

 require_once('model/datos.php');
 require_once("model/bitacora.php");



 class Empleado extends datos
 {


 	PRIVATE $con, $cedula, $tipo_identif, $nombre, $apellido, $salario, $fecha_contrato, $domicilio, $telefono, $correo, $cargo, $fecha_nacim, $estado_civil;
 	PUBLIC function __construct()
	{
		$this->con = $this->conecta();
 		$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
 	PUBLIC function chequearpermisos(){

 		$id_rol = $_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol'];
 		$modulo = $_GET['p'];
 		$co = $this->con;
 		$guarda = $co->query("SELECT * FROM `roles_modulos` inner join `modulos` on roles_modulos.id_modulo = modulos.id inner join `roles` on roles_modulos.id_rol = roles.id where modulos.nombre = '$modulo' and roles_modulos.id_rol = '$id_rol'");
 		$guarda->execute();
 		$fila = array();
 		$fila = $guarda->fetch(PDO::FETCH_NUM);
 		return $fila;		
 	}
 	PUBLIC function incluir_empleado()
 	{
 		$r = array();

 		try {

 			$consulta = $this->con->prepare("SELECT * FROM empleado WHERE rif_cedula = ? AND tipo_identificacion = ?");
 			$consulta->execute([$this->cedula, $this->tipo_identif]);
 			if(!$consulta->fetch()){
				$consulta = $this->con->prepare("INSERT INTO `empleado`(`rif_cedula`, `tipo_identificacion`, `nombre`, `apellido`, `fecha_contratacion`, `salario`, `domicilio`, `telefono`, `correo`, `cargo`, `fecha_nacimiento`, `estado_civil`) VALUES (:rif_cedula,:tipo_identificacion,:nombre,:apellido,:fecha_contratacion,:salario,:domicilio,:telefono,:correo,:cargo,:fecha_nacimiento,:estado_civil)");

				$consulta->bindValue(":rif_cedula", $this->cedula);
				$consulta->bindValue(":tipo_identificacion", $this->tipo_identif);
				$consulta->bindValue(":nombre", $this->nombre);
				$consulta->bindValue(":apellido", $this->apellido);
				$consulta->bindValue(":salario", $this->salario);
				$consulta->bindValue(":fecha_contratacion", $this->fecha_contrato);
				$consulta->bindValue(":domicilio", $this->domicilio);
				$consulta->bindValue(":telefono", $this->telefono);
				$consulta->bindValue(":correo", $this->correo);
				$consulta->bindValue(":cargo", $this->cargo);
				$consulta->bindValue(":fecha_nacimiento", $this->fecha_nacim);
				$consulta->bindValue(":estado_civil", $this->estado_civil);

				$consulta->execute();
	 			
	 			$r['resultado'] = 'incluir';
	 			$r['mensaje'] =  "Registro Incluido";
	 			$bitacora = new Bitacora();
	 			$bitacora->b_incluir();

	 			
 			}
 			else{
 				$r['resultado'] = 'error';
	 			$r['mensaje'] =  "La cedula ya existe";
 			}
 		} catch (Exception $e) {
 			$r['resultado'] = 'error';
 			$r['mensaje'] =  $e->getMessage();
 		}			

 		return $r;
 	}
 	

 	PUBLIC function modificar_empleado($id_tipo_apartamento, $descripcion, $alicuota)
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
 				$bitacora = new Bitacora();
 				$bitacora->b_modificar();

 			} catch (Exception $e) {
 				return $e->getMessage();
 			}
 		} else {
 			$r['resultado'] = 'error';
 			$r['mensaje'] =  "Tipo de apartamento no encontrado";
 		}
 		return $r;
 	}
 	PUBLIC function eliminar_empleado($id_tipo_apartamento)
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

 				
 				$bitacora = new Bitacora();
 				$bitacora->b_eliminar();
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

 	PUBLIC function listadoEmpleados() {
 		try {
			$respuesta = $this->con->query("SELECT * FROM `empleado`")->fetchall(PDO::FETCH_ASSOC);
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




	PUBLIC function get_cedula(){
		return $this->cedula;
	}
	PUBLIC function set_cedula($value){
		$this->cedula = $value;
	}
	PUBLIC function get_nombre(){
		return $this->nombre;
	}
	PUBLIC function set_nombre($value){
		$this->nombre = $value;
	}
	PUBLIC function get_salario(){
		return $this->salario;
	}
	PUBLIC function set_salario($value){
		$this->salario = $value;
	}
	PUBLIC function get_fecha_contrato(){
		return $this->fecha_contrato;
	}
	PUBLIC function set_fecha_contrato($value){
		$this->fecha_contrato = $value;
	}
	PUBLIC function get_domicilio(){
		return $this->domicilio;
	}
	PUBLIC function set_domicilio($value){
		$this->domicilio = $value;
	}
	PUBLIC function get_teléfono(){
		return $this->teléfono;
	}
	PUBLIC function set_teléfono($value){
		$this->teléfono = $value;
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
	PUBLIC function get_fecha_nacim(){
		return $this->fecha_nacim;
	}
	PUBLIC function set_fecha_nacim($value){
		$this->fecha_nacim = $value;
	}
	PUBLIC function get_estado_civil(){
		return $this->estado_civil;
	}
	PUBLIC function set_estado_civil($value){
		$this->estado_civil = $value;
	}

 }

?>
