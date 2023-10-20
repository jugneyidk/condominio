<?php
if (!is_file("model/" . $p . ".php")) {
  echo "Falta definir la clase " . $p;
  exit;
}
require_once("model/" . $p . ".php");
if (is_file("vista/" . $p . ".php")) {
  if (isset($_SESSION['id_habitante'])) {
    $o = new detallesdeuda();
    if (!empty($_POST)) {
      $accion = $_POST['accion'];
      if ($accion == 'listadodeudas') {
        $respuesta = $o->listadodeudas();
        echo json_encode($respuesta);
       }else if($accion == 'historialpagos'){
          $respuesta = $o->historialpagos();
          echo json_encode($respuesta);
      } else if ($accion == 'registrarpago') {

        $o->set_obj_pagos($_POST["obj_pagos"]);
        $o->set_id($_POST["id_deuda_tosend"]);




        $respuesta = $o->registrarpago_s();
        echo json_encode($respuesta);
      }
      exit;
    }
    require_once("vista/" . $p . ".php");
  } else {
    header("Location: ?p=consulta");
  }
} else {
  require_once("vista/404.php");
}
?>