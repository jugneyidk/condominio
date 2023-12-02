let variable_divisa_global;//solo modificar por la funcion
function load_bcv(control = false,async_control = true){// control false para que actualice, control true para que devuelva el ultimo dolar de la bd
	 console.log("me llamaron");


		var datos = new FormData();
		datos.append("accion","");
		if(!control){
			datos.append("control","xxx");
		}

		$.ajax({
			async: async_control,
			url: "./model/bcv_2.php",
			type: "POST",
			contentType: false,
			data: datos,
			processData: false,
			cache: false,
			timeout: 10000,
			success: function (respuesta) {
				if(control){

					try {
						var lee = JSON.parse(respuesta);
						// console.log(respuesta);
						if(lee.resultado == 'bcv'){
							variable_divisa_global = lee.mensaje;
						}
						else{
							console.error("Error BCV");
							console.error(lee.mensaje);
						}
					} catch (e) {
						//alert("Error en " + e.name + " !!!");
						console.error(e);
						console.log(respuesta);
						console.log("----- BCV -------");
					}
				}
				else{
					console.log(variable_divisa_global);
					console.log(respuesta);
				}
			}
			// ,complete: function(xhr){
			// 	console.log(variable_divisa_global);
			// 	console.log(xhr.responseText);
			// }

			//,
			// complete: function (xhr, status) {
			// 	var respuesta = xhr.responseText;
			// 	try{
			// 		var lee = JSON.parse(respuesta);
			// 		console.log(lee);
			// 	}
			// 	catch(e){
			// 		console.error(e);
			// 		console.log(respuesta);
			// 	}
			// }
		});

}

console.log("No borrar esto.\nSe debe eliminar las lineas \ncurl_setopt($cliente, CURLOPT_SSL_VERIFYHOST, 0);\ncurl_setopt($cliente, CURLOPT_SSL_VERIFYPEER, 0);")
console.log("Es por mi pc que lo necesita, pero es un problema de seguridad y se debe eliminar esta lineas para un servidor");
console.log("");
console.log("");
console.log("");


//load_bcv();