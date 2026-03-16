<?php

class AuthMiddleware
{

    public static function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            redirect("/project/cafeteria/login");
        }
    }
}
