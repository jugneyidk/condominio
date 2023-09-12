<?php 
class datos{
    // DATOS DE LA DB
	PRIVATE $ip = "localhost";
    PRIVATE $bd = "condominio_jmv02";
    PRIVATE $usuario = "root";
    PRIVATE $contrasena = "";
    // FUNCION PARA ESTABLECER CONEXION
    PUBLIC function conecta(){
        $pdo = new PDO("mysql:host=".$this->ip.";dbname=".$this->bd."",$this->usuario,$this->contrasena);
         $pdo->exec("set names utf8");
         return $pdo;
         }        
}      
?>