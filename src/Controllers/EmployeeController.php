<?php

require_once __DIR__ . '/../Database.php';

class EmployeeController
{
    public function index(): void
    {
        // 1) Nos conectamos a la DB
        $pdo = Database::getConexion();

        // 2) Pedimos todos los empleados
        $stmt = $pdo->query("
            SELECT id, cuil, apellido, nombre, fecha_ingreso, tiene_titulo
            FROM empleados
            ORDER BY apellido, nombre
        ");

        $empleados = $stmt->fetchAll();

        // 3) Cargamos la vista y le pasamos $empleados
        require __DIR__ . '/../../views/employees/index.php';
    }

    public function create(): void
    {
        require __DIR__ . '/../../views/employees/create.php';
    }

    public function store(): void
    {
        // 1) Aseguramos que venga por POST (guardar datos)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Método no permitido.";
            return;
        }

        // 2) Tomamos datos del formulario (nombres iguales a las columnas de DB)
        $cuil         = trim($_POST['cuil'] ?? '');
        $apellido     = trim($_POST['apellido'] ?? '');
        $nombre       = trim($_POST['nombre'] ?? '');
        $fechaIngreso = $_POST['fecha_ingreso'] ?? '';
        $tieneTitulo  = isset($_POST['tiene_titulo']) ? 1 : 0;

        // 3) Validación mínima
        if ($cuil === '' || $apellido === '' || $nombre === '' || $fechaIngreso === '') {
            http_response_code(400);
            echo "Faltan campos obligatorios.";
            return;
        }

        // 4) Conexión (usamos el MISMO método que en index)
        $pdo = Database::getConexion();

        // 5) Insert usando nombres reales de tu tabla
        $sql = "INSERT INTO empleados (cuil, apellido, nombre, fecha_ingreso, tiene_titulo)
                VALUES (:cuil, :apellido, :nombre, :fecha_ingreso, :tiene_titulo)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cuil'          => $cuil,
            ':apellido'      => $apellido,
            ':nombre'        => $nombre,
            ':fecha_ingreso' => $fechaIngreso,
            ':tiene_titulo'  => $tieneTitulo
        ]);

        // 6) Redirigir al listado (Post/Redirect/Get)
        header("Location: /?r=employees");
        exit;
    }
    public function edit(): void
{
    // 1) Validar que venga un ID
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($id <= 0) {
        http_response_code(400);
        echo "ID inválido.";
        return;
    }

    // 2) Conectarnos y buscar el empleado por ID
    $pdo = Database::getConexion();

    $stmt = $pdo->prepare("
        SELECT id, cuil, apellido, nombre, fecha_ingreso, tiene_titulo
        FROM empleados
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([':id' => $id]);
    $empleado = $stmt->fetch();

    if (!$empleado) {
        http_response_code(404);
        echo "Empleado no encontrado.";
        return;
    }

    // 3) Cargar la vista del formulario de edición
    require __DIR__ . '/../../views/employees/edit.php';
}

public function update()
{
    // 1) Aseguramos que venga por POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo "Método no permitido.";
        return;
    }

    // 2) Tomamos el ID desde la URL
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo "ID inválido.";
        return;
    }

    // 3) Tomamos los campos del formulario (ojo: nombres reales de tu tabla)
    $cuil         = trim($_POST['cuil'] ?? '');
    $apellido     = trim($_POST['apellido'] ?? '');
    $nombre       = trim($_POST['nombre'] ?? '');
    $fechaIngreso = $_POST['fecha_ingreso'] ?? '';
    $tieneTitulo  = isset($_POST['tiene_titulo']) ? 1 : 0;

    // 4) Validación mínima
    if ($cuil === '' || $apellido === '' || $nombre === '' || $fechaIngreso === '') {
        http_response_code(400);
        echo "Faltan campos obligatorios.";
        return;
    }

    // 5) Conectamos a DB
    $pdo = Database::getConexion();

    // 6) UPDATE
    $sql = "UPDATE empleados
            SET cuil = :cuil,
                apellido = :apellido,
                nombre = :nombre,
                fecha_ingreso = :fecha_ingreso,
                tiene_titulo = :tiene_titulo
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cuil' => $cuil,
        ':apellido' => $apellido,
        ':nombre' => $nombre,
        ':fecha_ingreso' => $fechaIngreso,
        ':tiene_titulo' => $tieneTitulo,
        ':id' => $id,
    ]);

    // 7) Volvemos al listado
    header("Location: /?r=employees");
    exit;
}

public function destroy()
{
    // 1) Asegurar POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo "Método no permitido.";
        return;
    }

    // 2) Tomar ID desde la URL
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo "ID inválido.";
        return;
    }

    // 3) Conectar a DB
    $pdo = Database::getConexion();

    // 4) DELETE
    $stmt = $pdo->prepare("DELETE FROM empleados WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // 5) Volver al listado
    header("Location: /?r=employees");
    exit;
}



}
