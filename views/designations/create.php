<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Nueva Designación</title>
</head>

<body>

    <h1>Nueva Designación</h1>

    <form method="POST" action="/?r=designations/store">

        <div>
            <label>Empleado</label><br>
            <select name="empleado_id" required>
                <option value="">-- Seleccionar --</option>
                <?php foreach ($empleados as $e): ?>
                    <option value="<?= (int)$e['id'] ?>">
                        <?= htmlspecialchars($e['apellido'] . ', ' . $e['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Cargo</label><br>
            <select name="cargo_id" required>
                <option value="">-- Seleccionar --</option>
                <?php foreach ($cargos as $c): ?>
                    <option value="<?= (int)$c['id'] ?>">
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Fecha inicio</label><br>
            <input type="date" name="fecha_inicio" required>
        </div>

        <div>
            <label>Fecha fin</label><br>
            <input type="date" name="fecha_fin">
        </div>

        <br>
        <button type="submit">Guardar</button>
    </form>

    <p><a href="/?r=designations">Volver</a></p>

</body>

</html>