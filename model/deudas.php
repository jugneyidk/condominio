<?php

require_once('model/datos.php');
require_once('model/enviar-correo.php');
require_once("model/bitacora.php");


class deuda extends datos
{
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
	PUBLIC function listadodeudas()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select DISTINCT id, deuda_pendiente.id_apartamento, fecha_a_cancelar, deuda_pendiente.total, apartamento.num_letra_apartamento, apartamento.torre, apartamento.piso, deuda_pendiente.id_deuda_condominio from deuda_pendiente inner join apartamento on deuda_pendiente.id_apartamento = apartamento.id_apartamento left join pago on pago.deuda = deuda_pendiente.id where deuda_pendiente.id_apartamento=apartamento.id_apartamento AND (id NOT IN (SELECT deuda from pago WHERE estado = 'confirmado' OR estado = 'pendiente')) ORDER BY deuda_pendiente.id DESC;");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r['id'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['num_letra_apartamento'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['torre'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['piso'];
					$respuesta = $respuesta . "</td>";
					$fecha_original = $r['fecha_a_cancelar'];
					$nuevo_formato = "d-m-Y";
					$fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $fecha_cambiada;
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['total'];
					$respuesta = $respuesta . "$</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . "<button class='btn btn-success' style='font-size: 13px;'' onclick='mostrar_registrar_pago(this)'>Pagar</button>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listadodeudas';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function listadomorosos()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select DISTINCT dp.id, dp.id_deuda_condominio, dp.id_apartamento, fecha_a_cancelar, dp.total, a.num_letra_apartamento, a.torre, a.piso, h.nombres, h.apellidos  from deuda_pendiente dp inner join apartamento a on dp.id_apartamento = a.id_apartamento JOIN habitantes h on h.id = a.propietario left join pago on pago.deuda = dp.id where dp.id_apartamento=a.id_apartamento AND dp.fecha_a_cancelar < CURRENT_DATE AND (dp.id NOT IN (SELECT deuda from pago WHERE estado = 'confirmado' OR estado = 'pendiente')) ORDER BY dp.id DESC;");
			$respuesta = '';
			if ($resultado) {
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr>";
					$respuesta = $respuesta . "<td style='display:none'>";
					$respuesta = $respuesta . $r['id'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['num_letra_apartamento'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['torre'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['piso'];
					$respuesta = $respuesta . "</td>";
					$fecha_original = $r['fecha_a_cancelar'];
					$nuevo_formato = "d-m-Y";
					$fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $fecha_cambiada;
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . $r['total'];
					$respuesta = $respuesta . "$</td>";
					$respuesta = $respuesta . "<td class='align-middle'>";
					$respuesta = $respuesta . "<button class='btn btn-success' style='font-size: 13px;'' onclick='mostrar_registrar_pago(this)'>Pagar</button>";
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
			}
			$r['resultado'] = 'listadomorosos';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	PUBLIC function registrarpago($id_deuda, $monto, $referencia, $fecha, $tipo_pago)
	{
		$usuario = $_SESSION['id_usuario'];
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		$monto = trim($monto,'$');
		try {
			$guarda = $co->query("insert into pago(referencia,fecha_entrega,tipo_pago,total,deuda,id_usuario,estado) 
		   values ('$referencia','$fecha','$tipo_pago','$monto',$id_deuda,$usuario,'confirmado')");
			$id = $co->lastInsertId();
			$enviarcorreo = new enviarcorreo();
			$correo = $enviarcorreo->enviar_correo($id);
			if($correo == true){
				$r['resultado'] = 'registrado';
				$r['mensaje'] =  "Pago Registrado";
				$bitacora = new Bitacora();
				$bitacora->b_incluir();

			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
}
