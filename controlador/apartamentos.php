<?php
if (!is_file("model/" . $p . ".php")) {

  echo "Falta definir la clase " . $p;
  exit;
}
require_once("model/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
  $o = new apto();
  $permisos = $o->chequearpermisos();
  if (!empty($_POST)) {
    $accion = $_POST['accion'];
    if ($accion == 'listadoapartamentos') {
      $respuesta = $o->listadoapartamentos();
      echo json_encode($respuesta);
    } else if ($accion == 'listadohabitantes') {
      $respuesta = $o->listadohabitantes();
      echo json_encode($respuesta);
    } else if ($accion == 'incluir') {
      $respuesta = $o->incluir_s(
        $_POST['torre'],
        $_POST['piso'],
        $_POST['numapto'],
        $_POST['tipoapto'],
        $_POST['propietario'],
        $_POST['inquilino']
      );
      echo json_encode($respuesta);
    } else if ($accion == 'modificar') {
      $respuesta = $o->modificar_s(
        $_POST['id'],
        $_POST['numapto'],
        $_POST['propietario'],
        $_POST['inquilino'],
        $_POST['torre'],
        $_POST['piso'],
        $_POST['tipoapto']
      );
      echo json_encode($respuesta);
    } else if ($accion == 'eliminar') {
      $respuesta = $o->eliminar_s($_POST['id']);
      echo json_encode($respuesta);
    } else if ($accion == 'listadotipo') {
      $respuesta = $o->listadotipos();
      echo json_encode($respuesta);
    }
    exit;
  }
  require_once("vista/" . $p . ".php");
} else {
    require_once("vista/404.php"); 
}
