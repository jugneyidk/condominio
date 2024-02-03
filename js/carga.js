function modalcarga(parametro) {

	return new Promise((resolve, reject) => {
		if (parametro) {
		  $("#carga").modal("show");
		  $("body").addClass("carga");
		  console.log(document.getElementById('carga'));
		  resolve();
		} else {
		  setTimeout(function () {
		    $("#carga").modal("hide");
		    $("body").removeClass("carga");
		    resolve();
		  }, 100);
		}
	});
}