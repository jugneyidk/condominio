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

// //      $o->set_id();
//       $o->set_clave();
//       $o->set_cedula_rif();
//       $o->set_tipo_identificacion();
//       $o->set_nombres();
//       $o->set_apellidos();
//       $o->set_telefono();
//       $o->set_correo();
//       $o->set_domicilio_fiscal();


      $respuesta = $o->incluir_s(
        $_POST["clave"],
        $_POST['cedula_rif'],
        $_POST['tipo_identificacion'],
        $_POST['nombres'],
        $_POST['apellidos'],
        $_POST['telefono'],
        $_POST['correo'],
        $_POST['domicilio_fiscal']
      );
      echo json_encode($respuesta);
    }
    elseif ($accion == 'modificar') {

      // $o->set_id();
      // $o->set_clave();
      // $o->set_cedula_rif();
      // $o->set_tipo_identificacion();
      // $o->set_nombres();
      // $o->set_apellidos();
      // $o->set_telefono();
      // $o->set_correo();
      // $o->set_domicilio_fiscal();



      $respuesta = $o->modificar_s(
        $_POST['id'],
        $_POST["clave"],
        $_POST['cedula_rif'],
        $_POST['tipo_identificacion'],
        $_POST['nombres'],
        $_POST['apellidos'],
        $_POST['telefono'],
        $_POST['correo'],
        $_POST['domicilio_fiscal']
      );
      echo json_encode($respuesta);
    }
    elseif ($accion == 'eliminar') {
      // $o->set_id($_POST['id']);
      $respuesta = $o->eliminar_s(
        $_POST["id"]
      );
      echo json_encode($respuesta);
    }
    exit;
  }
  $b_temp = new Bitacora;
  $b_temp->b_registro("Ingreso en el modulo \"".$_GET["p"]."\"");
  require_once("vista/" . $p . ".php");
} else {
  require_once("vista/404.php");
}
