<?php require_once('comunes/head.php'); ?>

<body>
    <?php require_once("comunes/carga.php"); ?>
    <?php require_once("comunes/modal.php"); ?>
    <?php require_once('comunes/menu.php'); ?>
    <main role="main">
        <div class="jumbotron">
            <div class="container">
                <h1 class="display-3">Bienvenido al Foro del Condominio!</h1>
                <p class="lead">En este foro podrás encontrar los temas actuales con respecto a la junta de condominio además de dar tu opinión en las votaciones.</p>

            </div>
        </div>
        <div class="container">
            <div class="row d-flex align-items-stretch mb-4" id="postsContainer">
            </div>
        </div>
    </main>
    <?php require_once('comunes/foot.php'); ?>
    <script src="js/carga.js"></script>
    <script src="js/comun_x.js"></script>
    <script>
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
                    lee.mensaje.forEach(post => {
                        forosPosts += '<div class="col-md-4 d-flex align-self-stretch mb-3">';
                        forosPosts += '<div class="card text-white bg-primary w-100">';
                        forosPosts += '<div class="card-body d-flex flex-column">';
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
    </script>
</body>