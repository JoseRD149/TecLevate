<?php
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


$controllers = [
    'users' => new UsersController(),
    'companies' => new CompaniesController(),
    'courses' => new CoursesController(),
    'projects' => new ProjectsController(),
    'user-courses' => new UsersCoursesController(),
    'user-projects' => new UsersProjectsController(),
    'company-courses' => new CompanyCourseController(),
    'company-projects' => new CompanyProjectController(),
];

$routes = [
    'GET' => [
        '#^/users$#' => ['users', 'index'],
        '#^/users/(\d+)$#' => ['users', 'show'],
        '#^/companies$#' => ['companies', 'index'],
        '#^/companies/(\d+)$#' => ['companies', 'show'],
        '#^/courses$#' => ['courses', 'index'],
        '#^/courses/(\d+)$#' => ['courses', 'show'],
        '#^/projects$#' => ['projects', 'index'],
        '#^/projects/(\d+)$#' => ['projects', 'show'],
        '#^/user-courses$#' => ['user-courses', 'index'],
        '#^/user-courses/(\d+)$#' => ['user-courses', 'show'],
        '#^/user-projects$#' => ['user-projects', 'index'],
        '#^/user-projects/(\d+)$#' => ['user-projects', 'show'],
        '#^/company-courses$#' => ['company-courses', 'index'],
        '#^/company-projects$#' => ['company-projects', 'index'],
    ],
    'POST' => [
        '#^/users$#' => ['users', 'create'],
        '#^/login$#' => ['users', 'login'],
        '#^/logout$#' => ['users', 'logout'],
        '#^/companies$#' => ['companies', 'store'],
        '#^/courses$#' => ['courses', 'create'],
        '#^/courses/assign/(\d+)/(\d+)$#' => ['courses', 'assign'],
        '#^/projects$#' => ['projects', 'create'],
        '#^/projects/assign/(\d+)/(\d+)$#' => ['projects', 'assign'],
        '#^/user-courses$#' => ['user-courses', 'store'],
        '#^/user-projects$#' => ['user-projects', 'store'],
        '#^/company-courses$#' => ['company-courses', 'store'],
        '#^/company-projects$#' => ['company-projects', 'store'],
    ],
    'PUT' => [
        '#^/users/(\d+)$#' => ['users', 'update'],
        '#^/companies/(\d+)$#' => ['companies', 'update'],
        '#^/courses/(\d+)$#' => ['courses', 'update'],
        '#^/projects/(\d+)$#' => ['projects', 'update'],
        '#^/user-courses/(\d+)$#' => ['user-courses', 'update'],
        '#^/user-projects/(\d+)$#' => ['user-projects', 'update'],
    ],
    'DELETE' => [
        '#^/users/(\d+)$#' => ['users', 'destroy'],
        '#^/companies/(\d+)$#' => ['companies', 'destroy'],
        '#^/courses/(\d+)$#' => ['courses', 'destroy'],
        '#^/projects/(\d+)$#' => ['projects', 'destroy'],
        '#^/user-courses/(\d+)$#' => ['user-courses', 'destroy'],
        '#^/user-projects/(\d+)$#' => ['user-projects', 'destroy'],
    ]
];


$found = false;

if (isset($routes[$method])) {
    foreach ($routes[$method] as list($pattern, $ctrlKey, $action, $hasBody)) {
        if (preg_match($pattern, $uri, $matches)) {
            $found = true;
            array_shift($matches);
            $args = $matches;
            if ($hasBody) {
                $args[] = json_decode(file_get_contents('php://input'), true);
            }
            $controller = $controllers[$ctrlKey];
            $result = call_user_func_array([$controller, $action], $args);
            echo json_encode($result);
            break;
        }
    }
}

if (!$found) {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
