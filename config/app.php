<?php

declare(strict_types=1);

if (!defined('APP_NAME')) {
    define('APP_NAME', getenv('APP_NAME') ?: 'Cafeteria Management System');
}

if (!defined('BASE_URL')) {
    define('BASE_URL', rtrim((string) (getenv('BASE_URL') ?: ''), '/'));
}

if (!defined('UPLOAD_MAX_SIZE')) {
    define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024);
}

if (!defined('LOG_FILE')) {
    define('LOG_FILE', dirname(__DIR__) . '/logs/error.log');
}
