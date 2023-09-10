$(document).ready(function () {
  carga_pagos();
  carga_pagos_pendientes();
  carga_pagos_confirmados();
  carga_pagos_declinados();
  $(function () {
    var hash = document.location.hash;
    if (hash) {
      $(".nav-tabs a[href=\\" + hash + "]").tab("show");
    }
    $('a[data-toggle="tab"]').on("show.bs.tab", function (e) {
      window.location.hash = e.target.hash;
    });
  });
});

function carga_pagos() {
  var datos = new FormData();
  datos.append("accion", "listadopagos");
  enviaAjax(datos);
}
function carga_pagos_pendientes() {
  var datos = new FormData();
  datos.append("accion", "listadopagospendientes");
  enviaAjax(datos);
}
function carga_pagos_confirmados() {
  var datos = new FormData();
  datos.append("accion", "listadopagosconfirmados");
  enviaAjax(datos);
}
function carga_pagos_declinados() {
  var datos = new FormData();
  datos.append("accion", "listadopagosdeclinados");
  enviaAjax(datos);
}
function carga_detalles_pago(linea) {
  var linea = $(linea).closest("tr");
  var id = $(linea).find("td:eq(0)").text();
  var datos = new FormData();
  datos.append("accion", "detallespago");
  datos.append("id", id);
  enviaAjax(datos);
}
function confirmar_declinar_pago(linea, accion) {
  var linea = $(linea).closest("tr");
  var id = $(linea).find("td:eq(0)").text();
  if (accion == 1) {
    $("#accion").val("confirmar");
    $("#id").val(id);
    Swal.fire({
      title: "¿Estás Seguro?",
      text: "¿Está seguro que desea confirmar este pago?",
      showCancelButton: true,
      confirmButtonText: "Confirmar",
      confirmButtonColor: "#007bff",
      cancelButtonText: `Cancelar`,
      icon: "warning",
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData();
        datos.append("accion", $("#accion").val());
        datos.append("id", $("#id").val());
        enviaAjax(datos);
      }
    });
  } else if (accion == 2) {
    $("#accion").val("declinar");
    $("#id").val(id);
    Swal.fire({
      title: "¿Estás Seguro?",
      text: "¿Está seguro que desea declinar este pago?",
      showCancelButton: true,
      confirmButtonText: "Declinar",
      confirmButtonColor: "#dc3545",
      cancelButtonText: `Cancelar`,
      icon: "warning",
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData();
        datos.append("accion", $("#accion").val());
        datos.append("id", $("#id").val());
        enviaAjax(datos);
      }
    });
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
        if (lee.resultado == "listadopagos") {
          if ($.fn.DataTable.isDataTable("#tablapagos")) {
            $("#tablapagos").DataTable().destroy();
          }
          $("#listadopagos").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tablapagos")) {
            $("#tablapagos").DataTable({
              columnDefs: [
                { targets: [5, 6], orderable: false, searchable: false },
              ],
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron pagos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay pagos disponibles",
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
              order: [[0, "desc"]],
            });
          }
        } else if (lee.resultado == "listadopagospendientes") {
          if ($.fn.DataTable.isDataTable("#tablapendiente")) {
            $("#tablapendiente").DataTable().destroy();
          }
          $("#listadopagospendientes").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tablapendiente")) {
            $("#tablapendiente").DataTable({
              columnDefs: [
                { targets: [5, 6], orderable: false, searchable: false },
              ],
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron pagos pendientes",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay pagos pendientes disponibles",
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
              order: [[0, "desc"]],
            });
          }
        } else if (lee.resultado == "listadopagosconfirmados") {
          if ($.fn.DataTable.isDataTable("#tablaconfirmado")) {
            $("#tablaconfirmado").DataTable().destroy();
          }
          $("#listadopagosconfirmados").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tablaconfirmado")) {
            $("#tablaconfirmado").DataTable({
              columnDefs: [
                { targets: [5, 6], orderable: false, searchable: false },
              ],
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron pagos confirmados",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay pagos confirmados",
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
              order: [[0, "desc"]],
            });
          }
        } else if (lee.resultado == "listadopagosdeclinados") {
          if ($.fn.DataTable.isDataTable("#tabladeclinado")) {
            $("#tabladeclinado").DataTable().destroy();
          }
          $("#listadopagosdeclinados").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tabladeclinado")) {
            $("#tabladeclinado").DataTable({
              columnDefs: [
                { targets: [5, 6], orderable: false, searchable: false },
              ],
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron pagos declinados",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay pagos declinados",
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
              order: [[0, "desc"]],
            });
          }
        } else if (lee.resultado == "detallespago") {
          $("#detallespago").html(lee.mensaje);
          $("#modaldetalles").modal("show");
        } else if (
          lee.resultado == "confirmado" ||
          lee.resultado == "declinado"
        ) {
          muestraMensaje(lee.mensaje, "", "success");
          carga_pagos();
          carga_pagos_pendientes();
          carga_pagos_confirmados();
          carga_pagos_declinados();
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
