$(document).ready(function () {
  $("#usuario").on("keypress", function (e) {
    validarkeypress(/^[aA-zZ0-9-.@\b]*$/, e);
  });

  $("#usuario").on("keyup", function () {
    validarkeyup(
      /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/,
      $(this),
      $("#susuario"),
      "El correo debe ser alguien@dominio.com"
    );
  });

  $("#clave").on("keypress", function (e) {
    validarkeypress(/^[0-9\b]*$/, e);
  });

  $("#clave").on("keyup", function () {
    validarkeyup(
      /^[0-9]{7,9}$/,
      $(this),
      $("#sclave"),
      "El formato debe ser 12345678"
    );
  });

  //FIN DE VALIDACION DE DATOS

  //CONTROL DE BOTONES

  $("#entrar").on("click", function () {
    if (validarenvio()) {
      var datos = new FormData($("#f")[0]);
      datos.append("accion","iniciar")
      enviaAjax(datos);
    }
  });
  //FIN DE CONTROL DE BOTONES
});
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
        if (lee.resultado=="correcto") {
          location = "?p=detallesdeuda";
        }else if (lee.resultado=="incorrecto") {
          muestraMensaje(lee.mensaje,"","error");       
        }
      } catch (e) {
        alert("Error en JSON " + e.name);
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
    complete: function(){
      modalcarga(false);
    }
  });
}
function limpia() {
  $("#usuario").val("");
  $("#clave").val("");
}

//Validación de todos los campos antes del envio
function validarenvio() {
  if (
    validarkeyup(
      /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/,
      $("#usuario"),
      $("#susuario"),
      "El correo debe ser alguien@dominio.com"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "El formato del usuario debe ser alguien@dominio.com",
      "error"
    );
    return false;
  } else if (
    validarkeyup(
      /^[0-9]{7,9}$/,
      $("#clave"),
      $("#sclave"),
      "El formato debe ser 12345678"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "El formato de la clave debe ser 12345678",
      "error"
    );
    return false;
  }
  return true;
}

//Funcion que muestra el modal con un mensaje
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
//Función para validar por Keypress
function validarkeypress(er, e) {
  key = e.keyCode;

  tecla = String.fromCharCode(key);

  a = er.test(tecla);

  if (!a) {
    e.preventDefault();
  }
}
//Función para validar por keyup
function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
  a = er.test(etiqueta.val());
  if (a) {
    etiquetamensaje.text("");
    return 1;
  } else {
    etiquetamensaje.text(mensaje);
    return 0;
  }
}
