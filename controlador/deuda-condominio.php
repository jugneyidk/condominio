<?php
if (!is_file("model/" . $p . ".php")) {

  echo "Falta definir la clase " . $p;
  exit;
}

require_once("model/" . $p . ".php");

if (is_file("vista/" . $p . ".php")) {
  $o = new Deudacondominio();
  $permisos = $o->chequearpermisos();
  if (!empty($_POST)) {
    $accion = $_POST['accion'];
    if ($accion == 'listado_deudas') {
      $respuesta = $o->listadodeudas();
      echo json_encode($respuesta);

    } 
    else if ($accion == "lista_apartamentos"){
      echo json_encode($o->lista_apartamentos());
    }
    else if ($accion == "lista_apartamentos_select"){
      $o->set_id($_POST["id"]);
      echo json_encode($o->lista_apartamentos());
    }
    else if ($accion == "lista_cargos"){
      echo json_encode($o->lista_cargos());
    }
    else if ($accion == "incluir_cargo"){



      $o->set_concepto($_POST["cargo_concepto"]);
      $o->set_monto($_POST["cargo_monto"]);
      $o->set_tipo_monto($_POST["cargo_tipo_monto"]);
      $o->set_tipo_cargo($_POST["cargo_tipo_cargo_global_dedicado"]);
      $o->set_mensual($_POST["cargo_tipo_cargo"]);
      $o->set_aplicar_next_mes($_POST["cargo_aplicar_next"]);
      if(isset($_POST["lista"])){
        $o->set_apartamentos($_POST["lista"]);
      }

      echo json_encode($o->incluir_cargo_s());
    }
    else if($accion == "modificar_cargo"){
      $o->set_id($_POST["id"]);
      $o->set_concepto($_POST["cargo_concepto"]);
      $o->set_monto($_POST["cargo_monto"]);
      $o->set_tipo_monto($_POST["cargo_tipo_monto"]);
      $o->set_tipo_cargo($_POST["cargo_tipo_cargo_global_dedicado"]);
      $o->set_mensual($_POST["cargo_tipo_cargo"]);
      $o->set_aplicar_next_mes($_POST["cargo_aplicar_next"]);
      if(isset($_POST["lista"])){
        $o->set_apartamentos($_POST["lista"]);
      }

      echo json_encode($o->modificar_cargo_s());

      

    }
    else if($accion== "eliminar_cargo" ){
      $o->set_id($_POST["id"]);
      echo json_encode($o->eliminar_cargo_s());
    }
    elseif ($accion == 'incluir') {
      $respuesta = $o->incluir($_POST['monto'], $_POST['concepto'], $_POST['fecha']);
      echo json_encode($respuesta);
    } elseif ($accion == 'distribuir_deuda') {
      $respuesta = $o->distribuir_deuda($_POST['id_deuda']);
      echo json_encode($respuesta);
    } elseif ($accion == 'calculo_deuda') {
      $respuesta = $o->calcular_deuda($_POST['id_deuda']);
      echo json_encode($respuesta);
    } elseif ($accion == 'modificar') {
      $respuesta = $o->modificar($_POST['id_deuda'], $_POST['monto'], $_POST['concepto'], $_POST['fecha']);
      echo json_encode($respuesta);
    } elseif ($accion == 'eliminar') {
      $respuesta = $o->eliminar($_POST['id_deuda']);
      echo json_encode($respuesta);
    }
    exit;
  }
  require_once("vista/" . $p . ".php");
} else {
  require_once("vista/404.php");
}
