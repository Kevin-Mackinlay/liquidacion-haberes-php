<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Empleados</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 24px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #f5f5f5; }
  </style>
</head>
<body>

  <h1>Listado de Empleados</h1>

  <?php if (empty($empleados)): ?>
    <p>No hay empleados cargados.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>CUIL</th>
          <th>Apellido</th>
          <th>Nombre</th>
          <th>Fecha ingreso</th>
          <th>Título</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($empleados as $e): ?>
          <tr>
            <td><?= htmlspecialchars($e['id']) ?></td>
            <td><?= htmlspecialchars($e['cuil']) ?></td>
            <td><?= htmlspecialchars($e['apellido']) ?></td>
            <td><?= htmlspecialchars($e['nombre']) ?></td>
            <td><?= htmlspecialchars($e['fecha_ingreso']) ?></td>
            <td><?= $e['tiene_titulo'] ? 'Sí' : 'No' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

</body>
</html>
