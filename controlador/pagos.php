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
      $respuesta = $o->detallespago($_POST['id']);
      echo json_encode($respuesta);
    }elseif ($accion == 'confirmar' || $accion == 'declinar') {
      $respuesta = $o->confirmar_declinar_pago($_POST['id'],$_POST['accion'],$_SESSION['id_usuario']);
      echo json_encode($respuesta);
    }
    exit;
  }

  require_once("vista/" . $p . ".php");
} else {
  require_once("vista/404.php");
}
