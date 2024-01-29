<?php
require_once("model/generar-reporte.php");
require_once("model/bitacora.php");

  //verifica que exista la vista de
  //la pagina
  if(is_file("vista/".$p.".php")){
	  //si existe se la trae, ahora ve a la carpeta vista
	  //y busca el archivo principal.php 
    $o = new generarreporte;
    $morososData = $o->getMorososData();
    $b_temp = new Bitacora;
    $b_temp->b_registro("Ingreso en el modulo \"".$_GET["p"]."\"");
	  require_once("vista/".$p.".php"); 
  } 
  else{
	  require_once("vista/404.php"); 
  }
?>