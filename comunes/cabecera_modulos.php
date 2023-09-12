<div class="h5 mb-4 mt-4 mt-md-0 row justify-content-around">
    <div class="col">
        <a href="." class="btn btn-secondary">
            <span class="fa fa-chevron-left"></span><span class="ml-2">Regresar</span>
        </a>
    </div>
    
    <?php if ($p == "usuarios-administracion" || $p == "habitantes" || $p == "tipoapto" || $p == "apartamentos" || $p == "deuda-condominio" || $p == "nomina" || $p == 'servicios' || $p == 'estacionamiento'): ?>

    <div class="col d-flex justify-content-end">
        <button class="btn btn-warning" id="limpiar">
            <span class="ml-2">Limpiar</span><span class="fa fa-eraser ml-1"></span>
        </button>
    </div>
    <?php endif;?>
</div>