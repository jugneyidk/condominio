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
	PRIVATE $c,$usuario_id,$tipo;
	PUBLIC function __construct()
	{
		$habitante = [
		  "detallesdeuda",
		  "foro-index-h",
		  "foro-post-h",
		  "foroAction"
		];
		if(!empty($_GET["p"])){
			$p = $_GET['p'];
		}

		$this->c = $this->conecta();
		$this->c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if(!empty($_GET['p']) and in_array($p, $habitante)){
			$this->usuario_id = $_SESSION['id_habitante'];
			$this->tipo = 0;
		}
		else if(isset($_SESSION["id_usuario"])){
			$this->usuario_id = $_SESSION['id_usuario'];
			$this->tipo = 1;	
		}
		else{
			$this->usuario_id = null;
			$this->tipo = null;
		}
	}

	PUBLIC function b_incluir($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user_id,user_type, descrip) values (?,?,?)");
			$descrip= "Registro en ".$_GET["p"];
			$descrip.=" ".$string;
			$consult->execute([$this->usuario_id,$this->tipo, $descrip]);
		} finally{

		}
	}

	PUBLIC function b_modificar($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user_id, user_type, descrip) values (?,?,?)");
			$descrip= "Modificación en ".$_GET["p"];
			$descrip.=" ".$string;
			$consult->execute([$this->usuario_id,$this->tipo, $descrip]);
		} finally{

		}
	}

	PUBLIC function b_eliminar($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user_id,user_type, descrip) values (?,?,?)");
			$descrip= "Eliminación en ".$_GET["p"];
			$descrip.=" ".$string;
			$consult->execute([$this->usuario_id,$this->tipo, $descrip]);
		} finally{

		}
	}
	PUBLIC function b_accion($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user_id,user_type, descrip) values (?,?,?)");
			$descrip= $string." ".$_GET["p"];
			$consult->execute([$this->usuario_id,$this->tipo, $descrip]);
		} finally{

		}
	}
	PUBLIC function b_registro($string = ""){
		try {
			$consult = $this->c->prepare("INSERT INTO bitacora (user_id,user_type, descrip) values (?,?,?)");
			$descrip = $string;
			$consult->execute([$this->usuario_id,$this->tipo, $descrip]);
		} finally{

		}
	}

	PUBLIC function load_bitacora(){
		try {
			$this->validar_conexion($this->c);
			$this->c->beginTransaction();
			//$consulta = $this->c->query("SELECT IF(u.razon_social IS NULL,'Sistema',u.razon_social) AS user,u.rif_cedula,u.tipo_identificacion, `descrip`,`fecha` FROM `bitacora` AS b JOIN datos_usuarios as u ON b.user = u.id WHERE 1;");
// 			$consulta = $this->c->query("SELECT
//     IF(
//         u.razon_social IS NULL,
//         'Sistema',
//         u.razon_social
//     ) AS user,
//     u.rif_cedula,
//     u.tipo_identificacion,
//     `descrip`,
//     `fecha`
// FROM
//     `bitacora` AS b
// LEFT JOIN datos_usuarios AS u
// ON
//     b.user = u.id
// WHERE 1 ORDER BY b.fecha DESC;");

			$consulta = $this->c->query("SELECT 
	u.razon_social AS user,
    u.rif_cedula,
    u.tipo_identificacion,
    `descrip`,
    `fecha`,
    `user_type`
    FROM `bitacora` AS b0 LEFT JOIN `datos_usuarios` as u on b0.user_id = u.id WHERE user_type = 1
UNION
SELECT 
	CONCAT(u.nombres,' ',u.apellidos) AS user,
    u.cedula_rif as rif_cedula, 
    u.tipo_identificacion,
    `descrip`,
    `fecha`,
    `user_type`
	
	FROM `bitacora` AS b1 LEFT JOIN habitantes as u on b1.user_id = u.id WHERE user_type = 0
UNION
SELECT 
	'Sistema' as user,
    null as 'rif_cedula',
    null as 'tipo_identificacion',
    `descrip`,
    `fecha`,
    `user_type`
    
FROM `bitacora` AS b2 LEFT JOIN `habitantes` as u on b2.user_id = u.id WHERE user_type IS NULL
ORDER BY fecha DESC");

			$consulta = $consulta->fetchall(PDO::FETCH_ASSOC);
			$dom = new DOMDocument();
			foreach ($consulta as $elem) {
				$tr = $dom->createElement("tr");
				if($elem['user'] != 'Sistema'){
					if($elem["user_type"] != null){
						if($elem["tipo_identificacion"] != null){
							$nueva_cedula = TIPO_INDENT_ARRAY[$elem["tipo_identificacion"]].$elem["rif_cedula"];
							$nueva_cedula .= ($elem["user_type"] == 1)?" (Usuario)":" (Habitante)";
							$tr->setAttribute("title", $nueva_cedula);
						}
						else{
							$texto = ($elem["user_type"] == 1)?"USUARIO":"HABITANTE";
							$texto.= " ELIMINADO";
							$elem["user"] = $texto;
						}
					}
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
	PUBLIC function get_tipo(){
		return $this->tipo;
	}
	PUBLIC function set_tipo($value){
		$this->tipo = $value;
	}

}
?>