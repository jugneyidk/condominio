<?php
require_once('dompdf/vendor/autoload.php');

use Dompdf\Dompdf;


require_once('model/datos.php');

class generarreporte extends datos
{
    PRIVATE $datos, $id, $caso;

    PUBLIC function generarPDF($datos)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $nombre_archivo = '';
        try {
            switch ($datos['tiporeporte']) {
                case '1'://morosidad
                    //$resultado = $co->prepare("Select DISTINCT dp.id, dp.id_deuda_condominio, dp.id_apartamento, fecha_a_cancelar, dp.total, a.num_letra_apartamento, a.torre, a.piso, h.nombres, h.apellidos  from deuda_pendiente dp inner join apartamento a on dp.id_apartamento = a.id_apartamento JOIN habitantes h on h.id = a.propietario left join pago on pago.deuda = dp.id where dp.id_apartamento=a.id_apartamento AND dp.fecha_a_cancelar < CURRENT_DATE AND (dp.id NOT IN (SELECT deuda from pago WHERE estado = 'confirmado')) ORDER BY h.nombres ASC;");
                    $co->query("SET @n = 0; SELECT @divisa_monto := divs.monto FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1;");
                    $resultado = $co->prepare("SELECT
                    dd.n_row
                    ,h.nombres
                    ,h.apellidos
                    ,a.piso
                    ,a.torre
                    ,a.num_letra_apartamento
                    ,dd.id_deuda
                    ,(SUM(IF(dd.tipo_monto = 1, dd.monto, (ROUND(dd.monto / @divisa_monto, 2)) ) ) ) AS total

                    FROM (
                        SELECT 
                        DISTINCT
                        dd1.*
                        ,d1.id_apartamento
                        ,d1.id_distribucion
                        ,d1.moroso
                        FROM (SELECT (@n:=@n + 1) as n_row,detalles_deudas.* FROM detalles_deudas WHERE 1) as dd1
                        LEFT JOIN deudas as d1 on d1.id_deuda = dd1.id_deuda
                        LEFT JOIN deuda_pagos as dp1 on dp1.id_deuda = d1.id_deuda
                        LEFT JOIN pagos AS p1 ON p1.id_pago = dp1.id_pago
                        WHERE NOT EXISTS (SELECT 
                                     1
                                     FROM deuda_pagos 
                                     LEFT JOIN pagos ON pagos.id_pago = deuda_pagos.id_pago
                                     WHERE pagos.estado = 2 AND deuda_pagos.id_deuda = d1.id_deuda)
                    ) AS dd
                    LEFT JOIN apartamento AS a on a.id_apartamento = dd.id_apartamento
                    LEFT JOIN habitantes as h on h.id = a.propietario

                    GROUP BY a.num_letra_apartamento
                    ;");




                    $resultado->execute();
                    $fila = $resultado->fetchall(PDO::FETCH_ASSOC);
                    $html = "<html><head><style>
                    *{ font-family: DejaVu Sans !important;}
                  </style></head><body>";
                    $html = $html . "<div style='display:table;width:100%;border:solid'>";
                    $html = $html . "<div style='display:table-row;width:100%;border:solid'>";
                    $html = $html . "<div style='display:table-cell;width:100%;border:solid'>";
                    $html = $html . "<h2 align='center'>";
                    $html = $html . "Conjunto Residencial Jose María Vargas";
                    $html = $html . "</h2>";
                    $html = $html . "<h3 align='center'>";
                    $html = $html . "Reporte de morosidad";
                    $html = $html . "</h3>";
                    $html = $html . "<table style='width:100%; padding: 30px 0;'>";
                    if ($fila) {
                        $html = $html . "<thead>";
                        $html = $html . "<tr>";
                        $html = $html . "<th>Apartamento</th>";
                        $html = $html . "<th>Torre</th>";
                        $html = $html . "<th>Piso</th>";
                        $html = $html . "<th>Propietario</th>";
                        // $html = $html . "<th>Fecha tope para cancelar</th>";
                        $html = $html . "<th>Total</th>";
                        $html = $html . "</tr>";
                        $html = $html . "</thead>";
                    }
                    $html = $html . "<tbody>";
                    if ($fila) {
                        foreach ($fila as $f) {
                            $html = $html . "<tr>";
                            $html = $html . "<td style='text-align:center'>" . $f['num_letra_apartamento'] . "</td>";
                            $html = $html . "<td style='text-align:center'>" . $f['torre'] . "</td>";
                            $html = $html . "<td style='text-align:center'>" . $f['piso'] . "</td>";
                            $html = $html . "<td style='text-align:center'>" . $f['nombres'] . " " . $f['apellidos'] . "</td>";
                            // $fecha_original = $f['fecha_a_cancelar'];
                            // $nuevo_formato = "d-m-Y";
                            // $fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
                            // $html = $html . "<td style='text-align:center'>" . $fecha_cambiada . "</td>";
                            $html = $html . "<td style='text-align:center'>" . $f['total'] . "$</td>";
                            $html = $html . "</tr>";
                        }
                    } else {
                        $html = $html . "<tr>";
                        $html = $html . "<td colspan='6'>";
                        $html = $html . "<h4 align='center'>No hay morosidad actualmente";
                        $html = $html . "</td>";
                        $html = $html . "</tr>";
                    }
                    $html = $html . "</tbody>";
                    $html = $html . "</table>";
                    $html = $html . "</div></div></div>";
                    $html = $html . "</body></html>";
                    $nombre_archivo = "Morosidad";
                    break;
                case '2':
                    if (!isset($this->existe($datos['id'], 1)['resultado'])) {
                        $nombre_archivo = $this->existe($datos['id'], 1);
                        //$resultado = $co->prepare("SELECT DISTINCT dp.*, a.num_letra_apartamento, a.torre, a.piso, h.nombres, h.apellidos FROM deuda_pendiente dp JOIN apartamento a ON dp.id_apartamento = a.id_apartamento JOIN habitantes h ON a.propietario = h.id LEFT JOIN pago p ON dp.id = p.deuda WHERE a.propietario = '{$datos['id']}' AND (dp.id NOT IN (SELECT deuda from pago WHERE estado = 'confirmado')) ORDER BY dp.id DESC;");
                        $co->query("SET @n = 0; SELECT @divisa_monto := divs.monto FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1;");
                        $resultado = $co->prepare("SELECT
                        dd.n_row
                        ,h.nombres
                        ,h.apellidos
                        ,a.piso
                        ,a.torre
                        ,a.num_letra_apartamento
                        ,dd.id_deuda
                        ,(SUM(IF(dd.tipo_monto = 1, dd.monto, (ROUND(dd.monto / @divisa_monto, 2)) ) ) ) AS total

                        FROM (
                            SELECT 
                            DISTINCT
                            dd1.*
                            ,d1.id_apartamento
                            ,d1.id_distribucion
                            ,d1.moroso
                            FROM (SELECT (@n:=@n + 1) as n_row,detalles_deudas.* FROM detalles_deudas WHERE 1) as dd1
                            LEFT JOIN deudas as d1 on d1.id_deuda = dd1.id_deuda
                            LEFT JOIN deuda_pagos as dp1 on dp1.id_deuda = d1.id_deuda
                            LEFT JOIN pagos AS p1 ON p1.id_pago = dp1.id_pago
                            WHERE NOT EXISTS (SELECT 
                                         1
                                         FROM deuda_pagos 
                                         LEFT JOIN pagos ON pagos.id_pago = deuda_pagos.id_pago
                                         WHERE pagos.estado = 2 AND deuda_pagos.id_deuda = d1.id_deuda)
                        ) AS dd
                        LEFT JOIN apartamento AS a on a.id_apartamento = dd.id_apartamento
                        LEFT JOIN habitantes as h on h.id = a.propietario
                        WHERE h.id = ?
                        GROUP BY a.num_letra_apartamento
                        ;");






                        $resultado->execute([$datos["id"]]);
                        $fila = $resultado->fetchall(PDO::FETCH_ASSOC);
                        $html = "<html><head><style>
                            *{ font-family: DejaVu Sans !important;}
                          </style></head><body>";
                        $html = $html . "<div style='display:table;width:100%;border:solid'>";
                        $html = $html . "<div style='display:table-row;width:100%;border:solid'>";
                        $html = $html . "<div style='display:table-cell;width:100%;border:solid'>";
                        $html = $html . "<h2 align='center'>";
                        $html = $html . "Conjunto Residencial Jose María Vargas";
                        $html = $html . "</h2>";
                        $html = $html . "<h3 align='center'>";
                        $html = $html . "Reporte de Propietario";
                        $html = $html . "</h3>";
                        $html = $html . "<h4 align='center'>";
                        $html = $html . $nombre_archivo;
                        $html = $html . "</h4>";
                        $html = $html . "<table style='width:100%; padding: 30px 0;'>";
                        if ($fila) {
                            $html = $html . "<thead>";
                            $html = $html . "<tr>";
                            $html = $html . "<th>Apartamento</th>";
                            $html = $html . "<th>Torre</th>";
                            $html = $html . "<th>Piso</th>";
                            $html = $html . "<th>Propietario</th>";
                            $html = $html . "<th>Total</th>";
                            $html = $html . "</tr>";
                            $html = $html . "</thead>";
                        }
                        $html = $html . "<tbody>";
                        if ($fila) {
                            foreach ($fila as $f) {
                                $html = $html . "<tr>";
                                $html = $html . "<td style='text-align:center'>" . $f['num_letra_apartamento'] . "</td>";
                                $html = $html . "<td style='text-align:center'>" . $f['torre'] . "</td>";
                                $html = $html . "<td style='text-align:center'>" . $f['piso'] . "</td>";
                                $html = $html . "<td style='text-align:center'>" . $f['nombres'] . " " . $f['apellidos'] . "</td>";
                                $html = $html . "<td style='text-align:center'>" . $f['total'] . "$</td>";
                                $html = $html . "</tr>";
                            }
                        } else {
                            $html = $html . "<tr>";
                            $html = $html . "<td colspan='6'>";
                            $html = $html . "<h4 align='center'>Este propietario no tiene deudas pendientes.";
                            $html = $html . "</td>";
                            $html = $html . "</tr>";
                        }
                        $html = $html . "</tbody>";
                        $html = $html . "</table>";
                        $html = $html . "</div></div></div>";
                        $html = $html . "</body></html>";
                    }
                    break;
                case '3':
                    if (!isset($this->existe($datos['id'], 2)['resultado'])) {
                        $datos_apto = $this->existe($datos['id'], 2);
                        $nombre_archivo = $datos_apto[0]['num_letra_apartamento'] . " Torre " . $datos_apto[0]['torre'];
                        //$resultado = $co->prepare("SELECT DISTINCT dp.*, a.num_letra_apartamento, a.torre, a.piso, h.nombres, h.apellidos FROM deuda_pendiente dp JOIN apartamento a ON dp.id_apartamento = a.id_apartamento JOIN habitantes h ON a.propietario = h.id LEFT JOIN pago p ON dp.id = p.deuda WHERE a.id_apartamento = '{$datos['id']}' AND (dp.id NOT IN (SELECT deuda from pago WHERE estado = 'confirmado')) ORDER BY dp.id DESC;");
                        $co->query("SET @n = 0; SELECT @divisa_monto := divs.monto FROM tipo_cambio_divisa AS divs WHERE 1 ORDER BY divs.fecha DESC LIMIT 1;");
                        $resultado = $co->prepare("SELECT
                        dd.n_row
                        ,dd.id_deuda
                        ,dis.concepto
                        ,dis.fecha
                        ,(SUM(IF(dd.tipo_monto = 1, dd.monto, (ROUND(dd.monto / @divisa_monto, 2)) ) ) ) AS total

                        FROM (
                            SELECT 
                            DISTINCT
                            dd1.*
                            ,d1.id_apartamento
                            ,d1.id_distribucion
                            ,d1.moroso
                            FROM (SELECT (@n:=@n + 1) as n_row,detalles_deudas.* FROM detalles_deudas WHERE 1) as dd1
                            LEFT JOIN deudas as d1 on d1.id_deuda = dd1.id_deuda
                            LEFT JOIN deuda_pagos as dp1 on dp1.id_deuda = d1.id_deuda
                            LEFT JOIN pagos AS p1 ON p1.id_pago = dp1.id_pago
                            WHERE NOT EXISTS (SELECT 
                                         1
                                         FROM deuda_pagos 
                                         LEFT JOIN pagos ON pagos.id_pago = deuda_pagos.id_pago
                                         WHERE pagos.estado = 2 AND deuda_pagos.id_deuda = d1.id_deuda)
                        ) AS dd
                        LEFT JOIN apartamento AS a on a.id_apartamento = dd.id_apartamento
                        LEFT JOIN habitantes as h on h.id = a.propietario
                        LEFT JOIN distribuciones as dis on dis.id_distribucion = dd.id_distribucion
                        WHERE a.id_apartamento = 1
                        GROUP BY a.num_letra_apartamento, dd.id_deuda
                        ;");





                        $resultado->execute([$datos["id"]]);
                        $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
                        $html = "<html><head><style>
                    *{ font-family: DejaVu Sans !important;}
                  </style></head><body>";
                        $html = $html . "<div style='display:table;width:100%;border:solid'>";
                        $html = $html . "<div style='display:table-row;width:100%;border:solid'>";
                        $html = $html . "<div style='display:table-cell;width:100%;border:solid'>";
                        $html = $html . "<h2 align='center'>";
                        $html = $html . "Conjunto Residencial Jose María Vargas";
                        $html = $html . "</h2>";
                        $html = $html . "<h3 align='center'>";
                        $html = $html . "Reporte de apartamento";
                        $html = $html . "</h3>";
                        $html = $html . "<h4 align='center'>";
                        $html = $html . $datos_apto[0]['num_letra_apartamento'] . " Torre " . $datos_apto[0]['torre'] . " Piso " . $datos_apto[0]['piso'];
                        $html .= "<br>".$datos_apto[0]['nombre'];
                        $html = $html . "</h4>";
                        $html = $html . "<table style='width:100%; padding: 30px 0;'>";
                        if ($fila) {
                            $html = $html . "<thead>";
                            $html = $html . "<tr>";
                            // $html = $html . "<th>Apartamento</th>";
                            // $html = $html . "<th>Torre</th>";
                            // $html = $html . "<th>Piso</th>";
                            // $html = $html . "<th>Propietario</th>";
                            $html = $html . "<th>Deuda</th>";
                            $html = $html . "<th>fecha</th>";
                            $html = $html . "<th>Total</th>";
                            $html = $html . "</tr>";
                            $html = $html . "</thead>";
                        }
                        $html = $html . "<tbody>";
                        if ($fila) {
                            foreach ($fila as $f) {
                                $html = $html . "<tr>";
                                // $html = $html . "<td style='text-align:center'>" . $f['num_letra_apartamento'] . "</td>";
                                // $html = $html . "<td style='text-align:center'>" . $f['torre'] . "</td>";
                                // $html = $html . "<td style='text-align:center'>" . $f['piso'] . "</td>";
                                // $html = $html . "<td style='text-align:center'>" . $f['nombres'] . " " . $f['apellidos'] . "</td>";
                                $html = $html . "<td style='text-align:left;padding-left:20px'>" . $f['concepto'] . "</td>";
                                $html = $html . "<td style='text-align:center'>" . $f['fecha'] . "</td>";
                                $html = $html . "<td style='text-align:center'>" . $f['total'] . "$</td>";
                                $html = $html . "</tr>";
                            }
                        } else {
                            $html = $html . "<tr>";
                            $html = $html . "<td colspan='6'>";
                            $html = $html . "<h4 align='center'>El apartamento no tiene deudas pendientes.";
                            $html = $html . "</td>";
                            $html = $html . "</tr>";
                        }
                        $html = $html . "</tbody>";
                        $html = $html . "</table>";
                        $html = $html . "</div></div></div>";
                        $html = $html . "</body></html>";
                    }
                    break;
                default:
                    header('location: ?p=generar-reporte');
                    break;
            }
        } catch (Exception $e) {
            echo $e->getMessage()." ".$e->getLine();
        }
        $pdf = new DOMPDF();
        $pdf->set_paper("A4", "portrait");
        $pdf->load_html($html, 'UTF-8');
        $pdf->render();
        $pdf->stream("Reporte {$nombre_archivo}.pdf", array("Attachment" => false));
        
    }
    PRIVATE function existe($id, $caso)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        switch ($caso) {
            case 1:
                try {
                    $resultado = $co->prepare("SELECT nombres, apellidos from habitantes WHERE id = '$id'");
                    $resultado->execute();
                    $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
                    if ($fila) {
                        $nombre = $fila[0][0] . " " . $fila[0][1];
                        return $nombre;
                    }
                    $r['resultado'] = 'error';
                    $r['mensaje'] =  "El habitante no existe";
                } catch (Exception $e) {
                    $r['resultado'] = 'error';
                    $r['mensaje'] =  $e->getMessage();
                }
                return $r;
                break;
            case 2:
                try {
                    $resultado = $co->prepare("SELECT num_letra_apartamento,piso,torre, CONCAT(h.nombres,' ',h.apellidos) as nombre from apartamento as a LEFT JOIN habitantes as h on a.propietario = h.id WHERE id_apartamento = ?");
                    $resultado->execute([$id]);
                    $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
                    if ($fila) {
                        return $fila;
                    }
                    $r['resultado'] = 'error';
                    $r['mensaje'] =  "El apartamento no existe";
                } catch (Exception $e) {
                    $r['resultado'] = 'error';
                    $r['mensaje'] =  $e->getMessage();
                    echo $e->getMessage();
                }
                return $r;
                break;
            default:
                break;
        }
    }
}
