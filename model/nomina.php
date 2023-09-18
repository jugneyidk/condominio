 <?php

 require_once('model/datos.php');
 require_once("model/bitacora.php");
class nomina extends datos
{
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
	function incluir($id_empleado,$descripcion,$metodo,$fecha,$monto,$referencia)
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
 	


	PUBLIC function listadoempleados1() 
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select empleado_id, rif_cedula, tipo_identificacion,nombre,apellido,fecha_contratacion,salario, domicilio, telefono, correo, cargo, fecha_nacimiento, estado_civil
			from 
			empleado ORDER BY empleado_id DESC");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocaempleados1(this);'>";
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
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[8];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[9];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[10];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[12];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
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

	PUBLIC function listadoempleados2() 
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select empleado_id, rif_cedula, tipo_identificacion,nombre,apellido,fecha_contratacion,salario, domicilio, telefono, correo, cargo, fecha_nacimiento, estado_civil
			from 
			empleado ORDER BY empleado_id DESC");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocaempleados2(this);'>";
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
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[8];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[9];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[10];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[12];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listado_empleados2';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function modificar($id_tipo_apartamento, $descripcion, $alicuota)
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
	PUBLIC function eliminar($id_tipo_apartamento)
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
	
}
?>