<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use TecLevate\Controllers\UsersController;
use TecLevate\Controllers\CompaniesController;

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/TecLevate';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$userController = new UsersController();

if ($method === 'GET' && $uri === '/users') {
    $userController->index();
}
elseif ($method === 'GET' && preg_match('#^/users/(\d+)$#', $uri, $matches)) {
    $userController->show($matches[1]);
}
elseif ($method === 'POST' && $uri === '/users') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userController->store($data);
}
elseif ($method === 'PUT' && preg_match('#^/users/(\d+)$#', $uri, $matches)) {
    $data = json_decode(file_get_contents('php://input'), true);
    $userController->update($matches[1], $data);
}
elseif ($method === 'DELETE' && preg_match('#^/users/(\d+)$#', $uri, $matches)) {
    $userController->destroy($matches[1]);
}
elseif ($method === 'POST' && $uri === '/login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userController->login($data);
}
elseif ($method === 'POST' && $uri === '/logout') {
    $userController->logout();
}
else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}

$companyController = new CompaniesController();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/companies') {
    $companyController->index();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('/^\/companies\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $companyController->show($matches[1]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/companies') {
    $data = json_decode(file_get_contents('php://input'), true);
    $companyController->store($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && preg_match('/^\/companies\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $data = json_decode(file_get_contents('php://input'), true);
    $companyController->update($matches[1], $data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && preg_match('/^\/companies\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $companyController->destroy($matches[1]);
}
else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}