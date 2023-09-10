<?php
if (!is_file("model/" . $p . ".php")) {

  echo "Falta definir la clase " . $p;
  exit;
}

require_once("model/" . $p . ".php");

if (is_file("vista/" . $p . ".php")) {
  $o = new deuda();
  $permisos = $o->chequearpermisos();
  $_SESSION['permisos'] = $permisos;  
  if (!empty($_POST)) {
    $accion = $_POST['accion'];
    if ($accion == 'listadodeudas') {
      $respuesta = $o->listadodeudas();
      echo json_encode($respuesta);
    } else if($accion == 'listadomorosos'){
      $respuesta = $o->listadomorosos();
      echo json_encode($respuesta);
    } else if($accion == 'registrarpago'){
      $respuesta = $o->registrarpago($_POST['id_deuda'],$_POST['monto'], $_POST['referencia'],$_POST['fecha'],$_POST['tipo_pago']);
      echo json_encode($respuesta);
    }
    exit;
  }

  require_once("vista/" . $p . ".php");
} else {
  echo "pagina en construccion";
}
