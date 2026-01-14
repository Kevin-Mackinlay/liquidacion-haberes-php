<?php

require_once __DIR__ . '/../Database.php';

class EmployeeController
{
    public function index(): void
    {
        // 1) Nos conectamos a la DB
        $pdo = Database::getConexion();

        $stmt = $pdo->query("
    SELECT 
        e.id,
        e.cuil,
        e.apellido,
        e.nombre,
        e.fecha_ingreso,
        e.tiene_titulo,
        e.cargo_id,
        c.nombre AS cargo_nombre,
        eo.nombre AS estructura_nombre
    FROM empleados e
    LEFT JOIN cargos c ON c.id = e.cargo_id
    LEFT JOIN estructuras_organizativas eo ON eo.id = c.estructura_organizativa_id
    ORDER BY e.apellido, e.nombre
");


        $empleados = $stmt->fetchAll();

        // 3) Cargamos la vista y le pasamos $empleados
        require __DIR__ . '/../../views/employees/index.php';
    }

    public function create(): void
    {

        $pdo = Database::getConexion();

        // Traer cargos para el select
        $stmt = $pdo->query("SELECT id, nombre FROM cargos ORDER BY nombre");
        $cargos = $stmt->fetchAll();
        
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

        // 2) Tomamos datos del formulario (names del create.php)
        $cuil         = trim($_POST['cuil'] ?? '');
        $apellido     = trim($_POST['apellido'] ?? '');
        $nombre       = trim($_POST['nombre'] ?? '');
        $fechaIngreso = $_POST['fecha_ingreso'] ?? '';
        $tieneTitulo  = isset($_POST['tiene_titulo']) ? 1 : 0;
        $cargoId      = (int)($_POST['cargo_id'] ?? 0);



        // 3) Validación mínima
        if ($cuil === '' || $apellido === '' || $nombre === '' || $fechaIngreso === '' || $cargoId <= 0) {
            http_response_code(400);
            echo "Faltan campos obligatorios.";
            return;
        }


        // 4) Conexión (usamos el MISMO método que en index)
        $pdo = Database::getConexion();

        // 5) Insert usando nombres reales de tu tabla
        $sql = "INSERT INTO empleados (cuil, apellido, nombre, fecha_ingreso, tiene_titulo, cargo_id)
        VALUES (:cuil, :apellido, :nombre, :fecha_ingreso, :tiene_titulo, :cargo_id)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cuil' => $cuil,
            ':apellido' => $apellido,
            ':nombre' => $nombre,
            ':fecha_ingreso' => $fechaIngreso,
            ':tiene_titulo' => $tieneTitulo,
            ':cargo_id' => $cargoId
        ]);


        // 6) Redirigir al listado (Post/Redirect/Get)
        setFlash('OK', 'Empleado creado correctamente');
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
        SELECT id, cuil, apellido, nombre, fecha_ingreso, tiene_titulo, cargo_id
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

        $stmtCargos = $pdo->query("
        SELECT id, nombre
        FROM cargos
        ORDER BY nombre
    ");
        $cargos = $stmtCargos->fetchAll();

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

        // 2) ID obligatorio
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo "ID inválido.";
            return;
        }

        // 3) Tomar datos del formulario (names del edit.php)
        $cuil         = trim($_POST['cuil'] ?? '');
        $apellido     = trim($_POST['apellido'] ?? '');
        $nombre       = trim($_POST['nombre'] ?? '');
        $fechaIngreso = $_POST['fecha_ingreso'] ?? '';
        $tieneTitulo  = isset($_POST['tiene_titulo']) ? 1 : 0;
        $cargoId      = (int)($_POST['cargo_id'] ?? 0);

        // 4) Validación mínima
        if ($cuil === '' || $apellido === '' || $nombre === '' || $fechaIngreso === '' || $cargoId <= 0) {
            http_response_code(400);
            echo "Faltan campos obligatorios.";
            return;
        }

        // 5) Conectamos a DB
        $pdo = Database::getConexion();

        // 6) UPDATE
        $sql = "
        UPDATE empleados
        SET cuil = :cuil,
            apellido = :apellido,
            nombre = :nombre,
            fecha_ingreso = :fecha_ingreso,
            tiene_titulo = :tiene_titulo,
            cargo_id = :cargo_id
        WHERE id = :id
        LIMIT 1
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cuil' => $cuil,
            ':apellido' => $apellido,
            ':nombre' => $nombre,
            ':fecha_ingreso' => $fechaIngreso,
            ':tiene_titulo' => $tieneTitulo,
            ':cargo_id' => $cargoId,
            ':id' => $id,
        ]);

        // 7) Flash + redirect
        setFlash('OK', 'Empleado actualizado correctamente');
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
        setFlash('OK', 'Empleado eliminado correctamente');
        header("Location: /?r=employees");
        exit;
    }
}
