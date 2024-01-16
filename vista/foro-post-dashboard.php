<main role="main" class="container-lg bg-white p-2 p-sm-4 p-md-5">
        <div class="container d-flex justify-content-between">
        <a href="?p=<?=($_GET["p"] == "foro-post-h")?"foro-index-h":"foro-index" ?>" class="btn btn-secondary regresar_btn mb-4">
            <span class="fa fa-chevron-left"></span><span class="ml-2">Regresar</span>
        </a>
            <button class="btn btn-info mb-4 d-none" id="mark-visibility"><span>Marcar como visto</span> <span class="fa fa-eye"></span></button>
        </div>
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
                <?php if(!isset($usuario_normal_no_habitante)){ ?>
                <div class="col">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-success" id="aprobar"><span class="fa fa-thumbs-up"></span> De acuerdo</button>
                        <button type="button" class="btn btn-outline-danger" id="rechazar"><span class="fa fa-thumbs-down"></span> En desacuerdo</button>
                    </div>
                    <small class="text-muted ml-3"><span id="numero-votos"></span> votos</small>
                </div>
            <?php }
            else{ ?>

                <div class="col">
                    <div class="btn-group" role="group">
                        <span type="button" class="btn btn-success" id="show_votos_p" ><span class="fa fa-thumbs-up"></span> De acuerdo</span>
                        <span type="button" class="btn btn-danger" id="show_votos_n" ><span class="fa fa-thumbs-down"></span> En desacuerdo</span>
                    </div>
                    <small class="text-muted ml-3"><span id="numero-votos"></span> votos</small>
                </div>

            <?php } ?>
            </div>
            <hr />
            <div class="row">
                <div class="col">
                    <h3>Comentarios</h3>
                </div>
            </div>
            <div class="row mb-3<?=(isset($usuario_normal_no_habitante))?" d-none":"";  ?>">
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