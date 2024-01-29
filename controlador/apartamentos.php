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
      //$o->set_id_apartamento();
      $o->set_num_letra_apartamento($_POST['numapto']);
      $o->set_propietario($_POST['propietario']);
      $o->set_inquilino($_POST['inquilino']);
      $o->set_torre($_POST['torre']);
      $o->set_piso($_POST['piso']);
      $o->set_tipo_apartamento($_POST['tipoapto']);
      $respuesta = $o->incluir_s();

      echo json_encode($respuesta);
    } else if ($accion == 'modificar') {
      
      $o->set_id_apartamento($_POST['id']);
      $o->set_num_letra_apartamento($_POST['numapto']);
      $o->set_propietario($_POST['propietario']);
      $o->set_inquilino($_POST['inquilino']);
      $o->set_torre($_POST['torre']);
      $o->set_piso($_POST['piso']);
      $o->set_tipo_apartamento($_POST['tipoapto']);

      $respuesta = $o->modificar_s();
      echo json_encode($respuesta);
    } else if ($accion == 'eliminar') {

      $o->set_id_apartamento($_POST['id']);
      $respuesta = $o->eliminar_s();

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
