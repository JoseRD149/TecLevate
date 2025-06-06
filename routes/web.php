<?php
$allowedOrigins = [
    'http://localhost:5173',
    'http://localhost:5174'
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); 
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Max-Age: 3600"); 
    header('HTTP/1.1 200 OK');
    exit(0); 
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use TecLevate\Controllers\UsersController;
use TecLevate\Controllers\CompaniesController;
use TecLevate\Controllers\CoursesController;
use TecLevate\Controllers\ProjectsController;
use TecLevate\Controllers\UsersCoursesController;
use TecLevate\Controllers\UsersProjectsController;
use TecLevate\Controllers\CompanyCourseController;
use TecLevate\Controllers\CompanyProjectController;

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/TecLevate';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$userController = new UsersController();
$companyController = new CompaniesController();
$projectController = new ProjectsController();
$courseController = new CoursesController();
$userCourseController = new UsersCoursesController();
$usersProjectsController = new UsersProjectsController();
$companyCourseController = new CompanyCourseController();
$companyProjectController = new CompanyProjectController();

if ($method === 'GET' && $uri === '/users') {
    $userController->index();
} elseif ($method === 'GET' && preg_match('#^/users/(\d+)$#', $uri, $matches)) {
    $userController->show($matches[1]);
} elseif ($method === 'POST' && $uri === '/users') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userController->create($data);
} elseif  ($method === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT' && preg_match('#^/users/(\d+)$#', $uri, $matches)) {
    $data      = $_POST; 
    $imageFile = $_FILES['profile_image'] ?? null; 

    $userController->update($matches[1], $data, $imageFile);
} elseif ($method === 'DELETE' && preg_match('#^/users/(\d+)$#', $uri, $matches)) {
    $userController->destroy($matches[1]);
} elseif ($method === 'POST' && $uri === '/login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userController->login($data);
} elseif ($method === 'POST' && $uri === '/logout') {
    $userController->logout();
} elseif ($method === 'GET' && $uri === '/companies') {
    $companyController->index();
} elseif ($method === 'GET' && preg_match('/^\/companies\/(\d+)$/', $uri, $matches)) {
    $companyController->show($matches[1]);
} elseif ($method === 'POST' && $uri === '/companies') {
    $data = json_decode(file_get_contents('php://input'), true);
    $companyController->store($data);
} elseif ($method === 'PUT' && preg_match('/^\/companies\/(\d+)$/', $uri, $matches)) {
    $data = json_decode(file_get_contents('php://input'), true);
    $companyController->update($matches[1], $data);
} elseif ($method === 'DELETE' && preg_match('/^\/companies\/(\d+)$/', $uri, $matches)) {
    $companyController->destroy($matches[1]);
} elseif ($method === 'GET' && $uri === '/courses') {
    $courseController->index();
} elseif ($method === 'GET' && preg_match('/^\/courses\/(\d+)$/', $uri, $matches)) {
    $courseController->show($matches[1]);
} elseif ($method === 'POST' && $uri === '/courses') {
    $data = json_decode(file_get_contents('php://input'), true);
    $courseController->create($data);
} elseif ($method === 'PUT' && preg_match('/^\/courses\/(\d+)$/', $uri, $matches)) {
    $data = json_decode(file_get_contents('php://input'), true);
    $courseController->update($matches[1], $data);
} elseif ($method === 'DELETE' && preg_match('/^\/courses\/(\d+)$/', $uri, $matches)) {
    $courseController->destroy($matches[1]);
} elseif ($method === 'POST' && preg_match('/^\/courses\/assign\/(\d+)\/(\d+)$/', $uri, $matches)) {
    $courseController->assign($matches[1], $matches[2]);
} elseif ($method === 'GET' && $uri === '/projects') {
    $projectController->index();
} elseif ($method === 'GET' && preg_match('/^\/projects\/(\d+)$/', $uri, $matches)) {
    $projectController->show($matches[1]);
} elseif ($method === 'POST' && $uri === '/projects') {
    $data = json_decode(file_get_contents('php://input'), true);
    $projectController->create($data);
} elseif ($method === 'PUT' && preg_match('/^\/projects\/(\d+)$/', $uri, $matches)) {
    $data = json_decode(file_get_contents('php://input'), true);
    $projectController->update($matches[1], $data);
} elseif ($method === 'DELETE' && preg_match('/^\/projects\/(\d+)$/', $uri, $matches)) {
    $projectController->destroy($matches[1]);
} elseif ($method === 'POST' && preg_match('/^\/projects\/assign\/(\d+)\/(\d+)$/', $uri, $matches)) {
    $projectController->assign($matches[1], $matches[2]);
} elseif ($method === 'GET' && $uri === '/user-courses') {
    $userCourseController->index();
} elseif ($method === 'GET' && preg_match('/^\/user-courses\/(\d+)$/', $uri, $matches)) {
    $userCourseController->show($matches[1]);
} elseif ($method === 'POST' && $uri === '/user-courses') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userCourseController->store($data);
} elseif ($method === 'PUT' && preg_match('/^\/user-courses\/(\d+)$/', $uri, $matches)) {
    $data = json_decode(file_get_contents('php://input'), true);
    $userCourseController->update($matches[1], $data);
} elseif ($method === 'DELETE' && preg_match('/^\/user-courses\/(\d+)$/', $uri, $matches)) {
    $userCourseController->destroy($matches[1]);
} elseif ($method === 'GET' && $uri === '/user-projects') {
    $userProjectController->index();
} elseif ($method === 'GET' && preg_match('/^\/user-projects\/(\d+)$/', $uri, $matches)) {
    $userProjectController->show($matches[1]);
} elseif ($method === 'POST' && $uri === '/user-projects') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userProjectController->store($data);
} elseif ($method === 'PUT' && preg_match('/^\/user-projects\/(\d+)$/', $uri, $matches)) {
    $data = json_decode(file_get_contents('php://input'), true);
    $userProjectController->update($matches[1], $data);
} elseif ($method === 'DELETE' && preg_match('/^\/user-projects\/(\d+)$/', $uri, $matches)) {
    $userProjectController->destroy($matches[1]);
} elseif ($method === 'GET' && $uri === '/company-courses') {
    $companyCourseController->index();
} else if ($method === 'POST' && $uri === '/company-courses') {
    $data = json_decode(file_get_contents('php://input'), true);
    $companyCourseController->store($data);
} elseif ($method === 'GET' && $uri === '/company-projects') {
    $companyProjectController->index();
} else if ($method === 'POST' && $uri === '/company-projects') {
    $data = json_decode(file_get_contents('php://input'), true);
    $companyProjectController->store($data);
} elseif ($method === 'GET' && preg_match('#^/user-courses/user/(\d+)$#', $uri, $matches)) {
    $userCourseController->getByUser(['user_id' => $matches[1]]);
}elseif ($method === 'GET' && preg_match('#^/user-projects/user/(\d+)$#', $uri, $matches)) {
    $usersProjectsController->getByUser(['user_id' => $matches[1]]);
}else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
