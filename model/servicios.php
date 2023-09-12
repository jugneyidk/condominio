<?php 

require_once("model/datos.php");
/**
 * 
 */
class servicio extends datos
{
	PRIVATE $con, $servicio, $descripcion, $fecha, $monto, $referencia;

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
			
			$consulta = $this->con->prepare("INSERT INTO servicios (servicio, descripcion, fecha, monto, referencia, usuario_id) VALUES(:servicio, :descripcion, :fecha, :monto, :referencia, :usuario_id)");
			$consulta->bindValue(":servicio",$this->servicio);
			$consulta->bindValue(":descripcion",$this->descripcion);
			$consulta->bindValue(":fecha",$this->fecha);
			$consulta->bindValue(":monto",$this->monto);
			$consulta->bindValue(":referencia",$this->referencia);
			$consulta->bindValue(":usuario_id",$_SESSION['id_usuario']);
			$consulta->execute();
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Registro Incluido';

		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}

		return $r;
/*
		if (!$this->existe()) {
			if(true || !$this->existe(2)){// si elimino el true validara que un apartamento no pueda tener mas de un estacionamiento

				try {
					$consulta = $this->con->prepare("INSERT INTO `estacionamiento`(`num_estacionamiento`, `id_apartamento`, `costo`) VALUES (?,?,?)");
					$consulta->execute([$this->num_estac, $this->apartamento_id, $this->costo]);
					if($this->existe()){ // si existe (se registro exitosamente)
						$r['resultado'] = 'incluir';
						$r['mensaje'] =  'Registro Incluido';
					}
					else{
						$r['resultado'] = 'error';
						$r['mensaje'] =  'Ocurrió un error en el Registro';
					}

				} catch (Exception $e) {
					$r['resultado'] = 'error';
					$r['mensaje'] =  $e->getMessage();
				}
			}
			else{
				$r['resultado'] = 'error';
				$r['mensaje'] =  "El apartamento ya tiene asignado un numero de estacionamiento y no puede asignarse otro";
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "El numero de estacionamiento ya existe";
		}
		*/
		
	}
PUBLIC function listadoservicios() 
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select servicio, descripcion, fecha, monto, referencia, usuario_id
			from 
			servicios ORDER BY id_nom DESC");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocatipo(this);'>";
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
					
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listado_servicios';
			$r['mensaje'] =  $respuesta;
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
}

?>