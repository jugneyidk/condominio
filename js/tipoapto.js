$(document).ready(function () {
  carga_tipos();
  $("#alicuota").on("keypress", function (e) {
    validarKeyPress(e, /^[0-9.]$/);
  });
  $("#alicuota").on("keyup", function () {
    validarKeyUp(
      /^[0-9_.]{1,8}$/,
      $(this),
      $("#salicuota"),
      "Solo numeros (012...) y puntos (.)"
    );
  });
  $("#descripcion").on("keypress", function (e) {
    validarKeyPress(e, /^[A-Za-z0-9,#\b\s\u00f1\u00d1\u00E0-\u00FC-]*$/);
  });
  $("#descripcion").on("keyup", function () {
    validarKeyUp(
      /^[A-Za-z0-9,#\b\s\u00f1\u00d1\u00E0-\u00FC-]{6,50}$/,
      $(this),
      $("#sdescripcion"),
      "Solo letras y/o numeros entre 6 y 50 caracteres"
    );
  });
  $("#limpiar").on("click", function () {
    limpia();
    limpiarerror();
    cambiarbotones(true);
  });
  $("#incluir").on("click", function () {
    if (validarEnvio()) {
      $("#accion").val("incluir");
      var datos = new FormData($("#f")[0]);
      enviaAjax(datos);
      cambiarbotones(true);
    }
  });
  $("#eliminar").on("click", function () {
    if (!$("#id_tipo_apartamento").val()) {
      muestraMensaje(
        "Error",
        "Debe seleccionar un tipo de apartamento antes de eliminarlo",
        "error"
      );
    } else {
      Swal.fire({
        title: "¿Estás Seguro?",
        text: "¿Está seguro que desea eliminar este tipo de apartamento?",
        showCancelButton: true,
        confirmButtonText: "Eliminar",
        confirmButtonColor: "#dc3545",
        cancelButtonText: `Cancelar`,
        icon: "warning",
      }).then((result) => {
        if (result.isConfirmed) {
          $("#accion").val("eliminar");
          var datos = new FormData($("#f")[0]);
          enviaAjax(datos);
        }
      });
    }
  });
  $("#modificar").on("click", function () {
    if (validarEnvio()) {
      Swal.fire({
        title: "¿Estás Seguro?",
        text: "¿Está seguro que desea modificar este tipo de apartamento?",
        showCancelButton: true,
        confirmButtonText: "Modificar",
        confirmButtonColor: "#007bff",
        cancelButtonText: `Cancelar`,
        icon: "warning",
      }).then((result) => {
        if (result.isConfirmed) {
          $("#accion").val("modificar");
          var datos = new FormData($("#f")[0]);
          enviaAjax(datos);
        }
      });
    }
  });
});
function validarKeyPress(e, er) {
  codigo = e.keyCode;
  tecla = String.fromCharCode(codigo);
  if (er.test(tecla) === false) {
    e.preventDefault();
  }
}
function validarKeyUp(er, etiqueta, etiquetamensaje, mensaje) {
  a = er.test(etiqueta.val());
  if (a) {
    if (etiqueta.hasClass("is-invalid")) {
      etiqueta.toggleClass("is-invalid");
    }
    if (!etiqueta.hasClass("is-valid")) {
      etiqueta.toggleClass("is-valid");
    }
    etiquetamensaje.text("");
    return 1;
  } else {
    if (etiqueta.hasClass("is-valid")) {
      etiqueta.toggleClass("is-valid");
    }
    if (!etiqueta.hasClass("is-invalid")) {
      etiqueta.toggleClass("is-invalid");
    }
    etiquetamensaje.text(mensaje);
    return 0;
  }
}
function validarEnvio() {
  if (
    validarKeyUp(
      /^[A-Za-z0-9,#\b\s\u00f1\u00d1\u00E0-\u00FC-]{6,50}$/,
      $("#descripcion"),
      $("#sdescripcion"),
      "Solo letras y/o numeros entre 6 y 50 caracteres"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "La descripción debe tener solo letras y/o numeros entre 6 y 50 caracteres",
      "error"
    );
    return false;
  } else if (
    validarKeyUp(
      /^[0-9_.]{1,8}$/,
      $("#alicuota"),
      $("#salicuota"),
      "Solo numeros (012...) y puntos (.)"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "La alicuota debe tener solo numeros (012...) y puntos (.)",
      "error"
    );
    return false;
  }
  return true;
}
function colocatipo(linea) {
  $("#id_tipo_apartamento").val($(linea).find("td:eq(0)").text());
  $("#descripcion").val($(linea).find("td:eq(1)").text());
  $("#alicuota").val($(linea).find("td:eq(2)").text());
  cambiarbotones(false);
  $("#modal1").modal("hide");
  limpiarerror();
  limpiarvalidacion();
  agregarvalidacion();
}
function muestraMensaje(titulo, mensaje, icono) {
  Swal.fire({
    title: titulo,
    text: mensaje,
    icon: icono,
    showConfirmButton: false,
    showCancelButton: true,
    cancelButtonText: "Cerrar",
  });
}
function carga_tipos() {
  var datos = new FormData();
  datos.append("accion", "listado_tipos");
  enviaAjax(datos);
}
function cambiarbotones(parametro) {
  $("#modificar").prop("disabled", parametro);
  $("#eliminar").prop("disabled", parametro);
  $("#incluir").prop("disabled", !parametro);
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
        if (lee.resultado == "listado_tipos") {
          $("#listadotipos").html(lee.mensaje);
        } else if (
          lee.resultado == "incluir" ||
          lee.resultado == "modificar" ||
          lee.resultado == "eliminar"
        ) {
          muestraMensaje(lee.mensaje, "", "success");
          limpia();
          carga_tipos();
          cambiarbotones(true);
        } else if (lee.resultado == "error") {
          muestraMensaje(lee.mensaje, "", "error");
        }
      } catch (e) {
        alert("Error en JSON " + e.name);
        console.error(respuesta);
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
  $("#id_tipo_apartamento").val("");
  $("#descripcion").val("");
  $("#alicuota").val("");
  limpiarvalidacion();
}
function limpiarerror() {
  $("#sdescripcion").text("");
  $("#salicuota").text("");
}
function limpiarvalidacion() {
  $("form input").removeClass("is-valid");
  $("form input").removeClass("is-invalid");
}
function agregarvalidacion() {
  $("form input").addClass("is-valid");
}

