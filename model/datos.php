<?php 
require_once ("model/validaciones.php");
require_once ("model/const.php");
class datos{
    // DATOS DE LA DB
	PRIVATE $ip = BD_IP;// constantes definidas en model/const.php
    PRIVATE $bd = BD_NAME;
    PRIVATE $usuario = BD_USER;
    PRIVATE $contrasena = BD_PASS;
    // FUNCION PARA ESTABLECER CONEXION
    PUBLIC function conecta(){
        try {
            
            $pdo = new PDO("mysql:host=".$this->ip.";dbname=".$this->bd."",$this->usuario,$this->contrasena);
             $pdo->exec("set names utf8");
             return $pdo;
            } catch (Exception $e) {
                return $e->getMessage();
            }
    }   
    PUBLIC function validar_conexion($pdo){
        if(!($pdo instanceof PDO)){
            throw new Exception($pdo, 1);
            
        }

    }
} 
?>