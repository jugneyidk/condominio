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
    timeout: 10000,
    success: function (respuesta) {
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "datos_barra") {
          $("#pagos_confirmados").text(lee.pagos_confirmados);
          $("#pagos_pendientes").text(lee.pagos_pendientes);
          $("#deudas_pendientes").text(lee.deudas_pendientes);
          $("#morosos").text(lee.morosos);
        } else if (lee.resultado == "ultimos_pagos") {
          $("#ultimos_pagos").html(lee.mensaje);
        }
      } catch (e) {
        alert("Error en JSON " + e.name);
      }
    },
    error: function (request, status, err) {
      modalcarga(false);
      if (status == "timeout") {
        muestraMensaje("Servidor ocupado, intente de nuevo");
      } else {
        muestraMensaje("ERROR: <br/>" + request + status + err);
      }
    },
    complete: function () {
      modalcarga(false);
    },
  });
}
