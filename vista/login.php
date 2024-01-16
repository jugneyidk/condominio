<?php require_once('comunes/head.php'); ?>
<?php require_once('comunes/carga.php'); ?>
<?php require_once('comunes/modal.php'); ?>
<link rel="stylesheet" type="text/css" href="css/login.css">

<body>
  <section id="login">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-lg-6 d-none d-lg-block">
                <img src="img/imgg.jpg" alt="" class="img-fluid h-100" style="border-radius: 1rem 0 0 1rem;" />
              </div>
              <div class="col col-lg-6 d-flex align-items-center p-4 p-lg-0">
                <div class="card-body p-4 p-lg-5 text-black">
                  <form method="post" id="f" autocomplete="off">
                    <div class="align-items-center mb-3 pb-1 d-none d-sm-block">
                      <span class="h1 fw-bold mb-0">Conjunto Residencial José María Vargas</span>
                    </div>
                    <h5 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Panel de administración</h5>
                    <div class="form-outline mb-4 mb-md-3 mb-lg-3">
                      <label class="form-label" for="usuario">Cedula</label>
                      <input type="text" id="usuario" name="usuario" class="form-control form-control-lg" value="27250544" />
                      <span id="susuario" class="text-danger espacio-vacio"></span>
                    </div>
                    <div class="form-outline mb-3">
                      <label class="form-label" for="clave">Contraseña</label>
                      <input type="password" id="clave" name="clave" class="form-control form-control-lg" value="hola123" />
                      <span id="sclave" class="text-danger espacio-vacio"></span>
                    </div>
                    <div class="from-group text-center mb-3">
                        <a href="?p=reset-pass">Has olvidado tu contraseña?</a>
                    </div>
                    <div class="pt-1 my-3 my-lg-4">
                      <button id="entrar" name="entrar" class="btn btn-info btn-lg btn-block" type="button">Ingresar</button>
                    </div>
                  </form>
                  <a href="./" class="btn btn-dark btn-lg btn-block">Regresar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="js/carga.js"></script>
  <script src="js/login.js"></script>
</body>

</html>