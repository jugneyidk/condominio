<?php 
require_once("model/datos.php");
require_once("model/bitacora.php");



/**
 * 
 */
class avisos extends datos
{
	PRIVATE $con, $id, $titulo, $descripcion, $desde, $hasta;
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
			$consulta = $this->con->prepare("INSERT INTO avisos (titulo, descripcion, desde, hasta) VALUES(?,?,?,?)");
			$consulta->execute([$this->titulo, $this->descripcion, $this->desde, $this->hasta ]);
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


	PUBLIC function modificar(){
		$r=array();
		try {
			$consulta = $this->con->prepare("UPDATE `avisos` SET `titulo`= ? ,`descripcion`= ? ,`desde`= ? ,`hasta`= ? WHERE `id_aviso` = ?");
			$consulta->execute([$this->titulo, $this->descripcion, $this->desde, $this->hasta, $this->id ]);
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


	PUBLIC function eliminar(){
		$r = array();
		try {
			$consulta = $this->con->prepare("SELECT * FROM avisos WHERE id_aviso = ?");
			$consulta->execute([$this->id]);
			if ($consulta->fetch()) {
				$consulta = $this->con->prepare("DELETE FROM avisos WHERE id_aviso = ?");
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


	PUBLIC function listaAvisos(){
		try {
			$respuesta = $this->con->query("SELECT * FROM `avisos`")->fetchall();

			

			$dom = new DOMDocument();
			$cont = 1;
			foreach ($respuesta as $elem) {
				$elem["desde"] = date_format( date_create($elem["desde"]),"d/m/Y" );
				$elem["hasta"] = date_format( date_create($elem["hasta"]),"d/m/Y" );


				$tr = $dom->createElement("tr");
				$tr->appendChild($dom->createElement("td",str_pad($cont, 2, "0", STR_PAD_LEFT)));

				$td = $dom->createElement("td",$elem["id_aviso"]);
				$td->setAttribute("class","d-none");
				$tr->appendChild($td);
				$td = $dom->createElement("td",$elem["titulo"]);
				$td->setAttribute("class","text-nowrap");
				$tr->appendChild($td);





				$td = $dom->createElement("td",$elem["descripcion"]);
				$tr->appendChild($td);






				$td = $dom->createElement("td",$elem["desde"]);
				$td->setAttribute("class","text-nowrap");
				$tr->appendChild($td);
				$td = $dom->createElement("td",$elem["hasta"]);
				$td->setAttribute("class","text-nowrap");
				$tr->appendChild($td);

				$dom->appendChild($tr);
				$cont++;
			}
			$r['resultado'] = 'listaAvisos';
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
	PUBLIC function get_desde(){
		return $this->desde;
	}
	PUBLIC function set_desde($value){
		$this->desde = $value;
	}
	PUBLIC function get_hasta(){
		return $this->hasta;
	}
	PUBLIC function set_hasta($value){
		$this->hasta = $value;
	}
}

?>