<?php 
$p = "principal"; 
session_start();
 if (!empty($_GET['p'])){
   $p = $_GET['p'];
   $p_verificar=[
   		"principal",
   		"login",
   		"consulta",
   		"detallesdeuda"
   	];
   //if (!isset($_SESSION['id_usuario']) && ($p != "principal" && $p != "login" && $p != "consulta" && $p != "detallesdeuda")) {
   if (!isset($_SESSION['id_usuario']) && !in_array($p, $p_verificar)) {
     header("Location: ?p=login");
   }
 }
 if(is_file("controlador/".$p.".php")){ 
    require_once("controlador/".$p.".php");
 }
 else{
   require_once("vista/404.php"); 
 }
?> 