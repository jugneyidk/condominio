<div class="container-fluid bg-light">
    <?php require_once('comunes/barra_estado.php'); ?>
    <div class="panel-body my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 ">
                <span class="h3 mb-2 d-block text-center">Ultimos pagos</span>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Apto</th>
                                <th>Torre</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Tipo de Pago</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="ultimos_pagos">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/carga.js"></script>
<script src="js/principal.js"></script>