<?php require_once('comunes/head.php'); ?>

<body>
  <?php require_once('comunes/menu.php'); ?>
  <br>
<div class="faq_area section_padding_130" id="faq">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-sm-8 col-lg-6">
            <!-- Section Heading-->
            <div class="section_heading text-center wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
              <h3><span>Preguntas</span> frecuentes</h3>
              <p>Interrogantes del usuario más frecuentes.</p>
              <div class="line"></div>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-12 col-sm-10 col-lg-8">
            <div class="accordion faq-accordian" id="faqAccordion">
              <div class="card border-0 wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                <div class="card-header" id="headingOne">
                  <h6 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">¿Cómo iniciar sesión?<span class="lni-chevron-up"></span></h6>
                </div>
                <div class="collapse" id="collapseOne" aria-labelledby="headingOne" data-parent="#faqAccordion">
                  <div class="card-body">
                    <p>Si es usted habitante o propietario de algun apartamento solo debe hacer click en el boton extra (o en el boton que se encuentra a un lado de la muestra de imagenes) e ingresar su correo y contraseña.</p>
                    <p>Si es usted de la administración el proceso es casi el mismo, pero puede simplemente presionar el boton en la esquina superior derecha que dice "iniciar sesión" e ingresar su usuario y contraseña</p>
                  </div>
                </div>
              </div>
              <div class="card border-0 wow fadeInUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">
                <div class="card-header" id="headingTwo">
                  <h6 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">¿Qué hacer si olvidé mi contraseña?<span class="lni-chevron-up"></span></h6>
                </div>
                <div class="collapse" id="collapseTwo" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                  <div class="card-body">
                    <p>Puedes contactar al soporte, o directamente en la administración del conjunto para que resuelvan tu caso, o solo comunicarte al correo proporcionado en <b>ayuda</b>.</p>
                    
                  </div>
                </div>
              </div>
              <div class="card border-0 wow fadeInUp" data-wow-delay="0.4s" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;">
                <div class="card-header" id="headingThree">
                  <h6 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">¿Cómo pagar mi deuda?<span class="lni-chevron-up"></span></h6>
                </div>
                <div class="collapse" id="collapseThree" aria-labelledby="headingThree" data-parent="#faqAccordion">
                  <div class="card-body">
                    <p>Debe darle click a la consulta cuando inicie sesión en su apartado (habitantes) y ver si tiene algun pago pendiente</p>
                    <p>Si tiene algun pago pendiente pues debe presionar e introducir los datos requeridos del metodo seleccionado para pagar.</p>
                  </div>
                </div>
              </div>
            </div>
            <!-- Support Button-->
            <div class="support-button text-center d-flex align-items-center justify-content-center mt-4 wow fadeInUp pb-4" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInUp;">
              <i class="lni-emoji-sad"></i>
              <a href="#">
                <p class="mb-0 px-2">¿No ha respondido su interrogante?</p>
              </a>

            </div>
          </div>
        </div>
      </div>
    </div>
<?php require_once('comunes/foot.php'); ?>
</body>