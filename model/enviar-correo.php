<?php 
use PHPMailer\PHPMailer\PHPMailer;
use Dompdf\Dompdf;

require_once('dompdf/vendor/autoload.php');
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once('model/datos.php');
class enviarcorreo extends datos
{
	PRIVATE $id;
    PUBLIC function enviar_correo($id)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$existe = $this->existe($id);
		try {
			if ($existe['resultado'] == 'existe') {
				$resultado = $co->prepare("SELECT pago.id_pago, pago.referencia, pago.fecha_entrega, pago.tipo_pago, pago.total, apartamento.num_letra_apartamento, apartamento.piso, apartamento.torre, habitantes.nombres, habitantes.apellidos, habitantes.correo FROM `pago` INNER JOIN deuda_pendiente on pago.deuda=deuda_pendiente.id INNER JOIN apartamento on deuda_pendiente.id_apartamento=apartamento.id_apartamento INNER JOIN habitantes on apartamento.propietario=habitantes.id WHERE pago.id_pago = '$id';");
				$resultado->execute();
				$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
				$correo = '';
				$nombre = '';
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
				$html = $html . "Comprobante de pago";
				$html = $html . "</h3>";
				$html = $html . "<h4 align='center'>";
				$html = $html . "Pago Nº" . $id;
				$html = $html . "</h4>";
				$html = $html . "<table style='width:100%; padding: 30px 0;'>";
				$html = $html . "<tbody>";
				if ($fila) {
					foreach ($fila as $f) {
						$correo = $f['correo'];
						$nombre = $f['nombres'] . " " . $f['apellidos'];
						$html = $html . "<tr align='center'>";
						$html = $html . "<td>";
						$html = $html . "<h5>Referencia:</h5>";
						$html = $html . "<span>";
						$html = $html . $f['referencia'];
						$html = $html . "</span>";
						$html = $html . "</td>";
						$html = $html . "<td colspan='2'>";
						$html = $html . "<h5>Método de pago:</h5>";
						$html = $html . "<span>";
						$html = $html . $f['tipo_pago'];
						$html = $html . "</span>";
						$html = $html . "</td>";
						$html = $html . "</tr>";
						$html = $html . "<tr align='center'>";
						$html = $html . "<td>";
						$html = $html . "<h5>Apartamento:</h5>";
						$html = $html . "<span>";
						$html = $html . $f['num_letra_apartamento'];
						$html = $html . "</span>";
						$html = $html . "</td>";
						$html = $html . "<td>";
						$html = $html . "<h5>Torre:</h5>";
						$html = $html . "<span>";
						$html = $html . $f['torre'];
						$html = $html . "</span>";
						$html = $html . "</td>";
						$html = $html . "<td>";
						$html = $html . "<h5>Piso:</h5>";
						$html = $html . "<span>";
						$html = $html . $f['piso'];
						$html = $html . "</span>";
						$html = $html . "</td>";
						$html = $html . "</tr>";
						$html = $html . "<tr align='center'>";
						$html = $html . "<td>";
						$html = $html . "<h5>Fecha:</h5>";
						$html = $html . "<span>";
						$fecha_original = $f['fecha_entrega'];
						$nuevo_formato = "d-m-Y";
						$fecha_cambiada = date($nuevo_formato, strtotime($fecha_original));
						$html = $html . $fecha_cambiada;
						$html = $html . "</span>";
						$html = $html . "</td>";
						$html = $html . "<td colspan='2'>";
						$html = $html . "<h5>Monto:</h5>";
						$html = $html . "<span>";
						$html = $html . $f['total'];
						$html = $html . "$</span>";
						$html = $html . "</td>";
						$html = $html . "</tr>";
					}
				}
				$html = $html . "</tbody>";
				$html = $html . "</table>";
				$html = $html . "</div></div></div>";
				$html = $html . "</body></html>";
				$pdf = new DOMPDF();
				$pdf->setPaper("A4", "portrait");
				$pdf->loadHtml($html, 'UTF-8');
				$pdf->render();
				$pdfpago = $pdf->output();
				$temp_pdf = tempnam(sys_get_temp_dir(), 'pdf_');
				file_put_contents($temp_pdf, $pdfpago);
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->SMTPDebug = 0;
				$mail->Debugoutput = 'html';
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 587;
				$mail->SMTPSecure = 'tls';
				$mail->SMTPAuth = true;
				$mail->Username = "diego14asf@gmail.com";
				$mail->Password = "okcycrqarqjqcnsk";
				$mail->setFrom('diego14asf@gmail.com', 'Diego Salazar');
				$mail->addAddress($correo, $nombre);
				$mail->CharSet = 'UTF-8';
				$mail->Subject = 'Pago Nº' . $id . ' confirmado';
				$mail->Body = "Le informamos que su pago ha sido confirmado.";
				$mail->addAttachment($temp_pdf, "Pago N$id.pdf");
				$mail->AltBody = 'This is a plain-text message';
				if (!$mail->send()) {
					$mail->clearAllRecipients();
					$mail->clearAttachments();
					$mail->clearCustomHeaders();
					unlink($temp_pdf);
					return false;
				} else {
					$mail->clearAllRecipients();
					$mail->clearAttachments();
					$mail->clearCustomHeaders();
					unlink($temp_pdf);
					return true;
				}
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
    PUBLIC function existe($id)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->prepare("SELECT id_pago from pago WHERE id_pago = '$id'");
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if ($fila) {
				$r['resultado'] = 'existe';
				return $r;
			}
			$r['resultado'] = 'noexiste';
			$r['mensaje'] =  "El pago no existe";
			return $r;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
			return $r;
		}
	}
}
