$(document).ready(function () {
  carga_deudas();
  carga_morosos();
  $(function() {
    var hash = document.location.hash;
    if (hash) {
      $('.nav-tabs a[href=\\'+hash+']').tab('show');
    }
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
      window.location.hash = e.target.hash;
    });
  });
  $("#registrarpago").on("hide.bs.modal", function () {
    setTimeout(limpiar_modal, 300);
  });
  $("#referencia").on("keypress", function (e) {
    validarKeyPress(e, /^[0-9A-Z]$/);
  });
  $("#referencia").on("keyup", function () {
    validarKeyUp(
      /^[0-9A-Z]{4,16}$/,
      $(this),
      $("#sreferencia"),
      "Solo números y letras mayusculas (A-Z) desde 4 hasta 16 caracteres"
    );
  });
  $("#monto").on("keypress", function (e) {
    validarKeyPress(e, /^[0-9.]$/);
  });
  $("#monto").on("keyup", function () {
    validarKeyUp(
      /^[0-9.$]{1,8}$/,
      $(this),
      $("#smonto"),
      "Solo numeros (012...) y puntos (.)"
    );
  });
  $("#fecha").change(function () {
    if (!verificarfecha()) {
      $("#sfecha").text("La fecha debe ser anterior al día actual");
    } else {
      $("#sfecha").text("");
    }
  });
  $("#tipo_pago").on("change", function () {
    if ($("#tipo_pago").val()) {
      $("#stipo_pago").text("");
      if ($("#tipo_pago").hasClass("is-invalid")) {
        $("#tipo_pago").toggleClass("is-invalid");
      }
      if (!$("#tipo_pago").hasClass("is-valid")) {
        $("#tipo_pago").toggleClass("is-valid");
      }
    } else if ($("#tipo_pago").val() == "-") {
      $("#stipo_pago").text("Debe elegir un tipo de pago");
      if (!$("#tipo_pago").hasClass("is-invalid")) {
        $("#tipo_pago").toggleClass("is-invalid");
      }
    }
  });
});
function validarEnvio() {
  if (
    validarKeyUp(
      /^[0-9A-Z]{4,16}$/,
      $("#referencia"),
      $("#sreferencia"),
      "Solo números y letras mayusculas (A-Z) desde 4 hasta 16 caracteres"
    ) == 0
  ) {
    return false;
  } else if (
    validarKeyUp(
      /^[0-9.$]{1,8}$/,
      $("#monto"),
      $("#smonto"),
      "Solo numeros (012...) y puntos (.)"
    ) == 0 ||
    $("#monto").val() <= 0
  ) {
    return false;
  } else if (!verificarfecha()) {
    $("#sfecha").text("La fecha debe ser anterior al día actual");
    return false;
  } else if (
    $("#tipo_pago").val() != "Efectivo" &&
    $("#tipo_pago").val() != "Pago Movil" &&
    $("#tipo_pago").val() != "Transferencia Bancaria" &&
    $("#tipo_pago").val() != "Zelle"
  ) {
    if (!$("#tipo_pago").hasClass("is-invalid")) {
      $("#tipo_pago").toggleClass("is-invalid");
    }
    $("#stipo_pago").text("Debe elegir un tipo de pago");
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
function verificarfecha() {
  hoy = new Date();
  fecha = new Date($("#fecha").val());
  timestamp = fecha.getTime();
  if (timestamp <= hoy.getTime() == false) {
    $("#fecha").removeClass("is-valid")
    $("#fecha").addClass("is-invalid")
  } else if (timestamp <= hoy.getTime() == true) {
    $("#fecha").removeClass("is-invalid")
    $("#fecha").addClass("is-valid")
  }
  return timestamp <= hoy.getTime();
}
function carga_deudas() {
  var datos = new FormData();
  datos.append("accion", "listadodeudas");
  enviaAjax(datos);
}
function carga_morosos() {
  var datos = new FormData();
  datos.append("accion", "listadomorosos");
  enviaAjax(datos);
}
function obtener_monto(linea) {
  var linea = $(linea).closest("tr");
  var monto = $(linea).find("td:eq(5)").text();
  return monto;
}
function obtener_id(linea) {
  var linea = $(linea).closest("tr");
  var id = $(linea).find("td:eq(0)").text();
  return id;
}
function mostrar_registrar_pago(linea) {
  var id = obtener_id(linea);
  $("#id_deuda").val(id);
  $("#monto").val(obtener_monto(linea));
  $("#registrarpago").modal("show");
}
function registrar_pago() {
  if (validarEnvio()) {
    var datos = new FormData($("#f")[0]);
    datos.append("accion", "registrarpago");
    enviaAjax(datos);
  }
}
function limpiar_modal() {
  $("#id_deuda").val("");
  $("#monto").val("");
  $("#fecha").val("");
  $("#tipo_pago").val("-");
  $("#referencia").val("");
  $("#stipo_pago").text("");
  $("#sreferencia").text("");
  $("#sfecha").text("");
  $("#smonto").text("");
  limpiarvalidacion();
}
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
        if (lee.resultado == "listadodeudas") {
          if ($.fn.DataTable.isDataTable("#tabladeudas")) {
            $("#tabladeudas").DataTable().destroy();
          }
          $("#listadodeudas").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tabladeudas")) {
            $("#tabladeudas").DataTable({
              columnDefs: [
                { 'targets': [6],
                  'orderable': false,
                  'searchable': false}
              ],
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron deudas",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay deudas disponibles",
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
              order: [[1, "asc"]],
            });
          }
        } else if (lee.resultado == "listadomorosos") {
          if ($.fn.DataTable.isDataTable("#tablamorosos")) {
            $("#tablamorosos").DataTable().destroy();
          }
          $("#listadomorosos").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tablamorosos")) {
            $("#tablamorosos").DataTable({
              columnDefs: [
                { 'targets': [6],
                  'orderable': false,
                  'searchable': false}
              ],
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron morosos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay morosos actualmente",
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
              order: [[1, "asc"]],
            });
          }
          
        } else if (lee.resultado == "registrado") {
          carga_deudas();
          carga_morosos();
          $("#registrarpago").modal("hide");
          limpiar_modal();
          muestraMensaje(lee.mensaje, "", "success");
        } else if (lee.resultado == "error") {
          muestraMensaje(lee.mensaje, "", "error");
        }
      } catch (e) {
        alert("Error en JSON " + e.name + " !!!");
      }
    },
    error: function (request, status, err) {
      modalcarga(false);
      if (status == "timeout") {
        muestraMensaje("Servidor ocupado", "Intente de nuevo", "error");
      } else {
        muestraMensaje("Error", request + status + err, "error");
      }
    },
    complete: function () {
      modalcarga(false);
    },
  });
}
function limpiarvalidacion() {
  $("form input").removeClass("is-valid");
  $("form select").removeClass("is-valid");
  $("form input").removeClass("is-invalid");
  $("form select").removeClass("is-invalid");
}