<?php require_once('comunes/head.php'); ?>
<link rel="stylesheet" type="text/css" href="css/consulta.css">
<body class="d-flex flex-column justify-content-center">
  <?php require_once('comunes/carga.php'); ?>
  <section class="body">
    <div class="container">
      <div class="px-2 px-sm-3 py-sm-5 login-box mx-auto d-flex flex-column justify-content-center">
        <div class="row">
          <div class="col-md-12 col-lg-7">
            <div class="logo font-weight-bold mb-5">
              <span>Condominio</span>
              <span class="logo-font d-block">José María Vargas</span>
            </div>
            <span class="h3 header-title d-block text-center">Consulta tu deuda</span>
          </div>
        </div>
        <div class="row p-2">
          <div class="col-md-12 col-lg-7">            
            <div class="table-responsive mb-4">
              <table class="table table-striped" id="tabladeudas">
                <thead>
                  <tr>
                    <th scope="col" class="d-none"></th>
                    <th scope="col" class="text-info">Apto</th>
                    <th scope="col" class="text-info d-none d-md-block">Torre</th>
                    <th scope="col" class="text-info">Fecha</th>
                    <th scope="col" class="text-info">Deuda</th>
                    <th scope="col" class="text-info"></th>
                  </tr>
                </thead>
                <tbody id="listadodeudas">
                </tbody>
              </table>
            </div>
            <div class="container-fluid pt-1 my-2">
              <div class="row">
                <div class="col-12 col-sm-6 d-flex justify-content-center order-2 order-sm-1">
                  <a class="btn btn-secondary" type="button" href="?p=consulta&out=1"><span class="fa fa-chevron-left mr-2"></span>Regresar</a>
                </div>
                <div class="col-12 col-sm-6 d-flex justify-content-center order-1 order-sm-2 mb-2 mb-sm-0">
                  <button class="btn btn-info" type="button" id="btn-historial"><span class="fa fa-list-alt mr-2"></span>Historial de pagos</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </section>
  <div class="modal fade" tabindex="-100" role="dialog" id="historialpagos">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header text-light bg-info">
          <h5 class="modal-title">Historial de pagos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="container-fluid table-responsive w-100">
          <table class="table w-100 table-striped table-hover" id="tablahistorial">
            <thead>
              <tr>
                <th scope="col" class="text-info">Nº Pago</th>
                <th scope="col" class="d-none d-lg-table-cell text-info">Apto</th>
                <th scope="col" class="d-none d-lg-table-cell text-info">Torre</th>
                <th scope="col" class="text-info">Piso</th>
                <th scope="col" class="text-info">Fecha</th>
                <th scope="col" class="text-info">Referencia</th>
                <th scope="col" class="text-info">Tipo de pago</th>
                <th scope="col" class="text-info">Monto</th>
                <th scope="col" class="text-info">Estado</th>
              </tr>
            </thead>
            <tbody class="w-100" id="listahistorial">
            </tbody>
          </table>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
        <form id="f" method="post" autocomplete="off">
          <input type="text" name="id_deuda" id="id_deuda" class="d-none">
          <div class="container mt-3">
            <div class="row mb-3">
              <div class="col">
                <label for="referencia">Referencia:</label>
                <input type="text" name="referencia" id="referencia" class="form-control">
                <span id="sreferencia" class="text-danger"></span>
              </div>
              <div class="col">
                <label for="monto">Monto:</label>
                <input type="text" name="monto" id="monto" class="form-control" readonly>
                <span id="smonto" class="text-danger"></span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col">
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
          <button type="button" class="btn btn-success" id="registrar" onclick="registrar_pago()">Registrar Pago</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <script src="js/carga.js"></script>
  <script src="js/detallesdeuda.js"></script>
</body>

</html>