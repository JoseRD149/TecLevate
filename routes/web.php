<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use TecLevate\Controllers\UsersController;

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
