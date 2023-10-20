let variable_divisa_global;//solo modificar por la funcion
function load_bcv(async_control = true){

		var datos = new FormData();
		datos.append("accion","");

		$.ajax({
			async: async_control,
			url: "./model/bcv.php",
			type: "POST",
			contentType: false,
			data: datos,
			processData: false,
			cache: false,
			timeout: 10000,
			success: function (respuesta) {
				try {
					var lee = JSON.parse(respuesta);
					console.log(respuesta);
					if(lee.resultado == 'bcv'){
						variable_divisa_global = lee.mensaje;
					}
				} catch (e) {
					//alert("Error en " + e.name + " !!!");
					console.error(e);
					console.log(respuesta);
					console.log("----- BCV -------");
				}
			},
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

load_bcv();