<?php
// 1) Mientras desarrollás: mostrás errores (te ayuda a no quedar en blanco)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 2) Ruta pedida por URL, ej: ?r=employees
$route = $_GET['r'] ?? 'employees'; // si no viene nada, por defecto employees

// 3) Cargamos el controlador (archivo PHP)
require_once __DIR__ . '/../src/Controllers/EmployeeController.php';

// 4) Enrutamiento mínimo (router casero)
switch ($route) {
    case 'employees':
        $controller = new EmployeeController();
        $controller->index();
        break;

    case 'employees/create':
        $controller = new EmployeeController();
        $controller->create();
        break;

    case 'employees/store':
        $controller = new EmployeeController();
        $controller->store();
        break;

    default:
        http_response_code(404);
        echo "Ruta no encontrada.";
        break;
}
