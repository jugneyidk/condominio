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

      // $o->set_id($_POST["id"])
      // $o->set_rif_cedula();
      // $o->set_tipo_identificacion();
      // $o->set_razon_social();
      // $o->set_domicilio_fiscal();
      // $o->set_telefono();
      // $o->set_correo();
      // $o->set_password();
      // $o->set_rol();

      $respuesta = $o->incluir_s(
        $_POST["rif_cedula"],
        $_POST["tipo_identificacion"],
        $_POST["razon_social"],
        $_POST["domicilio_fiscal"],
        $_POST["telefono"],
        $_POST["correo"],
        $_POST["password"],
        $_POST["rol"]
      );
      echo json_encode($respuesta);
    } elseif ($accion == 'modificar') {

      // $o->set_id();
      // $o->set_rif_cedula();
      // $o->set_tipo_identificacion();
      // $o->set_razon_social();
      // $o->set_domicilio_fiscal();
      // $o->set_telefono();
      // $o->set_correo();
      // $o->set_password();
      // $o->set_rol();
      $respuesta = $o->modificar_s(
        $_POST["id"],
        $_POST["rif_cedula"],
        $_POST["tipo_identificacion"],
        $_POST["razon_social"],
        $_POST["domicilio_fiscal"],
        $_POST["telefono"],
        $_POST["correo"],
        $_POST["password"],
        $_POST["rol"]
      );

      echo json_encode($respuesta);
    } elseif ($accion == 'eliminar') {
      // $o->set_id();
      $respuesta = $o->eliminar_s($_POST["id"]);
      echo json_encode($respuesta);
    }
    exit;
  }
  if ($_SESSION['Conjunto_Residencial_José_Maria_Vargas_rol']== 2) {
    $b_temp = new Bitacora;
    $b_temp->b_registro("Ingreso en el modulo \"Gestionar Usuarios\"");
    require_once("vista/" . $p . ".php");
  }else {
    header("Location: . ");
  }
} else {
  require_once("vista/404.php");
}
