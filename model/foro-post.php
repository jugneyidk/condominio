<?php
require_once("model/datos.php");
require_once("model/bitacora.php");

class ForoPost extends datos
{
	private $con, $id, $comentario, $post_id, $create_by, $apto_id, $voto;
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
			$respuesta = $this->con->query("SELECT foro.id,titulo,descripcion,fecha,create_by,h.nombres,h.apellidos,voto FROM `foro` LEFT JOIN votos ON id_foro = '$postId' AND id_apartamento = '$aptoId' INNER JOIN habitantes as h ON h.id = create_by WHERE foro.id = '$postId';")->fetch(PDO::FETCH_ASSOC);
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
			if (!$this->existeVoto()) {
				$consulta = $this->con->prepare("INSERT INTO votos (id_foro, id_apartamento, voto) VALUES(?,?,?)");
				$consulta->execute([$this->post_id, $this->apto_id, $this->voto]);
			} else {
				$consulta = $this->con->prepare("UPDATE `votos` SET `voto`= ?  WHERE `id_foro` = ? AND `id_apartamento` = ?");
				$consulta->execute([$this->voto, $this->post_id, $this->apto_id]);
				$consulta->execute();
			}
			$r['resultado'] = 'cambiarVoto';
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	private function existeVoto()
	{
		try {
			$postId = $this->post_id;
			$aptoId = $this->apto_id;
			$respuesta = $this->con->query("SELECT * FROM `votos` WHERE id_foro = '$postId' AND id_apartamento = '$aptoId'")->fetch(PDO::FETCH_ASSOC);
			if (!$respuesta) {
				return false;
			} else {
				return true;
			}
		} catch (Exception $e) {
			return $e;
		}
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
}
