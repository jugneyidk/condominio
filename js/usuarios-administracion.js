$(document).ready(function () {
  carga_usuarios();
  $("#rif_cedula").on("keypress", function (e) {
    validarKeyPress(/^[0-9\b]*$/, e);
  });
  $("#tipo_identificacion").change(function () {
    if (
      validarKeyUp(
        /^[0-9]{7,9}$/,
        $("#rif_cedula"),
        $("#srifcedula"),
        "El formato debe ser 12345678"
      ) == 1 &&
      $("#rif_cedula").val().length > 0
    ) {
      $("#srifcedula").text("");
    }
  });
  $("#rif_cedula").on("keyup", function () {
    validarKeyUp(
      /^[0-9]{7,9}$/,
      $(this),
      $("#srifcedula"),
      "El formato debe ser 12345678"
    );
  });
  $("#password").on("keypress", function (e) {
    validarKeyPress(/^[A-Za-z0-9\b]*$/, e);
  });

  $("#password").on("keyup", function () {
    if ($("#id").val().length > 0) {
      validarKeyUp(
        /^[A-Za-z0-9\b]{6,20}$/,
        $(this),
        $("#spassword"),
        "Solo letras y/o numeros entre 6 y 20 caracteres (Dejar en blanco si no desea modificar)"
      );
    } else {
      validarKeyUp(
        /^[A-Za-z0-9\b]{6,20}$/,
        $(this),
        $("#spassword"),
        "Solo letras y/o numeros entre 6 y 20 caracteres"
      );
    }
  });
  $("#razon_social").on("keypress", function (e) {
    validarKeyPress(/^[aA-zZ0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });
  $("#razon_social").on("keyup", function () {
    validarKeyUp(
      /^[aA-zZ0-9\s\u00f1\u00d1\u00E0-\u00FC]{5,45}$/,
      $(this),
      $("#srazon_social"),
      "La razón social debe contener entre 5 y 45 caracteres"
    );
  });
  $("#telefono").on("keypress", function (e) {
    validarKeyPress(/^[0-9-\b]*$/, e);
  });
  $("#telefono").on("keyup", function () {
    validarKeyUp(
      /^[0][0-9]{3}[-][0-9]{7}$/,
      $(this),
      $("#stelefono"),
      "El teléfono debe ser 0XXX-1234567"
    );
  });
  $("#domicilio_fiscal").on("keypress", function (e) {
    validarKeyPress(/^[aA-zZ0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });
  $("#domicilio_fiscal").on("keyup", function () {
    validarKeyUp(
      /^[aA-zZ0-9\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/,
      $(this),
      $("#sdomicilio_fiscal"),
      "El domicilio fiscal debe contener entre 5 y 50 caracteres"
    );
  });
  $("#correo").on("keypress", function (e) {
    validarKeyPress(/^[aA-zZ0-9-.@\b]*$/, e);
  });
  $("#correo").on("keyup", function () {
    validarKeyUp(
      /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/,
      $(this),
      $("#scorreo"),
      "El correo debe ser alguien@dominio.com"
    );
  });
  $("#limpiar").on("click", function () {
    limpia();
    limpiarerror();
    if ($("#dropIdent").hasClass("disabled")) {
      $("#dropIdent").toggleClass("disabled");
    }
    cambiarbotones(true);
  });
  $("#incluir").on("click", function () {
    if (validarEnvio()) {
      $("#accion").val("incluir");
      var datos = new FormData($("#f")[0]);
      enviaAjax(datos);
    }
  });
  $("#p-natural").on("click", function (e) {
    $("#tipo_identificacion").val("0").change();
    cambiarTipoIdent("0");
    e.preventDefault();
  });
  $("#p-extranjero").on("click", function (e) {
    $("#tipo_identificacion").val("1").change();
    cambiarTipoIdent("1");
    e.preventDefault();
  });
  $("#p-juridica").on("click", function (e) {
    $("#tipo_identificacion").val("2").change();
    cambiarTipoIdent("2");
    e.preventDefault();
  });
  $("#tipo_identificacion").on("change", function () {
    cambiarTipoIdent($("#tipo_identificacion").val());
  });
  $("#rol").on("change", function () {
    if ($("#rol").val() != "1" && $("#rol").val() != "2") {
      $("#srol").text("Debe elegir un rol para el usuario");
      if (!$("#rol").hasClass("is-invalid")) {
        $("#rol").toggleClass("is-invalid");
      }
    } else {
      $("#srol").text("");
      if ($("#rol").hasClass("is-invalid")) {
        $("#rol").toggleClass("is-invalid");
      }
      if (!$("#rol").hasClass("is-valid")) {
        $("#rol").toggleClass("is-valid");
      }
    }
  });
  $("#modificar").on("click", function () {
    if (validarEnvio()) {
      Swal.fire({
        title: "¿Estás Seguro?",
        text: "¿Está seguro que desea modificar este usuario?",
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
  $("#eliminar").on("click", function () {
    if (
      validarKeyUp(
        /^[0-9]{7,9}$/,
        $("#rif_cedula"),
        $("#srifcedula"),
        "El formato debe ser 12345678"
      ) == 0
    ) {
      muestraMensaje(
        "Error",
        "La cedula o rif debe coincidir con el formato 12345678",
        "error"
      );
    } else {
      Swal.fire({
        title: "¿Estás Seguro?",
        text: "¿Está seguro que desea eliminar este usuario?",
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
});

function validarKeyPress(er, e) {
  key = e.keyCode;
  tecla = String.fromCharCode(key);
  a = er.test(tecla);
  if (!a) {
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
function cambiarTipoIdent(val) {
  switch (val) {
    case "0":
      $("#dropIdent").text("V");
      break;
    case "1":
      $("#dropIdent").text("E");
      break;
    case "2":
      $("#dropIdent").text("J");
      break;
    default:
      $("#dropIdent").text("-");
      break;
  }
}
function validarEnvio() {
  if (
    validarKeyUp(
      /^[0-9]{7,9}$/,
      $("#rif_cedula"),
      $("#srifcedula"),
      "El formato debe ser 12345678"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "La cedula o rif debe coincidir con el formato 12345678",
      "error"
    );
    return false;
  } else if (
    $("#tipo_identificacion").val() != "0" &&
    $("#tipo_identificacion").val() != "1" &&
    $("#tipo_identificacion").val() != "2"
  ) {
    $("#srifcedula").text("Debe elegir persona natural o jurídica");
    muestraMensaje("Error", "Debe elegir persona natural o jurídica", "error");
    return false;
  } else if (
    validarKeyUp(
      /^[aA-zZ0-9\s\u00f1\u00d1\u00E0-\u00FC]{5,45}$/,
      $("#razon_social"),
      $("#srazon_social"),
      "La razón social debe contener entre 5 y 45 caracteres"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "La razón social debe contener entre 5 y 45 caracteres",
      "error"
    );
    return false;
  } else if (
    validarKeyUp(
      /^[0][0-9]{3}[-][0-9]{7}$/,
      $("#telefono"),
      $("#stelefono"),
      "El teléfono debe ser 0XXX-1234567"
    ) == 0
  ) {
    muestraMensaje("Error", "El teléfono debe ser 0XXX-1234567", "error");
    return false;
  } else if (
    validarKeyUp(
      /^[aA-zZ0-9\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/,
      $("#domicilio_fiscal"),
      $("#sdomicilio_fiscal"),
      "El domicilio fiscal debe contener entre 5 y 50 caracteres"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "El domicilio fiscal debe contener entre 5 y 50 caracteres",
      "error"
    );
    return false;
  } else if ($("#rol").val() != "1" && $("#rol").val() != "2") {
    $("#srol").text("Debe elegir un rol para el usuario");
    muestraMensaje("Error", "Debe elegir un rol para el usuario", "error");
    if (!$("#rol").hasClass("is-invalid")) {
      $("#rol").toggleClass("is-invalid");
    }
    return false;
  } else if (
    validarKeyUp(
      /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/,
      $("#correo"),
      $("#scorreo"),
      "El correo debe ser alguien@dominio.com"
    ) == 0
  ) {
    muestraMensaje("Error", "El correo debe ser alguien@dominio.com", "error");
    return false;
  } else if ($("#id").val().length > 0) {
    if (
      $("#password").val().length > 0 &&
      validarKeyUp(
        /^[A-Za-z0-9\b]{6,20}$/,
        $("#password"),
        $("#spassword"),
        "Solo letras y/o numeros entre 6 y 20 caracteres"
      ) == 0
    ) {
      muestraMensaje(
        "Error",
        "La contraseña debe ser solo letras y/o numeros entre 6 y 20 caracteres",
        "error"
      );
      return false;
    } else if ($("#password").val().length == 0) {
      return true;
    }
  } else if (
    $("#id").val().length == 0 &&
    $("#password").val().length >= 0 &&
    validarKeyUp(
      /^[A-Za-z0-9\b]{6,20}$/,
      $("#password"),
      $("#spassword"),
      "Solo letras y/o numeros entre 6 y 20 caracteres"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "La contraseña debe ser solo letras y/o numeros entre 6 y 20 caracteres",
      "error"
    );
    return false;
  }
  return true;
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

function colocausuario(linea) {
  var tipo;
  $("#rif_cedula").val($(linea).find("td:eq(2)").text());
  $("#id").val($(linea).find("td:eq(0)").text());
  if ($(linea).find("td:eq(1)").text() == "V") {
    $("#tipo_identificacion").val(0);
    tipo = "0";
  } else if ($(linea).find("td:eq(1)").text() == "E") {
    $("#tipo_identificacion").val(1);
    tipo = "1";
  } else if ($(linea).find("td:eq(1)").text() == "J") {
    $("#tipo_identificacion").val(2);
    tipo = "2";
  }
  cambiarTipoIdent(tipo);
  $("#razon_social").val($(linea).find("td:eq(3)").text());
  $("#domicilio_fiscal").val($(linea).find("td:eq(4)").text());
  $("#telefono").val($(linea).find("td:eq(5)").text());
  $("#correo").val($(linea).find("td:eq(6)").text());
  $("#rol").val($(linea).find("td:eq(8)").text());
  limpiarerror();
  cambiarbotones(false);
  if (!$("#dropIdent").hasClass("disabled")) {
    $("#dropIdent").toggleClass("disabled");
  }
  $("#modal1").modal("hide");
  limpiarvalidacion();
  agregarvalidacion();
}
function carga_usuarios() {
  var datos = new FormData();
  datos.append("accion", "listado_usuarios");
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
        if (lee.resultado == "listado_usuarios") {
          if ($.fn.DataTable.isDataTable("#tablausuarios")) {
            $("#tablausuarios").DataTable().destroy();
          }
          $("#listadousuarios").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tablausuarios")) {
            $("#tablausuarios").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron usuarios",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay usuarios disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                  first: "Primera",
                  last: "Última",
                  next: "Siguiente",
                  previous: "Anterior",
                },
              },
              autoWidth: false,
              order: [[2, "asc"]],
            });
          }
        } else if (
          lee.resultado == "incluir" ||
          lee.resultado == "modificar" ||
          lee.resultado == "eliminar"
        ) {
          muestraMensaje(lee.mensaje, "", "success");
          limpia();
          limpiarerror();
          carga_usuarios();
          if ($("#dropIdent").hasClass("disabled")) {
            $("#dropIdent").toggleClass("disabled");
          }
          cambiarbotones(true);
        } else if (lee.resultado == "error") {
          muestraMensaje(lee.mensaje, "", "error");
        }
      } catch (e) {
        alert("Error en JSON " + e.name);
        console.log(e);
        console.log(respuesta);
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
  $("#rif_cedula").val("");
  $("#tipo_identificacion").val("-").trigger("change");
  $("#password").val("");
  $("#razon_social").val("");
  $("#domicilio_fiscal").val("");
  $("#telefono").val("");
  $("#correo").val("");
  $("#rol").val("-");
  limpiarvalidacion();
}
function limpiasincedula() {
  $("#razon_social").val("");
  $("#domicilio_fiscal").val("");
  $("#telefono").val("");
  $("#correo").val("");
  limpiarerror();
  cambiarbotones(true);
  if ($("#dropIdent").hasClass("disabled")) {
    $("#dropIdent").toggleClass("disabled");
  }
}
function limpiarerror() {
  $("#srifcedula").text("");
  $("#spassword").text("");
  $("#srazon_social").text("");
  $("#sdomicilio_fiscal").text("");
  $("#stelefono").text("");
  $("#scorreo").text("");
  $("#srol").text("");
}
function limpiarvalidacion() {
  $("form input").removeClass("is-valid");
  $("form input").removeClass("is-invalid");
  $("form select").removeClass("is-valid");
  $("form select").removeClass("is-invalid");
}
function agregarvalidacion() {
  $("form input").addClass("is-valid");
  $("form select").addClass("is-valid");
}
