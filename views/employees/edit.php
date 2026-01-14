<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Editar empleado</title>
</head>

<body>

  <h1>Editar Empleado</h1>

  <!-- Importante: mandamos el ID por URL -->
  <form method="POST" action="/?r=employees/update&id=<?= (int)$empleado['id'] ?>">

    <div>
      <label>CUIL</label><br>
      <input type="text" name="cuil" required
        value="<?= htmlspecialchars($empleado['cuil']) ?>">
    </div>

    <div>
      <label>Apellido</label><br>
      <input type="text" name="apellido" required
        value="<?= htmlspecialchars($empleado['apellido']) ?>">
    </div>

    <div>
      <label>Nombre</label><br>
      <input type="text" name="nombre" required
        value="<?= htmlspecialchars($empleado['nombre']) ?>">
    </div>

    <div>
      <label>Fecha ingreso</label><br>
      <input type="date" name="fecha_ingreso" required
        value="<?= htmlspecialchars($empleado['fecha_ingreso']) ?>">
    </div>

    <div>
      <label>Título</label>
      <input type="checkbox" name="tiene_titulo" value="1"
        <?= ((int)$empleado['tiene_titulo'] === 1) ? 'checked' : '' ?>>
    </div>

    <div>
      <label>Cargo</label><br>
      <select name="cargo_id" required>
        <option value="">-- Seleccionar cargo --</option>

        <?php foreach ($cargos as $c): ?>
          <option value="<?= (int)$c['id'] ?>"
            <?= ((int)$empleado['cargo_id'] === (int)$c['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['nombre']) ?>
          </option>
        <?php endforeach; ?>

      </select>
    </div>

    <div>
      <label>
        <input type="checkbox" name="tiene_titulo" value="1"
          <?= ((int)$empleado['tiene_titulo'] === 1) ? 'checked' : '' ?>>
        Título
      </label>
    </div>

    <br>
    <button type="submit">Guardar cambios</button>
  </form>

  <p><a href="/?r=employees">Volver al listado</a></p>

</body>

</html>