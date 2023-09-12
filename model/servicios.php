<?php 

require_once("model/datos.php");
/**
 * 
 // CONTROL referencia duplicada
 */
class servicio extends datos
{
	PRIVATE $con, $servicio, $descripcion, $fecha, $monto, $referencia,$id_pago_serv;

	function __construct()
	{
		$this->con = $this->conecta();
		$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}


	PUBLIC function chequearpermisos()
	{
		$id_rol = $_SESSION['rol'];
		$modulo = $_GET['p'];
		$co = $this->con;
		$guarda = $co->query("SELECT * FROM `roles_modulos` inner join `modulos` on roles_modulos.id_modulo = modulos.id inner join `roles` on roles_modulos.id_rol = roles.id where modulos.nombre = '$modulo' and roles_modulos.id_rol = '$id_rol'");
		$guarda->execute();
		$fila = array();
		$fila = $guarda->fetch(PDO::FETCH_NUM);
		return $fila;
	} 

	PUBLIC function incluir()
	{
		$r = array();
		try {
			$consulta = $this->con->prepare("SELECT * FROM `lista_servicios` WHERE `id_servicios` = ?");
			$consulta->execute([$this->servicio]);
			if($consulta->fetch()){

				$consulta = $this->con->prepare("SELECT * FROM `servicios` WHERE `referencia` = ?");
				//$consulta->execute([$this->referencia]);
				if(true || !$consulta->fetch()){// CONTROL referencia duplicada
					$consulta = $this->con->prepare("INSERT INTO servicios (id_servicio, descripcion, fecha, monto, referencia, usuario_id) VALUES(:servicio, :descripcion, :fecha, :monto, :referencia, :usuario_id)");
					$consulta->bindValue(":servicio",$this->servicio);
					$consulta->bindValue(":descripcion",$this->descripcion);
					$consulta->bindValue(":fecha",$this->fecha);
					$consulta->bindValue(":monto",$this->monto);
					$consulta->bindValue(":referencia",$this->referencia);
					$consulta->bindValue(":usuario_id",$_SESSION['id_usuario']);
					$consulta->execute();
					$r['resultado'] = 'incluir';
					$r['mensaje'] =  'Registro Incluido';
				}
				else{
					$r['resultado'] = 'error_no_borrar';
					$r['mensaje'] =  'El numero de referencia ya esta registrado';
				}
			}
			else{
				$r['resultado'] = 'error';
				$r['mensaje'] =  'El servicio seleccionado no esta disponible';
			}

		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}

		return $r;
		
	}

	PUBLIC function modificar(){
		$r=array();
		try {
			$consulta = $this->con->prepare("SELECT * FROM `servicios` WHERE `id_servicios` = ?");
			$consulta->execute([$this->id_pago_serv]);
			if($consulta->fetch()){//valido que el id del pago de servicio exista
				$consulta = $this->con->prepare("SELECT s.monto,s2.monto as monto2 FROM servicios as s JOIN servicios as s2 on s2.referencia = ? AND s.id_servicios <> s2.id_servicios WHERE s.id_servicios = ?");
				//$consulta->execute([$this->referencia, $this->id_pago_serv])
				if(true || !$consulta->fetch()){// CONTROL referencia duplicada
					$consulta = $this->con->prepare("UPDATE `servicios` SET `id_servicio`= :id_servicio ,`descripcion`= :descripcion ,`fecha`= :fecha ,`monto`= :monto ,`referencia`= :referencia WHERE `id_servicios` = :id_servicios");
					$consulta->bindValue(":id_servicio",$this->servicio);
					$consulta->bindValue(":descripcion",$this->descripcion);
					$consulta->bindValue(":fecha",$this->fecha);
					$consulta->bindValue(":monto",$this->monto);
					$consulta->bindValue(":referencia",$this->referencia);
					$consulta->bindValue(":id_servicios",$this->id_pago_serv);

					$consulta->execute();
					


					$r['resultado'] = 'modificar';
					$r['mensaje'] =  'Registro Modificado';
				}
				else{
					$r['resultado'] = 'error_no_borrar';
					$r['mensaje'] =  'La nueva referencia ya existe';	
				}
			}
			else{
				$r['resultado'] = 'error';
				$r['mensaje'] =  'El Registro no existe';
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}


	PUBLIC function eliminar(){
		$r = array();

		if ($this->existe()) {
			$consulta = $this->con->prepare("DELETE FROM servicios WHERE id_servicios = ?");
			$consulta->execute([$this->id_pago_serv]);
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'Registro Eliminado';
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "El Registro no existe";
		}
		return $r;
	}




	








	PUBLIC function listadoPagosservicios() {
		$co = $this->con;
		$r = array();
		try {
			$resultado = $co->query("SELECT s.`id_servicios`, s.`id_servicio`, l.`nombre` AS servcio, s.`descripcion`, s.`monto`, s.`fecha`, s.`referencia` FROM `servicios` AS s JOIN lista_servicios AS l ON l.`id_servicios` = s.`id_servicio`")->fetchall(PDO::FETCH_ASSOC);
			$dom = new DOMDocument();
			if ($resultado) {
				foreach ($resultado as $elem) {
					$tr = $dom->createElement("tr");
					$tr->appendChild($dom->createElement( "td", $elem["id_servicios"] ));

					$td = $dom->createElement("td",$elem["id_servicio"]);
					$td->setAttribute("class","d-none");
					$tr->appendChild($td);

					$tr->appendChild($dom->createElement( "td", $elem["servcio"] ));
					$tr->appendChild($dom->createElement( "td", $elem["descripcion"] ));
					$tr->appendChild($dom->createElement( "td", number_format($elem["monto"],2,",",".") ));

					$td = $dom->createElement("td",$elem["fecha"]);
					$td->setAttribute("class","text-nowrap");
					$tr->appendChild($td);

					$tr->appendChild($dom->createElement( "td", $elem["referencia"] ));
					$dom->appendChild($tr);
				}
				
			}
			$r['resultado'] = 'listadoPagosservicios';

			$r['mensaje'] =  $dom->saveHTML();
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function lista_select_servicios(){
		try {
			$respuesta = $this->con->query("SELECT * FROM `lista_servicios`")->fetchall();

			$dom = new DOMDocument();
			$dom->appendChild($dom->createElement("option","-"));
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
	PUBLIC function get_fecha(){
		return $this->fecha;
	}
	PUBLIC function set_fecha($value){
		$this->fecha = $value;
	}
	PUBLIC function get_monto(){
		return $this->monto;
	}
	PUBLIC function set_monto($value){
		$value = preg_replace("/\./", "", $value);
		$value = preg_replace("/\,/", ".", $value);
		$value = doubleval($value);
		$this->monto = $value;
	}
	PUBLIC function get_referencia(){
		return $this->referencia;
	}
	PUBLIC function set_referencia($value){
		$this->referencia = $value;
	}
	PUBLIC function get_id_pago_serv(){
		return $this->id_pago_serv;
	}
	PUBLIC function set_id_pago_serv($value){
		$this->id_pago_serv = $value;
	}
}

?>