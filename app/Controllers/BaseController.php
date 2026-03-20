<?php

namespace App\Controllers;

abstract class BaseController
{
    protected function ensureAuthenticated(bool $isAjax = false): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            if ($isAjax) {
                $this->respondJson(['error' => 'Unauthorized'], 401);
            }

            $base = defined('BASE_URL') ? rtrim((string)BASE_URL, '/') : '';
            header("Location: " . ($base !== '' ? $base : '') . "/login");
            exit;
        }
    }

    protected function ensureAdmin(bool $isAjax = false): void
    {
        $this->ensureAuthenticated($isAjax);

        if ((int)($_SESSION['role_id'] ?? 0) !== 1) {
            if ($isAjax) {
                $this->respondJson(['error' => 'Forbidden'], 403);
            }

            $base = defined('BASE_URL') ? rtrim((string)BASE_URL, '/') : '';
            header("Location: " . ($base !== '' ? $base : '') . "/dashboard");
            exit;
        }
    }

    protected function respondJson(mixed $data, int $statusCode = 200): never
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}