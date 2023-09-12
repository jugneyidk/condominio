
<?php require_once('comunes/head.php'); ?>

<body class="bg-light">
  <?php require_once("comunes/carga.php"); ?>
  <?php require_once("comunes/modal.php"); ?>
  <?php require_once('comunes/menu.php'); ?>
  <div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
    <?php require_once('comunes/cabecera_modulos.php'); ?>

    <?php require_once('comunes/cabecera_modulos.php'); ?>
    <div>
      <h2 class="text-center h2 text-primary">Crear aviso</h2>
      <hr />
    </div>

    <form method="post" action="" id="f"> 
      <div class="row">
        <div class="col">
          <input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
          <input autocomplete="off" type="text" class="form-control" name="id" id="id" style="display: none;">
          <div class="container">
            <div class="row mb-3">
              <div class="col-6 col-md-9">
                
                <label for="descripcion">Titulo:</label>
                <input autocomplete="off" class="form-control" type="text" id="descripcion" name="descripcion" data-span="sdescripcion"/>
                <span id="sdescripcion" class="text-danger"></span>
              </div>
              <div class="col-12 col-md-9">
                <label for="descripcion">Descripción:</label>
                <input autocomplete="off" class="form-control" type="text" id="descripcion" name="descripcion" data-span="sdescripcion" style="height: 120px" />
                <span id="sdescripcion" class="text-danger"></span>
              </div>

            </div>

            <div class="row mb-3">
              <div class="col-6 col-md-2">
                <label for="descripcion">Desde:</label>
                <input autocomplete="off" class="form-control" type="date" id="fecha" name="fecha" data-span="sfecha"/>
                <span id="sfecha" class="text-danger"></span>
              </div>
              <div class="col-6 col-md-2">
                <label for="descripcion">Hasta:</label>
                <input autocomplete="off" class="form-control" type="date" id="fecha2" name="fecha2" data-span="sfecha2"/>
                <span id="sfecha2" class="text-danger"></span>
              </div>
            </div>
          </div>
          <hr>
          <div class="row justify-content-center">
            <?php //if ($permisos[2] == 1): ?>
              <div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
                <button type="button" class="btn btn-primary w-100 small-width" id="incluir" name="incluir">INCLUIR<span class="fa fa-plus-circle ml-2"></span></button>
              </div>
            <?php //endif; ?>
            <?php //if ($permisos[3] == 1) : ?>
              <div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
                <button type="button" class="btn btn-info w-100 small-width" id="consultar" data-toggle="modal" data-target="#modalEstacionamiento" name="consultar">CONSULTAR<span class="fa fa-table ml-2"></span></button>
              </div>
            <?php// endif; ?>
            <?php //if ($permisos[4] == 1) : ?>
              <div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
                <button type="button" class="btn btn-warning w-100 small-width" id="modificar" name="modificar" disabled>MODIFICAR<span class="fa fa-pencil-square-o ml-2"></span></button>
              </div>
            <?php// endif; ?>
            <?php// if ($permisos[5] == 1) : ?>
              <div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-3">
                <button type="button" class="btn btn-danger w-100 small-width" id="eliminar" name="eliminar" disabled>ELIMINAR<span class="fa fa-trash ml-2"></span></button>
              </div>
            <?php //endif; ?>
          </div>
        </div>
 </div>
    </form>
  </div>
  <script src="js/carga.js"></script>

  <?php require_once('comunes/foot.php'); ?>


</html>