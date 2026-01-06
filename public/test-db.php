<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../src/Database.php';

try {
    $pdo = Database::getConexion();

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM empleados");
    $fila = $stmt->fetch();

    echo "Conexión OK. Empleados cargados: " . $fila['total'];
} catch (Throwable $e) {
    echo "Hubo un error: " . $e->getMessage();
}
