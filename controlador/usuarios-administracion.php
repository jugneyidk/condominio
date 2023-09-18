<?php
if (!is_file("model/" . $p . ".php")) {

  echo "Falta definir la clase " . $p;
  exit;
}

require_once("model/" . $p . ".php");

if (is_file("vista/" . $p . ".php")) {
  $o = new usuarios();
  $permisos = $o->chequearpermisos();
  if (!empty($_POST)) {
    $accion = $_POST['accion'];
    if ($accion == 'listado_usuarios') {
      $respuesta = $o->listadousuarios();
      echo json_encode($respuesta);
    } elseif ($accion == 'incluir') {
      $respuesta = $o->incluir($_POST['rif_cedula'], $_POST['tipo_identificacion'], $_POST['razon_social'], $_POST['domicilio_fiscal'], $_POST['telefono'], $_POST['correo'], $_POST['password'], $_POST['rol']);
      echo json_encode($respuesta);
    } elseif ($accion == 'modificar') {
      $respuesta = $o->modificar($_POST['id'], $_POST['rif_cedula'], $_POST['tipo_identificacion'], $_POST['razon_social'], $_POST['domicilio_fiscal'], $_POST['telefono'], $_POST['correo'], $_POST['password'], $_POST['rol']);
      echo json_encode($respuesta);
    } elseif ($accion == 'eliminar') {
      $respuesta = $o->eliminar($_POST['id']);
      echo json_encode($respuesta);
    }
    exit;
  }
  if ($_SESSION['rol'] == 2) {
    require_once("vista/" . $p . ".php");
  }else {
    header("Location: . ");
  }
} else {
  require_once("vista/404.php");
}
