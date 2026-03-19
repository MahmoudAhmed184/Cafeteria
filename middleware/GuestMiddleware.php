<?php

class GuestMiddleware
{
    public static function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $base = defined('BASE_URL') ? rtrim((string) BASE_URL, '/') : '';

        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role_id'] == 1) {
                redirect(($base !== '' ? $base : '') . '/admin/orders');
            } else {
                redirect(($base !== '' ? $base : '') . '/dashboard');
            }
        }
    }
}
