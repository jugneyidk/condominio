<?php
if (!is_file("model/" . $p . ".php")) {

  echo "Falta definir la clase " . $p;
  exit;
}
require_once("model/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
  $o = new nomina();
  $permisos = $o->chequearpermisos();
  if (!empty($_POST)) {
    $accion = $_POST['accion'];
   if ($accion == 'incluir') {
   $respuesta = $o->incluir(
        $_POST['cedula_rif'], 
        $_POST['tipo_identificacion'],
        $_POST['nombres'],
        $_POST['apellidos'],
        $_POST['domicilio_fiscal'],
        $_POST['telefono'],
        $_POST['correo'],
        $_POST['descripcion'],
        $_POST['metodo'],
        $_POST['fecha'],
        $_POST['monto'],
        $_POST['referencia']
      );
     echo json_encode($respuesta);
    } /*else if ($accion == 'modificar') {
      $respuesta = $o->modificar(
        $_POST['nombres'],
        $_POST['apellidos'],
        $_POST['cedula_rif'],
        $_POST['domicilio_fiscal'],
        $_POST['telefono'],
        $_POST['correo'],
         $_POST['descripcion'],
        $_POST['metodo'],
        $_POST['fecha'],
        $_POST['monto'],
        $_POST['referencia']
      );
      //echo json_encode($respuesta);
    } else*/ if ($accion == 'eliminar') {
     // $respuesta = $o->eliminar($_POST['id']);
     // echo json_encode($respuesta);
    } else if ($accion == 'listadonomina') {
      $respuesta = $o->listadonomina();
     echo json_encode($respuesta);
    }
    exit;
  }
  require_once("vista/" . $p . ".php");
} else {
  echo "pagina en construccion";
}
