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

<?php $flash = getFlash(); ?>
<?php if ($flash): ?>
  <div style="padding:10px; margin:10px 0; border:1px solid #ccc;">
    <strong><?= htmlspecialchars($flash['type']) ?>:</strong>
    <?= htmlspecialchars($flash['message']) ?>
  </div>
<?php endif; ?>

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
        <th>Cargo</th>
        <th>Estructura</th>
        <th>Acciones</th>
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

         <!-- CARGO -->
<td><?= htmlspecialchars($e['cargo_nombre'] ?? '(sin cargo)') ?></td>

<!-- ESTRUCTURA -->
<td><?= htmlspecialchars($e['estructura_nombre'] ?? '(sin estructura)') ?></td>


          <!-- ACCIONES -->
          <td>
            <a href="/?r=employees/edit&id=<?= (int)$e['id'] ?>">Editar</a>

            <form method="POST"
                  action="/?r=employees/destroy&id=<?= (int)$e['id'] ?>"
                  style="display:inline;">
              <button type="submit"
                      onclick="return confirm('¿Seguro que querés eliminar este empleado?');">
                Eliminar
              </button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

</body>
</html>
