$(document).ready(function(){
    borrar();
    carga_apartamentos();
    cargar_estacionamientos();


    $("#numEstac").on("keypress", function (e) {
        validarKeyPress(e, /^[0-9]$/);
    });
    $("#numEstac").on("keyup", function (e) {
        validarKeyUp(/^[0-9]{1,3}$/,$(this),"Ingrese el numero de estacionamiento");
    });

    $("#numEstac").attr('maxlength','3');


    $("#estacCosto").on("keypress", function (e) {
        validarKeyPress(e, /^[0-9]$/);
    });
    $("#estacCosto").on("keyup",function(e){
        $(this).val(sepMiles($(this).val()));
        validarKeyUp(/^\d{1,3}(?:[\.]\d{3})*[,]\d{2}$/,$(this),"Ingrese un monto valido ej. 250,00");
    });
    document.getElementById('estacCosto').onpaste=function(e){
        e.preventDefault();
    }

    $("#btn_listaApartamentos").on("click",function(){
        $("#modalapartamentos").modal("show");
    });

    $("#limpiar").on("click", function () {
        borrar();
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
    $("#modificar").on("click", function () {
      if (validarEnvio()) {
        Swal.fire({
          title: "¿Estás Seguro?",
          text: "¿Está seguro que desea modificar numero estacionamiento: \""+ $("#id").val()+"\" ?",
          showCancelButton: true,
          confirmButtonText: "Modificar",
          confirmButtonColor: "#007bff",
          cancelButtonText: `Cancelar`,
          icon: "warning",
        }).then((result) => {
          if (result.isConfirmed) {
            $("#accion").val("modificar");
            if(!$("#id").val()){
                $("#id").val($("#numEstac"))
            }
            var datos = new FormData($("#f")[0]);
            enviaAjax(datos);
          }
        });
      }
    });
    $("#eliminar").on("click", function () {
      Swal.fire({
        title: "¿Estás Seguro?",
        text: "¿Está seguro que desea eliminar el numero de estacionamiento \""+ $("#numEstac").val()+"\" ?",
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

    
});



function validarEnvio(){

    if(!validarKeyUp(/^[0-9]{1,3}$/,$("#numEstac"),"Ingrese el numero de estacionamiento")){// valido numero de estacionamiento
        muestraMensaje("ERROR", "Ingrese el numero de estacionamiento","error");
        return false;
    }
    if(!validarKeyUp(/^\d{1,3}(?:[\.]\d{3})*[,]\d{2}$|^[^]{0}$/,$("#estacCosto"),"Ingrese un monto valido ej. 250,00")){
        muestraMensaje("ERROR", "Ingrese un monto valido ej. 250,00","error");
        return false;   
    }
    if(!validarKeyUp(/^[0-9]*$/,$("#apartamentos_id"),"Seleccione un apartamento valido")){
        muestraMensaje("ERROR", "Seleccione un apartamento valido", "error");
        return false;
    }


    limpiarerror();
    return true;
}



















function carga_apartamentos() {
  var datos = new FormData();
  datos.append("accion", "listadoapartamentos");
  enviaAjax(datos);
}
function cargar_estacionamientos() {
  var datos = new FormData();
  datos.append("accion", "listadoaEstacionamiento");
  enviaAjax(datos);
}
function colocaapartamento(datos){
    if(datos !== false){
        var cod = $(datos).find("td").eq(1).text();
        var id = $(datos).find("td").eq(0).text();
        document.getElementById('apartamento_info').value = cod
        document.getElementById('apartamentos_id').value = id
    }
    else{
        document.getElementById('apartamento_info').value = "";
        document.getElementById('apartamentos_id').value = "";
    }
    $("#modalapartamentos").modal('hide');
}
function colocarEstacionamiento(datos){
    $("#id").val( $(datos).find("td").eq(0).text());// para las modificaciones
    $("#numEstac").val( $(datos).find("td").eq(0).text());
    $("#estacCosto").val( $(datos).find("td").eq(1).text().replace("$",""));
    $("#apartamentos_id").val( $(datos).find("td").eq(2).text());
    $("#apartamento_info").val( $(datos).find("td").eq(3).text());
    $("#modalEstacionamiento").modal('hide');
    cambiarbotones(false);
}
function borrar() {
  $("#numEstac").val("");
  $("#estacCosto").val("");
  $("#apartamentos_id").val("");
  $("#apartamento_info").val("");
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
function limpiarerror() {
  $("#storre").text("");
  $("#spiso").text("");
  $("#snumapto").text("");
  $("#stipoapto").text("");
  $("#spropietario").text("");
  $("#sinquilino").text("");
}

function sepMiles (value,cond=false){
    sigDecim=",";
    sigSepar=".";
	if(value!=''){
			var x = 0;

			value = value.replace(/^[0]*\D*[0]*|\D/g,'');
			for(var i = 3;i>value.length;value = '0'+value){
				x++;
				if(x>1000){break;}
			}
			if(!cond){
				var expmonto= new RegExp("(\\d)(?:(?=\\d{3}\\"+sigDecim+"\\d+)|(?=(?:\\d{3})+\\"+sigDecim+"))","g");
				value = value.replace(/(\d)(\d\d)$/,"$1"+sigDecim+"$2");
				//expmonto = /(\d)(?:(?=\d{3}$)|(?=(?:\d{3})+$))/g;// si no tiene decimales
				value = value.replace(expmonto,"$1"+sigSepar);
			}
			else{
				value = value.replace(/(\d)(\d\d)$/,"$1.$2");
			}
			return value;
	}
	else{
		return '';
	}
}




function validarKeyPress(e, er) {
  codigo = e.keyCode;
  tecla = String.fromCharCode(codigo);
  if (er.test(tecla) === false) {
    e.preventDefault();
    return false;
  }
  else return true;
}



function validarKeyUp(er, etiqueta, mensaje, etiquetamensaje) {
    if(etiqueta.data("span")){
        etiquetamensaje = $("#"+etiqueta.data("span"));
    }
    else if(typeof etiquetamensaje === 'undefined'){
        console.error("falta la etiqueta mensaje",etiqueta);
    }

  a = er.test(etiqueta.val());
  if (a) {
    if (etiqueta.hasClass("is-invalid")) {
      etiqueta.toggleClass("is-invalid");
    }
    if (!etiqueta.hasClass("is-valid")) {
      etiqueta.toggleClass("is-valid");
    }
    etiquetamensaje.text("");
    return true;
  } else {
    if (etiqueta.hasClass("is-valid")) {
      etiqueta.toggleClass("is-valid");
    }
    if (!etiqueta.hasClass("is-invalid")) {
      etiqueta.toggleClass("is-invalid");
    }
    etiquetamensaje.text(mensaje);
    return false;
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
        } else if (lee.resultado == "listadoEstacionamiento") {
          if ($.fn.DataTable.isDataTable("#tablaEstacionamiento")) {
            $("#tablaEstacionamiento").DataTable().destroy();
          }
          $("#listadoEstacionamiento").html(lee.mensaje);
          if (!$.fn.DataTable.isDataTable("#tablaEstacionamiento")) {
            $("#tablaEstacionamiento").DataTable({
              language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron puestos de estacionamiento",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay puestos de estacionamiento disponibles",
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

                {
                  data: "col4",
                  render: function (data) {
                    if (data) {
                      return data;
                    } else if (data == "") {
                      return "<span class='badge badge-pill badge-secondary'>Sin Asignar</span>";
                    }
                  },
                }
              ]
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
          cargar_estacionamientos();
          cambiarbotones(true);
        } else if (lee.resultado == "listadotipo") {
          $("#tipoapto").html(lee.mensaje);
        } else if (lee.resultado == "error") {
          muestraMensaje(lee.mensaje, "", "error");
        }
      } catch (e) {
        alert("Error en JSON " + e.name + " !!!");
        console.error(e);
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