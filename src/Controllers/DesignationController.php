<?php

require_once __DIR__ . '/../Database.php';

class DesignationController
{
    public function index(): void
    {
        $pdo = Database::getConexion();

        $stmt = $pdo->query("
            SELECT
                d.id,
                d.empleado_id,
                e.apellido,
                e.nombre,
                d.cargo_id,
                c.nombre AS cargo_nombre,
                eo.nombre AS estructura_nombre,
                d.fecha_inicio,
                d.fecha_fin
            FROM designaciones d
            INNER JOIN empleados e ON e.id = d.empleado_id
            INNER JOIN cargos c ON c.id = d.cargo_id
            INNER JOIN estructuras_organizativas eo 
                ON eo.id = c.estructura_organizativa_id
            ORDER BY e.apellido, e.nombre, d.fecha_inicio DESC
        ");

        $designaciones = $stmt->fetchAll();

        require __DIR__ . '/../../views/designations/index.php';
    }

    public function create(): void
    {
        $pdo = Database::getConexion();

        $empleados = $pdo->query("
            SELECT id, apellido, nombre
            FROM empleados
            ORDER BY apellido, nombre
        ")->fetchAll();

        $cargos = $pdo->query("
            SELECT id, nombre
            FROM cargos
            ORDER BY nombre
        ")->fetchAll();

        require __DIR__ . '/../../views/designations/create.php';
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Método no permitido.";
            return;
        }

        $empleadoId  = (int)($_POST['empleado_id'] ?? 0);
        $cargoId     = (int)($_POST['cargo_id'] ?? 0);
        $fechaInicio = $_POST['fecha_inicio'] ?? '';
        $fechaFin    = $_POST['fecha_fin'] ?? '';

        if ($empleadoId <= 0 || $cargoId <= 0 || $fechaInicio === '') {
            http_response_code(400);
            echo "Faltan campos obligatorios.";
            return;
        }

        $fechaFin = trim($fechaFin);
        if ($fechaFin === '') {
            $fechaFin = null;
        }

        $pdo = Database::getConexion();

        $sql = "INSERT INTO designaciones (empleado_id, cargo_id, fecha_inicio, fecha_fin)
                VALUES (:empleado_id, :cargo_id, :fecha_inicio, :fecha_fin)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':empleado_id'  => $empleadoId,
            ':cargo_id'     => $cargoId,
            ':fecha_inicio' => $fechaInicio,
            ':fecha_fin'    => $fechaFin
        ]);

        setFlash('OK', 'Designación creada correctamente');
        header("Location: /?r=designations");
        exit;
    }
}
