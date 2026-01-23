<?php

session_start();

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) return null;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}


// 1) Mientras desarrollás: mostrás errores (te ayuda a no quedar en blanco)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 2) Ruta pedida por URL, ej: ?r=employees
$route = $_GET['r'] ?? 'employees'; // si no viene nada, por defecto employees

// 3) Cargamos el controlador (archivo PHP)
require_once __DIR__ . '/../src/Controllers/EmployeeController.php';
require_once __DIR__ . '/../src/Controllers/DesignationController.php';


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

    case 'employees/edit':
    $controller = new EmployeeController();
    $controller->edit();
    break;

    case 'employees/update':
    $controller = new EmployeeController();
    $controller->update();
    break;

    case 'employees/destroy':
    $controller = new EmployeeController();
    $controller->destroy();
    break;

    case 'designations':
        (new DesignationController())->index();
        break;

    case 'designations/create':
        (new DesignationController())->create();
        break;

    case 'designations/store':
        (new DesignationController())->store();
        break;

    case 'recibo':
        require __DIR__ . '/recibo.php';
        break;


    default:
        http_response_code(404);
        echo "Ruta no encontrada.";
        break;
}
