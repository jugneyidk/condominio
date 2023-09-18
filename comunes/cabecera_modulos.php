<div class="h5 mb-4 mt-4 mt-md-0 row justify-content-around">
    <div class="col">
        <a href="." class="btn btn-secondary regresar_btn">
            <span class="fa fa-chevron-left"></span><span class="ml-2">Regresar</span>
        </a>
    </div>
    <?php 
        $p_validar_cabecera = [
            "usuarios-administracion",
            "habitantes",
            "tipoapto",
            "deuda-condominio",
            "apartamentos",
            "nomina",
            "servicios",
            "estacionamiento",
            "avisos",
            "foroAction"
        ];
        if (in_array($p, $p_validar_cabecera)): 
    ?>

    <div class="col d-flex justify-content-end">
        <button class="btn btn-warning" id="limpiar">
            <span class="ml-2">Limpiar</span><span class="fa fa-eraser ml-1"></span>
        </button>
    </div>
    <?php endif;?>
</div>