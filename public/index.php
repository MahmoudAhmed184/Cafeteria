<?php

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

$envFile = APP_ROOT . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, ' "\'');
        if (!getenv($key)) {
            putenv("$key=$value");
        }
    }
}

require_once APP_ROOT . '/config/app.php';
require_once APP_ROOT . '/helpers/functions.php';
require_once APP_ROOT . '/helpers/csrf.php';
require_once APP_ROOT . '/app/Router.php';

$domain = $_SERVER['HTTP_HOST'] ?? '';
if (str_contains($domain, ':')) {
    $domain = explode(':', $domain)[0];
}

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Lax'
]);
ini_set('session.use_strict_mode', '1');

if (session_status() === PHP_SESSION_NONE) {
    $logFile = defined('LOG_FILE') ? (string) LOG_FILE : __DIR__ . '/../logs/error.log';
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', $logFile);

    set_exception_handler(function ($exception) use ($logFile) {
        $message = date('[Y-m-d H:i:s]') . " Uncaught Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . PHP_EOL;
        error_log($message, 3, $logFile);
        http_response_code(500);
        echo "A server error occurred. Please try again later.";
    });

    session_start();
}

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = strtoupper((string) ($_POST['_method'] ?? $_SERVER['REQUEST_METHOD'] ?? 'GET'));
$baseUrl = rtrim((string) (defined('BASE_URL') ? BASE_URL : ''), '/');

if ($baseUrl !== '' && str_starts_with($uri, $baseUrl)) {
    $uri = substr($uri, strlen($baseUrl)) ?: '/';
}

if (in_array($method, ['POST', 'PUT', 'DELETE'], true)) {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($token) && isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (is_array($input) && isset($input['csrf_token'])) {
            $token = $input['csrf_token'];
        }
    }
    error_log("CSRF Debug: Method=$method, Token=" . ($token ?: 'MISSING') . ", SessionToken=" . ($_SESSION['_csrf_token'] ?? 'NONE'));
    if (!verify_csrf_token((string) $token)) {
        error_log("CSRF FAILED: Verification failed for token $token");
        http_response_code(403);
        die('CSRF token validation failed');
    }
}

$router = \App\Router::create();
require APP_ROOT . '/config/routes.php';
$router->route($method, $uri);
