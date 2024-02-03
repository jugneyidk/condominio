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
                $("#comentario textarea").val("");
            } else {
                muestraMensaje("ERROR", lee.mensaje, "error");
            }
        })
    });
    if(document.getElementById('aprobar')){

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
    }
    if(document.getElementById('mark-visibility')){
        document.getElementById('mark-visibility').onclick=function(){
            var datos = new FormData();
            datos.append("accion","cambiar_estado");
            datos.append("control","visto");
            datos.append("value",(this.dataset.value==1)?0:1);
            enviaAjax(datos,function(respuesta){

                var lee = JSON.parse(respuesta);
                console.log(lee);
                if(lee.resultado == "cambiar_estado"){
                    document.getElementById('mark-visibility').dataset.value=lee.mensaje.value;
                    if(lee.mensaje.value == 1){
                        document.getElementById('mark-visibility').getElementsByClassName('fa')[0].className = "fa fa-eye-slash";
                        document.getElementById('mark-visibility').getElementsByTagName('span')[0].innerHTML = "Marcar como no visto"
                    }
                    else{
                        document.getElementById('mark-visibility').getElementsByClassName('fa')[0].className = "fa fa-eye";
                        document.getElementById('mark-visibility').getElementsByTagName('span')[0].innerHTML = "Marcar como visto"
                    }
                }
                else if (lee.resultado == 'is-invalid'){
                    muestraMensaje("ERROR", lee.mensaje,"error");
                }
                else if(lee.resultado == "error"){
                    muestraMensaje("ERROR", lee.mensaje,"error");
                    console.error(lee.mensaje);
                }
                else if(lee.resultado == "console"){
                    console.log(lee.mensaje);
                }
                else{
                    muestraMensaje("ERROR", lee.mensaje,"error");
                    console.error(respuesta);
                }
            });
        }
    }
});

function loadPost() {
    var datos = new FormData();
    datos.append("accion", "listaPost");
    enviaAjax(datos, function(respuesta) {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "listaPost") {
            console.log(lee);
            if (lee.mensaje) {

                if(lee.mensaje.aprobado == 1){
                    document.getElementById('mark-visibility').classList.remove("d-none");
                }
                else{
                    document.getElementById('mark-visibility').classList.add("d-none");
                }

                document.getElementById('mark-visibility').dataset.value=lee.mensaje.visto;
                if(lee.mensaje.visto == 1){
                    document.getElementById('mark-visibility').getElementsByClassName('fa')[0].className = "fa fa-eye-slash";
                    document.getElementById('mark-visibility').getElementsByTagName('span')[0].innerHTML = "Marcar como no visto"
                }
                else{
                    document.getElementById('mark-visibility').getElementsByClassName('fa')[0].className = "fa fa-eye";
                    document.getElementById('mark-visibility').getElementsByTagName('span')[0].innerHTML = "Marcar como visto"
                }



                $("#titulo-post").html(lee.mensaje.titulo);
                $("#contenido-post").html(lee.mensaje.descripcion);
                $("#fecha-post").html(lee.mensaje.fecha);
                $("#creador-post").html(lee.mensaje.nombres+" "+lee.mensaje.apellidos);
                if (lee.numero_votos) {
                    $("#numero-votos").html(lee.numero_votos);                            
                } else{
                    $("#numero-votos").html("0");
                }
                console.log(lee);
                if (lee.mensaje.voto && document.getElementById('aprobar')) {
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
                if(lee.mensaje.voto_positivo){
                    if(document.getElementById('show_votos_p')){
                        document.getElementById('show_votos_p').innerHTML += " ("+lee.mensaje.voto_positivo+")" ;
                        document.getElementById('show_votos_n').innerHTML += " ("+lee.mensaje.voto_negativo+")" ;
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
                console.log(lee.mensaje);
                lee.mensaje.forEach(comentario => {
                    comentarios += '<div class="row mb-3">';
                    comentarios += '<div class="col">';
                    comentarios += '<div class="card">';
                    comentarios += '<div class="card-body">';
                    const fecha = new Date(comentario.fecha);
                    comentarios += '<p class="card-title h5">' + comentario.nombres + " " + comentario.apellidos + '<small class="ml-2 mb-2 text-muted">' + fecha.toLocaleDateString("es-VE") + '</small></p>';
                    comentarios += comentario.comentario;
                    comentarios += '<a class="float-right d-none" href="#">Responder↲</a>';
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