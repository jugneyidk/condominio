<?php
if (!is_file("model/" . $p . ".php")) {
  echo "Falta definir la clase " . $p;
  exit;
}
require_once("model/" . $p . ".php");

if (is_file("vista/" . $p . ".php")) {
  if(isset($_GET['out'])){
    session_destroy();
  }
  if(isset($_SESSION['id_habitante'])){
    header("Location: ?p=detallesdeuda");
  }
  if (!empty($_POST)) {
    $o = new consulta();
    $accion = $_POST["accion"];
    if ($accion == "iniciar") {
      $respuesta = $o->iniciarSesion($_POST["usuario"], $_POST["clave"]);
      echo json_encode($respuesta);
    }
    exit;
  }
  require_once("vista/" . $p . ".php");
} else {
  require_once("vista/404.php");
}
