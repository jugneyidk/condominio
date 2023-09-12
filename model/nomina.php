 <?php

require_once('model/datos.php');

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
function incluir($cedula_rif, $tipo_identificacion, $nombres,$apellidos,$domicilio_fiscal,$telefono,$correo,$descripcion,$metodo,$fecha,$monto,$referencia)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
	
			try {
				$guarda = $co->query("insert into nomina(id_nom,cedula_rif,tipo_identificacion,nombres,apellidos,domicilio,telefono,correo,descripcion,metodo,fecha,monto,referencia) 
			   values('NULL','$cedula_rif', '$tipo_identificacion', '$nombres','$apellidos','$domicilio_fiscal','$telefono','$correo','$descripcion','$metodo','$fecha','$monto','$referencia')");
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
			$resultado = $co->query("Select id_nom,cedula_rif,tipo_identificacion,nombres,apellidos,domicilio,telefono,correo,descripcion,metodo,fecha,monto,referencia
			from 
			nomina ORDER BY id_nom DESC");
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
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r[7];
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
					$respuesta = $respuesta . $r[11];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listado_nomina';
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
