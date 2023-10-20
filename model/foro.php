<?php 
require_once("model/datos.php");
require_once("model/bitacora.php");



/**
 * 
 */
class Foro extends datos
{
	PRIVATE $con, $id, $titulo, $descripcion, $create_by;
	function __construct()
	{
		$this->con = $this->conecta();
		$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	PUBLIC function chequearpermisos()
	{
		$id_rol = $_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol'];
		$modulo = $_GET['p'];
		$co = $this->con;
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



	PRIVATE function incluir(){
		$r = array();
		try {
			$consulta = $this->con->prepare("INSERT INTO foro (titulo, descripcion, create_by) VALUES(?,?,?)");
			$consulta->execute([$this->titulo, $this->descripcion , $this->create_by]);
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Registro Incluido';
			$bitacora = new Bitacora();
			$bitacora->b_incluir();

		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PRIVATE function modificar(){
		$r=array();
		try {
			$consulta = $this->con->prepare("UPDATE `foro` SET `titulo`= ? ,`descripcion`= ? WHERE `id` = ?");
			$consulta->execute([$this->titulo, $this->descripcion, $this->id ]);
			$consulta->execute();
			


			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'Registro Modificado';

			$bitacora = new Bitacora();
			$bitacora->b_modificar();


		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PRIVATE function eliminar(){
		$r = array();
		try {
			$consulta = $this->con->prepare("SELECT * FROM foro WHERE id = ?");
			$consulta->execute([$this->id]);
			if ($consulta->fetch()) {
				$consulta = $this->con->prepare("DELETE FROM foro WHERE id = ?");
				$consulta->execute([$this->id]);
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  'Registro Eliminado';
				
				$bitacora = new Bitacora();
				$bitacora->b_eliminar();
			} else {
				$r['resultado'] = 'error';
				$r['mensaje'] =  "El Registro no existe";
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function listaForo(){
		try {
			$respuesta = $this->con->query("SELECT * FROM `foro` ORDER BY fecha")->fetchall();

			

			$dom = new DOMDocument();
			$cont = 1;
			foreach ($respuesta as $elem) {


				$tr = $dom->createElement("tr");
				$tr->appendChild($dom->createElement("td",str_pad($cont, 2, "0", STR_PAD_LEFT)));

				$td = $dom->createElement("td",$elem["id"]);
				$td->setAttribute("class","d-none");
				$tr->appendChild($td);
				
				$tr->appendChild($dom->createElement("td",$elem["titulo"]));
				$tr->appendChild($dom->createElement("td",$elem["descripcion"]));
				$tr->appendChild($dom->createElement("td",$elem["fecha"]));

				$dom->appendChild($tr);
				$cont++;
			}
			$r['resultado'] = 'listaForo';
			$r['mensaje'] =  $dom->saveHTML();
			// $r['mensaje'] =  "hola";
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
	PUBLIC function get_titulo(){
		return $this->titulo;
	}
	PUBLIC function set_titulo($value){
		$this->titulo = $value;
	}
	PUBLIC function get_descripcion(){
		return $this->descripcion;
	}
	PUBLIC function set_descripcion($value){
		$this->descripcion = $value;
	}
	PUBLIC function get_create_by(){
		return $this->create_by;
	}
	PUBLIC function set_create_by($value){
		$this->create_by = $value;
	}
}

?>