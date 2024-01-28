<?php require_once('comunes/head.php'); ?>

<style>.money_icon::after{content: "$"}</style>

<body class="bg-light">
  <?php require_once('comunes/menu.php'); ?>
  <div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
    <?php require_once('comunes/cabecera_modulos.php'); ?>
    <div>
      <span class="text-center h1 fw-bold mb-0 text-wrap d-block">Conjunto Residencial José María Vargas</span>
      <small class="h5 text-muted my-3 pb-3 text-center d-block">Consulta de deudas</small>
      </hr>
    </div>
    <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="todas-tab" data-toggle="tab" href="#todas" role="tab" aria-controls="todas" aria-selected="true">Todas las deudas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="morosos-tab" data-toggle="tab" href="#morosos" role="tab" aria-controls="morosos" aria-selected="false">Morosos</a>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="todas" role="tabpanel" aria-labelledby="home-tab">
        <div class="container">
          <div class="table-responsive">
            <table class="table table-striped table-hover" id="tabladeudas">
              <thead>
                <tr>
                  <th scope="col" class="text-info">Apto</th>
                  <th scope="col" class="text-info d-none d-md-table-cell">Torre</th>
                  <th scope="col" class="text-info d-none d-md-table-cell">Concepto</th>
                  <th scope="col" class="text-info">Fecha</th>
                  <th scope="col" class="text-info">Deuda</th>
                  <th scope="col" class="text-info"></th>
                </tr>
              </thead>
              <tbody id="listadodeudas">
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="morosos" role="tabpanel" aria-labelledby="morosos-tab">
        <div class="container">
          <div class="table-responsive">
            <table class="table table-striped table-hover" id="tablamorosos">
              <thead>
                <tr>
                  <th scope="col" class="text-info">Apto</th>
                  <th scope="col" class="text-info d-none d-md-table-cell">Torre</th>
                  <th scope="col" class="text-info d-none d-md-table-cell">Concepto</th>
                  <th scope="col" class="text-info">Fecha</th>
                  <th scope="col" class="text-info">Deuda</th>
                  <th scope="col" class="text-info"></th>
                </tr>
              </thead>
              <tbody id="listadomorosos">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" id="registrarpago">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header text-light bg-info">
          <h5 class="modal-title">Registrar Pago</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="container">
          <div class="row">
            <div class="col">
              <strong id="monto_para_calcular"></strong>
            </div>
          </div>
          <form id="f" method="POST" autocomplete="off" onsubmit="return false;">
            <input type="hidden" id="id_deuda_tosend" name="id_deuda_tosend" class="d-none">
              <?php require_once("comunes/tipo_pago.php"); ?>
          </form>
        </div>
        <div class="modal-footer bg-light">
            <button type="button" class="btn btn-success" id="registrar" onclick="registrar_pago()">Registrar Pago</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>




  <script src="js/tipo_pago_comun.js"></script>
  <script src="js/carga.js"></script>
  <script src="js/comun_x.js"></script>
  <script src="js/deudas.js"></script>
  <?php require_once('comunes/carga.php'); ?>
  <?php require_once('comunes/modal.php'); ?>
  <?php require_once('comunes/foot.php'); ?>
</body>

</html>