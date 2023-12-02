<?php

/**
 * 
require_once("model/bitacora.php");

$bitacora = new Bitacora();
$bitacora->b_incluir();

$bitacora = new Bitacora();
$bitacora->b_modificar();

$bitacora = new Bitacora();
$bitacora->b_eliminar();


 */
class Bitacora extends datos
{
	PRIVATE $c,$usuario_id;
	PUBLIC function __construct()
	{
		$this->c = $this->conecta();
		$this->c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->usuario_id = $_SESSION["id_usuario"];
	}

	PUBLIC function b_incluir($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user, descrip) values (?,?)");
			$descrip= "Registro en ".$_GET["p"];
			$descrip.=" ".$string;
			$consult->execute([$this->usuario_id, $descrip]);
		} finally{

		}
	}

	PUBLIC function b_modificar($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user, descrip) values (?,?)");
			$descrip= "Modificación en ".$_GET["p"];
			$descrip.=" ".$string;
			$consult->execute([$this->usuario_id, $descrip]);
		} finally{

		}
	}

	PUBLIC function b_eliminar($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user, descrip) values (?,?)");
			$descrip= "Eliminación en ".$_GET["p"];
			$descrip.=" ".$string;
			$consult->execute([$this->usuario_id, $descrip]);
		} finally{

		}
	}
	PUBLIC function b_accion($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user, descrip) values (?,?)");
			$descrip= $string." ".$_GET["p"];
			$consult->execute([$this->usuario_id, $descrip]);
		} finally{

		}
	}
	PUBLIC function b_accion_text($string = "",$user = null){
		try {
			$consult = $this->c->prepare("INSERT INTO bitacora (user, descrip) values (?,?)");
			$descrip = $string;
			$consult->execute([$user, $descrip]);
		} finally{

		}
	}
	PUBLIC function b_registro($string = ""){
		try {
			$consult = $this->c->prepare("INSERT INTO bitacora (user, descrip) values (?,?)");
			$descrip = $string;
			$consult->execute([$this->usuario_id, $descrip]);
		} finally{

		}
	}

	PUBLIC function load_bitacora(){
		try {
			$this->validar_conexion($this->c);
			$this->c->beginTransaction();
			//$consulta = $this->c->query("SELECT IF(u.razon_social IS NULL,'Sistema',u.razon_social) AS user,u.rif_cedula,u.tipo_identificacion, `descrip`,`fecha` FROM `bitacora` AS b JOIN datos_usuarios as u ON b.user = u.id WHERE 1;");
			$consulta = $this->c->query("SELECT
    IF(
        u.razon_social IS NULL,
        'Sistema',
        u.razon_social
    ) AS user,
    u.rif_cedula,
    u.tipo_identificacion,
    `descrip`,
    `fecha`
FROM
    `bitacora` AS b
LEFT JOIN datos_usuarios AS u
ON
    b.user = u.id
WHERE 1 ORDER BY b.fecha DESC;");

			$consulta = $consulta->fetchall(PDO::FETCH_ASSOC);
			$dom = new DOMDocument();
			foreach ($consulta as $elem) {
				$tr = $dom->createElement("tr");
				if($elem['user'] != 'Sistema'){
					$nueva_cedula = TIPO_INDENT_ARRAY[$elem["tipo_identificacion"]].$elem["rif_cedula"];
					$tr->setAttribute("title", $nueva_cedula);
				}
				$td = $dom->createElement("td",$elem["user"]);
				$tr->appendChild($td);
				$tr->appendChild($dom->createElement("td",$elem["descrip"]));
				$tr->appendChild($dom->createElement("td",$elem["fecha"]));
				$dom->appendChild($tr);
			}
			
			$r['resultado'] = 'load_bitacora';
			$r['mensaje'] =  $dom->saveHTML();
			$this->c->commit();
		
		} catch (Validaciones $e){
			if($this->c instanceof PDO){
				if($this->c->inTransaction()){
					$this->c->rollBack();
				}
			}
			$r['resultado'] = 'is-invalid';
			$r['mensaje'] =  $e->getMessage().": Code : ".$e->getLine();
		} catch (Exception $e) {
			if($this->c instanceof PDO){
				if($this->c->inTransaction()){
					$this->c->rollBack();
				}
			}
		
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage().": LINE : ".$e->getLine();
		}
		return $r;
	}

	PUBLIC function get_usuario_id(){
		return $this->usuario_id;
	}
	PUBLIC function set_usuario_id($value){
		$this->usuario_id = $value;
	}
	PUBLIC function get_c(){
		return $this->c;
	}
	PUBLIC function set_c($value){
		$this->c = $value;
	}

}
?>