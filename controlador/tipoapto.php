<?php
if (!is_file("model/" . $p . ".php")) {

  echo "Falta definir la clase " . $p;
  exit;
}

require_once("model/" . $p . ".php");

if (is_file("vista/" . $p . ".php")) {
  $o = new tipoapto();
  $permisos = $o->chequearpermisos();
  if (!empty($_POST)) {
    $accion = $_POST['accion'];
    if ($accion == 'listado_tipos') {
      $respuesta = $o->listadotipos();
      echo json_encode($respuesta);
    } elseif ($accion == 'incluir') {
      $respuesta = $o->incluir($_POST['descripcion'], $_POST['alicuota']);
      echo json_encode($respuesta);
    }
    elseif ($accion == 'modificar') {
      $respuesta = $o->modificar($_POST['id_tipo_apartamento'],$_POST['descripcion'], $_POST['alicuota']);
      echo json_encode($respuesta);
    }
    elseif ($accion == 'eliminar') {
      $respuesta = $o->eliminar($_POST['id_tipo_apartamento']);
      echo json_encode($respuesta);
    }
    exit;
  }
  $b_temp = new Bitacora;
  $b_temp->b_registro("Ingreso en el modulo \"Tipo de apartamento\"");
  require_once("vista/" . $p . ".php");
} else {
  require_once("vista/404.php");
}
