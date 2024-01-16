$(document).ready(function () {
  
  cedulaKeypress(document.getElementById('usuario'));

  $("#usuario").on("keyup", function () {
    validarkeyup(
      /(?:(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i,
      $(this),
      $("#susuario"),
      "La cedula no es valida ej.(V-0000000)"
    );
  });

  document.getElementById('clave').maxLength=20;

  // $("#clave").on("keypress", function (e) {
  //   validarkeypress(/^[0-9\b]*$/, e);
  // });

  // $("#clave").on("keyup", function () {
  //   validarkeyup(
  //     /^[0-9]{7,9}$/,
  //     $(this),
  //     $("#sclave"),
  //     "El formato debe ser 12345678"
  //   );
  // });

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
      /(?:(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i,
      $("#usuario"),
      $("#susuario"),
      "La cedula no es valida ej.(V-0000000)"
    ) == 0
  ) {
    muestraMensaje(
      "Error",
      "El formato de la cedula debe ser: V-0000000",
      "error"
    );
    return false;
  }
  console.log("hola");
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
    return false;
  }
  return true;
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

function cedulaKeypress(tag){
  tag.onkeypress=function(e){
    tecla = String.fromCharCode(e.keyCode);
    var cont_tecla_letra;

    if(!/^[vejg][-]?/i.test(this.value) && (!(cont_tecla_letra = /^[vejg]$/i.test(tecla)))){
      this.value = this.value.replace(/[^0-9]/g,"");
      this.value= "V-"+this.value;
      if(this.value.length >= this.value.maxLength){e.preventDefault();return 0;}
      validarkeypress(/^[0-9]$/,e);
    }
    else if(cont_tecla_letra){
      this.value = this.value.replace(/[^0-9]/g,"");
      this.value = tecla.toUpperCase()+"-"+this.value;
      e.preventDefault();
    }
    else if(/^[vejg][-]?/i.test(this.value) && /^[vejg]$/i.test(tecla)){
      this.value = this.value.replace(/^[VEJG][-]*(.{0,10}).*/, tecla.toUpperCase()+"-$1");
      e.preventDefault();
    }
    else{
      validarkeypress(/^[0-9]$/,e);
    }

  }
  tag.maxLength = 12;
}
