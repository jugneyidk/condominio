<?php
if (!is_file("model/" . $p . ".php")) {

  echo "Falta definir la clase " . $p;
  exit;
}
require_once("model/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
  $o = new servicio();
  $permisos = $o->chequearpermisos();
  if (!empty($_POST)) {
    $accion = $_POST['accion'];
   /* if ($accion == '') {
      $respuesta = $o->listadoapartamentos();
      echo json_encode($respuesta);
    } else if ($accion == '') {
      $respuesta = $o->listadohabitantes();
      echo json_encode($respuesta);
    } else */if ($accion == 'incluir') {
        $o->set_servicio($_POST['service']);
        $o->set_descripcion($_POST['descripcion']);
        $o->set_fecha($_POST['fecha']);
        $o->set_monto($_POST['monto']);
        $o->set_referencia($_POST['referencia']);


      $respuesta = $o->incluir();

      echo json_encode($respuesta);
    } /*else if ($accion == 'modificar') {
      $respuesta = $o->modificar(
        $_POST['servicio'],
        $_POST['descripcion'],
        $_POST['fecha'],
        $_POST['monto'],
        $_POST['referencia']
      );
      //echo json_encode($respuesta);
    } else*/ if ($accion == 'eliminar') {
     // $respuesta = $o->eliminar($_POST['id']);
     // echo json_encode($respuesta);
    } else if ($accion == 'listado') {
      //$respuesta = $o->listadotipos();
     // echo json_encode($respuesta);
    }
    exit;
  }
  require_once("vista/" . $p . ".php");
} else {
  echo "pagina en construccion";
}
