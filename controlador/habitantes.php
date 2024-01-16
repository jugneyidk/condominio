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

//      $o->set_id();
      $o->set_clave($_POST["clave"]);
      $o->set_cedula_rif($_POST['cedula_rif']);
      $o->set_tipo_identificacion($_POST['tipo_identificacion']);
      $o->set_nombres($_POST['nombres']);
      $o->set_apellidos($_POST['apellidos']);
      $o->set_telefono($_POST['telefono']);
      $o->set_correo($_POST['correo']);
      $o->set_domicilio_fiscal($_POST['domicilio_fiscal']);


      $respuesta = $o->incluir();
      echo json_encode($respuesta);
    }
    elseif ($accion == 'modificar') {

      $o->set_id($_POST['id']);
      $o->set_clave($_POST["clave"]);
      $o->set_cedula_rif($_POST['cedula_rif']);
      $o->set_tipo_identificacion($_POST['tipo_identificacion']);
      $o->set_nombres($_POST['nombres']);
      $o->set_apellidos($_POST['apellidos']);
      $o->set_telefono($_POST['telefono']);
      $o->set_correo($_POST['correo']);
      $o->set_domicilio_fiscal($_POST['domicilio_fiscal']);



      $respuesta = $o->modificar();
      echo json_encode($respuesta);
    }
    elseif ($accion == 'eliminar') {
      $o->set_id($_POST['id']);
      $respuesta = $o->eliminar();
      echo json_encode($respuesta);
    }
    exit;
  }
  require_once("vista/" . $p . ".php");
} else {
  require_once("vista/404.php");
}
