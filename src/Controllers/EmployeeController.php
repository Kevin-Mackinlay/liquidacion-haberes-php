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
}
