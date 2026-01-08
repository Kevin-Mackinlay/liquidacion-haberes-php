<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo empleado</title>
</head>
<body>

  <h1>Alta de Empleado</h1>

  <form method="POST" action="/?r=employees/store">
    <div>
      <label>CUIL</label><br>
      <input type="text" name="cuil" required>
    </div>

    <div>
      <label>Apellido</label><br>
      <input type="text" name="apellido" required>
    </div>

    <div>
      <label>Nombre</label><br>
      <input type="text" name="nombre" required>
    </div>

    <div>
      <label>Fecha ingreso</label><br>
      <input type="date" name="fecha_ingreso" required>
    </div>

    <div>
      <label>Título</label>
      <input type="checkbox" name="tiene_titulo" value="1">
    </div>

    <br>
    <button type="submit">Guardar</button>
  </form>

  <p><a href="/?r=employees">Volver al listado</a></p>

</body>
</html>
