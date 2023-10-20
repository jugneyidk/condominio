$(document).ready(function () {
  $("#usuario").on("keypress", function (e) {
    validarkeypress(/^[0-9]*$/, e);
  });

  $("#usuario").on("keyup", function () {
    validarkeyup(
      /^[0-9]{7,9}$/,
      $(this),
      $("#susuario"),
      "El formato debe ser 12345678"
    );
  });

  $("#clave").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z0-9]*/, e);
  });

  $("#clave").on("keyup", function () {
    validarkeyup(
      /^[A-Za-z0-9]{6,20}$/,
      $(this),
      $("#sclave"),
      "Solo letras y/o numeros y/o # - entre 6 y 20 caracteres"
    );
  });

  //FIN DE VALIDACION DE DATOS

  //CONTROL DE BOTONES

  $("#entrar").on("click", function () {
    if (validarenvio()) {
      var datos = new FormData($("#f")[0]);
      datos.append("accion", "iniciar");
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
        if (lee.resultado == "correcto") {
          location = ".";
        } else if (lee.resultado == "incorrecto") {
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
    complete: function () {
      modalcarga(false);
    },
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
      /^[0-9]{7,9}$/,
      $("#usuario"),
      $("#susuario"),
      "El formato debe ser 12345678"
    ) == 0
  ) {
    muestraMensaje("Error", "El formato de usuario debe ser 12345678", "error");
    return false;
  } else if (
    validarkeyup(
      /^[A-Za-z0-9,#\b\s\u00f1\u00d1\u00E0-\u00FC-]{6,20}$/,
      $("#clave"),
      $("#sclave"),
      "Solo letras y/o numeros y/o # - entre 6 y 20 caracteres"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "La clave debe ser solo letras y/o numeros y/o # - entre 6 y 20 caracteres",
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
