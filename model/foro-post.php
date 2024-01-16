<?php
require_once("model/datos.php");
require_once("model/bitacora.php");

class ForoPost extends datos
{
	private $con, $id, $comentario, $post_id, $create_by, $apto_id, $voto, $habitante_id;
	function __construct()
	{
		$this->con = $this->conecta();
		$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function chequearpermisos()
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

	public function incluir_s()
	{
		return $this->incluirComentario();
	}
	public function cambiar_voto_s()
	{
		return $this->cambiarVoto();
	}

	public function listaPost()
	{
		try {
			$postId = $this->post_id;
			$aptoId = $this->apto_id;

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

	private function incluirComentario()
	{
		$r = array();
		try {
			$consulta = $this->con->prepare("INSERT INTO comentarios (id_post, comentario, create_by) VALUES(?,?,?)");
			$consulta->execute([$this->post_id, $this->comentario, $this->create_by]);
			$r['resultado'] = 'incluirComentario';
			$r['mensaje'] =  'Registro Incluido';
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	public function listaComentarios()
	{
		try {
			$postId = $this->post_id;
			$respuesta = $this->con->query("SELECT *,h.nombres,h.apellidos FROM `comentarios` INNER JOIN `habitantes` as h ON create_by = h.id WHERE id_post = '$postId' ORDER BY comentarios.id DESC")->fetchAll(PDO::FETCH_ASSOC);
			$r['resultado'] = 'listaComentarios';
			$r['mensaje'] =  $respuesta;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	private function cambiarVoto()
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
				//$consulta->execute([$this->post_id, $temp["id_apartamento"], $this->voto]);

			}
			$consulta = $this->con->query("SELECT COUNT(a.id_apartamento) AS total_apto FROM apartamento AS a WHERE 1")->fetch(PDO::FETCH_ASSOC);
			$total = intval($consulta["total_apto"]);

			$total = $total/2;


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

	public function cambiar_estados($control,$value){
		try {
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



	public function get_id()
	{
		return $this->id;
	}
	public function set_id($value)
	{
		$this->id = $value;
	}
	public function get_voto()
	{
		return $this->voto;
	}
	public function set_voto($value)
	{
		$this->voto = $value;
	}
	public function get_apto_id()
	{
		return $this->apto_id;
	}
	public function set_apto_id($value)
	{
		$this->apto_id = $value;
	}
	public function get_post_id()
	{
		return $this->post_id;
	}
	public function set_post_id($value)
	{
		$this->post_id = $value;
	}
	public function get_comentario()
	{
		return $this->comentario;
	}
	public function set_comentario($value)
	{
		$this->comentario = $value;
	}
	public function get_create_by()
	{
		return $this->create_by;
	}
	public function set_create_by($value)
	{
		$this->create_by = $value;
	}
	PUBLIC function get_habitante_id(){
		return $this->habitante_id;
	}
	PUBLIC function set_habitante_id($value){
		$this->habitante_id = $value;
	}
}
