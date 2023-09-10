<?php require_once('comunes/head.php'); ?>

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
                  <th class="d-none"></th>
                  <th scope="col" class="text-info">Apto</th>
                  <th scope="col" class="text-info">Torre</th>
                  <th scope="col" class="text-info">Piso</th>
                  <th scope="col" class="text-info">Fecha Tope</th>
                  <th scope="col" class="text-info">Total</th>
                  <th scope="col" class="text-info">Acción</th>
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
                  <th class="d-none"></th>
                  <th scope="col" class="text-info">Apto</th>
                  <th scope="col" class="text-info">Torre</th>
                  <th scope="col" class="text-info">Piso</th>
                  <th scope="col" class="text-info">Fecha Tope</th>
                  <th scope="col" class="text-info">Total</th>
                  <th scope="col" class="text-info">Acción</th>
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
  <div class="modal fade" tabindex="-100" role="dialog" id="registrarpago">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-light bg-info">
          <h5 class="modal-title">Registrar pago</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <br>
        <form id="f" method="post" autocomplete="off">
          <input type="text" name="id_deuda" id="id_deuda" class="d-none">
          <div class="container">
            <div class="row mb-3">
              <div class="col">
                <label for="referencia">Referencia:</label>
                <input type="text" name="referencia" id="referencia" class="form-control">
                <span id="sreferencia" class="text-danger"></span>
              </div>
              <div class="col">
                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" id="fecha" class="form-control" style="-webkit-appearance: none;-moz-appearance: none;">
                <span id="sfecha" class="text-danger"></span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-5">
                <label for="monto">Monto:</label>
                <input type="text" name="monto" id="monto" class="form-control" readonly>
                <span id="smonto" class="text-danger"></span>
              </div>
              <div class="col mt-3 mt-md-0">
                <label for="tipo_pago">Tipo de pago:</label>
                <select name="tipo_pago" class="form-control" id="tipo_pago">
                  <option value="-" selected disabled>-</option>
                  <option value="Efectivo">Efectivo</option>
                  <option value="Pago Movil">Pago Movil</option>
                  <option value="Transferencia Bancaria">Transferencia Bancaria</option>
                  <option value="Zelle">Zelle</option>
                </select>
                <span id="stipo_pago" class="text-danger"></span>
              </div>

            </div>

          </div>
        </form>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-success" id="registrar" onclick="registrar_pago()">Registrar
            Pago</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <script src="js/carga.js"></script>
  <script src="js/deudas.js"></script>
  <?php require_once('comunes/carga.php'); ?>
  <?php require_once('comunes/modal.php'); ?>
  <?php require_once('comunes/foot.php'); ?>
</body>

</html>