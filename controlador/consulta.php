<?php

require_once("model/login.php");

if (is_file("vista/" . $p . ".php")) {
  if(isset($_GET['out'])){
    session_destroy();
  }
  if(isset($_SESSION['id_habitante'])){
    header("Location: ?p=detallesdeuda");
  }
  if (!empty($_POST)) {
    $o = new login();
    $accion = $_POST["accion"];
    if ($accion == "iniciar") {
      $respuesta = $o->iniciarSesion_habitante($_POST["usuario"], $_POST["clave"]);
      echo json_encode($respuesta);
    }
    exit;
  }
  require_once("vista/" . $p . ".php");
} else {
  require_once("vista/404.php");
}
