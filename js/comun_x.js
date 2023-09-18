let montoExp = /^\d{1,3}(?:[\.]\d{3})*[,]\d{2}$/; 
let fechaExp = /^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/;
function eventoKeypress(etiqueta,exp){
	etiqueta.onkeypress=function(e){
		validarKeyPress(e,exp);
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


function eventoKeyup(etiqueta, exp, mensaje, etiquetamensaje, func){
	etiqueta.onkeyup=function(){
		if(typeof func ==="function"){
			func(this);
		}
		validarKeyUp(exp,$(this),mensaje,etiquetamensaje);
	}
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

function enviaAjax(datos, func_success,func_beforesend="modal") {
	if(typeof func_success !== "function"){
		console.error("falta la funcion success");
	}
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
		timeout: 10000,
		success: function (respuesta) {
			try {
				if(typeof func_success === "function"){
					func_success(respuesta);
				}
				else throw "No hay una función definida";

			} catch (e) {
				alert("Error en " + e.name + " !!!");
				console.error(e);
				console.log(respuesta);
			}
		},
		error: function (request, status, err) {
			console.log("Hola");
			modalcarga(false);
			if (status == "timeout") {
				muestraMensaje("Servidor Ocupado", "Intente de nuevo", "error");

			} 
			else if(request.readyState===0){
				muestraMensaje("No Hay Conexión Con El Servidor", "Intente de nuevo", "error");
			}
			else {
				console.log("Hola3");
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
function vaciarSpanError() {
	$("input,select").each(function(index){
		if (typeof $(this).data('span') !== 'undefined')
		{
			$("#"+$(this).data("span")).text("");
		}
  	});
}


function removeSpace(cadena)
{
	if(typeof cadena==='string')
	{
		if(/(?:^\s)|(?:[\s][\s])|(?:[\s]+$)/.test(cadena))
		{
			cadena = cadena.replace(/\n/mg,"---WHITE_ENDL_SPACE---");
			cadena=cadena.replace(/(?:^\s+)|(?:[\s]+$)/mg,"");
			while(/[\s][\s]/.test(cadena)) cadena=cadena.replace(/(?:[\s][\s])+/," ");
			cadena = cadena.replace(/---WHITE_ENDL_SPACE---/g,"\n");
		}
		return cadena;
	}
	else{
		console.error('El argumento debe ser un string');
		return undefined;
	}
};

function rowsEvent(tbody,func,control=true){//
	if(typeof tbody==='string')
	{
		tbody=document.getElementById(tbody);
	}

	
	if(typeof func==='function')
	{
		tbody.addEventListener('click',function(e){
			var elem=e.target;
			count=0;
			if(control){
				while(elem.tagName!='TR'&&elem.tagName!='TBODY'&& elem!=this){
					
					count++;
					if(count>100)
					{
						console.error('se paso el while');
						return false;
						break
					}
					elem=elem.parentNode;
				}
				if(elem.tagName=='TBODY' || elem == this){
					return false;
				}
				if(!elem.getElementsByTagName('td')[0].classList.contains("dataTables_empty")){
					func(elem,e.target);
				}
			}
			else{
				func(elem);
			}
		},true);
	}
	else{
		console.error('el segundo argumento debe ser una función que se ejecutara al hacer click en el table');
	}
	
}

// function cambiarbotones(parametro) {
//   $("#modificar").prop("disabled", parametro);
//   $("#eliminar").prop("disabled", parametro);
//   $("#incluir").prop("disabled", !parametro);
// }

// function borrar(func) {
// 	$("form input").val("");
// 	$("form select").val("");
// 	limpiarvalidacion();
// }

// function limpiarvalidacion() {
// 	$("form input").removeClass("is-valid");
// 	$("form input").removeClass("is-invalid");
// 	$("form select").removeClass("is-valid");
// 	$("form select").removeClass("is-invalid");
// }