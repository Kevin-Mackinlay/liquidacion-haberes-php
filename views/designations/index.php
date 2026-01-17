<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Designaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 24px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f5f5f5;
        }
    </style>
</head>

<body>

    <h1>Designaciones</h1>

    <?php $flash = getFlash(); ?>
    <?php if ($flash): ?>
        <div style="padding:10px; margin:10px 0; border:1px solid #ccc;">
            <strong><?= htmlspecialchars($flash['type']) ?>:</strong>
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>


    <p>
        <a href="/?r=designations/create">Nueva designación</a> |
        <a href="/?r=employees">Volver a Empleados</a>
    </p>


    <?php if (empty($designaciones)): ?>
        <p>No hay designaciones cargadas.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Empleado</th>
                    <th>Cargo</th>
                    <th>Estructura</th>
                    <th>Fecha inicio</th>
                    <th>Fecha fin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($designaciones as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['id']) ?></td>
                        <td><?= htmlspecialchars($d['apellido'] . ', ' . $d['nombre']) ?></td>
                        <td><?= htmlspecialchars($d['cargo_nombre']) ?></td>
                        <td><?= htmlspecialchars($d['estructura_nombre'] ?? '(sin estructura)') ?></td>

                        <td><?= htmlspecialchars($d['fecha_inicio']) ?></td>
                        <td><?= $d['fecha_fin'] ? htmlspecialchars($d['fecha_fin']) : '' ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>

</html>