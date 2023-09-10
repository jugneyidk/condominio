<?php

require_once('model/datos.php');
class principal extends datos
{
    function datos_barra()
    {
        $datos = array();
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pagos_confirmados = $co->prepare("SELECT id_pago FROM pago WHERE estado = 'confirmado';");
            $pagos_confirmados->execute();
            $num_rows = $pagos_confirmados->rowCount();
            $datos['pagos_confirmados'] = $num_rows;
            $pagos_pendientes = $co->prepare("SELECT id_pago FROM pago WHERE estado = 'pendiente';");
            $pagos_pendientes->execute();
            $num_rows = $pagos_pendientes->rowCount();
            $datos['pagos_pendientes'] = $num_rows;
            $deudas_pendientes = $co->prepare("Select DISTINCT id from deuda_pendiente inner join apartamento on deuda_pendiente.id_apartamento = apartamento.id_apartamento left join pago on pago.deuda = deuda_pendiente.id where deuda_pendiente.id_apartamento=apartamento.id_apartamento AND (id NOT IN (SELECT deuda from pago WHERE estado = 'confirmado' OR estado = 'pendiente'));");
            $deudas_pendientes->execute();
            $num_rows = $deudas_pendientes->rowCount();
            $datos['deudas_pendientes'] = $num_rows;
            $morosos = $co->prepare("Select DISTINCT h.id FROM habitantes h join apartamento a on a.propietario = h.id join deuda_pendiente dp on dp.id_apartamento = a.id_apartamento WHERE dp.id_apartamento = a.id_apartamento AND dp.fecha_a_cancelar < CURRENT_DATE and (dp.id not in (SELECT deuda from pago  where estado = 'confirmado'));");
            $morosos->execute();
            $num_rows = $morosos->rowCount();
            $datos['morosos'] = $num_rows;
            $datos['resultado'] = 'datos_barra';
        } catch (Exception $e) {
            $datos['resultado'] = 'error';
            $datos['mensaje'] =  $e->getMessage();
        }
        return $datos;
    }
    function ultimos_pagos()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT p.id_pago, a.num_letra_apartamento, a.torre, p.fecha_entrega, p.total, p.estado, p.tipo_pago FROM pago p INNER JOIN deuda_pendiente dp ON p.deuda = dp.id INNER JOIN apartamento a ON dp.id_apartamento = a.id_apartamento ORDER BY p.id_pago DESC LIMIT 5;");
            $respuesta = '';
            if ($resultado) {
                foreach ($resultado as $r) {
                    $respuesta = $respuesta . "<tr>";
                    $respuesta = $respuesta . "<td style='display:none'>";
                    $respuesta = $respuesta . $r[0];
                    $respuesta = $respuesta . "</td>";
                    $respuesta = $respuesta . "<td class='font-weight-bold'>";
                    $respuesta = $respuesta . $r[1];
                    $respuesta = $respuesta . "</td>";
                    $respuesta = $respuesta . "<td>";
                    $respuesta = $respuesta . $r[2];
                    $respuesta = $respuesta . "</td>";
                    $fecha_original = $r[3];
                    $nuevo_formato = "d-m-Y";
                    $fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
                    $respuesta = $respuesta . "<td>";
                    $respuesta = $respuesta . $fecha_cambiada;
                    $respuesta = $respuesta . "</td>";
                    $respuesta = $respuesta . "<td>";
                    $respuesta = $respuesta . $r[4];
                    $respuesta = $respuesta . "$</td>";
                    $respuesta = $respuesta . "<td>";
                    $respuesta = $respuesta . $r[6];
                    $respuesta = $respuesta . "</td>";
                    $respuesta = $respuesta . "<td class='text-capitalize'>";
                    $respuesta = $respuesta . $r[5];
                    $respuesta = $respuesta . "</td>";
                    $respuesta = $respuesta . "</tr>";
                }
            }
            $r['resultado'] = 'ultimos_pagos';
            $r['mensaje'] =  $respuesta;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] =  $e->getMessage();
        }
        return $r;
    }
}
