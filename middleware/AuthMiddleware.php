<?php

class AuthMiddleware
{
    public static function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $base = defined('BASE_URL') ? rtrim((string) BASE_URL, '/') : '';
            redirect(($base !== '' ? $base : '') . '/login');
        }
    }
}
