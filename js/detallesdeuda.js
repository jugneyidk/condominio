$(document).ready(function () {
  carga_deudas();
  //carga_historial();
  load_tipo_pago_comun();
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
      /^[0-9.]{1,8}$/,
      $(this),
      $("#smonto"),
      "Solo numeros (012...) y puntos (.)"
    );
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
  $("#btn-historial").on("click", function () {
    carga_historial();
  });
});
function carga_deudas() {
  var datos = new FormData();
  datos.append("accion", "listadodeudas");
  enviaAjax(datos);
}
function carga_historial() {
  var datos = new FormData();
  datos.append("accion", "historialpagos");
  enviaAjax(datos);
}
function mostrar_registrar_pago(linea) {
  $("#id_deuda_tosend").val(linea.parentNode.parentNode.dataset.id);
  var divisa_temp = linea.parentNode.parentNode.querySelector("td:nth-child(5)").innerHTML;

  var monto = Number((parseFloat(divisa_temp) * Datos_divisa.monto).toFixed(2));
  console.log(monto);
  document.getElementById('monto_para_calcular').innerHTML="El total a pagar es: "+sepMiles(monto.toFixed(2))+`Bs (${divisa_temp}$)`;
  document.getElementById('monto_para_calcular').dataset.monto_a_pagar=monto;



  //var id = obtener_id(linea);
  // $("#id_deuda").val(id);
  // $("#monto").val(obtener_monto(linea));
  $("#registrarpago").modal("show");
}
function obtener_monto(linea) {
  var linea = $(linea).closest("tr");
  var monto = $(linea).find("td:eq(4)").text();
  return monto;
}
function obtener_id(linea) {
  var linea = $(linea).closest("tr");
  var id = $(linea).find("td:eq(0)").text();
  return id;
}
function registrar_pago() {

if(validar_tipo_pago_comunes()){
  var monto_para_calcular_total = parseFloat(document.getElementById('monto_para_calcular').dataset.monto_a_pagar);
  var monto_a_registrar=Number(parseFloat(sepMiles(document.getElementById('tipo_pago_comun-monto_total').value,true)).toFixed(2));
  if(monto_a_registrar>=monto_para_calcular_total){


    Swal.fire({
      title: "¿Estás Seguro?",
      text: "¿Está seguro que desea registrar el pago?",
      showCancelButton: true,
      confirmButtonText: "Registrar",
      confirmButtonColor: "#007bff", // amarillo #ffc107 rojo #dc3545 azul #007bff
      cancelButtonText: `Cancelar`,
      icon: "info",
    }).then((result) => {
      if (result.isConfirmed) {
        
        var datos = new FormData($("#f")[0]);
        datos.append("accion","registrarpago");
        datos.append("obj_pagos", JSON.stringify(objeto_tipo_pago_comun()));
        enviaAjax2(datos,function(respuesta){
        
          var lee = JSON.parse(respuesta);
          if(lee.resultado == "registrarpago"){
            borrar();
            
          }
          else if (lee.resultado == 'is-invalid'){
            muestraMensaje("ERROR", lee.mensaje,"error");
          }
          else if(lee.resultado == "error"){
            muestraMensaje("ERROR", lee.mensaje,"error");
            console.error(lee.mensaje);
          }
          else if(lee.resultado == "console"){
            console.log(lee.mensaje);
          }
          else{
            muestraMensaje("ERROR", lee.mensaje,"error");
          }
        }).then(()=>{
          $("#registrarpago").modal("hide");
          carga_deudas();
        });

      }
    });

  }
  else{
    muestraMensaje("El monto no es el correcto \n ( "+monto_para_calcular_total+"Bs )", '', "error");
  }
}












  // if (validarEnvio()) {
  //   var datos = new FormData($("#f")[0]);
  //   datos.append("accion", "registrarpago");
  //   enviaAjax(datos);
  // }
}

function eliminar_pagos(elem){
  var deuda_id = elem.parentNode.parentNode.dataset.id;
  var id_pago = elem.parentNode.parentNode.dataset.id_pago;
  if(deuda_id){
    Swal.fire({
      title: "¿Estás Seguro?",
      text: "¿Está seguro que desea Eliminar el pago?",
      showCancelButton: true,
      confirmButtonText: "Eliminar",
      confirmButtonColor: "#dc3545", // amarillo #ffc107 rojo #dc3545 azul #007bff
      cancelButtonText: `Cancelar`,
      icon: "warning",
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData();
        datos.append("accion","eliminar_pagos");
        datos.append("eliminar_id", deuda_id);
        datos.append("id_pago", id_pago);

        enviaAjax2(datos,function(respuesta){
        
          var lee = JSON.parse(respuesta);
          if(lee.resultado == "eliminar_pagos"){
            muestraMensaje("", lee.mensaje, "success");
          }
          else if (lee.resultado == 'is-invalid'){
            muestraMensaje("ERROR", lee.mensaje,"error");
          }
          else if(lee.resultado == "error"){
            muestraMensaje("ERROR", lee.mensaje,"error");
            console.error(lee.mensaje);
          }
          else if(lee.resultado == "console"){
            console.log(lee.mensaje);
          }
          else{
            muestraMensaje("ERROR", lee.mensaje,"error");
          }
        }).then(()=>{
          carga_deudas();
        });
    
      }
    });
  }
}


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
  } else if (
    $("#tipo_pago").val() != "Efectivo" &&
    $("#tipo_pago").val() != "Pago Movil" &&
    $("#tipo_pago").val() != "Transferencia Bancaria" &&
    $("#tipo_pago").val() != "Zelle"
  ) {
    $("#stipo_pago").text("Debe elegir un tipo de pago");
    return false;
  }
  return true;
}
function limpiar_modal() {
  $("#id_deuda").val("");
  $("#monto").val("");
  $("#tipo_pago").val("-");
  $("#referencia").val("");
  $("#stipo_pago").text("");
  $("#sreferencia").text("");
  $("#smonto").text("");
  limpiarvalidacion();
}
// function validarKeyPress(e, er) {
//   codigo = e.keyCode;
//   tecla = String.fromCharCode(codigo);
//   if (er.test(tecla) === false) {
//     e.preventDefault();
//   }
// }
// function validarKeyUp(er, etiqueta, etiquetamensaje, mensaje) {
//   a = er.test(etiqueta.val());
//   if (a) {
//     if (etiqueta.hasClass("is-invalid")) {
//       etiqueta.toggleClass("is-invalid");
//     }
//     if (!etiqueta.hasClass("is-valid")) {
//       etiqueta.toggleClass("is-valid");
//     }
//     etiquetamensaje.text("");
//     return 1;
//   } else {
//     if (etiqueta.hasClass("is-valid")) {
//       etiqueta.toggleClass("is-valid");
//     }
//     if (!etiqueta.hasClass("is-invalid")) {
//       etiqueta.toggleClass("is-invalid");
//     }
//     etiquetamensaje.text(mensaje);
//     return 0;
//   }
// }
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
    
    success: function (respuesta) {
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "listadodeudas") {
          if ($.fn.DataTable.isDataTable("#tabladeudas")) {
            $("#tabladeudas").DataTable().destroy();
          }
          $("#listadodeudas").html("");
          if (!$.fn.DataTable.isDataTable("#tabladeudas")) {
            $("#tabladeudas").DataTable({
              // columnDefs: [
              //   { targets: [5], orderable: false, searchable: false },
              // ],
              data:lee.mensaje,
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No hay deudas pendientes",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay deudas pendientes",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                  first: "Primera",
                  last: "Última",
                  next: "Siguiente",
                  previous: "Anterior",
                },
              },
              "ordering": false,
              columns: [
                  { data: "num_letra_apartamento" },
                  { data: "torre" },
                  { data: "concepto" },
                  { data: "fecha" },
                  { data: "monto" },
                  { data: "extra" }
                      ],
              createdRow: function(row,data){
                //console.table(data);
                
                
                row.dataset.id=data.id_deuda;
                if(data.id_pago){
                  row.dataset.id_pago = data.id_pago;
                }
                row.querySelector("td:nth-child(2)").classList.add("d-none","d-md-table-cell");
                row.querySelector("td:nth-child(3)").classList.add("d-none","d-md-table-cell");
                row.querySelector("td:nth-child(4)").classList.add("text-nowrap");
                row.querySelector("td:last-child").classList.add("text-center");
                if(data.estado != '0'){
                  row.querySelector("td:last-child").innerHTML="<button class='btn btn-success' style='font-size: 13px;' onclick='mostrar_registrar_pago(this)'>Pagar</button>";
                }
                else{
                  row.querySelector("td:last-child").innerHTML="<u class=\"no-select\" style=\"font-size: .6rem;\">por confirmar</u><br><button class='btn btn-danger' style='font-size: 13px;' onclick='eliminar_pagos(this)'>eliminar</button>";

                }
                
              },

              autoWidth: false,
              //order: [[1, "desc"]],
              // paging: false,
              searching: false,
              info: false,
            });
          }
        } else if (lee.resultado == "historialpagos") {
          if ($.fn.DataTable.isDataTable("#tablahistorial")) {
            $("#tablahistorial").DataTable().destroy();
          }
          $("#listahistorial").html("");
          if (!$.fn.DataTable.isDataTable("#tablahistorial")) {
            $("#tablahistorial").DataTable({
              data:lee.mensaje,
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
              createdRow: function(row,data){
                row.querySelector("td:nth-child(2)").classList.add("text-nowrap");
                row.querySelector("td:nth-child(3)").classList.add("text-nowrap");
                row.querySelector("td:nth-child(4)").classList.add("text-nowrap");
                row.querySelector("td:nth-child(5)").classList.add("text-nowrap");
                row.querySelector("td:nth-child(6)").classList.add("text-nowrap");
                row.querySelector("td:nth-child(6)").innerText = sepMiles(row.querySelector("td:nth-child(6)").innerText) + " Bs";


                console.log(data);
              }
            });
            $("#historialpagos").modal("show");
          }
        } else if (lee.resultado == "registrado") {
          carga_deudas();
          carga_historial();
          $("#registrarpago").modal("hide");
          limpiar_modal();
          muestraMensaje(lee.mensaje, "", "success");
        } else if (lee.resultado == "error") {
          muestraMensaje(lee.mensaje, "", "error");
        }
      } catch (e) {
        alert("Error en JSONxxxx " + e.name + " !!!");
        console.log(e);
        console.log(respuesta);
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


function enviaAjax2(datos, func_success,func_beforesend="modal") {
  if(typeof func_success !== "function"){
    console.error("falta la funcion success");
  }
  return new Promise(function(exito,fail) {
    $.ajax({
      async: true,
      url: "",
      type: "POST",
      contentType: false,
      data: datos,
      processData: false,
      cache: false,
      beforeSend: function () {
        if(func_beforesend=="modal"){
          modalcarga(true);
        }
        else if(typeof func_beforesend === "function"){
          func_beforesend();
          modalcarga();
        }
      },
      success: function (respuesta) {
        try {
          if(typeof func_success === "function"){
            func_success(respuesta);
            
          }
          else throw "No hay una función definida";

        } catch (e) {
          fail(e);
          alert("Error en " + e.name + " !!!");
          console.error(e);
          console.log(respuesta);
        }
      },
      error: function (request, status, err) {
        modalcarga(false);
        if (status == "timeout") {
          muestraMensaje("Servidor Ocupado", "Intente de nuevo", "error");

        } 
        else if(request.readyState===0){
          muestraMensaje("No Hay Conexión Con El Servidor", "Intente de nuevo", "error");
        }
        else {
          muestraMensaje("Error", request + status + err, "error");
        }
        fail(request, status, err);
      },
      complete: function (xhr, status) {
        modalcarga(false).then(function() {
          if(status === "success"){
            exito(xhr.responseText);
          }
        });
      },
    });
  })
  
}

function borrar() {
 $('input:not(:checkbox):not(:radio)').val("");
 // $("form input").val("");
 $("form select").val("");
 limpiarvalidacion();
}