

    <style>

        .container11 {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .balance {
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
            color: #007bff;
        }
    </style>
    <?php require_once('comunes/head.php'); ?>
<body class="bg-light">
	<?php require_once("comunes/carga.php"); ?>
	<?php require_once("comunes/modal.php"); ?>
	<?php require_once('comunes/menu.php'); ?>
	<div class="container-lg bg-white p-2 p-sm-4 p-md-5 mb-5">
		<?php require_once('comunes/cabecera_modulos.php'); ?>
		<div>
			<h2 class="text-center h2 text-primary"></h2>
			<hr />
		</div>

 <div class="container11">
        <center><h2>Saldo</h2></center>
        <?php
        // Simula un saldo ficticio
        $balance = 1000.50;
        ?>
        <div class="balance">
            Balance actual: $<?php echo number_format($balance, 2); ?>
        </div>
    </div>

<div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
        <div class="table-responsive">
          <table class="table table-striped" id="tablapagos">
            <thead>
              <tr>
                <th scope="col" class="text-info">Nº pago</th>
                <th scope="col" class="text-info">Apto</th>
                <th scope="col" class="text-info">Torre</th>
                <th scope="col" class="text-info">Fecha</th>
                <th scope="col" class="text-info">Monto</th>
                <th scope="col" class="text-info">Estado</th>
                <th scope="col" class="text-info">Acción</th>
              </tr>
            </thead>
            <tbody id="listadopagos">
            </tbody>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="pendiente" role="tabpanel" aria-labelledby="pendiente-tab">
        <div class="table-responsive">
          <table class="table table-striped" id="tablapendiente">
            <thead>
              <tr>
                <th scope="col" class="text-info">Nº pago</th>
                <th scope="col" class="text-info">Apto</th>
                <th scope="col" class="text-info">Torre</th>
                <th scope="col" class="text-info">Fecha</th>
                <th scope="col" class="text-info">Monto</th>
                <th scope="col" class="text-info">Estado</th>
                <th scope="col" class="text-info">Acción</th>
              </tr>
            </thead>
            <tbody id="listadopagospendientes">
            </tbody>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="confirmados" role="tabpanel" aria-labelledby="confirmados-tab">
        <div class="table-responsive">
          <table class="table table-striped" id="tablaconfirmado">
            <thead>
              <tr>
                <th scope="col" class="text-info">Nº pago</th>
                <th scope="col" class="text-info">Apto</th>
                <th scope="col" class="text-info">Torre</th>
                <th scope="col" class="text-info">Fecha</th>
                <th scope="col" class="text-info">Monto</th>
                <th scope="col" class="text-info">Estado</th>
                <th scope="col" class="text-info">Acción</th>
              </tr>
            </thead>
            <tbody id="listadopagosconfirmados">
            </tbody>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="declinados" role="tabpanel" aria-labelledby="declinados-tab">
        <div class="table-responsive">
          <table class="table table-striped" id="tabladeclinado">
            <thead>
              <tr>
                <th scope="col" class="text-info">Nº pago</th>
                <th scope="col" class="text-info">Apto</th>
                <th scope="col" class="text-info">Torre</th>
                <th scope="col" class="text-info">Fecha</th>
                <th scope="col" class="text-info">Monto</th>
                <th scope="col" class="text-info">Estado</th>
                <th scope="col" class="text-info">Acción</th>
              </tr>
            </thead>
            <tbody id="listadopagosdeclinados">
            </tbody>
          </table>
        </div>
      </div>
    </div>

	</div>
	<?php require_once('comunes/foot.php'); ?>
</body>
