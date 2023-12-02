$(document).ready(function () {
  carga_datos();
  carga_pagos();
});
function carga_datos() {
  var datos = new FormData();
  datos.append("accion", "datos_barra");
  enviaAjax(datos);
}
function carga_pagos() {
  var datos = new FormData();
  datos.append("accion", "ultimos_pagos");
  enviaAjax(datos);
}
function counter_animation (elemId){
  var elem = document.getElementById(elemId);
  if(typeof elem.dataset.counter_max ==='undefined'){
    console.error("Debe haber el atributo counter_max para realizar esta operación");
    return 0;
  }
  if(typeof elem.intervalo !== 'undefined'){
    clearInterval(elem.intervalo);
  }
  elem.dataset.counter_max;
  elem.contador = 0;
  elem.maximo_contador = parseInt(elem.dataset.counter_max) + 1;

  elem.intervalo = setInterval(function(){
    elem.innerText = elem.contador++;
    if(elem.contador >= elem.maximo_contador){
      clearInterval(elem.intervalo);
      
    }
  },15);
}
function enviaAjax(datos) {
  $.ajax({
    async: true,
    url: "",
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    beforeSend: function () {
      modalcarga(true);
    },
    timeout: 50000,
    success: function (respuesta) {
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "datos_barra") {
          //$("#pagos_confirmados").text(lee.pagos_confirmados);
          //$("#pagos_pendientes").text(lee.pagos_pendientes);
          //$("#deudas_pendientes").text(lee.deudas_pendientes);
          //$("#morosos").text(lee.morosos);
          document.getElementById('morosos').dataset.counter_max = lee.morosos;
          document.getElementById('pagos_confirmados').dataset.counter_max = lee.pagos_confirmados;
          document.getElementById('pagos_pendientes').dataset.counter_max = lee.pagos_pendientes;
          document.getElementById('deudas_pendientes').dataset.counter_max = lee.deudas_pendientes;
          counter_animation("morosos");
          counter_animation("pagos_confirmados");
          counter_animation("pagos_pendientes");
          counter_animation("deudas_pendientes");
        } else if (lee.resultado == "ultimos_pagos") {
          $("#ultimos_pagos").html(lee.mensaje);
        }
      } catch (e) {
        alert("Error en JSON " + e.name);
        console.error(e);
      }
    },
    error: function (request, status, err) {
      modalcarga(false);
      if (status == "timeout") {
        //muestraMensaje("Servidor ocupado, intente de nuevo");
      } else {
        //muestraMensaje("ERROR: <br/>" + request + status + err);
      }
    },
    complete: function () {
      modalcarga(false);
    },
  });
}
