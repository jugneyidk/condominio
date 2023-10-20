<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
    <?php require_once("comunes/carga.php"); ?>
    <?php require_once("comunes/modal.php"); ?>
    <?php require_once('comunes/menu.php'); ?>
    <main role="main" class="container-lg bg-white p-2 p-sm-4 p-md-5">
        <a href="?p=foro-index" class="btn btn-secondary regresar_btn mb-4">
            <span class="fa fa-chevron-left"></span><span class="ml-2">Regresar</span>
        </a>
        <div class="jumbotron">
            <div class="container">
                <h1 class="display-4" id="titulo-post"></h1>
                <p class="mt-3 lead">Creado por: <span id="creador-post"></span></p>
            </div>
        </div>
        <div class="container py-3">
            <div class="row mb-3">
                <div class="col">
                    <p id="fecha-post"></p>
                    <p id="contenido-post"></p>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-success" id="aprobar"><span class="fa fa-thumbs-up"></span> De acuerdo</button>
                        <button type="button" class="btn btn-outline-danger" id="rechazar"><span class="fa fa-thumbs-down"></span> En desacuerdo</button>
                    </div>
                    <small class="text-muted ml-3"><span id="numero-votos"></span> votos</small>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col">
                    <h3>Comentarios</h3>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <form id="comentario">
                        <div class="form-group">
                            <label for="comentario">Escribe un comentario</label>
                            <textarea type="text" class="form-control" name="comentario"></textarea>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-block" id="enviarComentario">Enviar Comentario</button>
                    </form>
                </div>
            </div>
            <div id="comentarios">
            </div>
        </div>
    </main>
    <?php require_once('comunes/foot.php'); ?>
    <script src="js/carga.js"></script>
    <script src="js/comun_x.js"></script>
    <script>
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
                        $("#creador-post").html(lee.mensaje.create_by);
                        if (lee.numero_votos) {
                            $("#numero-votos").html(lee.numero_votos.length);                            
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
                            comentarios += '<p class="card-title h5">' + comentario.create_by + '<small class="ml-2 mb-2 text-muted">' + fecha.toLocaleDateString("es-VE") + '</small></p>';
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
    </script>
</body>