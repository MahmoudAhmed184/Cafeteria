<?php

require_once __DIR__ . '/../app/Controllers/Admin/ProductController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

$method = $_SERVER['REQUEST_METHOD'];

$controller = new ProductController();

/* LIST PRODUCTS */
if ($uri === '/admin/products') {
    $controller->index();
    exit;
}

/* CREATE FORM */
if ($uri === '/admin/products/create') {
    $controller->create();
    exit;
}

/* STORE PRODUCT */
if ($uri === '/admin/products/store' && $method === 'POST') {
    $controller->store();
    exit;
}

/* EDIT */
if ($uri === '/admin/products/edit') {
    $controller->edit();
    exit;
}

/* UPDATE */
if ($uri === '/admin/products/update' && $method === 'POST') {
    $controller->update();
    exit;
}

/* DELETE */
if ($uri === '/admin/products/delete') {
    $controller->delete();
    exit;
}

/* TOGGLE AVAILABILITY */
if ($uri === '/admin/products/toggle' && $method === 'POST') {
    $controller->toggle();
    exit;
}

http_response_code(404);
echo "404 — Route not found";