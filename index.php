<?php 
$p = "principal"; 
session_start();
 if (!empty($_GET['p'])){
   $p = $_GET['p'];
   // $excepciones_p=[
   // 		"principal",
   // 		"login",
   // 		"consulta",
   // 		"detallesdeuda"
   // 	];
   //if (!isset($_SESSION['id_usuario']) && ($p != "principal" && $p != "login" && $p != "consulta" && $p != "detallesdeuda")) {
   // if (!isset($_SESSION['id_usuario']) && !in_array($p, $excepciones_p)) {
   //   $p="login";
   // }
 }

 $excepciones_p=[
    "principal",
    "login",
    "consulta",
    "detallesdeuda",
    "foro-index-h",
    "foro-post-h",
    "ventana-pruevas",
    "reset-pass"
  ];
  if(!isset($_SESSION['id_usuario']) and !in_array($p, $excepciones_p)){
    $p="login";
  }
 if(is_file("controlador/".$p.".php")){
    
    require_once("controlador/verificador.php");
    require_once("controlador/".$p.".php");

 }
 else{
   require_once("vista/404.php"); 
 }
?> 