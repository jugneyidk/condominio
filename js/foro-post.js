$(document).ready(function() {
    loadPost();
    loadComentarios();
    $("#enviarComentario").on("click", function() {
        var datos = new FormData($("#comentario")[0]);
        datos.append("accion", "incluirComentario");
        enviaAjax(datos, function(respuesta) {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "incluirComentario") {
                loadComentarios();
            } else {
                muestraMensaje("ERROR", lee.mensaje, "error");
            }
        })
    });
    $("#aprobar").on("click", function() {
        var datos = new FormData();
        datos.append("accion", "cambiarVoto");
        datos.append("voto", "1");
        enviaAjax(datos, function(respuesta) {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "cambiarVoto") {
                $("#rechazar").removeClass("btn-danger");
                $("#rechazar").addClass("btn-outline-danger");
                $("#aprobar").removeClass("btn-outline-success");
                $("#aprobar").addClass("btn-success");
                loadPost();
            } else {
                muestraMensaje("ERROR", lee.mensaje, "error");
            }
        })
    });
    $("#rechazar").on("click", function() {
        var datos = new FormData();
        datos.append("accion", "cambiarVoto");
        datos.append("voto", "0");
        enviaAjax(datos, function(respuesta) {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "cambiarVoto") {
                $("#rechazar").removeClass("btn-outline-danger");
                $("#rechazar").addClass("btn-danger");
                $("#aprobar").removeClass("btn-success");
                $("#aprobar").addClass("btn-outline-success");
                loadPost();
            } else {
                muestraMensaje("ERROR", lee.mensaje, "error");
            }
        })
    });
});

function loadPost() {
    var datos = new FormData();
    datos.append("accion", "listaPost");
    enviaAjax(datos, function(respuesta) {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "listaPost") {
            if (lee.mensaje) {
                $("#titulo-post").html(lee.mensaje.titulo);
                $("#contenido-post").html(lee.mensaje.descripcion);
                $("#fecha-post").html(lee.mensaje.fecha);
                $("#creador-post").html(lee.mensaje.nombres+" "+lee.mensaje.apellidos);
                if (lee.numero_votos) {
                    $("#numero-votos").html(lee.numero_votos);                            
                } else{
                    $("#numero-votos").html("0");
                }
                if (lee.mensaje.voto) {
                    switch (lee.mensaje.voto) {
                        case "0":
                            $("#rechazar").removeClass("btn-outline-danger");
                            $("#rechazar").addClass("btn-danger");
                            break;
                        case "1":
                            $("#aprobar").removeClass("btn-outline-success");
                            $("#aprobar").addClass("btn-success");
                            break;
                        default:
                            break;
                    }
                }
            }
        } else {
            muestraMensaje("ERROR", lee.mensaje, "error");
        }
    });
}

function loadComentarios() {
    var datos = new FormData();
    datos.append("accion", "listaComentarios");
    enviaAjax(datos, function(respuesta) {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "listaComentarios") {
            if (lee.mensaje) {
                var comentarios = '';
                lee.mensaje.forEach(comentario => {
                    comentarios += '<div class="row mb-3">';
                    comentarios += '<div class="col">';
                    comentarios += '<div class="card">';
                    comentarios += '<div class="card-body">';
                    const fecha = new Date(comentario.fecha);
                    comentarios += '<p class="card-title h5">' + comentario.nombres + " " + comentario.apellidos + '<small class="ml-2 mb-2 text-muted">' + fecha.toLocaleDateString("es-VE") + '</small></p>';
                    comentarios += comentario.comentario;
                    comentarios += '<a class="float-right" href="#">Responder↲</a>';
                    comentarios += '</div>';
                    comentarios += '</div>';
                    comentarios += '</div>';
                    comentarios += '</div>';

                });
                $("#comentarios").html(comentarios);
            }
        } else {
            muestraMensaje("ERROR", lee.mensaje, "error");
        }
    });
}