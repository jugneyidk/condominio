$(document).ready(function() {
	loadForos();
})



function loadForos() {
	var datos = new FormData();
	datos.append("accion", "listaForo");
	enviaAjax(datos, function(respuesta) {
		var lee = JSON.parse(respuesta);
		if (lee.resultado == "listaForo") {
			var forosPosts = '';
			console.log(lee.mensaje);
			lee.mensaje.forEach(post => {
				console.log(post);
				forosPosts += '<div class="post-card col-md-4 d-flex align-self-stretch mb-3">';
				forosPosts += '<div class="card text-white bg-primary w-100">';
				if(post.aprobado == 1 && post.visto == 0){
					forosPosts += '<div class="aprobado position-relative card-body d-flex flex-column">';
				}
				else{
					forosPosts += '<div class="position-relative card-body d-flex flex-column">';
				}
				forosPosts += '<small class="text-light">' + post.fecha + '</small>';
				if (post.titulo.length > 75) {
					forosPosts += '<h4 class="card-title">' + post.titulo.substring(0, 75) + '...</h4>';
				} else {
					forosPosts += '<h4 class="card-title">' + post.titulo + '</h4>';
				}
				if (post.descripcion.length > 75) {
					forosPosts += '<p class="card-text">' + post.descripcion.substring(0, 75) + '...</p>';
				} else {
					forosPosts += '<p class="card-text">' + post.descripcion + '</p>';
				}
				forosPosts += '<div class="mt-auto">';
				forosPosts += '<a class="btn btn-light float-right" href="?p=foro-post&postId=' + post.id + '" role="button">Ver más &raquo;</a>';
				forosPosts += '</div>';
				forosPosts += '</div>';
				forosPosts += '</div>';
				forosPosts += '</div>';
			});
			if (forosPosts !== '') {
				$("#postsContainer").html(forosPosts);
			}
		} else {
			muestraMensaje("ERROR", lee.mensaje, "error");
		}
	});
}