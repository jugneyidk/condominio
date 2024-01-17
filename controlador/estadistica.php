<?php
if (!is_file("model/" . $p . ".php")) {
  echo "Falta definir la clase " . $p;
  exit;
}
require_once("model/" . $p . ".php");
  //verifica que exista la vista de
  //la pagina
  if(is_file("vista/".$p.".php")){
	  //si existe se la trae, ahora ve a la carpeta vista
	  //y busca el archivo principal.php 
	  require_once("vista/".$p.".php"); 
  } 
  else{
	  require_once("vista/404.php"); 
  }
?>