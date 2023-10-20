$(document).ready(function () {
  borrar();
  carga_apartamentos();
  carga_habitantes();
  carga_tipos();
  $("#numapto").on("keypress", function (e) {
    validarKeyPress(e, /^[A-Za-z0-9-\b]$/);
  });
  $("#numapto").on("keyup", function () {

    $(this).val(function(i,value){
      return value.replace(/^[a-zA-Z]/, function(v) { return v.toUpperCase(); });
    })
    validarKeyUp(
      /^[A-Z]-[0-9]{1,3}$/,
      $(this),
      $("#snumapto"),
      "Debe ingresar un número de apartamento (X-123)"
    );
  });
  $("#torre").on("change", function () {
    if ($("#torre").val()) {
      $("#storre").text("");
      if ($("#torre").hasClass("is-invalid")) {
        $("#torre").toggleClass("is-invalid");
      }
      if (!$("#torre").hasClass("is-valid")) {
        $("#torre").toggleClass("is-valid");
      }
    } else if ($("#torre").val() == "-") {
      $("#storre").text("Debe elegir una torre");
      if (!$("#torre").hasClass("is-invalid")) {
        $("#torre").toggleClass("is-invalid");
      }
    }
  });
  $("#piso").on("change", function () {
    if ($("#piso").val()) {
      $("#spiso").text("");
      if ($("#piso").hasClass("is-invalid")) {
        $("#piso").toggleClass("is-invalid");
      }
      if (!$("#piso").hasClass("is-valid")) {
        $("#piso").toggleClass("is-valid");
      }
    } else if ($("#piso").val() == "-") {
      $("#spiso").text("Debe elegir un piso");
      if (!$("#piso").hasClass("is-invalid")) {
        $("#piso").toggleClass("is-invalid");
      }
    }
  });
  $("#tipoapto").on("change", function () {
    if ($("#tipoapto").val()) {
      $("#stipoapto").text("");
      if ($("#tipoapto").hasClass("is-invalid")) {
        $("#tipoapto").toggleClass("is-invalid");
      }
      if (!$("#tipoapto").hasClass("is-valid")) {
        $("#tipoapto").toggleClass("is-valid");
      }
    } else if ($("#tipoapto").val() == "-") {
      $("#stipoapto").text("Debe elegir un tipo de apartamento");
      if (!$("#tipoapto").hasClass("is-invalid")) {
        $("#tipoapto").toggleClass("is-invalid");
      }
    }
  });
  $("#propietario").on("change", function () {
    if ($("#propietario").val()) {
      $("#spropietario").text("");
    }
  });
  $("#inquilino").on("change", function () {
    if ($("#inquilino").val()) {
      $("#sinquilino").text("");
    }
  });

  $("#listadodeapartamentos").on("click", function () {
    $("#modalapartamentos").modal("show");
  });
  $("#listadopropietarios").on("click", function () {
    $("#modalhabitantes").modal("show");
    $("#campo-habitante").val("propietario");
  });
  $("#listadoinquilinos").on("click", function () {
    $("#modalhabitantes").modal("show");
    $("#campo-habitante").val("inquilino");
  });
  $("#inquilinobool1").on("click", function () {
    $("#listadoinquilinos").prop("disabled", false);
  });
  $("#inquilinobool2").on("click", function () {
    $("#listadoinquilinos").prop("disabled", true);
    $("#inquilino").val("0");
    $("#inquilinoci").val("");
    $("#detalleshabitante2").html("");
    $("#inquilinoci").removeClass("is-valid");
  });
  $("#limpiar").on("click", function () {
    borrar();
    limpiarerror();
    cambiarbotones(true);
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
function carga_apartamentos() {
  var datos = new FormData();
  datos.append("accion", "listadoapartamentos");
  enviaAjax(datos);
}
function carga_habitantes() {
  var datos = new FormData();
  datos.append("accion", "listadohabitantes");
  enviaAjax(datos);
}
function carga_tipos() {
  var datos = new FormData();
  datos.append("accion", "listadotipo");
  enviaAjax(datos);
}
$("#incluir").on("click", function () {
  if (validarEnvio()) {
    $("#accion").val("incluir");
    var datos = new FormData($("#f")[0]);
    enviaAjax(datos);
  }
});
$("#modificar").on("click", function () {
  if (validarEnvio()) {
    Swal.fire({
      title: "¿Estás Seguro?",
      text: "¿Está seguro que desea modificar este apartamento?",
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
  Swal.fire({
    title: "¿Estás Seguro?",
    text: "¿Está seguro que desea eliminar este apartamento?",
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
});
function validarEnvio() {
  if (!$("#torre").val()) {
    muestraMensaje("Error", "Debe elegir una torre", "error");
    $("#storre").text("Debe elegir una torre");
    if (!$("#torre").hasClass("is-invalid")) {
      $("#torre").toggleClass("is-invalid");
    }
    return false;
  } else if (!$("#piso").val()) {
    muestraMensaje("Error", "Debe elegir un piso", "error");
    $("#spiso").text("Debe elegir un piso");
    if (!$("#piso").hasClass("is-invalid")) {
      $("#piso").toggleClass("is-invalid");
    }
    return false;
  } else if (
    validarKeyUp(
      /^[A-Z]-[0-9]{1,3}$/,
      $("#numapto"),
      $("#snumapto"),
      "Debe ingresar un número de apartamento (X-123)"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "Debe ingresar un número de apartamento (X-123)",
      "error"
    );
    return false;
  } else if (!$("#tipoapto").val()) {
    muestraMensaje("Error", "Debe elegir un tipo de apartamento", "error");
    $("#stipoapto").text("Debe elegir un tipo de apartamento");
    if (!$("#tipoapto").hasClass("is-invalid")) {
      $("#tipoapto").toggleClass("is-invalid");
    }
    return false;
  } else if (!$("#propietario").val()) {
    muestraMensaje("Error", "Debe elegir un propietario", "error");
    $("#spropietario").text("Debe elegir un propietario");
    if (!$("#propietarioci").hasClass("is-invalid")) {
      $("#propietarioci").toggleClass("is-invalid");
    }
    return false;
  } else if (
    $("#inquilinobool1").is(":checked") &&
    (!$("#inquilino").val() || $("#inquilino").val() == 0)
  ) {
    muestraMensaje("Error", "Debe elegir un inquilino", "error");
    $("#sinquilino").text("Debe elegir un inquilino");
    return false;
  } else if ($("#inquilino").val() == $("#propietario").val()) {
    muestraMensaje(
      "Error",
      "El inquilino no puede ser igual al propietario",
      "error"
    );
    return false;
  } else if ($("#inquilinobool2").is(":checked")) {
    $("#inquilino").val("");
  }
  limpiarerror();
  return true;
}
function colocaapartamento(linea) {
  $("#id").val($(linea).find("td:eq(0)").text());
  $("#numapto").val($(linea).find("td:eq(1)").text());
  $("#tipoapto").val($(linea).find("td:eq(6)").text());
  $("#piso").val($(linea).find("td:eq(8)").text());
  $("#torre").val($(linea).find("td:eq(9)").text());
  $("#inquilino").val($(linea).find("td:eq(4)").text());
  if ($("#inquilino").val()) {
    actualizarhabitante($("#inquilino").val(), 2);
  } else {
    $("#inquilinoci").val("");
    $("#inquilinobool2").trigger("click");
  }
  $("#propietario").val($(linea).find("td:eq(2)").text());
  actualizarhabitante($("#propietario").val(), 1);
  cambiarbotones(false);
  limpiarerror();
  $("#modalapartamentos").modal("hide");
  limpiarvalidacion();
  agregarvalidacion();
  if ($("#inquilino").val() == "" || $("#inquilino").val() == 0) {
    $("#inquilinoci").removeClass("is-valid");
  }
}
function colocahabitante(linea) {
  switch ($("#campo-habitante").val()) {
    case "inquilino":
      $("#inquilino").val($(linea).find("td:eq(0)").text()).change();
      $("#inquilinoci").val(
        $(linea).find("td:eq(1)").text() +
          "-" +
          $(linea).find("td:eq(2)").text() +
          " - " +
          $(linea).find("td:eq(3)").text() +
          " " +
          $(linea).find("td:eq(4)").text()
      );
      if ($("#inquilinoci").hasClass("is-invalid")) {
        $("#inquilinoci").toggleClass("is-invalid");
      }
      if (!$("#inquilinoci").hasClass("is-valid")) {
        $("#inquilinoci").toggleClass("is-valid");
      }
      break;
    case "propietario":
      $("#propietario").val($(linea).find("td:eq(0)").text()).change();
      $("#propietarioci").val(
        $(linea).find("td:eq(1)").text() +
          "-" +
          $(linea).find("td:eq(2)").text() +
          " - " +
          $(linea).find("td:eq(3)").text() +
          " " +
          $(linea).find("td:eq(4)").text()
      );
      if ($("#propietarioci").hasClass("is-invalid")) {
        $("#propietarioci").toggleClass("is-invalid");
      }
      if (!$("#propietarioci").hasClass("is-valid")) {
        $("#propietarioci").toggleClass("is-valid");
      }
    default:
      break;
  }
  $("#modalhabitantes").modal("hide");
}
function actualizarhabitante(id, caso) {
  switch (caso) {
    case 1:
      $("#listadohabitantes tr").each(function () {
        if (id == $(this).find("td:eq(0)").text()) {
          $("#campo-habitante").val("propietario");
          colocahabitante($(this));
        }
      });
      break;
    case 2:
      $("#listadohabitantes tr").each(function () {
        if (id == $(this).find("td:eq(0)").text()) {
          $("#campo-habitante").val("inquilino");
          colocahabitante($(this));
        }
      });
      $("#inquilinobool1").trigger("click");
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
    timeout: 10000,
    success: function (respuesta) {
      console.log(datos);
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "listadoapartamentos") {
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
        } else if (lee.resultado == "listadohabitantes") {
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
        } else if (
          lee.resultado == "incluir" ||
          lee.resultado == "modificar" ||
          lee.resultado == "eliminar"
        ) {
          muestraMensaje(lee.mensaje, "", "success");
          borrar();
          carga_apartamentos();
          cambiarbotones(true);
        } else if (lee.resultado == "listadotipo") {
          $("#tipoapto").html(lee.mensaje);
        } else if (lee.resultado == "error") {
          muestraMensaje(lee.mensaje, "", "error");
        }

      } catch (e) {
        alert("Error en JSON " + e.name + " xxxx!!!");
        console.error(e);
        // console.log(respuesta);
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
function limpiarerror() {
  $("#storre").text("");
  $("#spiso").text("");
  $("#snumapto").text("");
  $("#stipoapto").text("");
  $("#spropietario").text("");
  $("#sinquilino").text("");
}
function borrar() {
  $("#id").val("");
  $("#torre").val("");
  $("#tipo").val("");
  $("#piso").val("");
  $("#numapto").val("");
  $("#tipoapto").val("");
  $("#propietario").val("");
  $("#inquilino").val("");
  $("#propietarioci").val("");
  $("#inquilinoci").val("");
  $("#accion").val("");
  $("#inquilinobool2").trigger("click");
  limpiarvalidacion();
}
function borrardatos() {
  $("#id").val("");
  $("#tipo").val("");
  $("#tipoapto").val("");
  $("#propietario").val("");
  $("#inquilino").val("");
  $("#propietarioci").val("");
  $("#inquilinoci").val("");
  $("#accion").val("");
  $("#inquilinobool2").trigger("click");
  limpiarvalidacion();
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
