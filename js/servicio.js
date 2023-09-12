$(document).ready(function () {
  carga_nomina();
function carga_servicios() {
  var datos = new FormData();
  datos.append("accion", "listadoservicios");
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
    timeout: 10000, //tiempo maximo de espera por la respuesta del servidor
    success: function (respuesta) {
      //si resulto exitosa la transmision
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "listado_servicios") {
          $("#listadoservicios").html(lee.mensaje);
        } else if (
          lee.resultado == "incluir" ||
          lee.resultado == "modificar" ||
          lee.resultado == "eliminar"
        ) {
          muestraMensaje(lee.mensaje, "", "success");
          limpia();
          carga_nomina();
          cambiarbotones(true);
        } else if (lee.resultado == "error") {
          muestraMensaje(lee.mensaje, "", "error");
        }
      } catch (e) {
        alert("Error en JSON " + e.name);
      }
    },
    error: function (request, status, err) {
      modalcarga(false);
      // si ocurrio un error en la trasmicion
      // o recepcion via ajax entra aca
      // y se muestran los mensaje del error
      if (status == "timeout") {
        //pasa cuando superan los 10000 10 segundos de timeout
        muestraMensaje("Servidor ocupado", "Intente de nuevo", "error");
      } else {
        //cuando ocurre otro error con ajax
        muestraMensaje("Error", request + status + err, "error");
      }
    },
    complete: function () {
      modalcarga(false);
    },
  });
}
function limpia() {
  $("#cedula_rif").val("");
  $("#tipo_identificacion").val("");
  $("#nombres").val("");
  $("#apellidos").val("");
  $("#domicilio_fiscal").val("");
  $("#telefono").val("");
  $("#correo").val("");
  $("#descripcion").val("");
  $("#metodo").val("");
  $("#fecha").val("");
  $("#monto").val("");
  $("#referencia").val("");
  limpiarvalidacion();
}
function limpiarerror() {
  $("#scedula_rif").text("");
  $("#stipo_identificacion").text("");
  $("#snombres").text("");
  $("#sapellidos").text("");
  $("#sdomicilio_fiscal").text("");
  $("#stelefono").text("");
  $("#scorreo").text("");
  $("#sdescripcion").text("");
  $("#smetodo").text("");
  $("#sfecha").text("");
  $("#smonto").text("");
  $("#sreferencia").text("");
}
function limpiarvalidacion() {
  $("form input").removeClass("is-valid");
  $("form input").removeClass("is-invalid");
}
function agregarvalidacion() {
  $("form input").addClass("is-valid");
}
