<?php

require_once __DIR__ . '/../src/Database.php';

$pdo = Database::getConnection(); // o getConexion() si ese es el nombre real en tu clase


$empleadoId = (int)($_GET['empleado_id'] ?? 1);
$periodo    = (int)($_GET['periodo'] ?? 202601);

// Trae encabezado + líneas del recibo
$sql = "
SELECT
  e.id AS empleado_id,
  e.nombre,
  e.apellido,
  l.id AS liquidacion_id,
  l.numero AS liquidacion_numero,
  l.periodo,
  re.id AS recibo_empleado_id,
  re.total_remunerativo,
  re.total_descuentos,
  re.neto,
  c.codigo,
  c.nombre AS concepto,
  c.tipo,
  rl.importe,
  rl.orden
FROM recibos_empleados re
JOIN liquidaciones l ON l.id = re.liquidacion_id
JOIN empleados e ON e.id = re.empleado_id
JOIN recibo_lineas rl ON rl.recibo_empleado_id = re.id
JOIN conceptos c ON c.id = rl.concepto_id
WHERE e.id = :empleado_id
  AND l.periodo = :periodo
  AND c.tipo <> 'total'
ORDER BY rl.orden;
";


$stmt = $pdo->prepare($sql);
$stmt->execute(['empleado_id' => $empleadoId, 'periodo' => $periodo]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    http_response_code(404);
    echo "No hay recibo generado para empleado_id={$empleadoId} en período={$periodo}.";
    exit;
}

$h = $rows[0];

$remunerativos = [];
$descuentos = [];

foreach ($rows as $r) {
    if ($r['tipo'] === 'descuento') {
        $descuentos[] = $r;
    } else {
        // remunerativo (u otro)
        $remunerativos[] = $r;
    }
}


function money($n)
{
    return number_format((float)$n, 2, ',', '.');
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Recibo de Haberes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 24px;
            background: #fafafa;
        }


        .header {
            text-align: center;
            margin-bottom: 14px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 12px;
        }



        @page {
            margin: 20mm 15mm;
        }


        h3 {
            margin: 18px 0 8px;
            font-size: 16px;
        }



        .neto {
            font-size: 20px;
        }


        .box {
            max-width: 900px;
            margin: auto;
            background: #fff;
            border: 1px solid #ddd;
            padding: 18px;
            border-radius: 8px;
        }

        .row {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }

        .card {
            flex: 1;
            min-width: 260px;
            border: 1px solid #eee;
            padding: 12px;
            border-radius: 8px;
            background: #fcfcfc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px 8px;
            font-size: 12px;
        }

        th {
            background: #f2f2f2;
            text-align: left;
        }

        td.importe,
        th.importe {
            text-align: right;
        }

        th {
            background: #f5f5f5;
        }

        .right {
            text-align: right;
        }

        .totales {
            border-top: 2px solid #eee;
            padding-top: 12px;
            margin-top: 14px;
            display: flex;
            justify-content: flex-end;
        }

        .totales .t {
            min-width: 320px;
        }

        .totales p {
            margin: 6px 0;
        }

        .neto {
            font-size: 18px;
        }

        .firma {
            margin-top: 35px;
            text-align: right;
            font-size: 12px;
        }

        .firma .linea {
            display: inline-block;
            width: 220px;
            border-top: 1px solid #333;
            margin-bottom: 4px;
        }

        .btn-pdf {
            display: inline-block;
            padding: 10px 14px;
            border: 1px solid #333;
            border-radius: 6px;
            text-decoration: none;
            color: #111;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .btn-pdf:hover {
            background: #f2f2f2;
        }

        <?php if (defined('ES_PDF')): ?>.row {
            display: block;
        }

        .card {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        <?php endif; ?>
    </style>
</head>

<body>
    <div class="box">
        <div class="header">
            <h1>RECIBO DE HABERES</h1>
            <p>Municipalidad de Viedma</p>
            <p>CUIT XX-XXXXXXXX-X</p>
        </div>

        <div class="row">
            <div class="card">
                <b>Empleado</b><br>
                <?= htmlspecialchars($h['apellido'] . ', ' . $h['nombre']) ?><br>
                ID: <?= (int)$h['empleado_id'] ?>
            </div>
            <div class="card">
                <b>Liquidación</b><br>
                Número: <?= (int)$h['liquidacion_numero'] ?> (ID <?= (int)$h['liquidacion_id'] ?>)<br>
                Período: <?= htmlspecialchars($h['periodo']) ?>
            </div>
        </div>

        <h3>Haberes (Remunerativos)</h3>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Concepto</th>
                    <th class="importe">Importe</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($remunerativos as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['codigo']) ?></td>
                        <td><?= htmlspecialchars($r['concepto']) ?></td>
                        <td class="importe"><?= money($r['importe']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (!empty($descuentos)): ?>
            <h3 style="margin-top:18px;">Descuentos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Concepto</th>
                        <th class="right">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($descuentos as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['codigo']) ?></td>
                            <td><?= htmlspecialchars($r['concepto']) ?></td>
                            <td class="right"><?= money($r['importe']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>


        <div class="totales">
            <div class="t">
                <p><b>Total Remunerativo:</b> <?= money($h['total_remunerativo']) ?></p>
                <p><b>Total Descuentos:</b> <?= money($h['total_descuentos']) ?></p>
                <p class="neto"><b>Neto:</b> <?= money($h['neto']) ?></p>
            </div>
        </div>

        <div class="firma">
            <span class="linea"></span><br>
            Firma y Aclaración
        </div>

        <?php if (!defined('ES_PDF')): ?>
            <a href="generar_pdf.php?empleado_id=<?= (int)$empleadoId ?>&periodo=<?= (int)$periodo ?>"
                target="_blank" class="btn-pdf">
                Descargar PDF
            </a>
        <?php endif; ?>


    </div>
</body>

</html>