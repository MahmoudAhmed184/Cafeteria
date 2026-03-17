<?php

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

require_once APP_ROOT . '/config/app.php';
require_once APP_ROOT . '/helpers/functions.php';
require_once APP_ROOT . '/helpers/validation.php';
require_once APP_ROOT . '/helpers/csrf.php';
require_once APP_ROOT . '/app/Router.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = strtoupper((string) ($_POST['_method'] ?? $_SERVER['REQUEST_METHOD'] ?? 'GET'));
$baseUrl = rtrim((string) (defined('BASE_URL') ? BASE_URL : ''), '/');

if ($baseUrl !== '' && str_starts_with($uri, $baseUrl)) {
    $uri = substr($uri, strlen($baseUrl)) ?: '/';
}

$router = \App\Router::create();
require APP_ROOT . '/config/routes.php';
$router->route($method, $uri);
