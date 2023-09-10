$(document).ready(function () {
  carga_habitantes();
  carga_apartamentos();
  $("#listado").on("click", function () {
    switch ($("#tiporeporte").val()) {
      case "2":
        $("#modalhabitantes").modal("show");
        break;
      case "3":
        $("#modalapartamentos").modal("show");
        break;
      default:
        break;
    }
  });
  $("#id").on("change", function () {
    if ($("#id").val() != "") {
      switch ($("#tiporeporte").val()) {
        case "2":
          $("#listadohabitantes tr").each(function () {
            if ($("#id").val() == $(this).find("td:eq(7)").text()) {
              coloca_reporte($(this), 1);
            }
          });
          break;
        case "3":
          $("#listadoapartamentos tr").each(function () {
            if ($("#id").val() == $(this).find("td:eq(0)").text()) {
              coloca_reporte($(this), 2);
            }
          });
          break;
        default:
          break;
      }
    }
  });
  $("#modalhabitantes").on("hide.bs.modal", function () {
    if ($("#id").val() == "") {
      $("#tiporeporte").val("1");
      $("#tiporeporte").trigger("change");
    }
  });
  $("#modalapartamentos").on("hide.bs.modal", function () {
    if ($("#id").val() == "") {
      $("#tiporeporte").val("1");
      $("#tiporeporte").trigger("change");
    }
  });
  $("#tiporeporte").change(function () {
    switch ($("#tiporeporte").val()) {
      case "1":
        limpia();
        ocultar_mensaje_reporte();
        habilitar_deshabilitar_boton(true);
        break;
      case "2":
        limpia();
        ocultar_mensaje_reporte();
        $("#modalhabitantes").modal("show");
        habilitar_deshabilitar_boton(false);
        break;
      case "3":
        limpia();
        ocultar_mensaje_reporte();
        $("#modalapartamentos").modal("show");
        habilitar_deshabilitar_boton(false);
        break;
      default:
        break;
    }
  });
});
function coloca(linea) {
  $("#id").val($(linea).find("td:eq(7)").text());
  $("#id").trigger("change");
  mostrar_mensaje_reporte();
  $("#modalhabitantes").modal("hide");
}
function colocaapartamento(linea) {
  $("#id").val($(linea).find("td:eq(0)").text());
  $("#id").trigger("change");
  mostrar_mensaje_reporte();
  $("#modalapartamentos").modal("hide");
}
function coloca_reporte(linea, caso) {
  switch (caso) {
    case 1:
      $("#reporte_a_generar").text(
        $(linea).find("td:eq(0)").text() +
          "-" +
          $(linea).find("td:eq(1)").text() +
          " - " +
          $(linea).find("td:eq(2)").text() +
          " " +
          $(linea).find("td:eq(3)").text()
      );
      break;
    case 2:
      $("#reporte_a_generar").text(
        $(linea).find("td:eq(1)").text() +
          " - " +
          $(linea).find("td:eq(3)").text() +
          " - " +
          " Torre " +
          $(linea).find("td:eq(9)").text() +
          " - Piso " +
          $(linea).find("td:eq(8)").text()
      );
      break;
    default:
      break;
  }
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
function carga_habitantes() {
  var datos = new FormData();
  datos.append("accion", "listado_habitantes");
  enviaAjax(datos);
}
function habilitar_deshabilitar_boton(parametro) {
  $("#listado").prop("disabled", parametro);
}
function mostrar_mensaje_reporte() {
  if ($("#mensaje_reporte").hasClass("d-none") && $("#id").val() != "") {
    $("#mensaje_reporte").toggleClass("d-none");
  }
}
function ocultar_mensaje_reporte() {
  if (!$("#mensaje_reporte").hasClass("d-none")) {
    $("#mensaje_reporte").toggleClass("d-none");
  }
}
function carga_apartamentos() {
  var datos = new FormData();
  datos.append("accion", "listadoapartamentos");
  enviaAjax(datos);
}
function limpia() {
  $("#id").val("");
  $("#reporte_a_generar").text("");
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
    timeout: 10000,
    beforeSend: function () {
      modalcarga(true);
    },
    success: function (respuesta) {
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "listado_habitantes") {
          if ($.fn.DataTable.isDataTable("#tablahabitantes")) {
            $("#tablahabitantes").DataTable().destroy();
          }
          $("#listadohabitantes").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tablahabitantes")) {
            $("#tablahabitantes").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron habitantes",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay habitantes disponibles",
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
        } else if (lee.resultado == "listadoapartamentos") {
          if ($.fn.DataTable.isDataTable("#tablaapartamentos")) {
            $("#tablaapartamentos").DataTable().destroy();
          }
          $("#listadoapartamentos").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tablaapartamentos")) {
            $("#tablaapartamentos").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron apartamentos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay apartamentos disponibles",
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
              columns: [
                { data: "col1" },
                { data: "col2" },
                { data: "col3" },
                { data: "col4" },
                { data: "col5" },
                {
                  data: "col6",
                  render: function (data) {
                    if (data) {
                      return data;
                    } else if (data == "") {
                      return "<span class='badge badge-pill badge-secondary'>Sin inquilino</span>";
                    }
                  },
                },
                { data: "col7" },
                { data: "col8" },
                { data: "col9" },
                { data: "col10" },
              ],
            });
          }
        } else if (lee.resultado == "error") {
          $("#listadoapartamentos").html(lee.mensaje);
        }
      } catch (e) {
        alert("Error en JSON " + e.name);
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
