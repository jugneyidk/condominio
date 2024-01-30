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
		$id_rol = $_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol'];
		$modulo = $_GET['p'];
		$co = $this->con;
		$guarda = $co->prepare("SELECT * FROM `roles_modulos` INNER JOIN `modulos` ON roles_modulos.id_modulo = modulos.id INNER JOIN `roles` ON roles_modulos.id_rol = roles.id WHERE modulos.nombre = ? AND roles_modulos.id_rol = ?");
		$guarda->execute([$modulo, $id_rol]);
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




	PUBLIC function incluir()
	{
		$r = array();
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("INSERT INTO avisos (titulo, descripcion, desde, hasta) VALUES(?,?,?,?)");
			$consulta->execute([$this->titulo, $this->descripcion, $this->desde, $this->hasta ]);
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Registro Incluido';
			$bitacora = new Bitacora($this->con);
  			$bitacora->b_registro("Registró el aviso \"$this->titulo\"");
  			$this->con->commit();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		return $r;
		
	}


	PUBLIC function modificar(){
		$r=array();
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("UPDATE `avisos` SET `titulo`= ? ,`descripcion`= ? ,`desde`= ? ,`hasta`= ? WHERE `id_aviso` = ?");
			$consulta->execute([$this->titulo, $this->descripcion, $this->desde, $this->hasta, $this->id ]);
			$consulta->execute();
			


			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'Registro Modificado';

			$bitacora = new Bitacora($this->con);
  			$bitacora->b_registro("Modificó el aviso \"$this->titulo\"");
			// $bitacora->b_modificar();
  			$this->con->commit();
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		return $r;
	}


	PUBLIC function eliminar(){
		$r = array();
		try {
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();
			$consulta = $this->con->prepare("SELECT * FROM avisos WHERE id_aviso = ?");
			$consulta->execute([$this->id]);
			if ($resp = $consulta->fetch(PDO::FETCH_ASSOC)) {
				$consulta = $this->con->prepare("DELETE FROM avisos WHERE id_aviso = ?");
				$consulta->execute([$this->id]);
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  'Registro Eliminado';

				$this->titulo = $resp["titulo"];
				
				$bitacora = new Bitacora();
  				$bitacora->b_registro("Eliminó el aviso \"$this->titulo\"");
				// $bitacora->b_eliminar();
			} else {
				$r['resultado'] = 'error';
				$r['mensaje'] =  "El Registro no existe";
			}
		} catch (Exception $e) {
			if($this->con instanceof PDO){
				if($this->con->inTransaction()){
					$this->con->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
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