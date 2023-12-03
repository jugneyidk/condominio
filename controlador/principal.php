<?php
if (!is_file("model/" . $p . ".php")) {
  echo "Falta definir la clase " . $p;
  exit;
}
require_once("model/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
  $o = new principal();
  if (!empty($_POST)) {
    $accion = $_POST['accion'];
    if ($accion == 'datos_barra') {
      $respuesta = $o->datos_barra();
      echo json_encode($respuesta);
    } else if($accion == "ultimos_pagos"){
      $respuesta = $o->ultimos_pagos();
      echo json_encode($respuesta);
    }
    exit;
  }
  if (!isset($_SESSION['id_usuario']) and !isset($_SESSION["id_habitante"])) {
    $avisos = $o->mostrar_avisos();
  }

  require_once("vista/" . $p . ".php");
} else {
  require_once("vista/404.php");
}
