<main role="main">
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-3">Bienvenido al Foro del Condominio!</h1>
            <p class="lead">En este foro podrás encontrar los temas actuales con respecto a la junta de condominio además de dar tu opinión en las votaciones.</p>

        </div>
    </div>
    <?php if(isset($_SESSION["id_habitante"])){?>
    <div class="container text-right mb-4">
        <a class="btn btn-secondary text-light"  href="?p=foroAction">Nuevo Foro</a>
    </div>
<?php }?>

<style type="text/css">
    .post-card .aprobado::before{
        font: 1.5rem FontAwesome;
        content: "\f12a";
        
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        background-color: red;
        height: 3rem;
        width: 3rem;
        border-radius: 100%;
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1; 
        animation-name: aprob;
        animation-iteration-count: infinite;
        animation-direction: alternate;
        animation-duration: .5s;   
    }

    @keyframes aprob{
        from{
            box-shadow: 0 0 0 0 red;
        }
        to{
            box-shadow: 0 0 5px 5px red;
        }
    }
</style>
    <div class="container">
        <div class="row d-flex align-items-stretch mb-4" id="postsContainer">
        </div>
    </div>
</main>