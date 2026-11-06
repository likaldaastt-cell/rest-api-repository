<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\BookController;
use App\Controllers\CategoryController;
use App\Repositories\MySQLCategoryRepository;
use App\Services\CategoryService;

header('Content-Type: application/json; charset=utf-8');

$database = new Database();
$db = $database->connect();

// Controller Book
$controller = new BookController($db);

// Controller Category
$categoryRepository = new MySQLCategoryRepository($db);
$categoryService = new CategoryService($categoryRepository);
$categoryController = new CategoryController($categoryService);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$path = rtrim($path, '/');

// ROUTE BOOK
if ($path === '/api/books') {
    if ($method === 'GET') {
        $controller->index();
    } elseif ($method === 'POST') {
        $controller->store();
    } else {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method tidak diizinkan'
        ]);
    }

} elseif (preg_match('#^/api/books/([0-9]+)$#', $path, $matches)) {
    $id = (int) $matches[1];

    if ($method === 'GET') {
        $controller->show($id);
    } elseif ($method === 'PUT') {
        $controller->update($id);
    } elseif ($method === 'DELETE') {
        $controller->destroy($id);
    } else {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method tidak diizinkan'
        ]);
    }

// ROUTE CATEGORY
} elseif ($path === '/api/categories') {
    if ($method === 'GET') {
        $categoryController->index();
    } elseif ($method === 'POST') {
        $categoryController->store();
    } else {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method tidak diizinkan'
        ]);
    }

} elseif (preg_match('#^/api/categories/([0-9]+)$#', $path, $matches)) {
    $id = (int) $matches[1];

    if ($method === 'GET') {
        $categoryController->show($id);
    } elseif ($method === 'PUT') {
        $categoryController->update($id);
    } elseif ($method === 'DELETE') {
        $categoryController->destroy($id);
    } else {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method tidak diizinkan'
        ]);
    }

} else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Endpoint tidak ditemukan'
    ]);
}