// $(document).ready(function () {
//   carga_nomina();
// $("#cedula_rif").on("keypress", function (e) {
//     validarKeyPress(/^[0-9\b]*$/, e);
//   });
//   $("#tipo_identificacion").change(function () {
//     if (
//       validarKeyUp(
//         /^[0-9]{7,9}$/,
//         $("#cedula_rif"),
//         $("#scedularif"),
//         "El formato debe ser 12345678"
//       ) == 1 &&
//       $("#cedula_rif").val().length >= 0
//     ) {
//       $("#srifcedula").text("");
//     }
//   });
//   $("#cedula_rif").on("keyup", function () {
//     validarKeyUp(
//       /^[0-9]{7,9}$/,
//       $(this),
//       $("#scedularif"),
//       "El formato debe ser 12345678"
//     );
//   });
//   $("#nombres").on("keypress", function (e) {
//     validarKeyPress(/^[aA-zZ\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
//   });
//   $("#nombres").on("keyup", function () {
//     validarKeyUp(
//       /^[aA-zZ\s\u00f1\u00d1\u00E0-\u00FC]{3,45}$/,
//       $(this),
//       $("#snombres"),
//       "El nombre debe contener entre 5 y 45 caracteres"
//     );
//   });
//   $("#apellidos").on("keypress", function (e) {
//     validarKeyPress(/^[aA-zZ\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
//   });
//   $("#apellidos").on("keyup", function () {
//     validarKeyUp(
//       /^[aA-zZ\s\u00f1\u00d1\u00E0-\u00FC]{3,45}$/,
//       $(this),
//       $("#sapellidos"),
//       "El apellido debe contener entre 5 y 45 caracteres"
//     );
//   });
//   $("#domicilio_fiscal").on("keypress", function (e) {
//     validarKeyPress(/^[aA-zZ0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
//   });
//   $("#domicilio_fiscal").on("keyup", function () {
//     validarKeyUp(
//       /^[aA-zZ0-9\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/,
//       $(this),
//       $("#sdomicilio_fiscal"),
//       "El domicilio fiscal debe contener entre 5 y 50 caracteres"
//     );
//   });
//   $("#telefono").on("keypress", function (e) {
//     validarKeyPress(/^[0-9-\b]*$/, e);
//   });
//   $("#telefono").on("keyup", function () {
//     validarKeyUp(
//       /^[0][0-9]{3}[-][0-9]{7}$/,
//       $(this),
//       $("#stelefono"),
//       "El teléfono debe ser 0XXX-1234567"
//     );
//   });
//   $("#correo").on("keypress", function (e) {
//     validarKeyPress(/^[aA-zZ0-9-.@\b]*$/, e);
//   });
//   $("#correo").on("keyup", function () {
//     validarKeyUp(
//       /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/,
//       $(this),
//       $("#scorreo"),
//       "El correo debe ser alguien@dominio.com"
//     );
//   });
//   $("#descripcion").on("keypress", function (e) {
//     validarKeyPress(e, /^[A-Za-z0-9,#\b\s\u00f1\u00d1\u00E0-\u00FC-]*$/);
//   });
//   $("#descripcion").on("keyup", function () {
//     validarKeyUp(
//       /^[A-Za-z0-9,#\b\s\u00f1\u00d1\u00E0-\u00FC-]{6,50}$/,
//       $(this),
//       $("#sdescripcion"),
//       "Solo letras y/o numeros entre 6 y 50 caracteres"
//     );
//   });
//   $("#limpiar").on("click", function () {
//     limpia();
//     limpiarerror();
//     cambiarbotones(true);
//   });
//   $("#incluir").on("click", function () {
//     if (validarEnvio()) {
//       $("#accion").val("incluir");
//       var datos = new FormData($("#f")[0]);
//       enviaAjax(datos);
//       cambiarbotones(true);
//     }
//   });
//   $("#eliminar").on("click", function () {
//     if (!$("#id_tipo_apartamento").val()) {
//       muestraMensaje(
//         "Error",
//         "Debe seleccionar un tipo de apartamento antes de eliminarlo",
//         "error"
//       );
//     } else {
//       Swal.fire({
//         title: "¿Estás Seguro?",
//         text: "¿Está seguro que desea eliminar este tipo de apartamento?",
//         showCancelButton: true,
//         confirmButtonText: "Eliminar",
//         confirmButtonColor: "#dc3545",
//         cancelButtonText: `Cancelar`,
//         icon: "warning",
//       }).then((result) => {
//         if (result.isConfirmed) {
//           $("#accion").val("eliminar");
//           var datos = new FormData($("#f")[0]);
//           enviaAjax(datos);
//         }
//       });
//     }
//   });
//    $("#p-natural").on("click", function (e) {
//     $("#tipo_identificacion").val("0").change();
//     cambiarTipoIdent("0");
//     e.preventDefault();
//   });
//   $("#p-extranjero").on("click", function (e) {
//     $("#tipo_identificacion").val("1").change();
//     cambiarTipoIdent("1");
//     e.preventDefault();
//   });
//   $("#p-juridica").on("click", function (e) {
//     $("#tipo_identificacion").val("2").change();
//     cambiarTipoIdent("2");
//     e.preventDefault();
//   });
//   $("#tipo_identificacion").on("change", function () {
//     cambiarTipoIdent($("#tipo_identificacion").val());
//   });
//   $("#modificar").on("click", function () {
//     if (validarEnvio()) {
//       Swal.fire({
//         title: "¿Estás Seguro?",
//         text: "¿Está seguro que desea modificar este tipo de apartamento?",
//         showCancelButton: true,
//         confirmButtonText: "Modificar",
//         confirmButtonColor: "#007bff",
//         cancelButtonText: `Cancelar`,
//         icon: "warning",
//       }).then((result) => {
//         if (result.isConfirmed) {
//           $("#accion").val("modificar");
//           var datos = new FormData($("#f")[0]);
//           enviaAjax(datos);
//         }
//       });
//     }
//   });
// });

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
// function carga_nomina() {
//   var datos = new FormData();
//   datos.append("accion", "listadonomina");
//   enviaAjax(datos);
// }
// function cambiarTipoIdent(val) {
//   switch (val) {
//     case "0":
//       $("#dropIdent").text("V");
//       break;
//     case "1":
//       $("#dropIdent").text("E");
//       break;
//     case "2":
//       $("#dropIdent").text("J");
//       break;
//     default:
//       $("#dropIdent").text("-");
//       break;
//   }
// }
// function validarEnvio() {
//   if (
//     validarKeyUp(
//       /^[A-Za-z0-9,#\b\s\u00f1\u00d1\u00E0-\u00FC-]{6,50}$/,
//       $("#descripcion"),
//       $("#sdescripcion"),
//       "Solo letras y/o numeros entre 6 y 50 caracteres"
//     ) == 0
//   ) {
//     muestraMensaje(
//       "Error",
//       "La descripción debe tener solo letras y/o numeros entre 6 y 50 caracteres",
//       "error"
//     );
//     return false;
//   } else if(
//     validarKeyUp(
//       /^[0-9]{7,9}$/,
//       $("#cedula_rif"),
//       $("#scedularif"),
//       "El formato debe ser 12345678"
//     ) == 0
//   ) {
//     muestraMensaje(
//       "Error",
//       "La cedula o rif debe coincidir con el formato 12345678",
//       "error"
//     );
//     return false;
//   } else if (
//     $("#tipo_identificacion").val() != "0" &&
//     $("#tipo_identificacion").val() != "1" &&
//     $("#tipo_identificacion").val() != "2"
//   ) {
//     $("#scedularif").text("Debe elegir persona natural o jurídica");
//     muestraMensaje("Error", "Debe elegir persona natural o jurídica", "error");
//     return false;
//   } else if (
//     validarKeyUp(
//       /^[aA-zZ\s\u00f1\u00d1\u00E0-\u00FC]{3,45}$/,
//       $("#nombres"),
//       $("#snombres"),
//       "El nombre debe contener entre 5 y 45 caracteres"
//     ) == 0
//   ) {
//     muestraMensaje(
//       "Error",
//       "El nombre debe contener entre 5 y 45 caracteres",
//       "error"
//     );
//     return false;
//   } else if (
//     validarKeyUp(
//       /^[aA-zZ\s\u00f1\u00d1\u00E0-\u00FC]{3,45}$/,
//       $("#apellidos"),
//       $("#sapellidos"),
//       "El apellido debe contener entre 5 y 45 caracteres"
//     ) == 0
//   ) {
//     muestraMensaje(
//       "Error",
//       "El apellido debe contener entre 5 y 45 caracteres",
//       "error"
//     );
//     return false;
//   } else if (
//     validarKeyUp(
//       /^[aA-zZ0-9\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/,
//       $("#domicilio_fiscal"),
//       $("#sdomicilio_fiscal"),
//       "El domicilio fiscal debe contener entre 5 y 50 caracteres"
//     ) == 0
//   ) {
//     muestraMensaje(
//       "Error",
//       "El domicilio fiscal debe tener entre 5 y 50 caracteres",
//       "error"
//     );
//     return false;
//   } else if (
//     validarKeyUp(
//       /^[0][0-9]{3}[-][0-9]{7}$/,
//       $("#telefono"),
//       $("#stelefono"),
//       "El teléfono debe ser 0XXX-1234567"
//     ) == 0
//   ) {
//     muestraMensaje("Error", "El teléfono debe ser 0XXX-1234567", "error");
//     return false;
//   } else if (
//     validarKeyUp(
//       /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/,
//       $("#correo"),
//       $("#scorreo"),
//       "El correo debe ser alguien@dominio.com"
//     ) == 0
//   ) {
//     muestraMensaje("Error", "El correo debe ser alguien@dominio.com", "error");
//     return false;
//   }
//   return true;

//   return true;
// }
// function colocatipo(linea) {
//   $("#id_tipo_apartamento").val($(linea).find("td:eq(0)").text());
//   $("#descripcion").val($(linea).find("td:eq(1)").text());
//   $("#alicuota").val($(linea).find("td:eq(2)").text());
//   cambiarbotones(false);
//   $("#modal1").modal("hide");
//   limpiarerror();
//   limpiarvalidacion();
//   agregarvalidacion();
// }
// function muestraMensaje(titulo, mensaje, icono) {
//   Swal.fire({
//     title: titulo,
//     text: mensaje,
//     icon: icono,
//     showConfirmButton: false,
//     showCancelButton: true,
//     cancelButtonText: "Cerrar",
//   });
// }
// /*function carga_tipos() {
//   var datos = new FormData();
//   datos.append("accion", "listado_tipos");
//   enviaAjax(datos);
// }*/
// function cambiarbotones(parametro) {
//   $("#modificar").prop("disabled", parametro);
//   $("#eliminar").prop("disabled", parametro);
//   $("#incluir").prop("disabled", !parametro);
// }
// function enviaAjax(datos) {
//   $.ajax({
//     async: true,
//     url: "",
//     type: "POST",
//     contentType: false,
//     data: datos,
//     processData: false,
//     cache: false,
//     beforeSend: function () {
//       modalcarga(true);
//     },
//     timeout: 10000, //tiempo maximo de espera por la respuesta del servidor
//     success: function (respuesta) {
//       //si resulto exitosa la transmision
//       try {
//         var lee = JSON.parse(respuesta);
//         if (lee.resultado == "listado_nomina") {
//           $("#listadonomina").html(lee.mensaje);
//         } else if (
//           lee.resultado == "incluir" ||
//           lee.resultado == "modificar" ||
//           lee.resultado == "eliminar"
//         ) {
//           muestraMensaje(lee.mensaje, "", "success");
//           limpia();
//           carga_nomina();
//           cambiarbotones(true);
//         } else if (lee.resultado == "error") {
//           muestraMensaje(lee.mensaje, "", "error");
//         }
//       } catch (e) {
//         alert("Error en JSON " + e.name);
//       }
//     },
//     error: function (request, status, err) {
//       modalcarga(false);
//       // si ocurrio un error en la trasmicion
//       // o recepcion via ajax entra aca
//       // y se muestran los mensaje del error
//       if (status == "timeout") {
//         //pasa cuando superan los 10000 10 segundos de timeout
//         muestraMensaje("Servidor ocupado", "Intente de nuevo", "error");
//       } else {
//         //cuando ocurre otro error con ajax
//         muestraMensaje("Error", request + status + err, "error");
//       }
//     },
//     complete: function () {
//       modalcarga(false);
//     },
//   });
// }
// function limpia() {
//   $("#cedula_rif").val("");
//   $("#tipo_identificacion").val("");
//   $("#nombres").val("");
//   $("#apellidos").val("");
//   $("#domicilio_fiscal").val("");
//   $("#telefono").val("");
//   $("#correo").val("");
//   $("#descripcion").val("");
//   $("#metodo").val("");
//   $("#fecha").val("");
//   $("#monto").val("");
//   $("#referencia").val("");
//   limpiarvalidacion();
// }
// function limpiarerror() {
//   $("#scedula_rif").text("");
//   $("#stipo_identificacion").text("");
//   $("#snombres").text("");
//   $("#sapellidos").text("");
//   $("#sdomicilio_fiscal").text("");
//   $("#stelefono").text("");
//   $("#scorreo").text("");
//   $("#sdescripcion").text("");
//   $("#smetodo").text("");
//   $("#sfecha").text("");
//   $("#smonto").text("");
//   $("#sreferencia").text("");
// }
// function limpiarvalidacion() {
//   $("form input").removeClass("is-valid");
//   $("form input").removeClass("is-invalid");
// }
// function agregarvalidacion() {
//   $("form input").addClass("is-valid");
// }

