<?php
if (!is_file("model/" . $p . ".php")) {
  echo "Falta definir la clase " . $p;
  exit;
}
require_once("model/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
  $o = new habitantes();
  $permisos = $o->chequearpermisos();
  if (!empty($_POST)) {
    $accion = $_POST['accion'];
    if ($accion == 'listado_habitantes') {
      $respuesta = $o->listadohabitantes();
      echo json_encode($respuesta);
    }  elseif ($accion == 'incluir') {
      $respuesta = $o->incluir($_POST['cedula_rif'], $_POST['tipo_identificacion'],$_POST['nombres'],$_POST['apellidos'],$_POST['telefono'],$_POST['correo'],$_POST['domicilio_fiscal']);
      echo json_encode($respuesta);
    }
    elseif ($accion == 'modificar') {
      $respuesta = $o->modificar($_POST['id'], $_POST['cedula_rif'], $_POST['tipo_identificacion'],$_POST['nombres'],$_POST['apellidos'],$_POST['telefono'],$_POST['correo'],$_POST['domicilio_fiscal']);
      echo json_encode($respuesta);
    }
    elseif ($accion == 'eliminar') {
      $respuesta = $o->eliminar($_POST['id']);
      echo json_encode($respuesta);
    }
    exit;
  }
  require_once("vista/" . $p . ".php");
} else {
  require_once("vista/404.php");
}
