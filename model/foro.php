<?php 
require_once("model/datos.php");
require_once("model/bitacora.php");

class Foro extends datos
{
	PRIVATE $con, $id, $comentario, $post_id, $create_by, $apto_id, $voto, $habitante_id, $titulo, $descripcion;
	function __construct()
	{
		$this->con = $this->conecta();
	}

	public function chequearpermisos()
	{
		$id_rol = $_SESSION['rol'];
		$modulo = $_GET['p'];
		$co = $this->con;
		$guarda = $co->prepare("SELECT * FROM `roles_modulos` INNER JOIN `modulos` ON roles_modulos.id_modulo = modulos.id INNER JOIN `roles` ON roles_modulos.id_rol = roles.id WHERE modulos.nombre = ? AND roles_modulos.id_rol = ?");
		$guarda->execute([$modulo, $id_rol]);
		$fila = array();
		$fila = $guarda->fetch(PDO::FETCH_NUM);
		return $fila;
	}


	PUBLIC function incluir_s($titulo, $descripcion, $create_by){
		
		$this->set_titulo($titulo);
		$this->set_descripcion($descripcion);
		$this->set_create_by($create_by);

		return $this->incluir();
	}
	PUBLIC function modificar_s($id, $titulo, $descripcion){
		$this->set_id($id);
		$this->set_titulo($titulo);
		$this->set_descripcion($descripcion);
		return $this->modificar();
	}
	PUBLIC function eliminar_s($id){
		$this->set_id($id);
		return $this->eliminar();
	}
	PUBLIC function incluir_comentario_s($post_id, $comentario, $create_by){
		$this->set_post_id($post_id);
		$this->set_comentario($comentario);
		$this->set_create_by($create_by);
		return $this->incluir_comentario();
	}

	PUBLIC function cambiar_voto_s($post_id, $habitante_id, $voto)
	{

		$this->set_post_id($post_id);
		$this->set_habitante_id($habitante_id);
		$this->set_voto($voto);
		return $this->cambiar_Voto();
	}





	PRIVATE function incluir(){
		$r = array();
		try {
			$consulta = $this->con->prepare("INSERT INTO foro (titulo, descripcion, create_by) VALUES(?,?,?)");
			$consulta->execute([$this->titulo, $this->descripcion , $this->create_by]);
			$id = $this->con->lastInsertId();
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Registro Incluido';
			$bitacora = new Bitacora($this->con);
			$bitacora->b_registro("Registró el nuevo foro \"$id\"");

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

			$bitacora = new Bitacora($this->con);
			$bitacora->b_registro("Modificó el foro \"$this->id\"");


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
				
				$bitacora = new Bitacora($this->con);
				$bitacora->b_registro("Eliminó el foro \"$this->id\"");
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
			if(isset($this->create_by)){
				$respuesta = $this->con->prepare("SELECT * FROM `foro` where create_by = ? ORDER BY fecha DESC;");
				$respuesta->execute([$this->create_by]);
			}
			else{
				$respuesta = $this->con->prepare("SELECT * FROM `foro` ORDER BY fecha DESC;");
				$respuesta->execute();

			}
			$r['resultado'] = 'listaForo';
			$r['mensaje'] =  $respuesta->fetchall(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage()." xxxx  ".$this->create_by;
		}
		return $r;

	}

	PUBLIC function listaPost($postId, $habitante_id)
	{
		try {
			$this->post_id = $postId;
			$this->habitante_id = $habitante_id;

			if(isset($this->habitante_id)){
				$respuesta = $this->con->query("SELECT foro.id,titulo,descripcion,fecha,create_by,h.nombres,h.apellidos FROM `foro` INNER JOIN habitantes as h ON h.id = create_by WHERE foro.id = '$postId';")->fetch(PDO::FETCH_ASSOC);
				$voto = $this->con->query("
					SELECT v.* FROM votos as v 
					LEFT JOIN apartamento as a
						ON a.id_apartamento = v.id_apartamento
					WHERE v.id_foro = $postId AND (a.inquilino = $this->habitante_id OR a.propietario = $this->habitante_id) LIMIT 1");
				if($voto = $voto->fetch(PDO::FETCH_ASSOC)){ // si el usuario ha votado en el foro o no
					$respuesta["voto"] = $voto["voto"];
				}

			}
			else{
				//$respuesta = $this->con->query("SELECT foro.id,titulo,descripcion,fecha,create_by,h.nombres,h.apellidos FROM `foro` INNER JOIN habitantes as h ON h.id = create_by WHERE foro.id = '$postId';")->fetch(PDO::FETCH_ASSOC);
				$respuesta = $this->con->query("SELECT foro.id,aprobado,visto,votaciones, titulo, descripcion, fecha, create_by, h.nombres, h.apellidos, voto_positivo, voto_negativo FROM `foro` INNER JOIN habitantes AS h ON h.id = create_by LEFT JOIN (SELECT id_foro as votos_id,COUNT(IF(voto = 1,1,null)) AS voto_positivo,COUNT(IF(voto = 0,1,null)) as voto_negativo FROM votos WHERE id_foro = $postId ) as v ON v.votos_id = foro.id WHERE foro.id = $postId;")->fetch(PDO::FETCH_ASSOC);
			}
			$numero_votos = $this->con->query("SELECT voto FROM `foro` INNER JOIN votos ON id_foro = '$postId' WHERE id = '$postId';")->fetchAll(PDO::FETCH_ASSOC);
			$r['resultado'] = 'listaPost';
			$r['mensaje'] =  $respuesta;
			$r['numero_votos'] =  count($numero_votos);
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PRIVATE function incluir_comentario()
	{
		$r = array();
		try {

			$consulta = $this->con->prepare("INSERT INTO comentarios (id_post, comentario, create_by) VALUES(?,?,?)");
			$consulta->execute([$this->post_id, $this->comentario, $this->create_by]);
			$r['resultado'] = 'incluirComentario';
			$r['mensaje'] =  'Registro Incluido';
			$b = new Bitacora($this->con);
			$b->b_registro("Escribió un comentario en foro \"$this->post_id\"");
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function listaComentarios($postId)
	{
		try {
			
			$respuesta = $this->con->prepare("SELECT *,h.nombres,h.apellidos FROM `comentarios` INNER JOIN `habitantes` as h ON create_by = h.id WHERE id_post = ? ORDER BY comentarios.id DESC");
			$respuesta->execute([$postId]);
			$respuesta = $respuesta->fetchall(PDO::FETCH_ASSOC);
			$r['resultado'] = 'listaComentarios';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}


	PRIVATE function cambiar_Voto()
	{
		try {
			$consulta = $this->con->prepare("SELECT DISTINCT a.id_apartamento FROM apartamento AS a WHERE a.propietario = :id_habitante OR a.inquilino = :id_habitante;");
			$consulta->bindValue(":id_habitante", $this->habitante_id);
			$consulta->execute();
			$respuesta = $consulta;


			




			while ($temp = $respuesta->fetch(PDO::FETCH_ASSOC)) {
				$consulta = $this->con->prepare("INSERT INTO votos (id_foro,id_apartamento,voto) VALUES(:post_id,:aptoId,:voto) ON DUPLICATE KEY UPDATE voto = :voto;");
				$consulta->bindValue(":post_id",$this->post_id);
				$consulta->bindValue(":aptoId",$temp["id_apartamento"]);
				$consulta->bindValue(":voto",$this->voto);
				$consulta->execute();

				$bitacora = new Bitacora($this->con);
				$bitacora->b_registro("Realizo un voto en el foro \"$this->post_id\"");

			}
			$consulta = $this->con->query("SELECT COUNT(a.id_apartamento) AS total_apto FROM apartamento AS a WHERE 1")->fetch(PDO::FETCH_ASSOC);
			$total = intval($consulta["total_apto"]);
			$porcentaje = 50;

			$total = $total*($porcentaje / 100);


			$consulta = $this->con->prepare("SELECT IF( COUNT(IF( v.voto = 1,1,NULL )) >= :mitad_apartamentos, 1, 0 ) as mayor_positivos FROM votos AS v WHERE v.id_foro = :foro;");
			$consulta->bindValue(":mitad_apartamentos", $total);
			$consulta->bindValue(":foro", $this->post_id);
			$consulta->execute();

			$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

			if($consulta["mayor_positivos"] == 1){
				$consulta = $this->con->prepare("UPDATE foro SET aprobado = 1 WHERE id = ?");
			}
			else{
				$consulta = $this->con->prepare("UPDATE foro SET aprobado = 0 WHERE id = ?");

			}
			$consulta->execute([$this->post_id]);

			




			$r['resultado'] = 'cambiarVoto';
			
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	PUBLIC function cambiar_estados($control,$value,$post_id){
		try {
			$this->set_post_id($post_id);
			$this->validar_conexion($this->con);
			$this->con->beginTransaction();

			if($control == "visto"){
				$consulta = $this->con->prepare("UPDATE foro SET visto = ? WHERE id = ?");
			}
			else if ($control == "votaciones"){
				$consulta = $this->con->prepare("UPDATE foro SET votaciones = ? WHERE id = ?");
			}
			else{
				throw new Exception("control error Invalid", 1);
			}

			if(!($value == 1 or $value == 0)){
				throw new Exception("value error Invalid ($value)", 1);
			}

			$consulta->execute([$value ,$this->post_id]);

			
			$r['resultado'] = 'cambiar_estado';
			$r['mensaje'] =  $control;
			$r['mensaje'] =  array('control' => $control,'value' => $value );;
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
		finally{
			//$this->con = null;
		}
		return $r;
	}



	PUBLIC function get_con(){
		return $this->con;
	}
	PUBLIC function set_con($value){
		$this->con = $value;
	}
	PUBLIC function get_id(){
		return $this->id;
	}
	PUBLIC function set_id($value){
		$this->id = $value;
	}
	PUBLIC function get_comentario(){
		return $this->comentario;
	}
	PUBLIC function set_comentario($value){
		$this->comentario = $value;
	}
	PUBLIC function get_post_id(){
		return $this->post_id;
	}
	PUBLIC function set_post_id($value){
		$this->post_id = $value;
	}
	PUBLIC function get_create_by(){
		return $this->create_by;
	}
	PUBLIC function set_create_by($value){
		$this->create_by = $value;
	}
	PUBLIC function get_apto_id(){
		return $this->apto_id;
	}
	PUBLIC function set_apto_id($value){
		$this->apto_id = $value;
	}
	PUBLIC function get_voto(){
		return $this->voto;
	}
	PUBLIC function set_voto($value){
		$this->voto = $value;
	}
	PUBLIC function get_habitante_id(){
		return $this->habitante_id;
	}
	PUBLIC function set_habitante_id($value){
		$this->habitante_id = $value;
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
}

?>