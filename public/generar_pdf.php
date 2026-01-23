<?php

declare(strict_types=1);

// Marca para que recibo.php oculte el botón
define('ES_PDF', true);

// Evita que warnings/notices rompan el PDF
ini_set('display_errors', '0');
error_reporting(0);

require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Leer parámetros (vienen del botón / URL)
$empleadoId = (int)($_GET['empleado_id'] ?? 1);
$periodo    = (int)($_GET['periodo'] ?? 202601);

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);

// Capturar HTML del recibo (recibo.php usa $empleadoId y $periodo)
ob_start();
include __DIR__ . '/recibo.php';
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Bytes del PDF
$pdfOutput = $dompdf->output();

// Guardar en /public/recibos/
$dir = __DIR__ . '/recibos';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// Nombre dinámico
$filename = "recibo_emp{$empleadoId}_per{$periodo}.pdf";
file_put_contents($dir . '/' . $filename, $pdfOutput);

// Mostrar en navegador (inline)
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Length: ' . strlen($pdfOutput));

echo $pdfOutput;
exit;
