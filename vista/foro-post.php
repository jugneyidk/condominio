<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
    <?php require_once("comunes/carga.php"); ?>
    <?php require_once("comunes/modal.php"); ?>
    <?php require_once('comunes/menu.php'); ?>
    <?php require_once("vista/foro-post-dashboard.php"); ?>

    <!-- PARA USUARIOS NORMALES -->


    <!-- <main role="main" class="container-lg bg-white p-2 p-sm-4 p-md-5">
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
                    <p id="fecha-post" class="text-muted"></p>
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
    </main> -->
    
    <?php require_once('comunes/foot.php'); ?>
    <script src="js/carga.js"></script>
    <script src="js/comun_x.js"></script>
    <script src="js/foro-post.js"></script>
</body>