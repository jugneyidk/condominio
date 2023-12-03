<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
  <?php require_once('comunes/menu.php');
  if (!isset($_SESSION['id_usuario'])) :
  ?>
    <link rel="stylesheet" href="css/landing.css">
    <div class="jumbotron mb-0">
      <div class="container">
        <h2 class="display-4 font-weight-bold text-break">CONJUNTO JOSÉ MARÍA VARGAS</h2>
        <p class="lead mt-1">Sistema de registro y control de ingresos del condominio.</p>
        <hr class="my-4">
        <p>Creado con el fin de facilitar a los habitantes el pago de las deudas del condominio.</p>
        <a class="btn btn-primary btn-lg rounded-pill" href="?p=consulta" role="button">Consultar deuda</a>
      </div>
    </div>
    <div class="container-lg m-auto bg-white p-4">
      <div class="row justify-content-between align-items-center p-0 p-lg-3">
        <div class="col-12 col-md-6 mb-4 mb-md-0">
          <div class="card">
            <div class="card-body">
              <p class="card-text">El <span class="font-weight-bold">Conjunto Residencial José María Vargas</span> es un grupo de viviendas compuesto por 4 torres, ubicado en la Avenida Las Palmas, frente al Hospital Central Antonio María Pineda en el municipio Iribarren de Barquisimeto, Estado Lara. Este sistema, se encarga de realizar el cobro mensual a los propietarios de los apartamentos para ejecutar el pago de los gastos del conjunto, tales como aseo, vigilancia, reparaciones, entre otros.</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 endeos-unslider">
          <ul>
            <li><img class="imgw" src="img/condominio.jpg"></li>
            <li><img class="imgw" src="img/condominio2.jpg"></li>
            <li><img class="imgw" src="img/condominio1.jpg"></li>
            <li><img class="imgw" src="img/jumbotron.jpg"></li>
          </ul>
        </div>
      </div>
      <hr class="my-3">

      <?php if(isset($avisos) and $avisos["resultado"] == "avisos"){ ?>

      <style type="text/css">
        .aviso-titulo{
          text-decoration: underline;
        }
      </style>
      <p class="h3 mb-2 text-center">Avisos</p>
      <div class="container-fluid my-3" id="avisos-container">
        <?php foreach ($avisos["mensaje"] as $elem) {?>
        <div class="row justify-content-center">
          <div class="col col-md-8">
            <div class="row">
              <div class="col">
                <div class="h4 text-info text-capitalize aviso-titulo"><strong><?= $elem["titulo"]; ?></strong></div>
              </div>
            </div>
            <div class="row">
              <div class="col text-justify aviso-content">
                <?= $elem["descripcion"]; ?>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <?php } ?>
        <div class="row justify-content-center d-none">
          <div class="col col-md-8">
            <div class="row">
              <div class="col">
                <div class="h4 text-info text-capitalize aviso-titulo"><strong>titulo</strong></div>
              </div>
            </div>
            <div class="row">
              <div class="col text-justify">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php }else if(isset($avisos)){
        $avisos = json_encode($avisos);
        echo "<script>
          console.error({$avisos})
        </script>";
      } ?>

      

      <div class="row mb-3 justify-content-center">
        <div class="col col-md-8">
          <div class="accordion" id="accordionFaq">
            <p class="h3 mb-2 text-center">Preguntas frecuentes</p>
            <p class="text-center">Interrogantes del usuario más frecuentes.</p>
            <div class="card">
              <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    ¿Cómo iniciar sesión?
                  </button>
                </h2>
              </div>
              <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionFaq">
                <div class="card-body">
                  Si usted es propietario de algun apartamento solo debe hacer click en el botón "<span class="font-weight-bold">Consultar deuda</span>" (en la parte superior derecha de la página) e introducir sus datos.
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingTwo">
                <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    ¿Qué hacer si olvidé mi contraseña?
                  </button>
                </h2>
              </div>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionFaq">
                <div class="card-body">
                  Puedes contactar al soporte, o directamente en la administración del condominio para que resuelvan su caso.
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingThree">
                <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    ¿Cómo pagar mi deuda?
                  </button>
                </h2>
              </div>
              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionFaq">
                <div class="card-body">
                  Luego de haber ingresado al sistema y verificar si hay alguna deuda pendiente, puede presionar el botón "<span class="font-weight-bold">Pagar</span>" para introducir los datos del pago para que posteriormente sea verificado.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="js/landing.js"></script>
  <?php else :
    require_once('vista/dashboard.php');
  endif; ?>
  <?php require_once('comunes/carga.php'); ?>
  <script src="js/unslider.js"></script>
  <?php require_once('comunes/foot.php'); ?>
</body>

</html>