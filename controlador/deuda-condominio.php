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
    if ($accion == "lista_apartamentos"){
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

      if($_POST["cargo_tipo_cargo_global_dedicado"] != "dedicado"){
        $_POST["lista"] = null;
      }

      echo json_encode($o->incluir_cargo_s(
        $_POST["cargo_concepto"],
        $_POST["cargo_monto"],
        $_POST["cargo_tipo_monto"],
        $_POST["cargo_tipo_cargo_global_dedicado"],
        $_POST["cargo_tipo_cargo"],
        $_POST["cargo_aplicar_next"],
        $_POST["lista"]
      ));
    }
    else if ($accion == "modificar_cargo"){
     

      if($_POST["cargo_tipo_cargo_global_dedicado"] != "dedicado"){
        $_POST["lista"] = null;
      }


      echo json_encode($o->modificar_cargo_s(
        $_POST["id"],
        $_POST["cargo_concepto"],
        $_POST["cargo_monto"],
        $_POST["cargo_tipo_monto"],
        $_POST["cargo_tipo_cargo_global_dedicado"],
        $_POST["cargo_tipo_cargo"],
        $_POST["cargo_aplicar_next"],
        $_POST["lista"]
      ));
    }
    else if ($accion == "eliminar_cargo" ){
      echo json_encode($o->eliminar_cargo_s($_POST["id"]));

      //  dasf
    }
    else if ($accion == "lista_resumen_cargos"){
      echo json_encode($o->lista_resumen_cargos());
    }
    else if ($accion == "distribuir_deudas"){//incluir deudas
      echo json_encode($o->distribuir_deudas_s(
        $_POST["fecha"],
        $_POST["concepto"]
      ));

    }
    else if ($accion == "consultar_distribucion_deuda"){
      if(isset($_POST["id_seleccionado"])){

        
        echo json_encode($o->consultar_distribucion_deuda($_POST["id_seleccionado"]));

      }
      else{

        echo json_encode($o->consultar_distribucion_deuda(null));

      }
    }
    else if ($accion == "modificar_deuda"){  
      // $o->set_id();
      // $o->set_fecha();
      // $o->set_concepto();
      echo json_encode($o->modificar_distribucion_s(
        $_POST["id"],
        $_POST["fecha"],
        $_POST["concepto"]
      ));
    }
    else if ($accion == "eliminar_deuda"){   
      // $o->set_id();
      echo json_encode($o->eliminar_distribucion_s($_POST["id"]));
    }

    // elseif ($accion == 'incluir') {
    //   $respuesta = $o->incluir($_POST['monto'], $_POST['concepto'], $_POST['fecha']);
    //   echo json_encode($respuesta);
    // } elseif ($accion == 'distribuir_deuda') {
    //   $respuesta = $o->distribuir_deuda($_POST['id_deuda']);
    //   echo json_encode($respuesta);
    // } elseif ($accion == 'calculo_deuda') {
    //   $respuesta = $o->calcular_deuda($_POST['id_deuda']);
    //   echo json_encode($respuesta);
    // } elseif ($accion == 'modificar') {
    //   $respuesta = $o->modificar($_POST['id_deuda'], $_POST['monto'], $_POST['concepto'], $_POST['fecha']);
    //   echo json_encode($respuesta);
    // } elseif ($accion == 'eliminar') {
    //   $respuesta = $o->eliminar($_POST['id_deuda']);
    //   echo json_encode($respuesta);
    // }
    $o->set_con(null);
    exit;
  }
  $b_temp = new Bitacora;
  $b_temp->b_registro("Ingreso en el modulo \"Deuda del Condominio\"");
  require_once("vista/" . $p . ".php");
  $o->set_con(null);
} else {
  require_once("vista/404.php");
}
