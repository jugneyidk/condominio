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
	private $c;
	public function __construct()
	{
		$this->c = $this->conecta();
		$this->c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function b_incluir($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user, descrip) values (?,?)");
			$descrip= "Registro en ".$_GET["p"];
			$descrip.=" ".$string;
			$consult->execute([$_SESSION['id_usuario'], $descrip]);
		} finally{

		}
	}

	public function b_modificar($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user, descrip) values (?,?)");
			$descrip= "Modificación en ".$_GET["p"];
			$descrip.=" ".$string;
			$consult->execute([$_SESSION['id_usuario'], $descrip]);
		} finally{

		}
	}

	public function b_eliminar($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user, descrip) values (?,?)");
			$descrip= "Eliminación en ".$_GET["p"];
			$descrip.=" ".$string;
			$consult->execute([$_SESSION['id_usuario'], $descrip]);
		} finally{

		}
	}
	public function b_accion($string = ""){
		try {
			$consult=$this->c->prepare("INSERT INTO bitacora (user, descrip) values (?,?)");
			$descrip= $string.$_GET["p"];
			$consult->execute([$_SESSION['id_usuario'], $descrip]);
		} finally{

		}
	}

}
?>