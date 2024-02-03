<?php
if (!is_file("model/" . $p . ".php")) {

  echo "Falta definir la clase " . $p;
  exit;
}

require_once("model/" . $p . ".php");

if (is_file("vista/" . $p . ".php")) {
  $o = new pagos();
  if (!empty($_POST)) {
    $accion = $_POST['accion'];
    if ($accion == 'listadopagos') {

      $respuesta = $o->listadopagos();
      echo json_encode($respuesta);


    }else if ($accion == 'listadopagospendientes') {

      $respuesta = $o->listadopagospendientes();
      echo json_encode($respuesta);

    }else if ($accion == 'listadopagosconfirmados') {

      $respuesta = $o->listadopagosconfirmados();
      echo json_encode($respuesta);


    }else if ($accion == 'listadopagosdeclinados') {

      $respuesta = $o->listadopagosdeclinados();
      echo json_encode($respuesta);


    }elseif ($accion == 'detallespago') {

      $o->set_id($_POST["id"]);
      $respuesta = $o->detallespago();
      echo json_encode($respuesta);


    }elseif ($accion == 'confirmar' || $accion == 'declinar' || $accion == "deshacer_conf") {


      $o->set_id($_POST["id"]);
      $o->set_accion($_POST["accion"]);
      $respuesta = $o->confirmar_declinar_pago();
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
