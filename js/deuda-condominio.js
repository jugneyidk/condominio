$(document).ready(function () {
  carga_deudas();
  $("#monto").on("keypress", function (e) {
    validarKeyPress(e, /^[0-9.]$/);
  });
  $("#monto").on("keyup", function () {
    validarKeyUp(
      /^[0-9.]{1,8}$/,
      $(this),
      $("#smonto"),
      "Solo numeros (012...) y puntos (.)"
    );
  });
  $("#concepto").on("keypress", function (e) {
    validarKeyPress(e, /^[A-Za-z0-9,#\b\s\u00f1\u00d1\u00E0-\u00FC-]*$/);
  });
  $("#concepto").on("keyup", function () {
    validarKeyUp(
      /^[A-Za-z0-9,#\b\s\u00f1\u00d1\u00E0-\u00FC-]{3,50}$/,
      $(this),
      $("#sconcepto"),
      "Solo letras y/o numeros entre 3 y 50 caracteres"
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
    }
  });
  $("#fecha").change(function () {
    if (!verificarfecha()) {
      console.log("hola")
      $("#sfecha").text("La fecha debe ser anterior al día actual");
    } else {
      $("#sfecha").text("");
    }
  });
  $("#eliminar").on("click", function () {
    if (!$("#id_deuda").val()) {
      muestraMensaje(
        "Debe seleccionar una deuda antes de eliminarlo."
      );
    } else {
      Swal.fire({
        title: "¿Estás Seguro?",
        text: "¿Está seguro que desea eliminar esta deuda?",
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
        text: "¿Está seguro que desea modificar esta deuda?",
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
function confirmar_accion() {
  var datos = new FormData($("#f")[0]);
  enviaAjax(datos);
  $("#modal-confirmacion").modal("hide");
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
function validarEnvio() {
  if (!verificarfecha()) {
    muestraMensaje("Error", "La fecha debe ser anterior al día actual", "error");
    $("#sfecha").text("La fecha debe ser anterior al día actual");
    return false;
  } else if (
    validarKeyUp(
      /^[0-9_.]{1,8}$/,
      $("#monto"),
      $("#smonto"),
      "Solo numeros (012...) y puntos (.)"
    ) == 0 ||
    $("#monto").val() <= 0
  ) {
    muestraMensaje("Error", "El monto debe ser solo numeros (012...) y puntos (.)", "error");
    return false;
  } else if (
    validarKeyUp(
      /^[A-Za-z0-9,#\b\s\u00f1\u00d1\u00E0-\u00FC-]{3,50}$/,
      $("#concepto"),
      $("#sconcepto"),
      "Solo letras y/o numeros entre 3 y 50 caracteres"
    ) == 0
  ) {
    muestraMensaje("Error", "El concepto debe ser solo letras y/o numeros entre 3 y 50 caracteres", "error");
    return false;
  }
  return true;
}
function colocadeuda(linea) {
  linea = $(linea).closest("tr");
  var fecha = $(linea).find("td:eq(1)").text();
  var partesFecha = fecha.split("-");
  var dia = partesFecha[0];
  var mes = partesFecha[1] - 1;
  var anio = partesFecha[2];
  var fecha_valor = new Date(anio, mes, dia);
  $("#id_deuda").val($(linea).find("td:eq(0)").text());
  $("#fecha").val(fecha_valor.toISOString().substring(0, 10));
  $("#concepto").val($(linea).find("td:eq(3)").text());
  $("#monto").val($(linea).find("td:eq(2)").text());
  cambiarbotones(false);
  limpiarvalidacion();
  agregarvalidacion();
  limpiarerror();
  $("#modal1").modal("hide");
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
function carga_deudas() {
  var datos = new FormData();
  datos.append("accion", "listado_deudas");
  enviaAjax(datos);
}
function calcular_deuda(linea, event) {
  event.stopPropagation();
  linea = $(linea).closest("tr");
  var id = $(linea).find("td:eq(0)").text();
  var datos = new FormData();
  datos.append("accion", "calculo_deuda");
  datos.append("id_deuda", id); 
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
        if (lee.resultado == "listado_deudas") {
          if ($.fn.DataTable.isDataTable("#tabladeudas")) {
            $("#tabladeudas").DataTable().destroy();
          }
          $("#listadodeudas").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tabladeudas")) {
            $("#tabladeudas").DataTable({
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
              order: [[2, "asc"]],
              columns: [
                { data: "col1" },
                { data: "col2" },
                { data: "col3" },
                { data: "col4" },
                { data: "col5" },
                {
                  data: "col6",
                  render: function (data) {
                    if (data == "0") {
                      return (
                        "<button id='distribuir' class='btn btn-sm btn-primary' onclick='calcular_deuda(this,event)'>Distribuir</td>"
                      );
                    } else if (data == "1") {
                      return (
                        "<span class='badge badge-success'>Deuda distribuida</span>"
                      );
                    }
                  },
                },
              ],
            });
          }
          $('[data-toggle="tooltip"]').tooltip();
        } else if (
          lee.resultado == "incluir" ||
          lee.resultado == "modificar" ||
          lee.resultado == "eliminar"
        ) {
          muestraMensaje(lee.mensaje, "", "success");
          limpia();
          carga_deudas();
          cambiarbotones(true);
        } else if (lee.resultado == "calculo_deuda") {
          $("#modal1").modal("hide");
          Swal.fire({
            title: "¿Estás Seguro?",
            html: lee.mensaje,
            showCancelButton: true,
            confirmButtonText: "Distribuir",
            confirmButtonColor: "#007bff",
            cancelButtonText: `Cancelar`,
            icon: "warning",
          }).then((result) => {
            if (result.isConfirmed) {
              $("#accion").val("distribuir_deuda");
              $("#id_deuda").val(lee.id);
              var datos = new FormData($("#f")[0]);
              enviaAjax(datos);
            }
          });
        } else if (lee.resultado == "distribuir_deuda") {
          muestraMensaje(lee.mensaje, "", "success");
          limpia();
          carga_deudas();
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
  $("#id_deuda").val("");
  $("#monto").val("");
  $("#concepto").val("");
  $("#fecha").val("");
  limpiarvalidacion();
}
function limpiarerror() {
  $("#smonto").text("");
  $("#sfecha").text("");
  $("#sconcepto").text("");
}
function limpiarvalidacion() {
  $("form input").removeClass("is-valid");
  $("form input").removeClass("is-invalid");
}
function agregarvalidacion() {
  $("form input").addClass("is-valid");
}