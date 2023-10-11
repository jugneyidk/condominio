let montoExp = /^\d{1,3}(?:[\.]\d{3})*[,]\d{2}$/; 
let fechaExp = /^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/;
let alfanume = /^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]*$/;
function eventoKeypress(etiqueta,exp){
	if(typeof etiqueta === "String"){
		etiqueta = document.getElementById(etiqueta);
	}
	etiqueta.onkeypress=function(e){
		validarKeyPress(e,exp);
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
			validarKeyPress(e,/^[0-9]$/);
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
			validarKeyPress(e,/^[0-9]$/);
		}

	}
	tag.maxLength = 12;
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

class Validaciones{

	constructor(){
		this.expCedula_l = /(?:(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i;
		this.expCedula = /(?:(?:^[0-9]{7,8}$)|(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i;
		this.expHora = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
		this.expTelefono = /^[0-9]{4}[-\s]?[0-9]{7}$/;
		this.expEmail = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
		///^[a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]$/
		///^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]/
	}

	cedula(ci,tipo=true)	{//tipo = true obligatorio v,e,j false no
		if(tipo == true){
			var exp = this.expCedula_l;
		}
		else var exp = this.expCedula;

		return exp.test(ci);
	}
	fecha(dateV){
		if(typeof dateV==='string')
		{
			if(!/^[\d][\d][\d][\d][\D][\d][\d][\D][\d][\d]$/.test(dateV)) {return false;}
			dateV=dateV.split(/\D/);
		}
		else {return false;}
		var d=dateV[2];//dia
		var m=dateV[1];//mes
		var a=dateV[0];//year
		var ok = true;
		if( (a < 1900) || (m < 1) || (m > 12) || (d < 1) || (d > 31) )
			ok = false;
		else
		{
			if((a%4 != 0) && (m == 2) && (d > 28))
			ok = false;
			else
			{
			if( (((m == 4) || (m == 6) || (m == 9) || (m==11)) && (d>30)) || ((m==2) && (d>29)) )
				ok = false;
			}
		}
		return ok;
		//no puede ser menor a 1900 el año
	}
	hora(string){

		return this.expHora.test(string);
	}
	telefono(string){

		return this.expTelefono.test(string);
	}
	email(string){

		return this.expEmail.test(string);
	}
	monto(string,sep=true){
		if(sep==true){
			var exp = /^[0-9]{1,3}(?:[\.][0-9]{3}){0,6}[,][0-9]{2}$/;
		}
		else{
			var exp = /^[0-9]{1,20}(?:[\,\.][0-9]{2})?$/;
		}
		return exp.test(string);
	}
	texto(string,n=100,vacio=false){
		if(vacio==true){
			vacio = 0;
		}
		else vacio = 1;
		var exp = new RegExp("^[a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{"+vacio+","+n+"}$","m");
		return exp.test(string)
	}
	alfanumerico(string,n=100,vacio=false){
		if(vacio==true){
			vacio = 0;
		}
		else vacio = 1;
		var exp = new RegExp("^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{"+vacio+","+n+"}$","m");
		return exp.test(string)

	}
	

}
const V = new Validaciones();
function eventoKeyup(etiqueta, exp, mensaje, etiquetamensaje, func, func2){
	if(typeof etiqueta === "string"){
		etiqueta = document.getElementById(etiqueta);
	}
	etiqueta.onkeyup=function(){
		if(typeof func ==="function"){
			func(this);
		}

		var resp = validarKeyUp(exp,$(this),mensaje,etiquetamensaje);

		if(typeof func2 === "function"){
			func2(this, resp);
		}

	}
}
function validarKeyUp(er, etiqueta, mensaje, etiquetamensaje) {
	if(etiqueta.data("span")){
		etiquetamensaje = $("#"+etiqueta.data("span"));
	}
	else if(typeof etiquetamensaje === 'undefined'){
		console.error("falta la etiqueta mensaje",etiqueta);
	}
	if(er === true||er === false){
		a = er;
	}
	else{
		a = er.test(etiqueta.val());
	}
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
			timeout: 10000,
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

function crearElem(type,attr='',content='')
{
	var elem=document.createElement(type);
	if(elem)
	{
		if(attr!='')
		{
			attr=attr.split(',');
			if(attr.length%2==0)
			{
				for(var i=0;i<attr.length;i++)
				{
					elem.setAttribute(attr[i],attr[(i+1)]);
					i++;
				}
				if(content.tagName){elem.appendChild(content);}
				else if(content!=''){elem.innerHTML=content;}
				return elem;
			}
			else
			{
				console.error('Los attr debent tener un valor separado por "," ej. id,value')
				return undefined;
			}
		}
		return elem;
	}
	else return undefined;
}











// function cambiarbotones(parametro) {
//   $("#modificar").prop("disabled", parametro);
//   $("#eliminar").prop("disabled", parametro);
//   $("#incluir").prop("disabled", !parametro);
// }


// function borrar() {
// 	$('input:not(:checkbox):not(:radio)').val("");
// 	// $("form input").val("");
// 	$("form select").val("");
// 	limpiarvalidacion();
// 	cambiarbotones();
// 	cambiarbotones_2();
// }
// function limpiarvalidacion() {
// 	$("form input").removeClass("is-valid");
// 	$("form input").removeClass("is-invalid");
// 	$("form select").removeClass("is-valid");
// 	$("form select").removeClass("is-invalid");
// }


/*
BEGIN
IF (NEW.estado <> OLD.estado) THEN
	INSERT INTO `pago_historial_estado` (id_historial,estatus_viejo, estatus_nuevo, fecha_cambio) VALUES (id_pago,OLD.estado, NEW.estado, CURRENT_TIMESTAMP);
    END IF;
END

*/