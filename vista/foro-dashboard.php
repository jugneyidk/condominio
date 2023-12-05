<main role="main">
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-3">Bienvenido al Foro del Condominio!</h1>
            <p class="lead">En este foro podrás encontrar los temas actuales con respecto a la junta de condominio además de dar tu opinión en las votaciones.</p>

        </div>
    </div>
    <?php if(isset($_SESSION["id_habitante"])){?>
    <div class="container text-right mb-4">
        <a class="btn btn-secondary text-light" onclick="alert('cambiar direccion');return false;" href="?p=foroAction">Nuevo Foro</a>
    </div>
<?php } ?>
    <div class="container">
        <div class="row d-flex align-items-stretch mb-4" id="postsContainer">
        </div>
    </div>
</main>