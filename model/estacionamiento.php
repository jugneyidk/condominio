<?php 

require_once('model/datos.php');

/**
 * 

num_estacionamiento
id_apartamento
costo
 */
class estac extends datos
{
	PRIVATE $con, $num_estac, $costo, $apartamento_id,$num_estac_original;
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

	PUBLIC function listadoapartamentos()
	{
		$co = $this->con;
		try {
			$resultado = $co->query("SELECT a.id_apartamento, a.num_letra_apartamento, a.propietario, a.inquilino, a.tipo_apartamento, a.piso, a.torre, h1.nombres as nombre_propietario, h1.apellidos as apellido_propietario, tipo_apartamento.descripcion, h2.nombres as nombre_inquilino, h2.apellidos as apellido_inquilino  
			FROM apartamento a 
			left join habitantes h1 on a.propietario = h1.id
			left join habitantes h2 on a.inquilino = h2.id 
			INNER JOIN tipo_apartamento on a.tipo_apartamento = tipo_apartamento.id_tipo_apartamento ORDER BY a.id_apartamento DESC");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocaapartamento(this);'>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[0];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle font-weight-bold'>";
					$respuesta = $respuesta . $r[1];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[2];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[7] . " " . $r[8];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[3];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[10] . " " . $r[11];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r[4];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[9];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[5];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[6];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listadoapartamentos';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function listadoaEstacionamiento(){
		try {
			
			$con = $this->con;
			$consulta = "SELECT e.*, a.num_letra_apartamento as apartamento FROM `estacionamiento` AS e LEFT JOIN apartamento AS a on a.id_apartamento = e.id_apartamento order by e.`num_estacionamiento` ASC";
			$resp = $con->query($consulta)->fetchall(PDO::FETCH_ASSOC);
			$dom = new DOMDocument();

			foreach ($resp as $elem) {
				$tr = $dom->createElement("tr");
				$tr->setAttribute("style","cursor:pointer;");
				$tr->setAttribute("onclick","colocarEstacionamiento(this);");
				$tr->appendChild($dom->createElement('td',$elem['num_estacionamiento']));
				$tr->appendChild($dom->createElement('td',number_format($elem['costo'],2,',','.')."$"));

				$td = $dom->createElement("td",$elem['id_apartamento']);
				$td->setAttribute("class",'d-none');
				$tr->appendChild($td);
				$tr->appendChild($dom->createElement('td',$elem['apartamento']));
				$dom->appendChild($tr);
			}
			$r['resultado'] = 'listadoEstacionamiento';
			$r['mensaje'] =  $dom->saveHTML();
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}


	PUBLIC function incluir()
	{
		$r = array();

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
		return $r;
	}

	PUBLIC function modificar(){
		$r = array();

		if ($this->existe(3)) {
			if(true || !$this->existe(2)){// si elimino el true validara que un apartamento no pueda tener mas de un estacionamiento

				try {
						$consulta = $this->con->prepare("UPDATE `estacionamiento` SET `num_estacionamiento`= ?,`id_apartamento`= ?,`costo`= ? WHERE `num_estacionamiento` = ?");
						if($this->num_estac != $this->num_estac_original and $this->existe()){
							$r['resultado'] = 'error';
							$r['mensaje'] =  "El nuevo numero de estacionamiento ya existe";
							return $r;

						}
						$consulta->execute([$this->num_estac, $this->apartamento_id, $this->costo, $this->num_estac_original]);
						if($this->existe()){ // si existe (se registro exitosamente)
							$r['resultado'] = 'modificar';
							$r['mensaje'] =  'Registro Modificado';
						}
						else{
							$r['resultado'] = 'error';
							$r['mensaje'] =  'Ocurrió un error en la Modificación Inténtelo nuevamente';
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
			$r['mensaje'] =  "El numero de estacionamiento ha modificar no existe";
		}
		return $r;
	}

	PUBLIC function eliminar(){
		$r = array();

		if ($this->existe()) {
			$consulta = $this->con->prepare("DELETE FROM estacionamiento WHERE num_estacionamiento = ?");
			$consulta->execute([$this->num_estac]);
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'Registro Eliminado';
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] =  "El numero de estacionamiento no existe";
		}
		return $r;
	}


	// 1 para validar numero del estacionamiento 
	// 2 para validar el apartamento (que ya tenga un estacionamiento asignado)
	// 3 para validar el que existe el numero de apartamento original (`para modificar)
	PUBLIC function existe ($cond = 1){
		if($cond == 1 || $cond == 3){
			$consulta = $this->con->prepare("SELECT * FROM estacionamiento WHERE num_estacionamiento = ?");
			if($cond == 1){
				$consulta->execute([$this->num_estac]);
				
			}
			else $consulta->execute([$this->num_estac_original]);
		}
		else if ($cond == 2){
			$consulta = $this->con->prepare("SELECT * FROM estacionamiento WHERE id_apartamento = ?");
			$consulta->execute([$this->apartamento_id]);
		}
		if($consulta->fetch()){
			return true;
		}
		else return false;
	}



	PUBLIC function get_num_estac(){
		return $this->num_estac;
	}
	PUBLIC function set_num_estac($value){
		$this->num_estac = $value;
	}
	PUBLIC function get_costo(){
		return $this->costo;
	}
	PUBLIC function set_costo($value){
		if($value == ''){
			$this->costo = null;
		}
		else{
			$value = preg_replace("/\./", "", $value);
			$value = preg_replace("/\,/", ".", $value);
			$value = doubleval($value);
			$this->costo = $value;
		}
	}
	PUBLIC function get_apartamento_id(){
		return $this->apartamento_id;
	}
	PUBLIC function set_apartamento_id($value){
		if($value == ''){
			$this->apartamento_id = null;
		}
		else{
			$this->apartamento_id = $value;
		}
	}
	PUBLIC function get_num_estac_original(){
		return $this->num_estac_original;
	}
	PUBLIC function set_num_estac_original($value){
		$this->num_estac_original = $value;
	}



}


 ?>